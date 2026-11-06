<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * One notification, already resolved to everything the UI needs.
 *
 * Built per-requesting-user, so `read` is *this account's* read state and never
 * a global flag.
 */
class NotificationItem
{
    public function __construct(
        public string $type,
        public string $key,
        public string $requestId,
        public string $modalKey,
        public string $fullname,
        public string $department,
        public ?string $email,
        public string $status,
        public ?Carbon $createdAt,
        public bool $read,
        public string $openUrl,
        public ?string $dashboardUrl,
    ) {}

    public function label(): string
    {
        return NotificationType::label($this->type);
    }

    public function icon(): string
    {
        return NotificationType::icon($this->type);
    }

    /**
     * Human status for the badge, matching each family's own semantics.
     */
    public function statusLabel(): ?string
    {
        if ($this->status === '') {
            return null;
        }

        return self::statusText($this->type, $this->status);
    }

    /**
     * Status codes differ between request families:
     *  - socmed / photo-video : 0 pending, 1 in progress, 2 posted, 3 rejected
     *  - publishing (layout, production, uzpost, uzpr): 0 pending, 1 completed,
     *    2 in progress, 3 rejected, 4 for approval
     *  - softcopy / trademark: 0 pending, 1 completed, 2 rejected
     */
    public static function statusText(string $type, string $status): ?string
    {
        $status = (string) $status;

        $maps = [
            'default' => [
                '0' => 'Pending',
                '1' => 'In progress',
                '2' => 'Posted',
                '3' => 'Rejected',
            ],
            'publishing' => [
                '0' => 'Pending',
                '1' => 'Completed',
                '2' => 'In progress',
                '3' => 'Rejected',
                '4' => 'For approval',
            ],
            'simple' => [
                '0' => 'Pending',
                '1' => 'Completed',
                '2' => 'Rejected',
            ],
        ];

        $publishingTypes = ['layout', 'production', 'publishing', 'uzpost', 'uzpr'];
        $simpleTypes = ['softcopy', 'trademark'];

        $map = match (true) {
            in_array($type, $publishingTypes, true) => $maps['publishing'],
            in_array($type, $simpleTypes, true) => $maps['simple'],
            default => $maps['default'],
        };

        return $map[$status] ?? null;
    }

    public function timeAgo(): string
    {
        return $this->createdAt ? $this->createdAt->diffForHumans() : '';
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'key' => $this->key,
            'request_id' => $this->requestId,
            'modal_key' => $this->modalKey,
            'fullname' => $this->fullname,
            'department' => $this->department,
            'email' => $this->email,
            'status' => $this->status,
            'status_label' => $this->statusLabel(),
            'label' => $this->label(),
            'icon' => $this->icon(),
            'created_at' => $this->createdAt?->toIso8601String(),
            'time_ago' => $this->timeAgo(),
            'read' => $this->read,
            'open_url' => $this->openUrl,
            'dashboard_url' => $this->dashboardUrl,
        ];
    }
}
