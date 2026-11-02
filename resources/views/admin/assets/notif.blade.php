<div class="nav-item ml-16pt dropdown dropdown-notifications">
    <button class="nav-link btn-flush dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            data-dropdown-disable-document-scroll
            data-caret="false">
        <i class="material-icons">notifications</i>
            
       <span class="badge badge-notifications badge-accent" id="notif-badge">{{ $totalNotifications }}</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <div data-perfect-scrollbar class="position-relative">
            <div class="dropdown-header"><strong>System notifications</strong></div>
          <div class="list-group list-group-flush mb-0" id="notif-list">
                
                <!-- Loop 1: Social Media Requests -->
                @foreach ($socmed as $soc)
                    <a href="{{ route('socmed.home') }}"
                       onclick="markNotificationRead(event, '{{ $soc->id }}', '{{ route('socmed.home') }}')"
                       id="notif-item-soc-{{ $soc->id }}"
                       class="list-group-item list-group-item-action unread">
                        <span class="d-flex align-items-center mb-1">
                            <small class="text-black-50">{{ $soc->created_at ? \Carbon\Carbon::parse($soc->created_at)->diffForHumans() : '' }}</small>
                            <span class="ml-auto unread-indicator bg-accent"></span>
                        </span>
                        <span class="d-flex">
                        <!-- Avatar Icon -->
                        <span class="avatar avatar-xs mr-2">
                            <span class="avatar-title rounded-circle bg-light">
                                <i class="material-icons font-size-16pt text-accent">camera</i>
                            </span>
                        </span>

                        <!-- Request Details -->
                        <span class="flex d-flex flex-column">
                            <span class="text-black-70 font-weight-bold">{{ $soc->fullname }}</span>
                            <small class="text-muted">Social Media Request</small>
                        </span>
                    </span>
                    </a>
                @endforeach

                <!-- Loop 2: Photo / Video / Publishing Requests -->
                @foreach ($photovid as $photo)
                    <a href="{{ route('photo.home') }}"
                       onclick="markNotificationReadphoto(event, '{{ $photo->id }}', '{{ route('photo.home') }}')"
                       id="notif-item-photo-{{ $photo->id }}"
                       class="list-group-item list-group-item-action unread">
                        <span class="d-flex align-items-center mb-1">
                            <small class="text-black-50">{{ $photo->created_at ? \Carbon\Carbon::parse($photo->created_at)->diffForHumans() : '' }}</small>
                            <span class="ml-auto unread-indicator bg-accent"></span>
                        </span>
                        <span class="d-flex">
                            <span class="avatar avatar-xs mr-2">
                                <span class="avatar-title rounded-circle bg-light">
                                    <i class="material-icons font-size-16pt text-accent">image</i>
                                </span>
                            </span>
                            <span class="flex d-flex flex-column">
                            <span class="text-black-70 font-weight-bold">{{ $photo->fullname }}</span>
                            <small class="text-muted">Photo Service </small>
                        </span>
                        </span>
                    </a>
                @endforeach

                @foreach ($layout as $layout)
                    <a href="{{ route('socmed.home') }}"
                       onclick="markNotificationReadlayout(event, '{{ $layout->id }}', '{{ route('layout.home') }}')"
                       id="notif-item-layout-{{ $layout->id }}"
                       class="list-group-item list-group-item-action unread">
                        <span class="d-flex align-items-center mb-1">
                            <small class="text-black-50">{{ $layout->created_at ? \Carbon\Carbon::parse($layout->created_at)->diffForHumans() : '' }}</small>
                            <span class="ml-auto unread-indicator bg-accent"></span>
                        </span>
                        <span class="d-flex">
                            <span class="avatar avatar-xs mr-2">
                                <span class="avatar-title rounded-circle bg-light">
                                    <i class="material-icons font-size-16pt text-accent">brush</i>
                                </span>
                            </span>
                            <span class="flex d-flex flex-column">
                            <span class="text-black-70 font-weight-bold">{{ $layout->fullname }}</span>
                            <small class="text-muted">Layout Request</small>
                        </span>
                        </span>
                    </a>
                @endforeach

                @foreach ($production as $production)
                    <a href="{{ route('socmed.home') }}"
                       onclick="markNotificationRead(event, '{{ $production->id }}', '{{ route('production.home') }}')"
                       id="notif-item-production-{{ $production->id }}"
                       class="list-group-item list-group-item-action unread">
                        <span class="d-flex align-items-center mb-1">
                            <small class="text-black-50">{{ $production->created_at ? \Carbon\Carbon::parse($production->created_at)->diffForHumans() : '' }}</small>
                            <span class="ml-auto unread-indicator bg-accent"></span>
                        </span>
                        <span class="d-flex">
                            <span class="avatar avatar-xs mr-2">
                                <span class="avatar-title rounded-circle bg-light">
                                    <i class="material-icons font-size-16pt text-accent">brush</i>
                                </span>
                            </span>
                            <span class="flex d-flex flex-column">
                            <span class="text-black-70 font-weight-bold">{{ $production->fullname }}</span>
                            <small class="text-muted">Layout Request</small>
                        </span>
                        </span>
                    </a>
                @endforeach

                @foreach ($uzpr as $uzpr)
                    <a href="{{ route('socmed.home') }}"
                       onclick="markNotificationRead(event, '{{ $uzpr->id }}', '{{ route('uzpr.home') }}')"
                       id="notif-item-uzpr-{{ $uzpr->id }}"
                       class="list-group-item list-group-item-action unread">
                        <span class="d-flex align-items-center mb-1">
                            <small class="text-black-50">{{ $uzpr->created_at ? \Carbon\Carbon::parse($uzpr->created_at)->diffForHumans() : '' }}</small>
                            <span class="ml-auto unread-indicator bg-accent"></span>
                        </span>
                        <span class="d-flex">
                            <span class="avatar avatar-xs mr-2">
                                <span class="avatar-title rounded-circle bg-light">
                                    <i class="material-icons font-size-16pt text-accent">brush</i>
                                </span>
                            </span>
                            <span class="flex d-flex flex-column">
                            <span class="text-black-70 font-weight-bold">{{ $uzpr->fullname }}</span>
                            <small class="text-muted">Layout Request</small>
                        </span>
                        </span>
                    </a>
                @endforeach

                @foreach ($uzpost as $uzpost)
                    <a href="{{ route('socmed.home') }}"
                       onclick="markNotificationRead(event, '{{ $uzpost->id }}', '{{ route('uzpost.home') }}')"
                       id="notif-item-uzpost-{{ $uzpost->id }}"
                       class="list-group-item list-group-item-action unread">
                        <span class="d-flex align-items-center mb-1">
                            <small class="text-black-50">{{ $uzpost->created_at ? \Carbon\Carbon::parse($uzpost->created_at)->diffForHumans() : '' }}</small>
                            <span class="ml-auto unread-indicator bg-accent"></span>
                        </span>
                        <span class="d-flex">
                            <span class="avatar avatar-xs mr-2">
                                <span class="avatar-title rounded-circle bg-light">
                                    <i class="material-icons font-size-16pt text-accent">newspaper</i>
                                </span>
                            </span>
                            <span class="flex d-flex flex-column">
                            <span class="text-black-70 font-weight-bold">{{ $uzpost->fullname }}</span>
                            <small class="text-muted">Layout Request</small>
                        </span>
                        </span>
                    </a>
                @endforeach

                 @foreach ($trade as $trade)
                    <a href="{{ route('socmed.home') }}"
                       onclick="markNotificationRead(event, '{{ $trade->id }}', '{{ route('trademark.home') }}')"
                       id="notif-item-trade-{{ $trade->id }}"
                       class="list-group-item list-group-item-action unread">
                        <span class="d-flex align-items-center mb-1">
                            <small class="text-black-50">{{ $trade->created_at ? \Carbon\Carbon::parse($trade->created_at)->diffForHumans() : '' }}</small>
                            <span class="ml-auto unread-indicator bg-accent"></span>
                        </span>
                        <span class="d-flex">
                            <span class="avatar avatar-xs mr-2">
                                <span class="avatar-title rounded-circle bg-light">
                                   <i class="material-icons font-size-16pt text-accent">copyright</i>
                                </span>
                            </span>
                            <span class="flex d-flex flex-column">
                            <span class="text-black-70 font-weight-bold">{{ $trade->fullname }}</span>
                            <small class="text-muted">Trademark Request</small>
                        </span>
                        </span>
                    </a>
                @endforeach

                @foreach ($softcopy as $softcopy)
                    <a href="{{ route('socmed.home') }}"
                       onclick="markNotificationRead(event, '{{ $softcopy->id }}', '{{ route('softcopy.home') }}')"
                       id="notif-item-softcopy-{{ $softcopy->id }}"
                       class="list-group-item list-group-item-action unread">
                        <span class="d-flex align-items-center mb-1">
                            <small class="text-black-50">{{ $softcopy->created_at ? \Carbon\Carbon::parse($softcopy->created_at)->diffForHumans() : '' }}</small>
                            <span class="ml-auto unread-indicator bg-accent"></span>
                        </span>
                        <span class="d-flex">
                            <span class="avatar avatar-xs mr-2">
                                <span class="avatar-title rounded-circle bg-light">
                                   <i class="material-icons font-size-16pt text-accent">gavel</i>
                                </span>
                            </span>
                            <span class="flex d-flex flex-column">
                            <span class="text-black-70 font-weight-bold">{{ $softcopy->fullname }}</span>
                            <small class="text-muted">Softcopy Request</small>
                        </span>
                        </span>
                    </a>
                @endforeach

            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle the asynchronous update and redirect -->
<script>
function markNotificationRead(event, id, redirectUrl) {
    event.preventDefault(); // Stop instant navigation

    // Send AJAX request to update the token column
    fetch(`/socmed/mark-read/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Redirect user to the destination page
            window.location.href = redirectUrl;
        }
    })
    .catch(error => {
        console.error('Error updating notification:', error);
        window.location.href = redirectUrl; // Redirect anyway even if fetch fails
    });
}

function markNotificationReadphoto(event, id, redirectUrl) {
    event.preventDefault(); // Stop instant navigation

    // Send AJAX request to update the token column
    fetch(`/photography/mark-read/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Redirect user to the destination page
            window.location.href = redirectUrl;
        }
    })
    .catch(error => {
        console.error('Error updating notification:', error);
        window.location.href = redirectUrl; // Redirect anyway even if fetch fails
    });
}

function markNotificationReadlayout(event, id, redirectUrl) {
    event.preventDefault(); // Stop instant navigation

    // Send AJAX request to update the token column
    fetch(`/layoutservices/mark-read/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Redirect user to the destination page
            window.location.href = redirectUrl;
        }
    })
    .catch(error => {
        console.error('Error updating notification:', error);
        window.location.href = redirectUrl; // Redirect anyway even if fetch fails
    });
}

(function () {
    function refreshNotifications() {
        if (document.hidden) return;

        fetch(window.location.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');

            // Badge number: always update
            const badge = document.getElementById('notif-badge');
            const newBadge = doc.getElementById('notif-badge');
            if (badge && newBadge) badge.textContent = newBadge.textContent;

            // List: only update while the dropdown is closed,
            // so items don't jump under the cursor
            const menu = document.querySelector('.dropdown-notifications .dropdown-menu');
            const list = document.getElementById('notif-list');
            const newList = doc.getElementById('notif-list');
            if (list && newList && !(menu && menu.classList.contains('show'))) {
                list.innerHTML = newList.innerHTML;
            }
        })
        .catch(err => console.error('Notification refresh failed:', err));
    }

    setInterval(refreshNotifications, 10000);
})();
</script>