{{-- Photography request modals (Bootstrap 4) --}}
@include('admin.assets.request-modal-styles')

@php
    $userType = auth()->user()->user_type;
    $canAssign = in_array($userType, ['content_admin', 'admin', 'superadmin'], true);
    $canEditStatus = in_array($userType, ['admin', 'superadmin'], true);
    $isContentAdmin = in_array($userType, ['content_admin'], true);

    // value => label (same values and labels as before)
    $statuses = [
        0 => 'Pending',
        1 => 'Approve',
        2 => 'Done',
        3 => 'Rejected',
    ];

    $statusColors = [
        0 => 'warning',
        1 => 'success',
        2 => 'info',
        3 => 'danger',
    ];
@endphp

<!-- pop for active -->
@foreach ($requests as $req)
    @php
        $photo = $req->photo;
        $status = $photo?->status ?? 0;
        $rid = $req->request_id;
    @endphp

    <div class="modal fade request-modal" id="viewModal-{{ $rid }}" tabindex="-1" role="dialog"
        aria-labelledby="viewModalLabel-{{ $rid }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <div class="d-flex align-items-center request-modal__heading">
                        <span class="request-modal__glyph"><i class="material-icons">photo_camera</i></span>
                        <div class="request-modal__heading">
                            <h5 class="modal-title" id="viewModalLabel-{{ $rid }}">Photography Request</h5>
                            <div class="request-modal__meta">
                                <span class="request-modal__ref">{{ $rid }}</span>
                                <span>{{ $req->fullname }}</span>
                                @if ($req->created_at)
                                <span>{{ $req->created_at->format('M d, Y') }}</span>
                                @endif
                                <span class="badge badge-pill badge-{{ $statusColors[(int) $status] ?? 'secondary' }}">
                                    {{ $statuses[(int) $status] ?? $status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Body (the Save button in the footer is linked to this form with form="...") -->
                <div class="modal-body text-left">
                    <form id="requestForm-{{ $rid }}" action="{{ route('photo.update', $rid) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">person_outline</i>Requester
                            </h6>
                            <dl class="request-modal__grid">
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Full Name</dt>
                                    <dd class="request-modal__value">{{ $req->fullname ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Email</dt>
                                    <dd class="request-modal__value">{{ $req->email ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Department</dt>
                                    <dd class="request-modal__value">{{ $req->department ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Date Needed</dt>
                                    <dd class="request-modal__value">
                                        {{ $photo?->date_needed ? \Carbon\Carbon::parse($photo->date_needed)->format('M d, Y') : 'N/A' }}
                                    </dd>
                                </div>
                            </dl>
                        </section>

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">description</i>Request Details
                            </h6>
                            <dl class="request-modal__grid">
                                <div class="request-modal__field request-modal__field--full">
                                    <dt class="request-modal__label">Event</dt>
                                    <dd class="request-modal__value">{{ $photo?->event ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field request-modal__field--full">
                                    <dt class="request-modal__label">Purpose of Request</dt>
                                    <dd class="request-modal__value">{{ $photo?->purpose_document ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Category</dt>
                                    <dd class="request-modal__value">{{ $photo?->category ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Section</dt>
                                    <dd class="request-modal__value">{{ $photo?->section ?: '—' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">schedule</i>Schedule &amp; Venue
                            </h6>
                            <dl class="request-modal__grid">
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Time Start</dt>
                                    <dd class="request-modal__value">{{ $photo?->start_time ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Time End</dt>
                                    <dd class="request-modal__value">{{ $photo?->end_time ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Venue</dt>
                                    <dd class="request-modal__value">{{ $photo?->venue ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Approve by</dt>
                                    <dd class="request-modal__value">{{ $photo?->approve ?: '—' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">assignment_ind</i>Assignment &amp; Status
                            </h6>

                            <div class="form-row">
                                <div class="form-group col-12 @if (! $canEditStatus) mb-0 @endif">
                                    <label for="produce-{{ $rid }}" class="form-label">Photographer</label>

                                    @if ($canAssign && empty($photo?->produce))
                                        <!-- Show select dropdown when produce is empty -->
                                        <select name="produce" id="produce-{{ $rid }}" class="form-control"
                                            data-target="#user_assigned_{{ $rid }}"
                                            data-email-target="#user_email_{{ $rid }}">
                                            <option value="" disabled selected>Select a user</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->employee_id }}">{{ $user->fullname }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <!-- Read-only display when a photographer is already assigned -->
                                        @unless ($canAssign)
                                            <input type="hidden" name="produce"
                                                value="{{ $photo?->user?->employee_id }}">
                                        @endunless
                                        <input type="text" id="produce-{{ $rid }}" class="form-control" readonly
                                            value="{{ $photo?->user?->fullname ?? 'Not yet assigned' }}">
                                    @endif
                                </div>
                            </div>

                            @if ($canEditStatus)
                                <div class="form-row">
                                    <div class="form-group col-12 mb-0">
                                        <label for="status-{{ $rid }}" class="form-label">Status</label>
                                        <select name="status" id="status-{{ $rid }}"
                                            class="form-control status-dropdown" data-id="{{ $rid }}" required>
                                            @unless (array_key_exists($status, $statuses))
                                                <option value="{{ $status }}" selected>{{ $status }}</option>
                                            @endunless
                                            @foreach ($statuses as $value => $label)
                                                <option value="{{ $value }}" @selected((int) $status === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </section>
                    </form>
                </div>

                <!-- Footer -->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>

                    <div class="footer-actions request-modal__actions">
                        @if ($canEditStatus)
                            <button type="submit" form="requestForm-{{ $rid }}"
                                class="btn btn-primary">Save Changes</button>
                        @endif

                        @if (empty($photo?->produce))
                            <form action="{{ route('photo.taketask', $rid) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="user" value="{{ auth()->user()->employee_id }}">
                                <button type="submit" class="btn btn-success">Take Task</button>
                            </form>
                        @endif

                        @if ($isContentAdmin)
                            @if (empty($req->publishing?->produce))
                                <form action="{{ route('layout.assignTask', $rid) }}" method="POST"
                                    onsubmit="sendAssignEmailJS(event, @js($rid), @js(auth()->user()->fullname), @js($req->publishing?->purpose))">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="user_assigned" id="user_assigned_{{ $rid }}"
                                        value="">
                                    <input type="hidden" name="user_email" id="user_email_{{ $rid }}"
                                        value="">
                                    <button type="submit" id="assignBtn-{{ $rid }}"
                                        class="btn btn-info">Assign Task</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endforeach

<!-- Delete Confirmation Bootstrap Modal -->
<div class="modal fade request-modal" id="deleteConfirmModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this request? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <form id="activeDeleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts (unchanged logic; the status script now appears once instead of once per request) -->
<script>
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('status-dropdown')) {
            let requestId = e.target.getAttribute('data-id');
            let publishedInput = document.getElementById('published-date-' + requestId);

            // If "Posted" (value "2") is selected and the input exists
            if (publishedInput && e.target.value === '2') {
                let today = new Date().toISOString().split('T')[0];
                publishedInput.value = today;
            }
        }
    });
</script>
<script>
    // When a user is picked, copy their id, email and name into the hidden inputs
    document.addEventListener('change', function(e) {
        const select = e.target.closest('.assign-select');
        if (!select) return;

        const option = select.options[select.selectedIndex];
        const idInput = document.getElementById(select.dataset.target.replace('#', ''));
        const emailInput = document.getElementById(select.dataset.emailTarget.replace('#', ''));

        if (idInput) idInput.value = select.value;
        if (emailInput) {
            emailInput.value = option.dataset.email || '';
            emailInput.dataset.userName = option.text.trim();
        }
    });

    function sendAssignEmailJS(event, requestId, assignedBy, purpose) {
        event.preventDefault(); // Prevent standard page reload

        const form = event.target;
        const userId = document.getElementById('user_assigned_' + requestId).value;
        const emailInput = document.getElementById('user_email_' + requestId);
        const submitBtn = document.getElementById('assignBtn-' + requestId);

        if (!userId || !emailInput.value) {
            alert('Please select a user to assign first.');
            return;
        }

        // Disable button & show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Sending...';

        // Same variables as your approval email, so the same template works
        // Variables that match the template text exactly
        const templateParams = {
            to_email: emailInput.value,
            to_title: 'New Task Assigned to You',
            to_name: emailInput.dataset.userName || '',
            assigned_by: assignedBy,
            request_id: requestId,
            purpose: purpose || 'N/A'
        };

        emailjs.send('service_ydriqxs', 'YOUR_ASSIGN_TEMPLATE_ID', templateParams)

        emailjs.send('service_ydriqxs', 'template_1njzwjo', templateParams)
            .then(function(response) {
                console.log('EmailJS SUCCESS!', response.status, response.text);

                // Save the assignment through the same Laravel route the form uses
                fetch(form.action, {
                        method: 'POST', // the form's hidden _method field turns this into PUT
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Server returned ' + res.status);
                        alert('Task assigned and email sent successfully!');
                        location.reload(); // Refresh to show the assigned user
                    })
                    .catch(err => {
                        console.error('Database update error:', err);
                        alert('Email sent, but failed to save the assignment in the database.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Assign Task';
                    });

            }, function(error) {
                console.error('EmailJS FAILED...', error);
                alert('Failed to send the email: ' + ((error && (error.text || error.message)) || 'Unknown error'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Assign Task';
            });
    }
</script>
<script>
    function openDeleteModal(actionUrl) {
        // Set the form action URL dynamically to the specific request route
        document.getElementById('activeDeleteForm').action = actionUrl;

        // Show the Bootstrap modal
        $('#deleteConfirmModal').modal('show');
    }
</script>