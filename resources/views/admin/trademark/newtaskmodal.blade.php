<!-- Trademark Request Modal -->
<div class="modal fade" id="trademarkModal" tabindex="-1" role="dialog" aria-labelledby="trademarkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            
            <form action="{{route ('trademark.store')}}" method="POST">
                @csrf
                
                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-1" id="trademarkModalLabel">
                            <i class="material-icons align-middle mr-1">verified</i> New Trademark Request
                        </h5>
                        <small class="text-white-50">Please complete the required details below to submit a request.</small>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body px-4 py-4" style="max-height: 75vh; overflow-y: auto;">
                    
                    <!-- Section 1: Requestor Information -->
                    <h6 class="text-uppercase text-muted font-weight-bold small mb-3 border-bottom pb-2">
                        1. Requestor Information
                    </h6>

                    <div class="form-row">
                        <!-- Salutation -->
                        <div class="form-group col-md-3">
                            <label for="salutation" class="font-weight-semibold text-dark small">Salutation <span class="text-danger">*</span></label>
                            <select name="salutation" id="salutation" class="form-control" required>
                                <option value="" disabled selected>Select</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Ms.">Ms.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Dr.">Dr.</option>
                                <option value="Prof.">Prof.</option>
                            </select>
                        </div>

                        <!-- First Name -->
                        <div class="form-group col-md-3">
                            <label for="f_name" class="font-weight-semibold text-dark small">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="f_name" id="f_name" class="form-control" placeholder="John" required>
                        </div>

                        <!-- Middle Name -->
                        <div class="form-group col-md-3">
                            <label for="middle_name" class="font-weight-semibold text-dark small">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control" placeholder="M.">
                        </div>

                        <!-- Last Name -->
                        <div class="form-group col-md-3">
                            <label for="l_name" class="font-weight-semibold text-dark small">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="l_name" id="l_name" class="form-control" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Category Requestor -->
                        <div class="form-group col-md-6">
                            <label for="category_requestor" class="font-weight-semibold text-dark small">Category Requestor <span class="text-danger">*</span></label>
                            <select name="category_requestor" id="category_requestor" class="form-control" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="Student">Student</option>
                                <option value="Faculty / Teacher">Faculty / Teacher</option>
                                <option value="Administrative Staff">Administrative Staff</option>
                                <option value="Alumni">Alumni</option>
                                <option value="External Partner / Organization">External Partner / Organization</option>
                            </select>
                        </div>

                        <!-- Position -->
                        <div class="form-group col-md-6">
                            <label for="position" class="font-weight-semibold text-dark small">Position / Designation <span class="text-danger">*</span></label>
                            <input type="text" name="position" id="position" class="form-control" placeholder="e.g. Club President, Instructor" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Department / Organization -->
                        <div class="form-group col-md-6">
                            <label for="department_org" class="font-weight-semibold text-dark small">Department / Organization <span class="text-danger">*</span></label>
                            <select name="department_org" id="department_org" class="form-control" required>
                                        <option value="Arturo Eustaquio Libraries and Information Centre">Arturo Eustaquio Libraries and Information Centre</option>
                                        <option value="Education Technology Centre">Education Technology Centre</option>
                                        <option value="External Affairs and Institutional Development Centre">External Affairs and Institutional Development Centre</option>
                                        <option value="Finance Department">Finance Department</option>
                                        <option value="F45 Zamboanga">F45 Zamboanga</option>
                                        <option value="General Services Department">General Services Department</option>
                                        <option value="High School Department">High School Department</option>
                                        <option value="Human Resources Department">Human Resources Department</option>
                                        <option value="Institute of Technical Education">Institute of Technical Education</option>
                                        <option value="Logistics and Procurement Department">Logistics and Procurement Department</option>
                                        <option value="Management and Information Systems Department">Management and Information Systems Department</option>
                                        <option value="Office of the Bldg. Admin (Lab. & HS)">Office of the Bldg. Admin (Lab. & HS)</option>
                                        <option value="Office of the Vice President for Academic Affairs">Office of the Vice President for Academic Affairs</option>
                                        <option value="Office of the Vice President for Administration">Office of the Vice President for Administration</option>
                                        <option value="Office of the Vice President for Finance">Office of the Vice President for Finance</option>
                                        <option value="Office of the University President">Office of the University President</option>
                                        <option value="Quality Management Systems Department">Quality Management Systems Department</option>
                                        <option value="Safety, Health, and Environment Department">Safety, Health, and Environment Department</option>
                                        <option value="School of Allied Medicine">School of Allied Medicine</option>
                                        <option value="School of Business and Management">School of Business and Management</option>
                                        <option value="School of Criminal Justice">School of Criminal Justice</option>
                                        <option value="School of Dentistry">School of Dentistry</option>
                                        <option value="School of Education">School of Education</option>
                                        <option value="School of Engineering, Information, Communications, and Technology">School of Engineering, Information, Communications, and Technology</option>
                                        <option value="School of Liberal Arts and Sciences">School of Liberal Arts and Sciences</option>
                                        <option value="Student Affairs and Services Department">Student Affairs and Services Department</option>
                                        <option value="Summit Centre">Summit Centre</option>
                                        <option value="University Bookstore and Merchandise">University Bookstore and Merchandise</option>
                                        <option value="University Communications Department">University Communications Department</option>
                                        <option value="University Registrar">University Registrar</option>
                                        <option value="University Research and Development Centre">University Research and Development Centre</option>
                                        <option value="UZ-Ipil Branch">UZ-Ipil Branch</option>
                                        <option value="UZ-Pagadian Branch">UZ-Pagadian Branch</option>
                                        <option value="Others">Others</option>
                            </select>
                        </div>

                        <!-- Contact Number -->
                        <div class="form-group col-md-6">
                            <label for="contact_number" class="font-weight-semibold text-dark small">Contact Number <span class="text-danger">*</span></label>
                            <input type="tel" name="contact_number" id="contact_number" class="form-control" placeholder="e.g. 09123456789" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Email -->
                        <div class="form-group col-md-6">
                            <label for="email" class="font-weight-semibold text-dark small">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required>
                        </div>

                        <!-- Office Address -->
                        <div class="form-group col-md-6">
                            <label for="office_address" class="font-weight-semibold text-dark small">Office / Mailing Address <span class="text-danger">*</span></label>
                            <input type="text" name="office_address" id="office_address" class="form-control" placeholder="Room/Building/Address" required>
                        </div>
                    </div>

                    <!-- Section 2: Trademark Details -->
                    <h6 class="text-uppercase text-muted font-weight-bold small mb-3 mt-3 border-bottom pb-2">
                        2. Trademark Usage Details
                    </h6>

                    <div class="form-row">
                        <!-- Trademark to Use -->
                        <div class="form-group col-md-6">
                            <label for="trademark_to_use" class="font-weight-semibold text-dark small">Trademark to Use <span class="text-danger">*</span></label>
                            <select name="trademark_to_use" id="trademark_to_use" class="form-control" required>
                                <option value="" disabled selected>Select Trademark Option</option>
                                <option value="University Official Logo">University Official Logo</option>
                                <option value="Institutional Seal">Institutional Seal</option>
                                <option value="Wildcat Mascot Logo">Wildcat Mascot Logo</option>
                                <option value="Department / School Emblem">Department / School Emblem</option>
                                <option value="Official Typography / Tagline">Official Typography / Tagline</option>
                            </select>
                        </div>

                        <!-- Date to Use -->
                        <div class="form-group col-md-6">
                            <label for="date_to_use" class="font-weight-semibold text-dark small">Date to Use <span class="text-danger">*</span></label>
                            <input type="date" name="date_to_use" id="date_to_use" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Category for Trademark Use -->
                        <div class="form-group col-md-6">
                            <label for="category_trademark_use" class="font-weight-semibold text-dark small">Category for Trademark Use <span class="text-danger">*</span></label>
                            <select name="category_trademark_use" id="category_trademark_use" class="form-control" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="profit">Profit</option>
                                <option value="non-profit">Non-Profit</option>
                                <option value="fundrasing">Fundrasing</option>
                            </select>
                        </div>

                        <!-- Target Audience -->
                        <div class="form-group col-md-6">
                            <label for="target_audience" class="font-weight-semibold text-dark small">Target Audience <span class="text-danger">*</span></label>
                            <input type="text" name="target_audience" id="target_audience" class="form-control" placeholder="e.g. Enrolled Students, General Public" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Materials / Media -->
                        <div class="form-group col-md-6">
                            <label for="materials_media" class="font-weight-semibold text-dark small">Materials / Media <span class="text-danger">*</span></label>
                            <input type="text" name="materials_media" id="materials_media" class="form-control" placeholder="e.g. Posters, Shirts, Social Media Banner" required>
                        </div>

                        <!-- Location Use -->
                        <div class="form-group col-md-6">
                            <label for="location_use" class="font-weight-semibold text-dark small">Location of Use <span class="text-danger">*</span></label>
                            <input type="text" name="location_use" id="location_use" class="form-control" placeholder="e.g. Main Campus, Facebook Page" required>
                        </div>
                    </div>

                    <!-- Purpose of Use -->
                    <div class="form-group">
                        <label for="purpose_of_use" class="font-weight-semibold text-dark small">Purpose of Use <span class="text-danger">*</span></label>
                        <textarea name="purpose_of_use" id="purpose_of_use" class="form-control" rows="3" placeholder="Explain the context and reason for requesting the trademark..." required></textarea>
                    </div>

                    <!-- Note / Remarks -->
                    <div class="form-group">
                        <label for="note" class="font-weight-semibold text-dark small">Additional Note / Remarks</label>
                        <textarea name="note" id="note" class="form-control" rows="2" placeholder="Any special notes or extra details..."></textarea>
                    </div>

                    <!-- Approval Status (Hidden or Checkbox for Admins) -->
                    <div class="form-group mb-0">
                        <label for="approved_by" class="font-weight-semibold text-dark small">
                            Approved By:
                        </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="material-icons text-muted" style="font-size: 18px;">person_outline</i>
                                    </span>
                                </div>
                                <input type="text" 
                                    class="form-control" 
                                    id="approved_by" 
                                    name="approved_by" 
                                    placeholder="e.g. Dr. Jane Doe / Dean's Office">
                            </div>
                        </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="material-icons align-middle mr-1" style="font-size: 16px;">save</i> Submit Request
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>