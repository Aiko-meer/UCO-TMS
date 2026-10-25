@foreach ($softCopies as $soft)
<div class="modal fade" id="denyModal-{{ $soft->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            
            <!-- Handled by EmailJS JavaScript function -->
            <form id="denyForm-{{ $soft->id }}" onsubmit="sendDenyEmailWithEmailJS(event, '{{ $soft->id }}', '{{ $soft->email }}', '{{ $soft->f_name }} {{ $soft->l_name }}')">
                @csrf
                
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-1 text-danger">Deny Request</h5>
                        <small class="text-muted">Recipient: <span class="font-weight-semibold text-dark">{{ $soft->f_name }} {{ $soft->l_name }}</span></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body px-4 py-4">
                    <!-- Student Email (Read-only for clarity) -->
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted">Student Email Address:</label>
                        <input type="email" class="form-control bg-light" value="{{ $soft->email }}" readonly>
                    </div>

                    <!-- Reason Input -->
                    <div class="form-group mb-0">
                        <label for="reason-{{ $soft->id }}" class="font-weight-semibold small text-muted">Reason for Denial:</label>
                        <textarea id="reason-{{ $soft->id }}" 
                                  name="reason" 
                                  class="form-control" 
                                  rows="3"  
                                  required>Good day!

Thank you for contacting the University Communications Office (UCO) and for patiently waiting for an update regarding your request.

We regret to inform you that your request for a softcopy of your picture has not been approved.

Due to:

We appreciate your understanding. If you have any questions or would like to request further clarification regarding this decision, please feel free to contact our office.

Thank you for your interest and understanding. Have a great day!

Best regards,
University Communications Office (UCO)</textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="denySubmitBtn-{{ $soft->id }}" class="btn btn-danger px-4">Confirm Denial</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach
<script>
function sendDenyEmailWithEmailJS(event, softId, studentEmail, studentName) {
    event.preventDefault(); // Stop default form submit / page reload

    // Grab reason and submit button
    const reasonText = document.getElementById('reason-' + softId).value;
    const submitBtn = document.getElementById('denySubmitBtn-' + softId);

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
            fetch(`/admin/softcopy/mark-denied/${softId}`, {
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
            submitBtn.innerHTML = 'Confirm Denial';
        });
}
</script>
      