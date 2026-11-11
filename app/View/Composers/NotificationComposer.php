<?php

namespace App\View\Composers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Supplies the notification bell with its data.
 *
 * This used to query `where('token', '0')` on shared tables, which meant the
 * bell showed a global "not yet read by anyone" state - the reason a request
 * appeared to vanish for a second account the moment the first account opened
 * it. It now resolves everything through NotificationService for the signed-in
 * user, so both the badge and the list are that account's own view.
 */
class NotificationComposer
{
    public function __construct(private NotificationService $notifications) {}

    public function compose(View $view): void
    {
        $user = Auth::user();

        $items = collect();
        $unread = 0;

        if ($user) {
            try {
                $items = $this->notifications->recent($user);
                $unread = $this->notifications->unreadCount($user);
            } catch (\Throwable $e) {
                // A broken feed must never take down the whole page.
                report($e);
            }
        }

        $view->with([
            'notificationItems' => $items,
            'unreadNotifications' => $unread,
            // Kept for backwards compatibility with any view still reading it.
            'totalNotifications' => $unread,
        ]);
    }
}
