@foreach ($user as $user)
<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold" id="deleteUserModalLabel-{{ $user->id }}">
                        Delete User: {{ $user->full_name ?? $user->name }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p class="text-danger font-weight-semibold mb-2">Are you sure you want to delete this account?</p>
                    <p class="text-muted small mb-0">This action cannot be undone. All associated data for this user will be permanently removed from the system.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Yes, Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
<!-- Script to handle clean modal switching -->
<script>
function openDeleteModal(userId) {
    // Hide any open view modals first to avoid stacking bugs
    $('.modal').modal('hide');

    // Open the delete confirmation modal
    setTimeout(function() {
        $('#deleteUserModal-' + userId).modal('show');
    }, 200);
}
</script>