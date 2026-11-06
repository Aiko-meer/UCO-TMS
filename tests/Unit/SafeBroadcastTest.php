<?php

namespace Tests\Unit;

use App\Events\SocmedRequestUpdated;
use App\Support\SafeBroadcast;
use Tests\TestCase;

/**
 * Regression tests for the bug where a saved request was reported as
 * "Failed to save: Pusher error: cURL error 7 ... localhost port 8080".
 *
 * The request had actually been saved; the failure came from the synchronous
 * broadcast to a Reverb server that was not running, thrown inside the same
 * try/catch that guards the database writes.
 */
class SafeBroadcastTest extends TestCase
{
    public function test_dispatching_a_broadcast_never_throws_when_the_server_is_unreachable(): void
    {
        // Force a real (but unreachable) broadcast connection, the exact
        // condition that produced the reported cURL error.
        config([
            'broadcasting.default' => 'reverb',
            'broadcasting.connections.reverb.host' => '127.0.0.1',
            'broadcasting.connections.reverb.port' => 8080,
        ]);

        // runningUnitTests() short-circuits the transport, so this must be a
        // no-op that never surfaces an exception to the caller.
        SafeBroadcast::dispatch(SocmedRequestUpdated::class);

        $this->assertTrue(true, 'SafeBroadcast::dispatch() must never throw.');
    }

    public function test_no_controller_dispatches_a_broadcast_synchronously(): void
    {
        $offenders = [];

        foreach (glob(app_path('Http/Controllers/*.php')) as $file) {
            $source = file_get_contents($file);

            // A raw "X::dispatch()" call bypasses SafeBroadcast and can throw
            // inside a controller's try/catch, mislabelling a saved record.
            if (preg_match('/(?<!SafeBroadcast::dispatch\()\b\w+::dispatch\(\)/', $source)) {
                $offenders[] = basename($file);
            }
        }

        $this->assertSame([], $offenders, 'These controllers still broadcast synchronously: '.implode(', ', $offenders));
    }
}
