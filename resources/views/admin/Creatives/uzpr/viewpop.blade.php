{{-- UZ PR services: request details modal --}}
@include('admin.assets.request-modal-styles')

@php
    $statusMap = [
        0 => ['Pending', 'warning'],
        1 => ['Approved', 'success'],
        2 => ['In progress', 'info'],
        3 => ['Rejected', 'danger'],
    ];
@endphp

@foreach ($requests as $req)
    @php
        $info = $req->information;
        $statusValue = (int) ($info?->status ?? 0);
        [$statusLabel, $statusColor] = $statusMap[$statusValue] ?? ['Unknown', 'secondary'];
        $dateNeeded = $info?->date_needed ? \Carbon\Carbon::parse($info->date_needed) : null;
        $dateEvent = $info?->date_event ? \Carbon\Carbon::parse($info->date_event) : null;
    @endphp

    <div class="modal fade request-modal" id="viewModal-{{ $req->request_id }}" tabindex="-1" role="dialog"
        aria-labelledby="viewModalLabel-{{ $req->request_id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form action="{{ route('uzpr.update', $req->request_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <div class="d-flex align-items-center request-modal__heading">
                            <span class="request-modal__glyph"><i class="material-icons">article</i></span>
                            <div class="request-modal__heading">
                                <h5 class="modal-title" id="viewModalLabel-{{ $req->request_id }}">UZ PR Request</h5>
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
                        @php
                            $sdgMap = [
                                '1' => 'No Poverty', '2' => 'Zero Hunger', '3' => 'Good Health and Well-being',
                                '4' => 'Quality Education', '5' => 'Gender Equality', '6' => 'Clean Water and Sanitation',
                                '7' => 'Affordable and Clean Energy', '8' => 'Decent Work and Economic Growth',
                                '9' => 'Industry, Innovation and Infrastructure', '10' => 'Reduced Inequalities',
                                '11' => 'Sustainable Cities and Communities', '12' => 'Responsible Consumption and Production',
                                '13' => 'Climate Action', '14' => 'Life Below Water', '15' => 'Life on Land',
                                '16' => 'Peace, Justice and Strong Institutions', '17' => 'Partnerships for the Goals'
                            ];
                            $selectedSdgs = $info?->sdg ?? [];
                        @endphp

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
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Date of the Event</dt>
                                    <dd class="request-modal__value">
                                        {{ $dateEvent ? $dateEvent->format('M d, Y') : 'N/A' }}
                                    </dd>
                                </div>
                                <div class="request-modal__field request-modal__field--full">
                                    <dt class="request-modal__label">SDG Included</dt>
                                    <dd class="request-modal__value">
                                        <span class="request-modal__chips">
                                            @forelse($selectedSdgs as $id)
                                                <span class="request-modal__chip">{{ $id }}. {{ $sdgMap[$id] ?? 'Unknown' }}</span>
                                            @empty
                                                <span class="request-modal__value request-modal__value--muted">N/A</span>
                                            @endforelse
                                        </span>
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
                                    <dd class="request-modal__value">{{ $info?->purpose ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Specification</dt>
                                    <dd class="request-modal__value">{{ $info?->specification ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Section</dt>
                                    <dd class="request-modal__value">{{ $info?->section ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Approved By</dt>
                                    <dd class="request-modal__value">{{ $info?->approve ?: '—' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">attachment</i>Attachments
                            </h6>
                            <dl class="request-modal__grid">
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Write-up</dt>
                                    <dd class="request-modal__value">
                                        @if($info?->reference)
                                            <a href="{{ $info->content_information }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                                                <i class="material-icons mr-1" style="font-size: 16px;">attachment</i>
                                                View Attachment
                                            </a>
                                        @else
                                            <span class="text-muted small">No attachment</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Photos</dt>
                                    <dd class="request-modal__value">
                                        @if($info?->reference)
                                            <a href="{{ $info->reference }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                                                <i class="material-icons mr-1" style="font-size: 16px;">attachment</i>
                                                View Attachment
                                            </a>
                                        @else
                                            <span class="text-muted small">No attachment</span>
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
                                    @if(empty($info?->produce))
                                    <label><strong>Assign to:</strong></label>
                                        <!-- Show select dropdown when produce is empty -->
                                       <select name="produce" class="form-control assign-select" required
                                            data-target="#user_assigned_{{ $req->request_id }}"
                                            data-email-target="#user_email_{{ $req->request_id }}">
                                            <option value="" disabled selected>Select a user</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->employee_id }}" data-email="{{ $user->email }}">{{ $user->fullname }}</option>
                                            @endforeach
                                        </select>
                                    @else

                                    <label><strong>Assigned:</strong></label>
                                    <input type="text" class="form-control" name="produce" value="{{ $info?->user?->employee_id  }}" hidden><span class="request-modal__value d-block">{{$info?->user?->fullname  }}</span>
                                    @endif
                                </div>
                            </div>
                        </section>

                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">tune</i>Status
                            </h6>
                            <div class="row">
                                <div class="col-md-12 form-group mb-0">
                                    <select name="status" class="form-control status-dropdown" data-id="{{ $req->request_id }}" required>
                                       <option value="{{ $req->information->status }}"  selected>
                                            @if($req->information->status == 0) Pending
                                            @elseif($req->information->status == 1) Approve
                                            @elseif($req->information->status == 2) in progress
                                            @elseif($req->information->status == 3) Rejected
                                            @else {{ $req->information->status }}
                                            @endif
                                        </option>
                                        <option value="1">Approve</option>
                                        <option value="2">in progress</option>
                                        <option value="0">Pending</option>
                                        <option value="3">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </section>

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
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @auth

                    @if (in_array(auth()->user()->user_type, ['admin', 'superadmin'], true))
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    @endif
            </form>
            @if (empty($req->information?->produce))
            <form action="{{ route('uzpr.taketask', $req->request_id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="user" value="{{ auth()->user()->employee_id }}">
                <button type="submit" class="btn btn-success">Take Task</button>
            </form>
            @endif
            @if (in_array(auth()->user()->user_type, ['creative_admin'], true))
            @if (empty($req->information?->produce))
            <form action="{{ route('uzpr.assignTask', $req->request_id) }}" method="POST"
                onsubmit="sendAssignEmailJS(event, @js($req->request_id), @js(auth()->user()->fullname), @js($req->information?->purpose))">
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