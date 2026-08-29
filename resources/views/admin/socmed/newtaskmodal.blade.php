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
                        <p class="text-muted small mb-0">
                            Please provide your personal and department information.
                        </p>
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
                                <option value="" selected disabled>
                                    Select department
                                </option>

                                <option value="1">Information Technology</option>
                                <option value="2">Student Affairs</option>
                                <option value="3">Marketing</option>
                                <option value="4">Human Resources</option>
                                <option value="5">Administration</option>
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

                            <option value="low">Facebook Page</option>
                            <option value="normal">Website</option>
                            <option value="high">Twitter</option>
                            <option value="urgent">Youtube</option>
                            <option value="urgent">Instagram</option>
                            <option value="urgent">Campus Connect (GLOBE/TM/SMART)</option>

                        </select>
                    </div>
                </div>

                 <!-- Specification -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="priority" class="font-weight-semibold">
                            Specification
                            <span class="text-danger">*</span>
                        </label>

                        <select id="priority"
                                name="specification"
                                class="form-control"
                                required>

                            <option value="" selected disabled>
                                Select 
                            </option>                  
                            <option value="">Advertisements</option>
                            <option value="low">Advisory</option>
                            <option value="normal">Album</option>
                            <option value="high">Announcement</option>
                            <option value="urgent">Urgent</option>
                            <option value="urgent">Banner</option>
                            <option value="urgent">Celebration</option>
                            <option value="urgent">Congratulatory</option>
                            <option value="urgent">Guidelines</option>
                            <option value="urgent">Live Stream </option>
                        </select>
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

                <!-- For layout 
                  <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-semibold">Do you need a layout for this request?</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="layoutYes" name="needs_layout" value="yes" class="custom-control-input" onchange="toggleLayoutSection()">
                                    <label class="custom-control-label" for="layoutYes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="layoutNo" name="needs_layout" value="no" class="custom-control-input" checked onchange="toggleLayoutSection()">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                        <label for="attachment" class="font-weight-semibold">
                            Attachments
                        </label>
                        <span>for layout reference</span>

                        <div class="custom-file">
                            <input type="file"
                                class="custom-file-input"
                                id="attachment"
                                name="attachment[]"
                                multiple>

                            <label class="custom-file-label" for="attachment">
                                Choose file(s)
                            </label>
                        </div>

                        <small class="form-text text-muted">
                            PDF, DOCX, JPG, PNG — Max 10 MB 
                        </small>
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
        
        if (layoutYes.checked) {
            layoutSection.style.display = 'block';
        } else {
            layoutSection.style.display = 'none';
        }
    }
</script>-->

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
                        <label for="attachment" class="font-weight-semibold">
                            Attachments
                        </label>

                        <div class="custom-file">
                            <input type="file"
                                class="custom-file-input"
                                id="attachment"
                                name="content_attachement[]"
                                multiple>

                            <label class="custom-file-label" for="attachment">
                                Choose file(s)
                            </label>
                        </div>

                        <small class="form-text text-muted">
                            PDF, DOCX, JPG, PNG — Max 10 MB 
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