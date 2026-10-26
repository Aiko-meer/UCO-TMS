<div class="modal fade" id="newTaskModal" tabindex="-1"
     role="dialog" aria-labelledby="newTaskModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">

        <div class="modal-header">
            <div>
                <h5 class="modal-title font-weight-bold mb-1">
                    Create New Request
                </h5>

                <small class="text-muted">
                    Submit a request to the UCO team
                </small>
            </div>

            <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form action="{{route ('socmed.store')}}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="modal-body px-4 py-4">

                <div class="row">

                    <!-- Requester Information -->
                    <div class="col-12 mb-4">
                        <h6 class="text-uppercase font-weight-bold mb-1">
                            Requester Information
                        </h6>
                    </div>

                    <!-- Full Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="full_name" class="font-weight-semibold">
                                Full Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                class="form-control"
                                id="full_name"
                                name="full_name"
                                placeholder="Enter your full name"
                                required>
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="department" class="font-weight-semibold">
                                Department
                                <span class="text-danger">*</span>
                            </label>

                            <select id="department"
                                    name="department"
                                    class="form-control"
                                    required>
                                <option value="UCO">University Communication Office</option>
                            </select>
                        </div>
                    </div>

                     <!-- Email -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="font-weight-semibold">
                                Email Address
                                <span class="text-danger" aria-hidden="true">*</span>
                            </label>
                            <small id="emailHelp" class="text-danger form-text  d-block mb-1 ">
                                Note: Please use your official UZ email address; other emails will not be accepted.
                            </small>

                            <input type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="name@uz.edu.ph"
                                required
                                pattern=".*@uz\.edu\.ph$"
                                aria-describedby="emailHelp">
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

                <!-- Purpose -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="full_name" class="font-weight-semibold">
                                Purpose
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                class="form-control"
                                id="full_name"
                                name="purpose"
                                placeholder="Purpose of the Request"
                                required>
                        </div>
                    </div>
               <!-- Date Needed -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_needed" class="font-weight-semibold">
                            Date Needed
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                            class="form-control"
                            id="date_needed"
                            name="date_needed"
                            required>
                    </div>
                </div>

                <!-- Category -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="priority" class="font-weight-semibold">
                            Category
                            <span class="text-danger">*</span>
                        </label>

                        <select id="priority"
                                name="category"
                                class="form-control"
                                required>

                            <option value="" selected disabled>
                                Select
                            </option>

                            <option value="Facebook Page">Facebook Page</option>
                            <option value="Website">Website</option>
                            <option value="Twitter">Twitter</option>
                            <option value="Youtube">Youtube</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Campus Connect (GLOBE/TM/SMART)">Campus Connect (GLOBE/TM/SMART)</option>

                        </select>
                    </div>
                </div>

                <!-- Specification Dropdown -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="priority" class="font-weight-semibold">
                            Specification
                            <span class="text-danger">*</span>
                        </label>

                        <select id="specification"
                                name="specification"
                                class="form-control"
                                required>

                            <option value="" selected disabled>
                                Select 
                            </option>             
                            <option value="Advertisements">Advertisements</option>
                            <option value="Advisory">Advisory</option>
                            <option value="Album">Album</option>
                            <option value="Announcement">Announcement</option>
                            <option value="Urgent">Urgent</option>
                            <option value="Banner">Banner</option>
                            <option value="Celebration">Celebration</option>
                            <option value="Congratulatory">Congratulatory</option>
                            <option value="Guidelines">Guidelines</option>
                            <option value="Live Stream ">Live Stream </option>
                        </select>
                    </div>
                </div>

               <!-- Conditional Album Photos Field (Hidden by default) -->
                <div class="col-md-6" id="albumAttachmentContainer" style="display: none;">
                    <div class="form-group">
                        <label for="album_photos" class="font-weight-semibold text-dark">
                            <i class="material-icons font-size-16pt text-accent align-middle mr-1" style="font-size: 18px;">photo_library</i>
                            Album Photos Attachment
                            <span class="text-danger">*</span>
                        </label>
                        
                        <div class="custom-file-upload-wrapper p-3 border rounded bg-light">
                            <input type="file" 
                                name="album_photo" 
                                id="album_photos" 
                                class="form-control-file border-0 bg-transparent p-0" 
                                multiple 
                                accept="image/png, image/jpeg, image/jpg">
                            <div class="d-flex align-items-center mt-2 text-muted" style="font-size: 85%;">
                                <i class="material-icons mr-1" style="font-size: 14px;">info</i>
                                <span>Accepted formats: JPEG, PNG. You can select multiple photos simultaneously. Max size: 10MB per file.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Information -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="request_title" class="font-weight-semibold">
                            Content Information
                            <span class="text-danger">*</span>
                        </label>
                        <textarea
                        class="form-control"
                        id="request_title"
                        name="content_information"
                        rows="4"
                        placeholder="e.g. 🎉 Facebook promotional post for Foundation Day..."
                        required></textarea>
                    </div>
                </div>

                 <!-- Section -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="priority" class="font-weight-semibold">
                            Section
                            <span class="text-danger">*</span>
                        </label>

                        <select id="priority"
                                name="section"
                                class="form-control"
                                required>

                            <option value="" selected disabled>
                                Select
                            </option>

                            <option value="low">Institutional</option>
                            <option value="normal">Academic</option>
                            <option value="high">Administrative</option>
                            <option value="urgent">Affilate</option>
                            <option value="urgent">External</option>

                        </select>
                    </div>
                </div>

                <!-- For layout -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-semibold">Do you need a layout for this request?</label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="layoutYes" value="1" name="needs_layout" class="custom-control-input" onchange="toggleLayoutSection()">
                                <label class="custom-control-label" for="layoutYes">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <!-- Changed value from "no" to "0" so it functions correctly as a radio group -->
                                <input type="radio" id="layoutNo" value="0" name="needs_layout" class="custom-control-input" checked onchange="toggleLayoutSection()">
                                <label class="custom-control-label" for="layoutNo">No</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="layoutDetailsSection" class="col-md-12" style="display: none;">
                    <div class="card border-primary mb-3">
                        <div class="card-header bg-light font-weight-semibold text-primary">Layout Details</div>
                        <div class="card-body">
                            <div class="form-row">
                                <!-- Category -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority" class="font-weight-semibold">
                                            Category
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="priority"
                                                name="category_layout"
                                                class="form-control" >
                                            <option value="" selected disabled>Select</option>
                                            <option value="low">Advisory</option>
                                            <option value="normal">Congratulatory</option>
                                            <option value="high">Social Card/Facebook Banner</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Attachments -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attachment" class="font-weight-semibold">Attachments</label>
                                        <span>for layout reference</span>

                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input"
                                                id="attachment"
                                                name="reference[]"
                                                multiple
                                                onchange="previewAndManageFiles(this)">
                                            <label class="custom-file-label" for="attachment" id="fileCustomLabel">
                                                Choose file(s)
                                            </label>
                                        </div>

                                        <small class="form-text text-muted">
                                            PDF, DOCX, JPG, PNG — Max 10 MB 
                                        </small>

                                        <div id="filePreviewContainer" class="mt-2 d-flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<script>
   function toggleLayoutSection() {
    const layoutYes = document.getElementById('layoutYes');
    const layoutSection = document.getElementById('layoutDetailsSection');
    const categorySelect = document.getElementById('priority'); 
    
    // Correct element ID targeting your Cloud Storage URL field
    const contentAttachmentUrlInput = document.getElementById('content_attachment_url');
    
    if (layoutYes.checked) {
        // Show layout section and make category required
        layoutSection.style.display = 'block';
        categorySelect.required = true; 

        // Disable and clear the cloud link URL input when layout is YES
        if (contentAttachmentUrlInput) {
            contentAttachmentUrlInput.disabled = true;
            contentAttachmentUrlInput.value = '';
            contentAttachmentUrlInput.removeAttribute('required'); // Removes requirement if it was mandatory before
        }
    } else {
        // Hide layout section and clear category requirement
        layoutSection.style.display = 'none';
        categorySelect.required = false; 
        categorySelect.value = ''; 

        // Re-enable cloud link URL input when layout is NO
        if (contentAttachmentUrlInput) {
            contentAttachmentUrlInput.disabled = false;
        }
    }
}

document.addEventListener("DOMContentLoaded", function() {
    toggleLayoutSection();
});

    // Single unified initialization block on page load
    document.addEventListener("DOMContentLoaded", function() {
        toggleLayoutSection();
    });

    let dtFiles = new DataTransfer();

    function previewAndManageFiles(input) {
        const customLabel = document.getElementById('fileCustomLabel');
        
        for (let i = 0; i < input.files.length; i++) {
            dtFiles.items.add(input.files[i]);
        }
        
        input.files = dtFiles.files;
        renderPreviews();
        
        if (dtFiles.files.length > 0) {
            customLabel.textContent = `${dtFiles.files.length} file(s) chosen`;
        } else {
            customLabel.textContent = 'Choose file(s)';
        }
    }

    function removeFile(index) {
        let newDt = new DataTransfer();
        
        for (let i = 0; i < dtFiles.files.length; i++) {
            if (i !== index) {
                newDt.items.add(dtFiles.files[i]);
            }
        }
        
        dtFiles = newDt;
        const input = document.getElementById('attachment');
        const customLabel = document.getElementById('fileCustomLabel');
        
        input.files = dtFiles.files;
        renderPreviews();
        
        if (dtFiles.files.length > 0) {
            customLabel.textContent = `${dtFiles.files.length} file(s) chosen`;
        } else {
            customLabel.textContent = 'Choose file(s)';
            input.value = '';
        }
    }

    function renderPreviews() {
        const previewContainer = document.getElementById('filePreviewContainer');
        previewContainer.innerHTML = '';
        
        Array.from(dtFiles.files).forEach((file, index) => {
            const fileBadge = document.createElement('div');
            fileBadge.className = 'badge badge-secondary p-2 m-1 d-flex align-items-center';
            fileBadge.style.fontSize = '0.9rem';
            
            let displayName = file.name.length > 25 ? file.name.substring(0, 22) + '...' : file.name;
            
            fileBadge.innerHTML = `
                <span class="mr-2">${displayName}</span>
                <button type="button" class="close text-white ml-auto" style="float: none; font-size: 1.1rem; line-height: 1;" onclick="removeFile(${index})">
                    &times;
                </button>
            `;
            previewContainer.appendChild(fileBadge);
        });
    }
</script>
<script>
    document.getElementById('specification').addEventListener('change', function () {
        var albumContainer = document.getElementById('albumAttachmentContainer');
        var albumInput = document.getElementById('album_photos');

        if (this.value === 'Album') {
            albumContainer.style.display = 'block'; // Show the field
            albumInput.setAttribute('required', 'required'); // Make it required if Album is chosen
        } else {
            albumContainer.style.display = 'none'; // Hide the field
            albumInput.removeAttribute('required'); // Remove requirement so form can submit
            albumInput.value = ''; // Clear selected files if they switch away from Album
        }
    });
</script>
                        <!-- Approved -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="description" class="font-weight-semibold">
                            Approved by:
                            <span class="text-danger">*</span>
                        </label>

                       <input type="text"
                                class="form-control"
                                id="full_name"
                                name="approve"
                                placeholder="Department Head"
                                required>
                    </div>
                </div>
               <!-- Attachment -->
               <div class="col-md-6">
                    <div class="form-group">
                        <label for="content_attachment_url" class="font-weight-semibold">
                            Resource Link / Cloud Storage URL <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light text-muted">
                                    <i class="material-icons font-size-16pt">link</i>
                                </span>
                            </div>
                            <input type="url"
                                class="form-control"
                                id="content_attachment_url"
                                name="content_attachement"
                                placeholder="https://drive.google.com/... or cloud link"
                                >
                        </div>

                        <small class="form-text text-muted">
                            Provide a shared public link (Google Drive, OneDrive, etc.) containing your reference files.
                        </small>
                        <small class="form-test text-danger">
                            if no attachment please click yes for layout to proceed.
                        </small>
                    </div>
                </div>

          



</div>

            </div>

            <div class="modal-footer bg-light">
                <button type="button"
                        class="btn btn-light border"
                        data-dismiss="modal">
                    Cancel
                </button>

                <button type="submit"
                        class="btn btn-accent px-4">
                    Submit Request
                </button>
            </div>

        </form>

    </div>
</div>
</div>