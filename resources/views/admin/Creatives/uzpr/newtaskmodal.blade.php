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

        <form action="{{route ('uzpr.store')}}" method="POST" enctype="multipart/form-data">
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

            
                <!-- event -->
                    <div class="col-12">
                        <div class="form-group">
                            <label for="full_name" class="font-weight-semibold">
                                Title Event: 
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

                    <!-- date event -->
                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_needed" class="font-weight-semibold">
                            Date Needed
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                            class="form-control"
                            id="date_event"
                            name="date_event"
                            required>
                    </div>
                </div>

                     <!-- event -->
                    <div class="col-12">
                        <div class="form-group">
                            <label for="content_attachment" class="font-weight-semibold">
                                Content Write-up
                                <span class="text-danger">*</span>
                            </label>
                            <span class="d-block  small mb-2 text-danger">For attachment, Google Drive link is only allowed for easy access of the materials </span>
                            <input type="url"
                                class="form-control"
                                id="content_information"
                                name="content_information"
                                placeholder="https://drive.google.com/..."
                                pattern="https:\/\/.*drive\.google\.com\/.*"
                                title="Please enter a valid Google Drive link"
                               >
                        </div>
                    </div>

                     <div class="col-12">
                        <div class="form-group">
                            <label for="content_attachment" class="font-weight-semibold">
                                Content  Attachment 
                                <span class="text-danger">*</span>
                            </label>
                            <span class="d-block  small mb-2 text-danger">For attachment, Google Drive link is only allowed for easy access of the materials </span>
                            <input type="url"
                                class="form-control"
                                id="content_attachment"
                                name="content_attachment"
                                placeholder="https://drive.google.com/..."
                                pattern="https:\/\/.*drive\.google\.com\/.*"
                                title="Please enter a valid Google Drive link"
                               >
                        </div>
                    </div>

                    <div class="col-md-12"> <!-- Expanded to col-md-12 for better room with long titles -->
                    <div class="form-group">
                        <label class="font-weight-semibold d-block">
                            Choose SDG (Select all that apply):
                            <span class="text-danger">*</span>
                        </label>

                        <div class="mt-2">
                            <!-- SDG 1 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_1" value="1">
                                <label class="form-check-label" for="sdg_1">1. No Poverty</label>
                            </div>

                            <!-- SDG 2 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_2" value="2">
                                <label class="form-check-label" for="sdg_2">2. Zero Hunger</label>
                            </div>

                            <!-- SDG 3 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_3" value="3">
                                <label class="form-check-label" for="sdg_3">3. Good Health and Well-being</label>
                            </div>

                            <!-- SDG 4 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_4" value="4">
                                <label class="form-check-label" for="sdg_4">4. Quality Education</label>
                            </div>

                            <!-- SDG 5 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_5" value="5">
                                <label class="form-check-label" for="sdg_5">5. Gender Equality</label>
                            </div>

                            <!-- SDG 6 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_6" value="6">
                                <label class="form-check-label" for="sdg_6">6. Clean Water and Sanitation</label>
                            </div>

                            <!-- SDG 7 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_7" value="7">
                                <label class="form-check-label" for="sdg_7">7. Affordable and Clean Energy</label>
                            </div>

                            <!-- SDG 8 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_8" value="8">
                                <label class="form-check-label" for="sdg_8">8. Decent Work and Economic Growth</label>
                            </div>

                            <!-- SDG 9 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_9" value="9">
                                <label class="form-check-label" for="sdg_9">9. Industry, Innovation and Infrastructure</label>
                            </div>

                            <!-- SDG 10 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_10" value="10">
                                <label class="form-check-label" for="sdg_10">10. Reduced Inequalities</label>
                            </div>

                            <!-- SDG 11 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_11" value="11">
                                <label class="form-check-label" for="sdg_11">11. Sustainable Cities and Communities</label>
                            </div>

                            <!-- SDG 12 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_12" value="12">
                                <label class="form-check-label" for="sdg_12">12. Responsible Consumption and Production</label>
                            </div>

                            <!-- SDG 13 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_13" value="13">
                                <label class="form-check-label" for="sdg_13">13. Climate Action</label>
                            </div>

                            <!-- SDG 14 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_14" value="14">
                                <label class="form-check-label" for="sdg_14">14. Life Below Water</label>
                            </div>

                            <!-- SDG 15 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_15" value="15">
                                <label class="form-check-label" for="sdg_15">15. Life on Land</label>
                            </div>

                            <!-- SDG 16 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_16" value="16">
                                <label class="form-check-label" for="sdg_16">16. Peace, Justice and Strong Institutions</label>
                            </div>

                            <!-- SDG 17 -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sdg[]" id="sdg_17" value="17">
                                <label class="form-check-label" for="sdg_17">17. Partnerships for the Goals</label>
                            </div>
                        </div>
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
