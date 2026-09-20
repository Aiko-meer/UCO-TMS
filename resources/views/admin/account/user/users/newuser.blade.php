<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            
            <!-- Modal Header -->
            <div class="modal-header bg-light px-4 py-3">
                <h5 class="modal-title font-weight-bold" id="addUserModalLabel">
                    <i class="material-icons text-primary mr-2" style="vertical-align: middle;">person_add</i> Add New User
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form action="" method="POST">
                @csrf
                <div class="modal-body px-4 py-4">
                    
                    <!-- Full Name Field -->
                    <div class="form-group mb-3">
                        <label for="name" class="form-label font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group mb-3">
                        <label for="email" class="form-label font-weight-bold text-dark">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    </div>

                    <!-- Department / Role Selection -->
                    <div class="form-group mb-3">
                        <label for="department" class="form-label font-weight-bold text-dark">Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="" disabled selected>Select department</option>
                           
                        </select>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group mb-0">
                        <label for="password" class="form-label font-weight-bold text-dark">Temporary Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-header bg-light px-4 py-3 border-top justify-content-end">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-accent px-4">Save User</button>
                </div>
            </form>

        </div>
    </div>
</div>