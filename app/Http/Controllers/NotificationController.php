<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Support\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * The notification bell, the "What's New" page, and the deep links that take a
 * user straight to the request they clicked.
 *
 * Every action resolves the feed through NotificationService for the *current*
 * account, so read state and counts are never shared between accounts.
 */
class NotificationController extends Controller
{
    public function __construct(private NotificationService $notifications) {}

    /**
     * Full "What's New" listing.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $items = $this->notifications->forUser($user);

        $filter = $request->query('type');
        if (is_string($filter) && NotificationType::exists($filter)) {
            $items = $items->where('type', $filter)->values();
        }

        return view('admin.notifications.index', [
            'items' => $items,
            'unread' => $items->where('read', false)->count(),
            'total' => $items->count(),
            'activeType' => $filter,
            'types' => collect(NotificationType::all())
                ->filter(fn ($type) => NotificationType::allows($type, $user?->user_type))
                ->values(),
        ]);
    }

    /**
     * JSON feed for the bell: pre-rendered HTML plus counts.
     *
     * Rendering the list server-side keeps the Blade markup in one place
     * (notif-items.blade.php) instead of duplicating it in JavaScript.
     */
    public function feed(Request $request)
    {
        $user = $request->user();
        $recent = $this->notifications->recent($user);

        return response()->json([
            'html' => view('admin.assets.notif-items', ['items' => $recent])->render(),
            'count' => $this->notifications->totalCount($user),
            'unread' => $this->notifications->unreadCount($user),
        ]);
    }

    /**
     * Just the unread number, for cheap polling.
     */
    public function count(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'unread' => $this->notifications->unreadCount($user),
            'total' => $this->notifications->totalCount($user),
        ]);
    }

    /**
     * Mark one notification read for the current account, then send the user
     * to the right dashboard with ?open=<modalKey> so the detail modal opens.
     */
    public function open(Request $request, string $key)
    {
        $user = $request->user();
        $item = $this->notifications->find($user, $key);

        // find() only returns items this user's role is allowed to see, so a
        // tampered key cannot be used to read another team's request.
        abort_if($item === null, 404, 'Notification not found.');

        $this->notifications->markRead($user, $key);

        $routeName = NotificationType::route($item->type);

        if (! $routeName || ! Route::has($routeName)) {
            // No dashboard for this type: show the detail partial instead.
            return redirect()->route('notifications.show', ['key' => $key]);
        }

        return redirect()->route($routeName, [
            'open' => $item->modalKey,
            'type' => $item->type,
        ]);
    }

    /**
     * Mark read without leaving the page (bell dropdown, before navigating).
     */
    public function read(Request $request, string $key)
    {
        $user = $request->user();

        if (! $this->notifications->markRead($user, $key)) {
            return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'unread' => $this->notifications->unreadCount($user),
        ]);
    }

    /**
     * Mark every visible notification read for the current account.
     */
    public function readAll(Request $request)
    {
        $user = $request->user();
        $marked = $this->notifications->markAllRead($user);

        return response()->json([
            'success' => true,
            'marked' => $marked,
            'unread' => $this->notifications->unreadCount($user),
        ]);
    }

    /**
     * Standalone detail view - the fallback for requests whose dashboard does
     * not render a detail modal, so "it will show the details" always holds.
     */
    public function show(Request $request, string $key)
    {
        $user = $request->user();
        $item = $this->notifications->find($user, $key);

        abort_if($item === null, 404, 'Notification not found.');

        $this->notifications->markRead($user, $key);

        $requestor = \App\Models\Requestor::with(['socmed', 'photo', 'publishing'])
            ->where('request_id', $item->requestId)
            ->first();

        return view('admin.notifications.show', [
            'item' => $item,
            'requestor' => $requestor,
        ]);
    }
}
