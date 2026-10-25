<div class="modal fade" id="newTaskModal" tabindex="-1"
    role="dialog" aria-labelledby="newTaskModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">

        <div class="modal-header">
            <div>
                <h5 class="modal-title font-weight-bold mb-1">
                    Create Soft Copy Request
                </h5>
                <small class="text-muted">
                    Submit a soft copy request to the UCO team
                </small>
            </div>

            <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Update route to point to your soft copy store route -->
        <form action="{{route ('softcopy.store')}}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="modal-body px-4 py-4">

                <div class="row">

                    <!-- Requester Information -->
                    <div class="col-12 mb-3">
                        <h6 class="text-uppercase font-weight-bold mb-1">
                            Requester Information
                        </h6>
                    </div>

                    <!-- First Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name" class="font-weight-semibold">
                                First Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="f_name"
                                   name="f_name"
                                   placeholder="First name"
                                   required>
                        </div>
                    </div>

                    <!-- Middle Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="middle_name" class="font-weight-semibold">
                                Middle Name
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="middle_name"
                                   name="middle_name"
                                   placeholder="Middle name (optional)">
                        </div>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="last_name" class="font-weight-semibold">
                                Last Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="l_name"
                                   name="l_name"
                                   placeholder="Last name"
                                   required>
                        </div>
                    </div>

                    <!-- Contact Number -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="number" class="font-weight-semibold">
                                Contact Number <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="number"
                                   name="number"
                                   placeholder="09XXXXXXXXX"
                                   required>
                        </div>
                    </div>

                    <!-- Student Number  -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="student_number" class="font-weight-semibold">
                                Student Number  <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="number"
                                   name="id_number"
                                   placeholder=""
                                   required>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="font-weight-semibold">
                                Email Address <span class="text-danger" aria-hidden="true">*</span>
                            </label>
                            <small id="emailHelp" class="text-danger form-text d-block mb-1">
                                Note: Please use your official UZ email address.
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
                        <hr class="my-3">
                    </div>

                    <!-- Status / Graduating Radio Input -->
                    <div class="col-12 mb-3">
                        <h6 class="text-uppercase font-weight-bold mb-1">
                            Status
                        </h6>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-semibold d-block">
                                Are you graduating? <span class="text-danger">*</span>
                            </label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="is_graduating_yes" name="is_graduating" value="Yes" class="custom-control-input" required>
                                <label class="custom-control-label" for="is_graduating_yes">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="is_graduating_no" name="is_graduating" value="No" class="custom-control-input" required>
                                <label class="custom-control-label" for="is_graduating_no">No</label>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="col-12">
                        <hr class="my-3">
                    </div>

                    <!-- Academic & Request Details -->
                    <div class="col-12 mb-3">
                        <h6 class="text-uppercase font-weight-bold mb-1">
                            Academic & Photo Details
                        </h6>
                    </div>

                    <!-- School Level -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="school_level" class="font-weight-semibold">
                                School Level <span class="text-danger">*</span>
                            </label>
                            <select id="school_level" name="school_level" class="form-control" required>
                                <option value="" selected disabled>Select School Level</option>
                                <option value="college">College</option>
                                <option value="gradstudies">Graduate Studies</option>
                            </select>
                        </div>
                    </div>

                    <!-- school -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="school" class="font-weight-semibold">
                                School:<span class="text-danger">*</span>
                            </label>
                            <select id="school" name="school" class="form-control" required>
                                <option value="" selected disabled>Select School</option>
                                <option value="1">School of allied medicine</option>
                                <option value="2">school of engineering information communicatio and technology</option>
                                <option value="3">School of Business management</option>
                                <option value="4">Institute of Technical Education</option>
                                <option value="5">School of Education</option>
                                <option value="6">School of Liberal arts and science </option>
                                <option value="7">Graduate Studies Program</option>
                                <option value="8">School of Dentistry</option>
                                <option value="9">Ipil Campus</option>
                                <option value="10">Pagadian Campus</option>
                            </select>
                        </div>
                    </div>

                    <!-- Program -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="program" class="font-weight-semibold">
                                Program / Course <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="program"
                                   name="program"
                                   placeholder="e.g. BS Information Technology"
                                   required>
                        </div>
                    </div>

                    <!-- Year Level -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="year_level" class="font-weight-semibold">
                                Year Level <span class="text-danger">*</span>
                            </label>
                            <select id="year_level" name="year_level" class="form-control" required>
                                <option value="" selected disabled>Select Year Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="5th Year">5th Year</option>
                                <option value="N/A">N/A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Year Graduated (grad) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="grad" class="font-weight-semibold">
                                Year Graduated (If Alumni)
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="grad"
                                   name="grad"
                                   placeholder="e.g. 2025">
                        </div>
                    </div>

                    <!-- Date of Photo -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_photo" class="font-weight-semibold">
                                Date of Photo<span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control"
                                   id="date_photo"
                                   name="date_photo"
                                   required>
                        </div>
                    </div>

                    <!-- ID Attachment -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_attachment" class="font-weight-semibold">
                                ID Attachment <span class="text-danger">*</span>
                            </label>

                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input"
                                       id="id_attachment"
                                       name="id_attachment"
                                       onchange="updateIdFileName(this)"
                                       required>
                                <label class="custom-file-label" for="id_attachment" id="idCustomLabel">
                                    Choose file
                                </label>
                            </div>

                            <small class="form-text text-muted">
                                Upload School ID or Valid ID (JPG, PNG — Max 10 MB)
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
                    Submit Soft Copy Request
                </button>
            </div>

        </form>

    </div>
</div>
</div>

<!-- Simple script to handle file label name update -->
<script>
    function updateIdFileName(input) {
        const customLabel = document.getElementById('idCustomLabel');
        if (input.files && input.files.length > 0) {
            customLabel.textContent = input.files[0].name;
        } else {
            customLabel.textContent = 'Choose file';
        }
    }
</script>