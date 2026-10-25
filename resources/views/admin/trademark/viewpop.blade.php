@foreach ($softCopies as $trade)
<div class="modal fade" id="viewModal-{{ $trade->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            
            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title font-weight-bold text-dark mb-1">
                        <i class="material-icons align-middle mr-1 text-primary" style="font-size: 20px;">visibility</i> 
                        Trademark Request Details 
                        <span class="badge badge-primary ml-2">{{ $trade->request_id }}</span>
                    </h5>
                    <small class="text-muted">Submitted on {{ $trade->created_at->format('M d, Y - h:i A') }}</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-4" style="max-height: 75vh; overflow-y: auto;">
                
                <!-- Section 1: Requestor Information -->
                <h6 class="text-uppercase text-muted font-weight-bold small mb-3 border-bottom pb-2">
                    1. Requestor Information
                </h6>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <span class="d-block text-muted small font-weight-semibold">Full Name</span>
                        <span class="text-dark font-weight-bold">{{ $trade->salutation }} {{ $trade->f_name }} {{ $trade->middle_name }} {{ $trade->l_name }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="d-block text-muted small font-weight-semibold">Category Requestor</span>
                        <span class="text-dark">{{ $trade->category_requestor }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="d-block text-muted small font-weight-semibold">Position / Designation</span>
                        <span class="text-dark">{{ $trade->position }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <span class="d-block text-muted small font-weight-semibold">Department / Organization</span>
                        <span class="text-dark">{{ $trade->department_org }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="d-block text-muted small font-weight-semibold">Contact Number</span>
                        <span class="text-dark">{{ $trade->contact_number }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="d-block text-muted small font-weight-semibold">Email Address</span>
                        <span class="text-dark">{{ $trade->email }}</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <span class="d-block text-muted small font-weight-semibold">Office / Mailing Address</span>
                        <span class="text-dark">{{ $trade->office_address }}</span>
                    </div>
                </div>

                <!-- Section 2: Trademark Usage Details -->
                <h6 class="text-uppercase text-muted font-weight-bold small mb-3 border-bottom pb-2">
                    2. Trademark Usage Details
                </h6>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <span class="d-block text-muted small font-weight-semibold">Trademark to Use</span>
                        <span class="text-dark font-weight-bold text-primary">{{ $trade->trademark_to_use }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block text-muted small font-weight-semibold">Date to Use</span>
                        <span class="text-dark">{{ \Carbon\Carbon::parse($trade->date_to_use)->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <span class="d-block text-muted small font-weight-semibold">Category for Trademark Use</span>
                        <span class="text-dark">{{ $trade->category_trademark_use }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block text-muted small font-weight-semibold">Target Audience</span>
                        <span class="text-dark">{{ $trade->target_audience }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <span class="d-block text-muted small font-weight-semibold">Materials / Media</span>
                        <span class="text-dark">{{ $trade->materials_media }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block text-muted small font-weight-semibold">Location of Use</span>
                        <span class="text-dark">{{ $trade->location_use }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <span class="d-block text-muted small font-weight-semibold">Purpose of Use</span>
                        <p class="text-dark bg-light p-3 rounded mb-0">{{ $trade->purpose_of_use }}</p>
                    </div>
                </div>

                @if($trade->note)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <span class="d-block text-muted small font-weight-semibold">Additional Note / Remarks</span>
                        <p class="text-dark bg-light p-3 rounded mb-0">{{ $trade->note }}</p>
                    </div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-12">
                        <span class="d-block text-muted small font-weight-semibold">Approve by:</span>
                        <p class="text-dark bg-light p-3 rounded mb-0">{{ $trade->approved_by }}</p>
                    </div>
                </div>

                <!-- Section 3: Status & Approver Information -->
                <h6 class="text-uppercase text-muted font-weight-bold small mb-3 border-bottom pb-2">
                    3. Administrative Status
                </h6>

                <div class="row">
                    <div class="col-md-6">
                        <span class="d-block text-muted small font-weight-semibold">Current Status</span>
                        @if($trade->status == 1)
                            <span class="badge badge-success px-3 py-2">Approved / Done</span>
                        @elseif($trade->status == 2)
                            <span class="badge badge-danger px-3 py-2">Denied / Rejected</span>
                        @else
                            <span class="badge badge-warning px-3 py-2">Pending Review</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <span class="d-block text-muted small font-weight-semibold">Approved / Reviewed By</span>
                        <span class="text-dark font-weight-bold">{{ $trade->produce ?? 'Not yet assigned' }}</span>
                    </div>
                    
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light">
                @if ($trade->status == 0)
                 <!-- 2. Send Soft Copy Action -->
                <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#denyModal-{{ $trade->id }}">
                    <i class="material-icons text-danger mr-2" style="font-size: 16px;">block</i> Deny Request
                </button>
                @endif
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endforeach