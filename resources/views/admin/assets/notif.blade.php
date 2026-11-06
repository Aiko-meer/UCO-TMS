<div class="nav-item ml-16pt dropdown dropdown-notifications">
    <button class="nav-link btn-flush dropdown-toggle"
        type="button"
        data-toggle="dropdown"
        data-dropdown-disable-document-scroll
        data-caret="false"
        aria-label="Notifications">
        <i class="material-icons">notifications</i>

        <span class="badge badge-notifications badge-accent"
            id="notif-badge"
            @if (($unreadNotifications ?? 0) === 0) style="display:none" @endif>{{ $unreadNotifications ?? 0 }}</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right" style="min-width: 320px;">
        <div data-perfect-scrollbar class="position-relative">
            <div class="dropdown-header d-flex align-items-center">
                <strong>System notifications</strong>
                <button type="button"
                    id="notif-mark-all"
                    class="btn btn-flush btn-sm ml-auto text-muted"
                    style="font-size: .75rem;">Mark all read</button>
            </div>
            <div class="list-group list-group-flush mb-0" id="notif-list">
                @include('admin.assets.notif-items', ['items' => $notificationItems])
            </div>
            <a href="{{ route('notifications.index') }}" class="dropdown-footer d-block text-center py-8pt">
                <small>View all</small>
            </a>
        </div>
    </div>
</div>

<script>
(function () {
    if (window.__notifBellReady) { return; }
    window.__notifBellReady = true;

    var csrf = '{{ csrf_token() }}';
    var badge = document.getElementById('notif-badge');
    var list = document.getElementById('notif-list');
    var markAll = document.getElementById('notif-mark-all');

    function setUnread(n) {
        if (!badge) { return; }
        badge.textContent = n;
        // Hide the badge at zero rather than showing a "0" bubble.
        badge.style.display = n > 0 ? '' : 'none';
    }

    function refresh() {
        fetch('{{ route('notifications.feed') }}', {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) {
            if (!data) { return; }
            if (list) { list.innerHTML = data.html; }
            setUnread(data.unread);
        })
        .catch(function () { /* keep polling, never break the page */ });
    }

    // A click marks the notification read for THIS account only, then follows
    // the server-side deep link (/notifications/{key}/open) which redirects to
    // the right dashboard with ?open=<modalKey>.
    document.addEventListener('click', function (event) {
        var link = event.target.closest ? event.target.closest('[data-notif-key]') : null;
        if (!link) { return; }

        var key = link.getAttribute('data-notif-key');
        if (!key) { return; }

        event.preventDefault();
        var target = link.getAttribute('href');

        fetch('/notifications/' + encodeURIComponent(key) + '/read', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) {
            if (data && typeof data.unread === 'number') { setUnread(data.unread); }
        })
        .catch(function () { /* navigate anyway */ })
        .finally(function () { window.location.href = target; });
    });

    if (markAll) {
        markAll.addEventListener('click', function () {
            fetch('{{ route('notifications.readAll') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                credentials: 'same-origin'
            })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function () { refresh(); })
            .catch(function () { /* ignore */ });
        });
    }

    // Realtime is optional: the Echo listener just triggers a refetch. Polling
    // keeps the bell correct when Reverb is not running, which is the default.
    var pollMs = {{ (int) config('notifications.poll_seconds', 45) }} * 1000;
    if (pollMs > 0) { setInterval(refresh, pollMs); }

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') { refresh(); }
    });

    if (window.Echo) {
        try {
            window.Echo.channel('admin.notifications')
                .listen('.notifications.updated', function () { refresh(); });
        } catch (e) { /* Echo unavailable: polling covers it */ }
    }
})();
</script>
