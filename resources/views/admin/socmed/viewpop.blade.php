{{-- Social media request: view / update popup (one modal per request) --}}
@include('admin.assets.request-modal-styles')

@php
    // status value => [label, bootstrap colour]
    $statusMap = [
        0 => ['Pending',  'warning'],
        1 => ['Approved', 'info'],
        2 => ['Posted',   'success'],
        3 => ['Rejected', 'danger'],
    ];
@endphp

@foreach($requests as $req)
    @php
        $info = $req->socmed;

        // ----- Status -----
        $statusInt = is_null($info?->status) ? null : (int) $info->status;
        [$statusLabel, $statusColor] = $statusMap[$statusInt] ?? ['Unknown', 'secondary'];

        // ----- Requester -----
        $fullName = collect([$req->f_name, $req->middle, $req->l_name])->filter()->implode(' ');

        // ----- Album photo (stored as a JSON string such as "uploads\/albums\/file.jpg") -----
        $albumRaw     = $info?->album_photo;
        $albumDecoded = $albumRaw ? json_decode($albumRaw, true) : null;
        $albumPath    = is_array($albumDecoded) ? ($albumDecoded[0] ?? null)
                      : (is_string($albumDecoded) ? $albumDecoded : $albumRaw);
        $albumExt     = $albumPath ? strtolower(pathinfo($albumPath, PATHINFO_EXTENSION)) : null;
        $albumIsImage = in_array($albumExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);

        // ----- Content banner attachment (a link, or a legacy list of uploaded files) -----
        $attRaw   = $info?->content_attachement;
        $attUrl   = null;
        $attFiles = [];
        if (!empty($attRaw) && $attRaw !== '[]') {
            $attDecoded = json_decode($attRaw, true);
            if (is_array($attDecoded)) {
                $attFiles = array_values(array_filter($attDecoded, 'is_string'));
            } else {
                $candidate = is_string($attDecoded) ? $attDecoded : stripslashes(trim($attRaw, '"'));
                // Only allow http(s) links so nothing like javascript: can end up in an href
                if (preg_match('#^https?://#i', $candidate) && filter_var($candidate, FILTER_VALIDATE_URL)) {
                    $attUrl = $candidate;
                }
            }
        }

        $isPublished = !empty($info?->published);
    @endphp

    <div class="modal fade request-modal" id="viewModal-{{ $req->request_id }}" tabindex="-1" role="dialog"
         aria-labelledby="viewModalLabel-{{ $req->request_id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <form action="{{ route('socmed.update', $req->request_id) }}" method="POST" class="d-flex flex-column" style="min-height: 0;">
                    @csrf
                    @method('PUT')

                    {{-- ===== Header ===== --}}
                    <div class="modal-header">
                        <div class="d-flex align-items-center request-modal__heading">
                            <span class="request-modal__glyph"><i class="material-icons">share</i></span>
                            <div class="request-modal__heading">
                                <h5 class="modal-title" id="viewModalLabel-{{ $req->request_id }}">
                                    Social Media Request
                                </h5>
                                <div class="request-modal__meta">
                                    <span class="request-modal__ref">{{ $req->request_id }}</span>
                                    <span>{{ $fullName ?: '—' }}</span>
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

                    {{-- ===== Body ===== --}}
                    <div class="modal-body text-left px-4">

                        {{-- Requester --}}
                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">person_outline</i>Requester
                            </h6>
                            <dl class="request-modal__grid">
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Full Name</dt>
                                    <dd class="request-modal__value">{{ $fullName ?: '—' }}</dd>
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
                                        {{ $info?->date_needed ? \Carbon\Carbon::parse($info->date_needed)->format('M d, Y') : 'N/A' }}
                                    </dd>
                                </div>
                            </dl>
                        </section>

                        {{-- Request details --}}
                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">description</i>Request Details
                            </h6>
                            <dl class="request-modal__grid">
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Category</dt>
                                    <dd class="request-modal__value">{{ $info?->category ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Section</dt>
                                    <dd class="request-modal__value">{{ $info?->section ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Specification</dt>
                                    <dd class="request-modal__value">{{ $info?->specification ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Approved By</dt>
                                    <dd class="request-modal__value">{{ $info?->approve ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field request-modal__field--full">
                                    <dt class="request-modal__label">Purpose</dt>
                                    <dd class="request-modal__value">{{ $info?->purpose ?: '—' }}</dd>
                                </div>
                                <div class="request-modal__field request-modal__field--full">
                                    <dt class="request-modal__label">Content Information</dt>
                                    <dd class="request-modal__value">{{ $info?->content_information ?: '—' }}</dd>
                                </div>
                            </dl>
                        </section>

                        {{-- Attachments --}}
                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">attachment</i>Attachments
                            </h6>

                            {{-- Album photo --}}
                            <div class="request-modal__label">Album Photo</div>
                            @if(!empty($albumPath))
                                <div class="d-flex align-items-center p-3 border rounded bg-light mb-3">
                                    <div class="mr-3">
                                        @if($albumIsImage)
                                            <img src="{{ asset($albumPath) }}" alt="Album photo"
                                                 class="rounded border" style="width: 56px; height: 56px; object-fit: cover;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center rounded border bg-white"
                                                 style="width: 56px; height: 56px;">
                                                <i class="material-icons text-muted">description</i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="font-weight-bold text-truncate">{{ basename($albumPath) }}</div>
                                        <small class="text-muted">{{ $albumIsImage ? 'Image file' : strtoupper($albumExt ?: 'File') . ' file' }}</small>
                                    </div>
                                    <a href="{{ asset($albumPath) }}" target="_blank" rel="noopener noreferrer"
                                       class="btn btn-sm btn-outline-primary ml-3">
                                        {{ $albumIsImage ? 'View Full Size' : 'Open File' }}
                                    </a>
                                </div>
                            @else
                                <div class="p-3 border rounded bg-light text-muted small mb-3">
                                    No album photo attached.
                                </div>
                            @endif

                            {{-- Content banner --}}
                            <div class="request-modal__label">Content Banner</div>
                            @if($attUrl)
                                <a href="{{ $attUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                                    <i class="material-icons mr-1" style="font-size: 16px;">link</i>
                                    Open Google Drive Link
                                </a>
                            @elseif(!empty($attFiles))
                                <ul class="list-unstyled mb-0">
                                    @foreach($attFiles as $file)
                                        <li class="small">{{ basename($file) }}</li>
                                    @endforeach
                                </ul>
                            @elseif(!empty($attRaw) && $attRaw !== '[]')
                                <div class="p-3 border rounded bg-light text-muted small">No valid reference found.</div>
                            @else
                                <div class="p-3 border rounded bg-light text-muted small">No file currently attached.</div>
                            @endif
                        </section>

                        {{-- Production --}}
                        <section class="request-modal__section">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">movie_creation</i>Production
                            </h6>
                            <dl class="request-modal__grid request-modal__grid--single">
                                <div class="request-modal__field">
                                    <dt class="request-modal__label">Produced By</dt>
                                    <dd class="request-modal__value">
                                        @if(empty($info?->produce))
                                            <span class="request-modal__value--muted">Will be updated once the Creative team finishes the layout.</span>
                                        @else
                                            {{ $info?->user?->fullname ?? $info?->produce ?? 'No User Found' }}
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </section>

                        {{-- Editable: status & publish date --}}
                        <div class="request-modal__panel mb-0">
                            <h6 class="request-modal__section-title">
                                <i class="material-icons">tune</i>Update Request
                            </h6>
                            <div class="form-row">
                                <div class="form-group col-md-6 mb-md-0">
                                    <label for="status-{{ $req->request_id }}" class="form-label">Status</label>
                                    <select name="status" id="status-{{ $req->request_id }}"
                                            class="form-control status-dropdown" data-id="{{ $req->request_id }}" required>
                                        @if(is_null($statusInt))
                                            <option value="" disabled selected>Select status</option>
                                        @endif
                                        @foreach($statusMap as $value => [$label, $color])
                                            <option value="{{ $value }}" @selected($statusInt === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 mb-0">
                                    <label for="published-date-{{ $req->request_id }}" class="form-label">Date Published</label>
                                    {{-- Always readonly: filled automatically when the status is set to "Posted".
                                         It keeps its name so the saved date is re-submitted and never wiped. --}}
                                    @php
                                        $publishedValue = $isPublished
                                            ? \Carbon\Carbon::parse($info->published)->format('Y-m-d')
                                            : '';
                                    @endphp
                                    <input type="date" name="published" id="published-date-{{ $req->request_id }}"
                                           class="form-control" readonly tabindex="-1"
                                           style="background-color: #e9ecef; cursor: not-allowed;"
                                           data-original="{{ $publishedValue }}"
                                           value="{{ $publishedValue }}">
                                    <small class="form-text text-muted">
                                        {{ $isPublished ? 'Date this request was posted.' : 'Set automatically when the status is “Posted”.' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Footer ===== --}}
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- Date Published follows the status (one listener for all modals):
     "Posted" fills today's date; any other status puts back the saved value (blank if never posted). --}}
<script>
    document.addEventListener('change', function (e) {
        if (!e.target || !e.target.classList.contains('status-dropdown')) return;

        var dateInput = document.getElementById('published-date-' + e.target.getAttribute('data-id'));
        if (!dateInput) return;

        if (e.target.value === '2') {
            // Keep an existing saved date; otherwise use today's local date
            // (toISOString alone would use UTC and can be a day off)
            if (!dateInput.dataset.original) {
                var now = new Date();
                dateInput.value = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
                    .toISOString().slice(0, 10);
            }
        } else {
            dateInput.value = dateInput.dataset.original || '';
        }
    });
</script>

{{-- Delete confirmation modal (form action is set by the table scripts) --}}
<div class="modal fade request-modal" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this request? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <form id="activeDeleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>