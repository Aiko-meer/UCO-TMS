@foreach ($softCopies as $soft)
<!-- Professional View Details Modal -->
<div class="modal fade" id="viewModal-{{ $soft->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel-{{ $soft->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title font-weight-bold mb-1" id="viewModalLabel-{{ $soft->id }}">
                        Request Details — <span class="text-primary">#{{ $soft->request_id }}</span>
                    </h5>
                    <small class="text-muted">
                        Submitted on {{ $soft->created_at ? $soft->created_at->format('M d, Y h:i A') : 'N/A' }}
                    </small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-4">
                <div class="row align-items-center">
                    
                    <!-- Left Side: ID Attachment / Image Preview -->
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <h6 class="text-uppercase font-weight-bold mb-3 text-muted" style="font-size: 11px; letter-spacing: 0.5px;">
                            ID Attachment
                        </h6>
                        <div class="border rounded p-2 bg-light shadow-sm" style="max-height: 280px; overflow: hidden;">
                            @if($soft->id_attachment)
                                <a href="{{ asset('storage/' . $soft->id_attachment) }}" target="_blank" title="Click to view full image">
                                    <img src="{{ asset('storage/' . $soft->id_attachment) }}" 
                                         alt="Student ID" 
                                         class="img-fluid rounded" 
                                         style="max-height: 200px; object-fit: contain; width: 100%;">
                                </a>
                                <small class="d-block text-muted mt-2" style="font-size: 11px;">Click image to view full size</small>
                            @else
                                <div class="py-5 text-muted small">No ID attached</div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Side: Detailed Info Grid -->
                    <div class="col-md-8">
                        <h6 class="text-uppercase font-weight-bold mb-3 text-muted" style="font-size: 11px; letter-spacing: 0.5px;">
                            Requester Information
                        </h6>
                        
                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Full Name:</div>
                            <div class="col-sm-8 font-weight-bold">
                                {{ trim($soft->f_name . ' ' . ($soft->middle_name ?? '') . ' ' . $soft->l_name) }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Email Address:</div>
                            <div class="col-sm-8">
                                <a href="mailto:{{ $soft->email }}" class="text-primary">{{ $soft->email }}</a>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Contact Number:</div>
                            <div class="col-sm-8">{{ $soft->number }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Graduating Status:</div>
                            <div class="col-sm-8">
                                <span class="badge {{ $soft->is_graduating == 'Yes' ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $soft->is_graduating }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Alumni:</div>
                            <div class="col-sm-8">
                                <span class="badge {{ $soft->grad ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                    {{ $soft->grad ?: 'Undergrad' }}
                                </span>
                            </div>
                        </div>

                        <hr class="my-3">
                        <h6 class="text-uppercase font-weight-bold mb-3 text-muted" style="font-size: 11px; letter-spacing: 0.5px;">
                            Academic Details
                        </h6>

                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">School Level:</div>
                            <div class="col-sm-8">{{ $soft->school_level }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Program / Course:</div>
                            <div class="col-sm-8">{{ $soft->program }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Year Level:</div>
                            <div class="col-sm-8">{{ $soft->year_level }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Year Graduated:</div>
                            <div class="col-sm-8">{{ $soft->grad ?? 'N/A' }}</div>
                        </div>

                       <div class="row mb-2">
                            <div class="col-sm-4 text-muted font-weight-semibold small">Photo Date:</div>
                            <div class="col-sm-8">
                                {{ $soft->date_photo ? \Carbon\Carbon::parse($soft->date_photo)->format('F d, Y') : 'N/A' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light">
                @if ($soft->status == 0)
                 <!-- 2. Send Soft Copy Action -->
                <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#denyModal-{{ $soft->id }}">
                    <i class="material-icons text-danger mr-2" style="font-size: 16px;">block</i> Deny Request
                </button>
                @endif
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>
<@endforeach