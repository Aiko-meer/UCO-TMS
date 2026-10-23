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

                <!-- Divider -->
                <div class="col-12 px-0">
                    <hr class="my-4">
                </div>

                <!-- Settings & Security Details -->
                <div class="row">
                    <div class="col-12 mb-2">
                        <h6 class="text-uppercase font-weight-bold mb-3 text-dark" style="font-size: 85%;">
                            Settings & Security
                        </h6>
                        <div class="d-flex flex-column">
                            <small class="text-muted mb-1">User Type: <strong>{{ ucwords(str_replace('_', ' ', $user->user_type)) }}</strong></small>
                            <small class="text-muted mb-1">Department: <strong>{{ $user->department }}</strong></small>
                            
                            <small class="text-muted mb-1">
                                Status: 
                                @if(isset($user->last_seen_at) && \Carbon\Carbon::parse($user->last_seen_at)->gt(now()->subMinutes(5)))
                                    <span class="badge badge-success px-2">● Online</span>
                                @else
                                    <span class="badge badge-secondary px-2">● Offline</span>
                                @endif
                            </small>

                            <small class="text-muted mb-1">
                                Account State: 
                                @if(isset($user->is_blocked) && $user->is_blocked)
                                    <span class="badge badge-danger">Blocked</span>
                                @else
                                    <span class="badge badge-success">Active</span>
                                @endif
                            </small>

                            <small class="text-muted mb-1">Date Created: <strong>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</strong></small>
                            <small class="text-muted mb-1">Last Updated: <strong>{{ \Carbon\Carbon::parse($user->updated_at)->format('M d, Y') }}</strong></small>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="col-12 px-0">
                    <hr class="my-4">
                </div>

                <!-- Segmented Task Proportion Bar -->
                    <div class="mb-3">
                        @php
                            $totalDone = ($user->uzpr_count ?? 0) + ($user->uzpost_count ?? 0) + ($user->coverage_count ?? 0) + ($user->layout_count ?? 0) + ($user->publication_count ?? 0);
                            
                            // Calculate exact percentages
                            $pUzpr = $totalDone > 0 ? (($user->uzpr_count ?? 0) / $totalDone) * 100 : 0;
                            $pUzpost = $totalDone > 0 ? (($user->uzpost_count ?? 0) / $totalDone) * 100 : 0;
                            $pCoverage = $totalDone > 0 ? (($user->coverage_count ?? 0) / $totalDone) * 100 : 0;
                            $pLayout = $totalDone > 0 ? (($user->layout_count ?? 0) / $totalDone) * 100 : 0;
                            $pPublication = $totalDone > 0 ? (($user->publication_count ?? 0) / $totalDone) * 100 : 0;
                        @endphp

                        <div class="d-flex justify-content-between small mb-1">
                            <span class="font-weight-bold text-dark">Category Share Breakdown</span>
                            <span class="text-muted">Total: {{ $totalDone }} tasks</span>
                        </div>

                        <!-- Multi-colored stacked progress bar -->
                        <div class="progress" style="height: 10px; border-radius: 5px; overflow: hidden;">
                            <div class="progress-bar bg-primary" style="width: {{ $pUzpr }}%;" title="Uzpr: {{ $user->uzpr_count ?? 0 }}"></div>
                            <div class="progress-bar bg-info" style="width: {{ $pUzpost }}%;" title="Uzpost: {{ $user->uzpost_count ?? 0 }}"></div>
                            <div class="progress-bar bg-success" style="width: {{ $pCoverage }}%;" title="Coverage: {{ $user->coverage_count ?? 0 }}"></div>
                            <div class="progress-bar bg-warning" style="width: {{ $pLayout }}%;" title="Layout: {{ $user->layout_count ?? 0 }}"></div>
                            <div class="progress-bar bg-danger" style="width: {{ $pPublication }}%;" title="Publication: {{ $user->publication_count ?? 0 }}"></div>
                        </div>
                    </div>

                    <div class="row text-center mt-3">
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block font-weight-bold">UZPR</small>
                                <span class="h5 font-weight-bold text-primary mb-0">{{ $user->uzpr_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block font-weight-bold">UZPOST</small>
                                <span class="h5 font-weight-bold text-info mb-0">{{ $user->uzpost_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block font-weight-bold">COVERAGE</small>
                                <span class="h5 font-weight-bold text-success mb-0">{{ $user->coverage_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block font-weight-bold">LAYOUT</small>
                                <span class="h5 font-weight-bold text-warning mb-0">{{ $user->layout_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block font-weight-bold">PUBLISH</small>
                                <span class="h5 font-weight-bold text-danger mb-0">{{ $user->publication_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-header bg-light px-4 py-3 border-top justify-content-end">
                <button type="button" class="btn btn-secondary px-4 btn-sm" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<!-- Vanilla JS Modal & Chart Initialization (No jQuery dependency) -->
<!-- Direct Modal-Specific Chart Initialization -->

@endforeach

