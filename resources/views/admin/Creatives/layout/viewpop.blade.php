{{-- Layout services: request details modal --}}
@include('admin.assets.request-modal-styles')

@php
    $statusMap = [
        0 => ['Pending', 'warning'],
        1 => ['Approved', 'success'],
        2 => ['In progress', 'info'],
        3 => ['Rejected', 'danger'],
        4 => ['For approval', 'primary'],
    ];
@endphp

@foreach ($requests as $req)
    @php
        $statusValue = (int) ($req->publishing?->status ?? 0);
        [$statusLabel, $statusColor] = $statusMap[$statusValue] ?? ['Unknown', 'secondary'];
        $dateNeeded = $req->publishing?->date_needed
            ? \Carbon\Carbon::parse($req->publishing->date_needed)
            : null;
        $dateApproved = $req->publishing?->date_approved
            ? \Carbon\Carbon::parse($req->publishing->date_approved)
            : null;
    @endphp

    <div class="modal fade request-modal" id="viewModal-{{ $req->request_id }}" tabindex="-1" role="dialog"
        aria-labelledby="viewModalLabel-{{ $req->request_id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form action="{{ route('layout.update', $req->request_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <div class="d-flex align-items-center request-modal__heading">
                            <span class="request-modal__glyph"><i class="material-icons">dashboard_customize</i></span>
                            <div class="request-modal__heading">
                                <h5 class="modal-title" id="viewModalLabel-{{ $req->request_id }}">Layout Request</h5>
                                <div class="request-modal__meta">
                                    <span class="request-modal__ref">{{ $req->request_id }}</span>
                                    <span>{{ $req->fullname }}</span>
                                    @if ($req->created_at)
                                    <span>{{ $req->created_at->format('M d, Y') }}</span>
                                    @endif
                                    <span class="badge badge-pill badge-{{ $statusColor }}">{{ $statusLabel }}</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-left">
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
                                        {{ $dateNeeded ? $dateNeeded->format('M d, Y') : 'N/A' }}
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
                                    <dt class="request-modal__label">Purpose</dt>
                                    <dd class="request-modal__value">{{ $req->publishing?->purpose ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Specification</dt>
                                    <dd class="request-modal__value">{{ $req->publishing?->specification ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Category</dt>
                                    <dd class="request-modal__value">{{ $req->publishing?->category ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Section</dt>
                                    <dd class="request-modal__value">{{ $req->publishing?->section ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field request-modal__field--full">
                                    <dt class="request-modal__label">Content Information</dt>
                                    <dd class="request-modal__value">{{ $req->publishing?->content_information ?: '—' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">attachment</i>Attachment
                            </h6>
                            @if ($req->publishing?->reference)
                            <div class="input-group">
                                <input type="text" readonly class="form-control bg-light"
                                    value="{{ $req->publishing->reference }}">
                                <div class="input-group-append">
                                    <a href="{{ $req->publishing->reference }}" target="_blank"
                                        class="btn btn-outline-primary">
                                        <i class="material-icons"
                                            style="font-size: 16px; vertical-align: middle;">open_in_new</i>
                                        Open Link
                                    </a>
                                </div>
                            </div>
                            @else
                            <input type="text" readonly class="form-control bg-light" value="No attachment provided">
                            @endif
                        </section>

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">verified_user</i>Approval
                            </h6>
                            <dl class="request-modal__grid">
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Approved By</dt>
                                    <dd class="request-modal__value">{{ $req->publishing?->approve ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Date Approved</dt>
                                    <dd class="request-modal__value">
                                        @if ($dateApproved)
                                            {{ $dateApproved->format('M d, Y') }}
                                            <span class="text-50 d-block">{{ $dateApproved->diffForHumans() }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </section>

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">assignment_ind</i>Assignment
                            </h6>
                            <div class="row">
                                <div class="col-md-12 form-group mb-0">
                                    @if (in_array(auth()->user()->user_type, ['admin', 'superadmin', 'creative_admin'], true) && empty($req->publishing?->produce))
                                    <label><strong>Assign to:</strong></label>
                                    <!-- Show select dropdown when produce is empty -->
                                    <select name="produce" class="form-control assign-select"
                                        data-target="#user_assigned_{{ $req->request_id }}"
                                        data-email-target="#user_email_{{ $req->request_id }}">
                                        <option value="" disabled selected>Select a user</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->employee_id }}" data-email="{{ $user->email }}">
                                            {{ $user->fullname }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <label><strong>Assigned:</strong></label>
                                    <input type="hidden" name="produce" value="{{ $req->publishing?->user?->employee_id }}">
                                    <input type="text" readonly class="form-control"
                                        value="{{ $req->publishing?->user?->fullname ?? 'Not yet assigned' }}">
                                    @endif
                                </div>
                            </div>
                        </section>

                        @if (in_array(auth()->user()->user_type, ['admin', 'superadmin'], true))
                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">tune</i>Status
                            </h6>
                            <div class="row">
                                <div class="col-md-12 form-group mb-0">
                                    <select name="status" class="form-control status-dropdown" data-id="{{ $req->request_id }}">
                                        <option value="{{ $req->publishing->status }}" selected>
                                            @if ($req->publishing->status == 0)
                                            Pending
                                            @elseif($req->publishing->status == 1)
                                            Approve
                                            @elseif($req->publishing->status == 2)
                                            in progress
                                            @elseif($req->publishing->status == 4)
                                            for Approval
                                            @elseif($req->publishing->status == 3)
                                            Rejected
                                            @else
                                            {{ $req->publishing->status }}
                                            @endif
                                        </option>
                                        <option value="1">Approve</option>
                                        <option value="2">in progress</option>
                                        <option value="0">Pending</option>
                                        <option value="3">Rejected</option>
                                        <option value="4">For Approval</option>
                                    </select>
                                </div>
                            </div>
                        </section>
                        @endif
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @auth
                    @if (in_array(auth()->user()->user_type, ['admin', 'superadmin'], true))
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    @endif
                    @endauth
            </form>
            @auth
            @if (empty($req->publishing?->produce))
            <form action="{{ route('layout.taketask', $req->request_id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success">Take Task</button>
            </form>
            @endif
            @if (in_array(auth()->user()->user_type, ['creative_admin'], true))
            @if (empty($req->publishing?->produce))
            <form action="{{ route('layout.assignTask', $req->request_id) }}" method="POST"
                onsubmit="sendAssignEmailJS(event, @js($req->request_id), @js(auth()->user()->fullname), @js($req->publishing?->purpose))">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_assigned" id="user_assigned_{{ $req->request_id }}" value="">
                <input type="hidden" name="user_email" id="user_email_{{ $req->request_id }}" value="">
                <button type="submit" id="assignBtn-{{ $req->request_id }}" class="btn btn-info">Assign Task</button>
            </form>
            @endif
            @endif
            @endauth
                </div>

    </div>
</div>
</div>
@endforeach

<!-- Delete Confirmation Bootstrap Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel"
    aria-hidden="true">
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
<script>
    function openDeleteModal(actionUrl) {
        // Set the form action URL dynamically to the specific request route
        document.getElementById('activeDeleteForm').action = actionUrl;

        // Show the Bootstrap modal
        $('#deleteConfirmModal').modal('show');
    }
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