<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
            <form action="{{route ('user.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 py-4">
                    
                    <div class="row">
                        <!-- Left Column: Text Inputs -->
                        <div class="col-md-7">
                            <!-- Full Name Field -->
                            <div class="form-group mb-3">
                                <label for="name" class="form-label font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                            </div>

                            <!-- Full Name Field -->
                            <div class="form-group mb-3">
                                <label for="emp_id" class="form-label font-weight-bold text-dark">Employee Id <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="emp_id" placeholder="Enter ID" required>
                            </div>

                            <!-- Email Field -->
                            <div class="form-group mb-3">
                                <label for="email" class="form-label font-weight-bold text-dark">Email Address <span class="text-danger">*</span></label>
                                <input type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="name@uz.edu.ph"
                                required
                                pattern=".*@uz\.edu\.ph$"
                                aria-describedby="emailHelp">
                            </div>

                            <!-- User Type Field -->
                            <div class="form-group mb-3">
                                <label for="user_type" class="form-label font-weight-bold text-dark">User Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="user_type" name="user_type" required>
                                    <option value="" disabled selected>Select user type</option>
                                    <option value="content_admin">Content Supervisor</option>
                                    <option value="creative_admin">Creative Supervisor</option>
                                    <option value="content_team">Content Team</option>
                                    <option value="creative_team">Creative Team</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">Viewer</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column: Image Preview & Verification -->
                        <div class="col-md-5 d-flex flex-column justify-content-between border-left pl-md-4">
                            
                            <!-- Profile Image Upload & Preview -->
                            <div class="form-group mb-3 text-center">
                                <label class="form-label font-weight-bold text-dark d-block text-left">Profile Image</label>
                                <div class="mb-2">
                                    <img id="imagePreview" src="https://via.placeholder.com/100" alt="Preview" class="rounded-circle shadow-sm border" style="width: 90px; height: 90px; object-fit: cover;">
                                </div>
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                    <label class="custom-file-label text-truncate" for="image">Choose file</label>
                                </div>
                            </div>

                        </div>
                    </div>

                   <!-- Password Field -->
                    <div class="form-group mb-0">
                        <label for="password" class="form-label font-weight-bold text-dark">Temporary Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" readonly>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-header bg-light px-4 py-3 border-top justify-content-end">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save User</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- JavaScript for Image Preview & Custom File Input Text update -->
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
            // Update label name if using Bootstrap custom file input
            let fileName = event.target.files[0].name;
            let nextSibling = event.target.nextElementSibling;
            if(nextSibling) {
                nextSibling.innerText = fileName;
            }
        }
    }

    function generateTempPassword(length = 10) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * chars.length);
        password += chars[randomIndex];
    }
    
    // Set the value to the password input field
    document.getElementById('password').value = password;
}

// Automatically run the function when the page loads
window.addEventListener('DOMContentLoaded', () => {
    generateTempPassword();
});
</script>
