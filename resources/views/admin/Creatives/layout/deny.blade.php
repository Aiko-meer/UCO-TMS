@foreach ($requests as $req)
<!-- Deny Request Modal -->
<div class="modal fade" id="denyModal-{{ $req->request_id }}" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel-{{ $req->request_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            
            <form onsubmit="sendDenyEmailWithEmailJS(event, '{{ $req->request_id }}', '{{ $req->email }}', '{{ $req->f_name ?? $req->full_name }}')">
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
                    <!-- Matched ID: submitDenyBtn- -->
                    <button type="submit" id="submitDenyBtn-{{ $req->request_id }}" class="btn btn-danger px-4">
                        <i class="material-icons mr-1" style="font-size: 16px; vertical-align: middle;">block</i> Confirm Denial
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach
<script>
function sendDenyEmailWithEmailJS(event, softId, studentEmail, studentName) {
    event.preventDefault(); // Stop default form submit / page reload

    // Grab reason and submit button using IDs matching the HTML
    const reasonText = document.getElementById('denial_reason-' + softId).value;
    const submitBtn = document.getElementById('submitDenyBtn-' + softId);

    // Disable button & show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Sending...';

    // Parameters mapped to your EmailJS Template variables for denial
    const templateParams = {
        to_email: studentEmail,
        to_name: studentName,
        to_title: 'Update on Your Softcopy Picture Request',
        denial_reason: reasonText
    };

    // Replace with your EmailJS Service ID and Template ID for Denied requests
    emailjs.send('service_ydriqxs', 'template_bm1wbqa', templateParams)
        .then(function(response) {
            console.log('SUCCESS!', response.status, response.text);
            
            // Call lightweight Laravel route to update database status to Rejected (status = 2)
            fetch(`/admin/layoutservices/mark-denied/${softId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ reason: reasonText })
            })
            .then(res => res.json())
            .then(data => {
                alert('Denial notification sent successfully.');
                location.reload(); // Refresh to reflect status update
            })
            .catch(err => {
                console.error('Database update error:', err);
                alert('Email sent, but failed to update status in database.');
                location.reload();
            });

        }, function(error) {
            console.log('FAILED...', error);
            alert('Failed to send email. Please check your EmailJS configuration.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="material-icons mr-1" style="font-size: 16px; vertical-align: middle;">block</i> Confirm Denial';
        });
}
</script>