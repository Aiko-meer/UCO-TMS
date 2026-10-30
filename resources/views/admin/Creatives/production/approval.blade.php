@foreach ($requests as $req)
<!-- Send Approval Modal via EmailJS -->
<div class="modal fade" id="sendModal-{{ $req->request_id }}" tabindex="-1" role="dialog" aria-labelledby="sendApprovalLabel-{{ $req->request_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            
            <form onsubmit="sendApprovalEmailJS(event, '{{ $req->request_id }}', '{{ $req->email }}', '{{ $req->f_name ?? $req->full_name }}')">
                @csrf
                
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-1" id="sendApprovalLabel-{{ $req->request_id }}">
                            Send Approval & Notification
                        </h5>
                        <small class="text-muted">Recipient: <span class="font-weight-semibold text-dark">{{ $req->email }}</span></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body px-4 py-4">
                    <!-- Read-only Email -->
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted"> Email Address:</label>
                        <input type="email" class="form-control bg-light" value="{{ $req->email }}" readonly>
                    </div>

                    <!-- Custom Approval Message -->
                    <div class="form-group mb-3">
                        <label for="custom_message-{{ $req->request_id }}" class="font-weight-semibold small text-muted">
                            Message:
                        </label>
                        <textarea name="custom_message" 
                                  id="custom_message-{{ $req->request_id }}" 
                                  class="form-control" 
                                  rows="6" 
                                  placeholder="">Good day!

We would like to inform you that your requested task has been completed and is now ready for your review and approval.

Kindly check the completed output and let us know if it meets your expectations or if any revisions are needed.

Thank you for your cooperation. Should you have any questions or concerns, please feel free to contact the UCO Team.

Best regards,
UCO Team</textarea>
                    </div>

                    <!-- Optional Drive Link / Notes -->
                    <div class="form-group mb-0">
                        <label for="drive_link-{{ $req->request_id }}" class="font-weight-semibold small text-muted">
                            Google Drive / Reference Link (Optional)
                        </label>
                        <input type="url" 
                               name="drive_link" 
                               id="drive_link-{{ $req->request_id }}" 
                               class="form-control" 
                               placeholder="https://drive.google.com/...">
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitApprovalBtn-{{ $req->request_id }}" class="btn btn-success px-4">
                        <i class="material-icons mr-1" style="font-size: 16px; vertical-align: middle;">send</i> Send Approval Email
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach
<script>
    function sendApprovalEmailJS(event, requestId, studentEmail, studentName) {
    event.preventDefault(); // Prevent standard page reload

    // Grab input values
    const customMessage = document.getElementById('custom_message-' + requestId).value;
    const driveLink = document.getElementById('drive_link-' + requestId).value;
    const submitBtn = document.getElementById('submitApprovalBtn-' + requestId);

    // Disable button & show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Sending...';

    // Parameters mapped to your EmailJS Template variables
    const templateParams = {
        to_email: studentEmail,
        to_title: 'Completed Task – For Your Review and Approval',
        to_name: studentName,
        custom_message: customMessage || 'Your request has been approved.',
        drive_link: driveLink || 'No link provided.'
    };

    // Send email via EmailJS (uses your service and template IDs)
    emailjs.send('service_ydriqxs', 'template_1njzwjo', templateParams)
        .then(function(response) {
            console.log('EmailJS SUCCESS!', response.status, response.text);
            
            // Call Laravel backend via fetch to update the status in the database
            fetch(`/layoutservices/dashboard/mark-sent/${requestId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ drive_link: driveLink })
            })
            .then(res => res.json())
            .then(data => {
                alert('Approval email sent and database updated successfully!');
                location.reload(); // Refresh to update status badges
            })
            .catch(err => {
                console.error('Database update error:', err);
                alert('Email sent successfully, but failed to update status in the database.');
                location.reload();
            });

        }, function(error) {
            console.error('EmailJS FAILED...', error);
            alert('Failed to send approval email. Please check your EmailJS configuration.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="material-icons mr-1" style="font-size: 16px; vertical-align: middle;">send</i> Send Approval Email';
        });
}
</script>