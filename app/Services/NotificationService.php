<?php

namespace App\Services;

use App\Models\NotificationRead;
use App\Models\Photo_vid_request_informations;
use App\Models\Publishing_request_informaitons;
use App\Models\Requestor;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use App\Models\User;
use App\Support\NotificationItem;
use App\Support\NotificationType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * Builds the notification feed for ONE account.
 *
 * Two independent things decide what a user sees:
 *   1. their role - a user is only offered links to dashboards they can open,
 *      so a notification can never lead to a 403;
 *   2. their own read receipts in `notification_reads` - opening a notification
 *      writes a row for that user only, so other accounts still see it.
 */
class NotificationService
{
    /** How far back the feed looks, so a new account is not flooded. */
    public int $windowDays;

    /** Maximum rows returned to the bell dropdown. */
    public int $limit;

    public function __construct()
    {
        $this->windowDays = (int) config('notifications.window_days', 60);
        $this->limit = (int) config('notifications.limit', 15);
    }

    /**
     * The full feed for a user: newest first, role-filtered, read-flagged.
     *
     * @return Collection<int, NotificationItem>
     */
    public function forUser(?User $user): Collection
    {
        if (! $user) {
            return collect();
        }

        return $this->candidates($user)
            ->sortByDesc(fn (NotificationItem $item) => $item->createdAt?->timestamp ?? 0)
            ->values();
    }

    /**
     * Unread count for this account - the number the badge must show.
     */
    public function unreadCount(?User $user): int
    {
        return $this->forUser($user)->where('read', false)->count();
    }

    public function totalCount(?User $user): int
    {
        return $this->forUser($user)->count();
    }

    /**
     * The most recent N items, for the dropdown.
     *
     * @return Collection<int, NotificationItem>
     */
    public function recent(?User $user, ?int $limit = null): Collection
    {
        return $this->forUser($user)->take($limit ?? $this->limit);
    }

    /**
     * Mark one notification read for one user only.
     */
    public function markRead(?User $user, string $key): bool
    {
        if (! $user || ! $this->isOwnedBy($user, $key)) {
            return false;
        }

        NotificationRead::updateOrCreate(
            ['user_id' => $user->id, 'notification_key' => $key],
            ['read_at' => now()]
        );

        return true;
    }

    /**
     * Mark every notification this user can currently see as read.
     */
    public function markAllRead(?User $user): int
    {
        if (! $user) {
            return 0;
        }

        $keys = $this->forUser($user)->where('read', false)->pluck('key');
        $count = 0;

        foreach ($keys as $key) {
            NotificationRead::updateOrCreate(
                ['user_id' => $user->id, 'notification_key' => $key],
                ['read_at' => now()]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Resolve a single notification by key, but only if this user may see it.
     */
    public function find(?User $user, string $key): ?NotificationItem
    {
        return $this->forUser($user)->firstWhere('key', $key);
    }

    /**
     * Guard against a user marking (or opening) a notification outside their role.
     */
    public function isOwnedBy(?User $user, string $key): bool
    {
        return $this->find($user, $key) !== null;
    }

    /**
     * Every candidate notification for this user, before sorting.
     *
     * @return Collection<int, NotificationItem>
     */
    private function candidates(User $user): Collection
    {
        $since = now()->subDays($this->windowDays);
        $items = collect();

        // --- Parent-row request types: socmed / photo / video / publishing ---
        if ($this->canSeeAny($user, ['socmed', 'photo', 'video', 'layout', 'production', 'publishing', 'uzpost', 'uzpr'])) {
            $requestors = Requestor::query()
                ->with(['socmed', 'photo', 'publishing'])
                ->where(function ($q) use ($since) {
                    $q->where('created_at', '>=', $since);
                })
                ->latest()
                ->limit(500)
                ->get();

            foreach ($requestors as $requestor) {
                $type = $requestor->notificationType();

                if (! $type || ! NotificationType::allows($type, $user->user_type)) {
                    continue;
                }

                $child = $requestor->publishing ?? $requestor->photo ?? $requestor->socmed;

                if (! $child) {
                    continue;
                }

                $items->push($this->makeItem(
                    user: $user,
                    type: $type,
                    requestId: (string) $requestor->request_id,
                    // Publishing/socmed/photo modals are keyed by request_id.
                    modalKey: (string) $requestor->request_id,
                    fullname: $requestor->fullname,
                    department: (string) ($requestor->department ?? ''),
                    email: $requestor->email,
                    status: (string) ($child->status ?? ''),
                    createdAt: $requestor->created_at,
                ));
            }
        }

        // --- Softcopy: its own table, modal keyed by numeric id ---
        if (NotificationType::allows('softcopy', $user->user_type) && Schema::hasTable('soft_copy_requests')) {
            $rows = Soft_copy_requests::query()
                ->where('created_at', '>=', $since)
                ->latest()
                ->limit(200)
                ->get();

            foreach ($rows as $row) {
                $items->push($this->makeItem(
                    user: $user,
                    type: 'softcopy',
                    requestId: (string) ($row->request_id ?? $row->id),
                    modalKey: (string) $row->id,
                    fullname: trim(($row->f_name ?? '').' '.($row->l_name ?? '')),
                    department: (string) ($row->program ?? ''),
                    email: $row->email ?? null,
                    status: (string) ($row->status ?? ''),
                    createdAt: $row->created_at,
                ));
            }
        }

        // --- Trademark: its own table, modal keyed by numeric id ---
        if (NotificationType::allows('trademark', $user->user_type) && Schema::hasTable('trademark_requests')) {
            $rows = TrademarkRequest::query()
                ->where('created_at', '>=', $since)
                ->latest()
                ->limit(200)
                ->get();

            foreach ($rows as $row) {
                $items->push($this->makeItem(
                    user: $user,
                    type: 'trademark',
                    requestId: (string) ($row->request_id ?? $row->id),
                    modalKey: (string) $row->id,
                    fullname: trim(($row->f_name ?? '').' '.($row->l_name ?? '')),
                    department: (string) ($row->department_org ?? ''),
                    email: $row->email ?? null,
                    status: (string) ($row->status ?? ''),
                    createdAt: $row->created_at,
                ));
            }
        }

        return $items;
    }

    private function canSeeAny(User $user, array $types): bool
    {
        foreach ($types as $type) {
            if (NotificationType::allows($type, $user->user_type)) {
                return true;
            }
        }

        return false;
    }

    private function makeItem(
        User $user,
        string $type,
        string $requestId,
        string $modalKey,
        string $fullname,
        string $department,
        ?string $email,
        string $status,
        ?Carbon $createdAt,
    ): NotificationItem {
        $key = $type.':'.$requestId;

        $routeName = NotificationType::route($type);
        $dashboardUrl = $routeName && Route::has($routeName) ? route($routeName) : null;

        return new NotificationItem(
            type: $type,
            key: $key,
            requestId: $requestId,
            modalKey: $modalKey,
            fullname: $fullname !== '' ? $fullname : 'Unknown requestor',
            department: $department,
            email: $email,
            status: $status,
            createdAt: $createdAt,
            read: $user->hasReadNotification($key),
            openUrl: route('notifications.open', ['key' => $key]),
            dashboardUrl: $dashboardUrl,
        );
    }
}
