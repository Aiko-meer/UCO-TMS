@foreach ($requests as $req)
<!-- Deny Request Modal -->
<div class="modal fade" id="denyModal-{{ $req->request_id }}" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel-{{ $req->request_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            
            <form onsubmit="denyRequestAction(event, '{{ $req->request_id }}', '{{ $req->email }}', '{{ $req->f_name ?? $req->full_name }}')">
                @csrf
                
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title font-weight-bold text-danger mb-1" id="denyModalLabel-{{ $req->request_id }}">
                            Deny Layout Request
                        </h5>
                        <small class="text-muted">Recipient: <span class="font-weight-semibold text-dark">{{ $req->email }}</span></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body px-4 py-4">
                    <!-- Warning Alert -->
                    <div class="alert alert-warning border-0 bg-light text-dark mb-3" role="alert">
                        <i class="material-icons align-middle mr-1 text-warning" style="font-size: 18px;">warning</i>
                        Denying this request will update its status and notify the student via email.
                    </div>

                    <!-- Read-only Email -->
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted">Student Email Address:</label>
                        <input type="email" class="form-control bg-light" value="{{ $req->email }}" readonly>
                    </div>

                    <!-- Reason for Denial -->
                    <div class="form-group mb-0">
                        <label for="denial_reason-{{ $req->request_id }}" class="font-weight-semibold small text-muted">
                            Reason for Denial / Feedback <span class="text-danger">*</span>
                        </label>
                        <textarea name="denial_reason" 
                                  id="denial_reason-{{ $req->request_id }}" 
                                  class="form-control" 
                                  rows="6" 
                                  placeholder="Please provide a clear reason why this request is being denied..." 
                                  required>Good day!

We regret to inform you that your layout request has been denied due to the following reason:

[Insert reason here]

If you have any questions or would like to submit a revised request, please feel free to reach out to the UCO Team.

Best regards,
UCO Team</textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitDenyBtn-{{ $req->request_id }}" class="btn btn-danger px-4">
                        <i class="material-icons mr-1" style="font-size: 16px; vertical-align: middle;">block</i> Confirm Denial
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach