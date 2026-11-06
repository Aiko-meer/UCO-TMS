{{--
    New requests panel for the dashboard.

    Everything here is computed for the signed-in account through
    NotificationService, so the count and the unread dots are this user's own -
    never a shared flag. That is the whole point: one account reading a request
    must not clear it for anybody else.
--}}
@php
    $__service = app(\App\Services\NotificationService::class);
    $__user = auth()->user();
    $__new = $__user ? $__service->recent($__user, 6) : collect();
    $__unread = $__user ? $__service->unreadCount($__user) : 0;
@endphp

@if ($__new->isNotEmpty())
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title mb-0">New requests</h4>
            @if ($__unread > 0)
                <span class="badge badge-danger ml-2">{{ $__unread }} unread</span>
            @endif
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary ml-auto">
                View all
            </a>
        </div>
        <div class="list-group list-group-flush">
            @foreach ($__new as $item)
                <a href="{{ $item->openUrl }}"
                   class="list-group-item list-group-item-action d-flex align-items-center">
                    <span class="material-icons text-muted mr-3">{{ $item->icon() }}</span>
                    <span class="flex">
                        <span class="d-block font-weight-bold">
                            {{ $item->label() }} &middot; {{ $item->requestId }}
                            @if (! $item->read)
                                <span class="badge badge-danger ml-1">New</span>
                            @endif
                        </span>
                        <span class="text-muted small">
                            {{ $item->fullname }}
                            @if ($item->department) &middot; {{ $item->department }} @endif
                            @if ($item->statusLabel()) &middot; {{ $item->statusLabel() }} @endif
                        </span>
                    </span>
                    <span class="text-muted small ml-auto">{{ $item->timeAgo() }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif
