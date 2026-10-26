 <!-- pop for active -->
                       @foreach($requests as $req)
                       <div class="modal fade" id="viewModal-{{ $req->request_id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="z-index: 1051; background: #fff;">
           <form action="{{ route('socmed.update', $req->request_id) }}" method="POST">
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
                                <i class="material-icons font-size-16pt text-accent align-middle mr-1" style="font-size: 18px;">photo_library</i>
                                Album Photo Attachment
                            </label>

                            @if(!empty($req->information->album_photo))
                                <!-- Clean Professional Attachment Card -->
                                <div class="d-flex align-items-center p-3 border rounded bg-light">
                                    <!-- Thumbnail Preview -->
                                    <div class="mr-3">
                                        <img src="{{ asset(json_decode($req->information->album_photo) ?? $req->information->album_photo) }}" 
                                            alt="Album Photo" 
                                            class="rounded border shadow-sm" 
                                            style="width: 64px; height: 64px; object-fit: cover;">
                                    </div>

                                    <!-- File Info -->
                                    <div class="flex-grow-1">
                                        <span class="d-block font-weight-semibold text-dark">
                                            {{ basename(json_decode($req->information->album_photo) ?? $req->information->album_photo) }}
                                        </span>
                                        <small class="text-muted">Uploaded Image File</small>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div>
                                        <a href="{{ asset(json_decode($req->information->album_photo) ?? $req->album_photo) }}" 
                                        target="_blank" 
                                        class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                                            <i class="material-icons mr-1" style="font-size: 16px;">visibility</i> View Full Size
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- Fallback if no photo was uploaded -->
                                <div class="p-3 border rounded bg-light text-muted font-italic">
                                    <i class="material-icons align-middle mr-1" style="font-size: 16px;">info</i>
                                    No album photo attached for this request.
                                </div>
                            @endif
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Content Banner:</strong></label>
                            
                                 @php
                                    $rawAttachment = $req->information?->content_attachement;
                                    
                                    // If it's saved as a JSON string (e.g. "https:\/\/..."), decode it back to a plain string
                                    $decodedLink = json_decode($rawAttachment, true);
                                    $targetUrl = is_string($decodedLink) ? $decodedLink : stripslashes(trim($rawAttachment, '"'));
                                    
                                    // Now validate if it's a real URL
                                    $isUrl = filter_var($targetUrl, FILTER_VALIDATE_URL);
                                    
                                    if (!$isUrl) {
                                        $fileDecoded = json_decode($rawAttachment, true);
                                        $filePath = is_array($fileDecoded) ? ($fileDecoded[0] ?? null) : $rawAttachment;
                                    }
                                @endphp

                                @if(!empty($rawAttachment) && $rawAttachment != '[]')
                                    <div class="mb-2">
                                        <span class="text-muted">Attachment Source:</span> 
                                        
                                        @if($isUrl)
                                            <!-- Displays the Google Drive Link cleanly -->
                                            <a href="{{ $targetUrl }}" target="_blank" class="text-primary font-weight-bold">
                                                <i class="material-icons align-middle" style="font-size: 16px;">link</i> Open Google Drive Link
                                            </a>
                                        @elseif(!empty($filePaths))
                                @else
                                            <span class="text-muted">No valid reference found.</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-2 text-muted small">No file currently attached.</div>
                                @endif
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Approve by:</strong></label>
                            <input type="text" readonly  class="form-control" value="{{ $req->information?->approve }}">
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Produce:</strong></label>
                            
                            @if(empty($req->information?->produce))
                                <!-- Show label when produce is empty -->
                                <label for="">Once Creative team Done with the layout this will be updated</label>
                            @else
                                <!-- Show read-only input text box handling both user or requestor -->
                                <input type="text" readonly class="form-control" value="{{ 
                                    $req->information?->user?->fullname 
                                    ?? $req->information?->produce 
                                    ?? 'No User Found' 
                                }}">
                            @endif
                        </div>
                    </div>

                   <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Published:</strong></label>
                            @if(empty($req->information?->published))
                                <small class="d-block text-muted mb-1">Not yet Posted</small>
                                <input type="date" name="published" id="published-date-{{ $req->request_id }}" class="form-control">
                            @else
                                <input type="date" readonly class="form-control" value="{{ \Carbon\Carbon::parse($req->information->published)->format('Y-m-d') }}">
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label><strong>Status:</strong></label>
                            <select name="status" class="form-control status-dropdown" data-id="{{ $req->request_id }}" required>
                               <option value="{{ $req->information->status }}" disabled selected>
                                    @if($req->information->status == 0) Pending
                                    @elseif($req->information->status == 1) Approve
                                    @elseif($req->information->status == 2) Posted
                                    @elseif($req->information->status == 3) Rejected
                                    @else {{ $req->information->status }}
                                    @endif
                                </option>
                                <option value="1">Approve</option>
                                <option value="2">Posted</option>
                                <option value="0">Pending</option>
                                <option value="3">Rejected</option>
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
 