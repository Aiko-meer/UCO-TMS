 <!-- pop for active -->
                       @foreach($requests as $req)
                       <div class="modal fade" id="viewModal-{{ $req->request_id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="z-index: 1051; background: #fff;">
           <form action="{{ route('layout.update', $req->request_id) }}" method="POST">
                @csrf
               @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Edit Request Details: {{ $req->request_id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body text-left">
                     <!-- Requester Information -->
                    <div class="col-12 mb-4">
                        <h6 class="text-uppercase font-weight-bold mb-1">
                            Requester Information
                        </h6>
                        <p class="text-muted small mb-0">
                            Please provide your personal and department information.
                        </p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label><strong>Full Name:</strong></label>
                            <input type="text"  class="form-control" value="{{ $req->fullname }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>Email:</strong></label>
                            <input type="email"  class="form-control" value="{{ $req->email }}">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label><strong>Department:</strong></label>
                            <input type="text"  class="form-control" value="{{ $req->department }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>Date Needed:</strong></label>
                            <input type="text" readonly  class="form-control" value="{{ $req->publishing?->date_needed ? \Carbon\Carbon::parse($req->publishing->date_needed)->format('M d, Y') : 'N/A' }}">
                        </div>
                    </div>

                      <!-- Divider -->
            <div class="col-12">
                <hr class="my-4">
            </div>

                <!-- Request Details -->
                <div class="col-12 mb-4">
                    <h6 class="text-uppercase font-weight-bold mb-1">
                        Request Details
                    </h6>

                    <p class="text-muted small mb-0">
                        Tell us what service or assistance you need.
                    </p>
                </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Purpose:</strong></label>
                            <textarea  class="form-control" rows="3" readonly>{{ $req->publishing?->purpose }}</textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Specification:</strong></label>
                             <input type="text" readonly  class="form-control" value="{{ $req->publishing?->specification }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Category:</strong></label>
                            <input type="text" readonly  class="form-control" value="{{ $req->publishing?->category }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Section:</strong></label>
                            <input type="text" readonly  class="form-control" value="{{ $req->publishing?->section }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Information:</strong></label>
                            <textarea  class="form-control" rows="3" readonly>{{ $req->publishing?->content_information }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Layout Attachment:</strong></label>
                            
                            @if($req->publishing?->reference)
                                <div class="input-group">
                                    <input type="text" readonly class="form-control bg-light" value="{{ $req->publishing->reference }}">
                                    <div class="input-group-append">
                                        <a href="{{ $req->publishing->reference }}" target="_blank" class="btn btn-outline-primary">
                                            <i class="material-icons" style="font-size: 16px; vertical-align: middle;">open_in_new</i> Open Link
                                        </a>
                                    </div>
                                </div>
                            @else
                                <input type="text" readonly class="form-control bg-light" value="No attachment provided">
                            @endif
                            
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Approve by:</strong></label>
                            <input type="text" readonly  class="form-control" value="{{ $req->publishing?->approve }}">
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Date Approve :</strong></label>
                             <div class="d-flex flex-column">
                                               <small class="js-lists-values-date">
                                                    <strong>{{ $req->publishing?->date_approved ? \Carbon\Carbon::parse($req->publishing->date_approved)->format('M d, Y') : 'N/A' }}</strong>
                                                </small>
                                                <small class="text-50">
                                                    {{ $req->publishing?->date_approved ? \Carbon\Carbon::parse($req->publishing->date_approved)->diffForHumans() : '' }}
                                                </small>
                                            </div>
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            @if(empty($req->publishing?->produce))
                            <label><strong>Assign to:</strong></label>
                                <!-- Show select dropdown when produce is empty -->
                               <select name="produce" class="form-control" required>
                                    <option value="" disabled selected>Select a user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->employee_id }}">{{ $user->fullname }}</option>
                                    @endforeach
                                </select>
                            @else

                            <label><strong>Assigned:</strong></label>  
                            <input type="text" class="form-control" name="produce" value="{{ $req->publishing?->user?->employee_id  }}" hidden>{{$req->publishing?->user?->fullname  }}
                            @endif
                        </div>
                    </div>

                   

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Status:</strong></label>
                            <select name="status" class="form-control status-dropdown" data-id="{{ $req->request_id }}" required>
                               <option value="{{ $req->publishing->status }}"  selected>
                                    @if($req->publishing->status == 0) Pending
                                    @elseif($req->publishing->status == 1) Approve
                                    @elseif($req->publishing->status == 2) in progress
                                    @elseif($req->publishing->status == 4) for Approval
                                    @elseif($req->publishing->status == 3) Rejected
                                    @else {{ $req->publishing->status }}
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
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
                        @endforeach

                        <!-- Delete Confirmation Bootstrap Modal -->
                                    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
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