@foreach($user as $user)   
<!-- View User Modal (Unique per user row) -->
<div class="modal fade" id="viewModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            
            <!-- Modal Header -->
            <div class="modal-header bg-light px-4 py-3">
                <h5 class="modal-title font-weight-bold" id="viewModalLabel-{{ $user->id }}">
                    <i class="material-icons text-info mr-2" style="vertical-align: middle;">account_circle</i> User Details: {{ $user->fullname }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-4">
                <div class="row align-items-center">
                    <!-- Left: Profile Image -->
                    <div class="col-md-4 text-center border-right">
                        <img src="{{ $user->image ? asset('storage/' . $user->image) : 'https://via.placeholder.com/100' }}" alt="Profile Image" class="rounded-circle shadow-sm border mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 class="font-weight-bold text-dark mb-1">{{ $user->fullname }}</h5>
                        <p class="text-muted small mb-0">{{ ucwords(str_replace('_', ' ', $user->user_type)) }}</p>
                    </div>

                    <!-- Right: Details -->
                    <div class="col-md-8 pl-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted small uppercase">Employee ID</label>
                            <p class="font-weight-bold text-dark mb-0">{{ $user->employee_id }}</p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted small uppercase">Email Address</label>
                            <p class="font-weight-bold text-dark mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold text-muted small uppercase">Account Status</label>
                            <p class="mb-0">
                                @if($user->verified)
                                    <span class="badge badge-success px-2 py-1">Verified</span>
                                @else
                                    <span class="badge badge-warning px-2 py-1">Unverified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-header bg-light px-4 py-3 border-top justify-content-end">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endforeach