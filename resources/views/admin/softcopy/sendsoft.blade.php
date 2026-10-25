@foreach ($softCopies as $soft)
<!-- Send Soft Copy Modal -->
<div class="modal fade" id="sendModal-{{ $soft->id }}" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel-{{ $soft->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            
            <!-- Notice we use id instead of action for JS handling -->
            <form id="emailForm-{{ $soft->id }}" onsubmit="sendEmailWithEmailJS(event, '{{ $soft->id }}', '{{ $soft->email }}', '{{ $soft->f_name }} {{ $soft->l_name }}')">
                @csrf
                
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-1" id="sendModalLabel-{{ $soft->id }}">
                            Send Soft Copy (Via EmailJS)
                        </h5>
                        <small class="text-muted">Recipient: <span class="font-weight-semibold text-dark">{{ $soft->f_name }} {{ $soft->l_name }}</span></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body px-4 py-4">
                    <!-- Read-only email view -->
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted">Student Email Address:</label>
                        <input type="email" class="form-control bg-light" value="{{ $soft->email }}" readonly>
                    </div>

                    <!-- Custom Message Input -->
                    <div class="form-group mb-3">
                        <label for="custom_message-{{ $soft->id }}" class="font-weight-semibold small text-muted">
                            Custom Message / Note for Student (Optional)
                        </label>
                        <textarea name="custom_message" 
                                  id="custom_message-{{ $soft->id }}" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="">Good day! 

Thank you for patiently waiting. We are pleased to inform you that your request for a softcopy of your picture has been approved.

You may download your picture using the Google Drive link provided below:

Google Drive Link:

Please be reminded of the following:

You have 24 hours from the time this email is sent to download your picture.

Please ensure that your Google Drive has sufficient storage space before downloading.

Kindly download your picture within the given timeframe, as the link will only be available for 24 hours.


Thank you for your understanding and cooperation.

Have a great day!

Best regards,
UCO Team"</textarea>
                    </div>

                    <!-- Google Drive Link Input -->
                    <div class="form-group mb-0">
                        <label for="drive_link-{{ $soft->id }}" class="font-weight-semibold small text-muted">
                            Google Drive Link <span class="text-danger">*</span>
                        </label>
                        <input type="url" 
                               name="drive_link" 
                               id="drive_link-{{ $soft->id }}" 
                               class="form-control" 
                               placeholder="https://drive.google.com/file/d/..." 
                               required>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitBtn-{{ $soft->id }}" class="btn btn-primary px-4">
                        <i class="material-icons mr-1" style="font-size: 16px; vertical-align: middle;">send</i> Send Email
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach


  
<script>
function sendEmailWithEmailJS(event, softId, studentEmail, studentName) {
    event.preventDefault(); // Stop page reload

    // Grab input values
    const driveLink = document.getElementById('drive_link-' + softId).value;
    const customMessage = document.getElementById('custom_message-' + softId).value;
    const submitBtn = document.getElementById('submitBtn-' + softId);

    // Disable button to prevent double-clicks & show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Sending...';

    // Parameters mapped to your EmailJS Template variables
    const templateParams = {
        to_email: studentEmail,
        to_title:'Approved Request for Softcopy Picture.',
        to_name: studentName,
        custom_message: customMessage || 'No additional notes provided.',
        drive_link: driveLink
    };

    // Replace with your actual EmailJS Service ID and Template ID
    emailjs.send('service_ydriqxs', 'template_1njzwjo', templateParams)
        .then(function(response) {
            console.log('SUCCESS!', response.status, response.text);
            
            // Now, call a Laravel route via fetch to update status to Approved (1) in the DB
            fetch(`/admin/softcopy/mark-sent/${softId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ drive_link: driveLink })
            })
            .then(res => res.json())
            .then(data => {
                alert('Soft copy email sent successfully!');
                location.reload(); // Refresh page to reflect status update
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
            submitBtn.innerHTML = 'Send Email';
        });
}
</script>