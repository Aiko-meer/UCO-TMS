<div class="nav-item ml-16pt dropdown dropdown-notifications">
    <button class="nav-link btn-flush dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            data-dropdown-disable-document-scroll
            data-caret="false">
        <i class="material-icons">notifications</i>
            
        <span class="badge badge-notifications badge-accent">{{$totalNotifications}}</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <div data-perfect-scrollbar class="position-relative">
            <div class="dropdown-header"><strong>System notifications</strong></div>
            <div class="list-group list-group-flush mb-0">
                
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
                       onclick="markNotificationRead(event, '{{ $layout->id }}', '{{ route('socmed.home') }}')"
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
</script>