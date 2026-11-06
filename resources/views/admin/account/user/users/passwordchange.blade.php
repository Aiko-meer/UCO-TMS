@foreach($user as $user)
<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="changePasswordModalLabel-{{ $user->id }}">
                        Change Password for {{ $user->full_name ?? $user->name }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    
                </div>

                <div class="modal-body">
                    <!-- New Password Field -->
                    <div class="form-group">
                        <label for="new_password_{{ $user->id }}" class="font-weight-semibold">New Password <span class="text-danger">*</span></label>
                        
                        <input type="password" name="password" id="new_password_{{ $user->id }}" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password" required>
                        
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Live Requirement Checklist -->
                        <div class="mt-2" style="font-size: 85%;">
                            <p class="mb-1 text-muted font-weight-bold">Password must contain:</p>
                            <ul class="pl-3 mb-0" style="list-style: none; padding-left: 0;">
                                <li id="rule-length-{{ $user->id }}" class="text-muted mb-1">○ At least 8 characters</li>
                                <li id="rule-uppercase-{{ $user->id }}" class="text-muted mb-1">○ At least 1 uppercase letter (A-Z)</li>
                                <li id="rule-number-{{ $user->id }}" class="text-muted mb-1">○ At least 1 number (0-9)</li>
                                <li id="rule-special-{{ $user->id }}" class="text-muted mb-1">○ At least 1 special character (!@#$%^&*)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password_confirmation_{{ $user->id }}" class="font-weight-semibold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation_{{ $user->id }}" class="form-control" placeholder="Confirm new password" required>
                        
                        <!-- Real-time match notification text -->
                        <small id="match-status-{{ $user->id }}" class="form-text mt-1" style="font-size: 85%;"></small>
                    </div>
                    <script>
document.addEventListener("DOMContentLoaded", function () {
    const userId = "{{ $user->id }}";
    const passwordInput = document.getElementById("new_password_" + userId);
    const confirmInput = document.getElementById("password_confirmation_" + userId);
    const matchStatus = document.getElementById("match-status-" + userId);

    if (!passwordInput || !confirmInput) return;

    // Rules Elements for New Password
    const ruleLength = document.getElementById("rule-length-" + userId);
    const ruleUppercase = document.getElementById("rule-uppercase-" + userId);
    const ruleNumber = document.getElementById("rule-number-" + userId);
    const ruleSpecial = document.getElementById("rule-special-" + userId);

    function updateRule(element, isValid, text) {
        if (!element) return;
        if (isValid) {
            element.style.color = "#28a745"; // Success Green
            element.style.fontWeight = "bold";
            element.innerHTML = "✓ " + text;
        } else {
            element.style.color = "#6c757d"; // Muted Gray
            element.style.fontWeight = "normal";
            element.innerHTML = "○ " + text;
        }
    }

    function checkPasswordMatch() {
        const passVal = passwordInput.value;
        const confirmVal = confirmInput.value;

        if (confirmVal === "") {
            matchStatus.textContent = "";
            confirmInput.classList.remove("is-valid", "is-invalid");
            return;
        }

        if (passVal === confirmVal) {
            matchStatus.style.color = "#28a745";
            matchStatus.textContent = "✓ Passwords match";
            confirmInput.classList.remove("is-invalid");
            confirmInput.classList.add("is-valid");
        } else {
            matchStatus.style.color = "#dc3545";
            matchStatus.textContent = "✕ Passwords do not match";
            confirmInput.classList.remove("is-valid");
            confirmInput.classList.add("is-invalid");
        }
    }

    // Listen to changes on the new password field
    passwordInput.addEventListener("input", function () {
        const val = this.value;

        const hasLength = val.length >= 8;
        const hasUpper = /[A-Z]/.test(val);
        const hasNum = /[0-9]/.test(val);
        const hasSpecial = /[^A-Za-z0-9]/.test(val);

        updateRule(ruleLength, hasLength, "At least 8 characters");
        updateRule(ruleUppercase, hasUpper, "At least 1 uppercase letter (A-Z)");
        updateRule(ruleNumber, hasNum, "At least 1 number (0-9)");
        updateRule(ruleSpecial, hasSpecial, "At least 1 special character (!@#$%^&*)");

        // Re-check match status if confirmation already has text
        if (confirmInput.value.length > 0) {
            checkPasswordMatch();
        }
    });

    // Listen to changes on the confirmation field
    confirmInput.addEventListener("input", checkPasswordMatch);
});
</script>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
