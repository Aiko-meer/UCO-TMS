<?php

namespace App\Support;

/**
 * The single registry describing every kind of request notification.
 *
 * Each type knows its label, icon, which dashboard it lives on, which roles are
 * allowed to open that dashboard, and whether its detail modal is keyed by the
 * shared `request_id` or by the row's own `id`.
 *
 * Keeping this in one place is what lets a notification link straight to the
 * right page and open the right modal, and what keeps the bell from offering a
 * link the user's role would get a 403 on.
 */
class NotificationType
{
    /** Roles that may see a type. `null` means "no role restriction". */
    public const REGISTRY = [
        'socmed' => [
            'label' => 'Social Media Request',
            'icon' => 'photo_camera',
            'route' => 'socmed.home',
            'roles' => ['content_team', 'content_admin'],
            'modal' => 'request_id',
            'model' => 'socmed',
            'color' => 'accent',
        ],
        'photo' => [
            'label' => 'Photo Request',
            'icon' => 'photo_library',
            'route' => 'photo.home',
            'roles' => ['content_team', 'content_admin'],
            'modal' => 'request_id',
            'model' => 'photo',
            'color' => 'accent',
        ],
        'video' => [
            'label' => 'Video Request',
            'icon' => 'videocam',
            'route' => 'video.home',
            'roles' => ['content_team', 'content_admin'],
            'modal' => 'request_id',
            'model' => 'photo',
            'color' => 'accent',
        ],
        'layout' => [
            'label' => 'Layout Request',
            'icon' => 'brush',
            'route' => 'layout.home',
            'roles' => ['creative_team', 'creative_admin'],
            'modal' => 'request_id',
            'model' => 'publishing',
            'color' => 'primary',
        ],
        'production' => [
            'label' => 'Production Request',
            'icon' => 'print',
            'route' => 'production.home',
            'roles' => ['creative_team', 'creative_admin'],
            'modal' => 'request_id',
            'model' => 'publishing',
            'color' => 'primary',
        ],
        'publishing' => [
            'label' => 'Publishing Request',
            'icon' => 'publish',
            'route' => 'publishing.home',
            'roles' => ['creative_team', 'creative_admin'],
            'modal' => 'request_id',
            'model' => 'publishing',
            'color' => 'primary',
        ],
        'uzpost' => [
            'label' => 'UZ Post Request',
            'icon' => 'newspaper',
            'route' => 'uzpost.home',
            'roles' => ['creative_team', 'creative_admin'],
            'modal' => 'request_id',
            'model' => 'publishing',
            'color' => 'primary',
        ],
        'uzpr' => [
            'label' => 'UZ PR Request',
            'icon' => 'campaign',
            'route' => 'uzpr.home',
            'roles' => ['creative_team', 'creative_admin'],
            'modal' => 'request_id',
            'model' => 'publishing',
            'color' => 'primary',
        ],
        'softcopy' => [
            'label' => 'Softcopy Request',
            'icon' => 'gavel',
            'route' => 'softcopy.home',
            // Route middleware is "role:yearbook_team"; admin/superadmin pass
            // the middleware implicitly, so they are added here too.
            'roles' => ['yearbook_team', 'admin', 'superadmin'],
            'modal' => 'id',
            'model' => 'softcopy',
            'color' => 'warning',
        ],
        'trademark' => [
            'label' => 'Trademark Request',
            'icon' => 'copyright',
            'route' => 'trademark.home',
            'roles' => ['admin', 'superadmin'],
            'modal' => 'id',
            'model' => 'trademark',
            'color' => 'success',
        ],
    ];

    /** Roles that bypass every restriction, mirroring EnsureUserHasRole. */
    private const PRIVILEGED = ['admin', 'superadmin'];

    public static function all(): array
    {
        return array_keys(self::REGISTRY);
    }

    public static function exists(string $type): bool
    {
        return isset(self::REGISTRY[$type]);
    }

    public static function meta(string $type): array
    {
        return self::REGISTRY[$type] ?? [];
    }

    public static function label(string $type): string
    {
        return self::meta($type)['label'] ?? ucfirst($type);
    }

    public static function icon(string $type): string
    {
        return self::meta($type)['icon'] ?? 'notifications';
    }

    public static function route(string $type): ?string
    {
        return self::meta($type)['route'] ?? null;
    }

    /**
     * Roles allowed to see this type, or null when unrestricted.
     *
     * @return string[]|null
     */
    public static function rolesFor(string $type): ?array
    {
        return self::meta($type)['roles'] ?? null;
    }

    /**
     * Is this user allowed to see notifications of this type?
     */
    public static function allows(string $type, ?string $userType): bool
    {
        if (! self::exists($type)) {
            return false;
        }

        $allowed = self::rolesFor($type);

        if ($allowed === null) {
            return true;
        }

        if ($userType === null) {
            return false;
        }

        if (in_array($userType, self::PRIVILEGED, true)) {
            return true;
        }

        return in_array($userType, $allowed, true);
    }

    /**
     * Map a publishing `specification` value to a notification type.
     */
    public static function forPublishingSpecification(?string $specification): ?string
    {
        $specification = strtolower(trim((string) $specification));

        return match ($specification) {
            'layout' => 'layout',
            'production' => 'production',
            'uzpost' => 'uzpost',
            'uzpr' => 'uzpr',
            'publishing', 'publish' => 'publishing',
            default => $specification === '' ? null : 'publishing',
        };
    }

    /**
     * Map a photo/video `category` value to a notification type.
     */
    public static function forPhotoCategory(?string $category): ?string
    {
        $category = strtolower(trim((string) $category));

        return match ($category) {
            'video', 'videography' => 'video',
            '' => null,
            default => 'photo',
        };
    }
}
