<div class="modal fade" id="photoModal" tabindex="-1"
     role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">

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

        <form action="{{route ('photo.store')}}" method="POST" enctype="multipart/form-data">

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
                                 @foreach($departments as $dep)
                                 <option value="{{$dep->code}}">{{$dep->name}}</option>
                                 @endforeach
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

                            <option value="Institutional">Institutional</option>
                            <option value="Academic">Academic</option>
                            <option value="Administrative">Administrative</option>
                            <option value="Affilate">Affilate</option>
                            <option value="External">External</option>

                        </select>
                    </div>
                </div>

                <!-- Category -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="priority" class="font-weight-semibold">
                            Category
                            <span class="text-danger">*</span>
                        </label>

                        <select name="category" id="categorySelect" class="form-control" onchange="toggleOtherField(this)">

                            <option value="" selected disabled>
                                Select
                            </option>
                            <option value="">Advisory</option>
                            <option value="">Congratulatory</option>
                            <option value="">Social Card/Facebook Banner</option>
                            <option value="">Certificate</option>
                            <option value="">Plaque</option>
                            <option value="">Press Release</option>
                            <option value="">Invitation/e-Invitation</option>
                            <option value="">LED (The Summit Centre)</option>
                            <option value="">IHK eBillboard</option>
                            <option value="">Event Programme</option>
                            <option value="">Logo Production</option>
                            <option value="">Logo Enhancement</option>
                            <option value="">Logo Soft Copy Request</option>
                            <option value="others">Others</option>

                        </select>

                        <!-- Hidden "Other" Input Field -->
                        <div class="form-group mt-2" id="otherFieldWrapper" style="display: none;">
                            <label for="other_category">Please specify other category:</label>
                            <input type="text" name="other_category" id="other_category" class="form-control" placeholder="Type category here...">
                        </div>
                                                <script>
                        function toggleOtherField(selectElement) {
                            var otherWrapper = document.getElementById('otherFieldWrapper');
                            
                            // Check if the selected value is 'others'
                            if (selectElement.value === 'others') {
                                otherWrapper.style.display = 'block'; // Show the field
                            } else {
                                otherWrapper.style.display = 'none';  // Hide the field
                                document.getElementById('other_category').value = ''; // Clear text if hidden
                            }
                        }
                        </script>
                    </div>
                </div>
                <!-- event -->
                    <div class="col-12">
                        <div class="form-group">
                            <label for="full_name" class="font-weight-semibold">
                                Purpose of Request
                                <span class="text-danger">*</span>
                            </label>

                            <textarea type="text"
                                class="form-control"
                                id="full_name"
                                name="purpose"
                                placeholder="event of the Request"
                                required></textarea>
                        </div>
                    </div>

                     <!-- event -->
                    <div class="col-12">
                        <div class="form-group">
                            <label for="full_name" class="font-weight-semibold">
                               Content Information
                                <span class="text-danger">*</span>
                            </label>

                            <textarea type="text"
                                class="form-control"
                                id="full_name"
                                name="content_info"
                                placeholder="event of the Request"
                                required></textarea>
                        </div>
                    </div>

                     <div class="col-12">
                        <div class="form-group">
                            <label for="content_attachment" class="font-weight-semibold">
                                Content Information Attachment 
                                <span class="text-danger">*</span>
                            </label>
                            <span class="d-block  small mb-2 text-danger">For attachment, Google Drive link is only allowed for easy access of the materials (optional)</span>
                            <input type="url"
                                class="form-control"
                                id="content_attachment"
                                name="content_attachment"
                                placeholder="https://drive.google.com/..."
                                pattern="https:\/\/.*drive\.google\.com\/.*"
                                title="Please enter a valid Google Drive link"
                                required>
                        </div>
                    </div>
               
                
                
                
                <!-- Request Details -->
                <div class="col-12 mb-4">
                    <h6 class="text-uppercase font-weight-bold mb-1">
                        Reminder
                    </h6>

                    <p class="text-muted font-weight-bold mb-0">
                        NOTE: AVP needs at least 7 DAYS Production Time.
                    </p>
                    <span>Sit-in is encouraged before the Final Cut.</span>
                </div>
              
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
