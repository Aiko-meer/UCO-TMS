{{--
    The notification list itself, shared by:
      - the bell dropdown (rendered on first paint),
      - the polled JSON feed (/notifications/feed), which swaps this HTML in.

    Every item is one account's view: "unread" here means unread by the
    signed-in user, resolved by NotificationService.
--}}
@forelse ($items as $item)
    <a href="{{ $item->openUrl }}"
       data-notif-key="{{ $item->key }}"
       class="list-group-item list-group-item-action {{ $item->read ? '' : 'unread' }}">
        <span class="d-flex align-items-center mb-1">
            <small class="text-black-50">{{ $item->timeAgo() }}</small>
            @if ($item->statusLabel())
                <span class="badge badge-soft-secondary ml-2">{{ $item->statusLabel() }}</span>
            @endif
            @unless ($item->read)
                <span class="ml-auto unread-indicator bg-accent" title="Unread"></span>
            @endunless
        </span>
        <span class="d-flex">
            <span class="avatar avatar-xs mr-2">
                <span class="avatar-title rounded-circle bg-light">
                    <i class="material-icons font-size-16pt text-{{ $item->type === 'softcopy' ? 'warning' : ($item->type === 'trademark' ? 'success' : 'accent') }}">{{ $item->icon() }}</i>
                </span>
            </span>
            <span class="flex d-flex flex-column">
                <span class="text-black-70 font-weight-bold">{{ $item->fullname }}</span>
                <small class="text-muted">{{ $item->label() }}</small>
            </span>
        </span>
    </a>
@empty
    <div class="list-group-item text-center text-muted py-4">
        <i class="material-icons d-block mb-2">notifications_off</i>
        <small>No new requests</small>
    </div>
@endforelse
