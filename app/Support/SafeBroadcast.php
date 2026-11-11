<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Fire-and-forget broadcasting.
 *
 * The broadcast events here implement ShouldBroadcastNow, which pushes to the
 * Reverb/Pusher server synchronously, inside the HTTP request. When that server
 * is not running the client throws a cURL error - and because these calls sat
 * inside the same try/catch that guards the database writes, a saved request
 * was reported to the user as "Failed to save".
 *
 * A realtime refresh is a convenience, never a reason to fail a save, and never
 * a reason to make one slow. Two things therefore happen here:
 *
 *  1. during an HTTP request the dispatch is deferred until after the response
 *     has been sent, so an unreachable broadcast server costs the user nothing
 *     (the connection timeout happens after their redirect is already on the
 *     wire, instead of holding the save for ~2s per request);
 *  2. the dispatch is wrapped in a catch-all, so a transport failure degrades
 *     to a log line and the UI simply falls back to polling.
 */
class SafeBroadcast
{
    /**
     * Dispatch an event without letting the broadcast layer break the caller.
     *
     * @param  object|string  $event  Event instance or class name.
     */
    public static function dispatch(object|string $event): void
    {
        // Never attempt a real broadcast while the test suite runs: the outcome
        // depends on whether a realtime server happens to be up, and a dead one
        // would add its connection timeout to every test.
        if (app()->runningUnitTests()) {
            return;
        }

        $fire = static function () use ($event): void {
            try {
                is_string($event) ? event(new $event()) : event($event);
            } catch (Throwable $e) {
                Log::warning('Broadcast failed (non-fatal, request unaffected): '.$e->getMessage(), [
                    'event' => is_object($event) ? $event::class : $event,
                ]);
            }
        };

        // After the response, when we can: the caller's save must not wait on
        // a broadcast server that may be down.
        if (function_exists('defer') && ! app()->runningInConsole()) {
            try {
                defer($fire);

                return;
            } catch (Throwable $e) {
                // Fall through to an inline attempt.
            }
        }

        $fire();
    }
}
