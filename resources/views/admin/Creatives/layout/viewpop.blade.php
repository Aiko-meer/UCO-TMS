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
                            <input type="text" readonly  class="form-control" value="{{ $req->information?->date_needed ? \Carbon\Carbon::parse($req->information->date_needed)->format('M d, Y') : 'N/A' }}">
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
                            <textarea  class="form-control" rows="3" readonly>{{ $req->information?->purpose }}</textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Specification:</strong></label>
                             <input type="text" readonly  class="form-control" value="{{ $req->information?->specification }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Category:</strong></label>
                            <input type="text" readonly  class="form-control" value="{{ $req->information?->category }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Section:</strong></label>
                            <input type="text" readonly  class="form-control" value="{{ $req->information?->section }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Content Information:</strong></label>
                            <textarea  class="form-control" rows="3" readonly>{{ $req->information?->content_information }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label class="font-weight-semibold text-dark mb-2">
                                <i class="material-icons font-size-16pt text-accent align-middle mr-1" style="font-size: 18px;">collections</i>
                                Reference Images / Attachments
                            </label>

                            @php
                                // Fixed typo: 'infromation' to 'information'
                                $rawReference = $req->information->reference ?? null;
                                
                                // Decode the JSON column into a usable PHP array
                                $references = is_string($rawReference) ? json_decode($rawReference, true) : $rawReference;
                            @endphp

                            @if(!empty($references) && is_array($references))
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($references as $path)
                                        <div class="d-flex align-items-center p-2 border rounded bg-light mb-2 mr-2" style="width: 300px;">
                                            <!-- Thumbnail Preview -->
                                            <div class="mr-3">
                                                <img src="{{ asset($path) }}" 
                                                    alt="Reference Image" 
                                                    class="rounded border shadow-sm" 
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </div>

                                            <!-- File Info -->
                                            <div class="flex-grow-1 overflow-hidden">
                                                <span class="d-block font-weight-semibold text-dark text-truncate" style="font-size: 13px;" title="{{ basename($path) }}">
                                                    {{ basename($path) }}
                                                </span>
                                                <small class="text-muted">Image File</small>
                                            </div>

                                            <!-- Action Button -->
                                            <div class="ml-2">
                                                <a href="{{ asset($path) }}" 
                                                target="_blank" 
                                                class="btn btn-sm btn-outline-primary p-1 d-inline-flex align-items-center"
                                                title="View Full Size">
                                                    <i class="material-icons" style="font-size: 16px;">visibility</i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-3 border rounded bg-light text-muted font-italic">
                                    <i class="material-icons align-middle mr-1" style="font-size: 16px;">info</i>
                                    No reference images attached.
                                </div>
                            @endif
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Approve by:</strong></label>
                            <input type="text" readonly  class="form-control" value="{{ $req->infromation?->approve }}">
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            @if(empty($req->information?->produce))
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
                            <input type="text" class="form-control" name="produce" value="{{ $req->information?->user?->employee_id  }}" hidden>{{$req->information?->user?->fullname  }}
                            @endif
                        </div>
                    </div>

                   

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Status:</strong></label>
                            <select name="status" class="form-control status-dropdown" data-id="{{ $req->request_id }}" required>
                               <option value="{{ $req->information->status }}"  selected>
                                    @if($req->information->status == 0) Pending
                                    @elseif($req->information->status == 1) Approve
                                    @elseif($req->information->status == 2) in progress
                                    @elseif($req->information->status == 4) for Approval
                                    @elseif($req->information->status == 3) Rejected
                                    @else {{ $req->information->status }}
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