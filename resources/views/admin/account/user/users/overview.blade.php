{{-- ============================================================
     User Details Modals
     Controller must pass the collection as $users (plural).
     Roles come from config/user_types.php
     ============================================================ --}}

<style>
    .user-modal .avatar-lg { width: 96px; height: 96px; object-fit: cover; }
    .user-modal .avatar-initials {
        width: 96px; height: 96px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 32px; font-weight: 600; color: #fff;
        background: linear-gradient(135deg, #26c6da, #00838f);
    }
    .user-modal .section-title {
        font-size: .72rem; letter-spacing: .08em; text-transform: uppercase;
        font-weight: 700; color: #8a8f98; margin-bottom: .75rem;
    }
    .user-modal .info-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: #8a8f98; margin-bottom: 2px; }
    .user-modal .info-value { font-weight: 600; color: #2d3436; margin-bottom: 0; word-break: break-word; }
    .user-modal .stat-tile { border: 1px solid #eceff1; border-radius: 8px; padding: .75rem .5rem; background: #fafbfc; }
    .user-modal .stat-tile .stat-num { font-size: 1.35rem; font-weight: 700; line-height: 1.1; }
    .user-modal .stat-tile .stat-name { font-size: .68rem; letter-spacing: .06em; font-weight: 700; color: #8a8f98; }
    .user-modal .role-card { border: 1px solid #e3e8ec; border-radius: 8px; background: #f8fafb; padding: 1rem; }
    .user-modal .legend-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 4px; }
</style>

@foreach($user as $user)
    @php
        // ---- Display helpers -------------------------------------------------
        $roles = [
        'content_admin'  => 'Content Supervisor',
        'creative_admin' => 'Creative Supervisor',
        'content_team'   => 'Content Team',
        'creative_team'  => 'Creative Team',
        'yearbook_team'  => 'Yearbook Team',
        'admin'          => 'Admin',
        'user'           => 'Viewer',
    ];
    $roleLabel = $roles[$user->user_type] ?? ucwords(str_replace('_', ' ', $user->user_type));
    // ...rest of your @php variables
        $isSelf     = auth()->id() === $user->id;

        $nameParts  = preg_split('/\s+/', trim($user->fullname));
        $initials   = strtoupper(mb_substr($nameParts[0] ?? '', 0, 1) . mb_substr(end($nameParts) ?: '', 0, 1));

        $isOnline   = !empty($user->last_seen_at)
                      && \Carbon\Carbon::parse($user->last_seen_at)->gt(now()->subMinutes(5));
        $isBlocked  = !empty($user->is_blocked);

        // ---- Task breakdown --------------------------------------------------
        $tasks = [
            ['label' => 'UZPR',     'count' => $user->uzpr_count ?? 0,        'color' => 'primary'],
            ['label' => 'UZPOST',   'count' => $user->uzpost_count ?? 0,      'color' => 'info'],
            ['label' => 'COVERAGE', 'count' => $user->coverage_count ?? 0,    'color' => 'success'],
            ['label' => 'LAYOUT',   'count' => $user->layout_count ?? 0,      'color' => 'warning'],
            ['label' => 'PUBLISH',  'count' => $user->publication_count ?? 0, 'color' => 'danger'],
        ];
        $totalDone = array_sum(array_column($tasks, 'count'));
    @endphp

    <div class="modal fade user-modal" id="viewModal-{{ $user->id }}" tabindex="-1" role="dialog"
         aria-labelledby="viewModalLabel-{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content shadow-lg border-0">

                <form method="POST" action="{{ route('users.updateType', $user->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- ================= HEADER ================= --}}
                    <div class="modal-header bg-light px-4 py-3">
                        <h5 class="modal-title font-weight-bold" id="viewModalLabel-{{ $user->id }}">
                            <i class="material-icons text-info mr-2" style="vertical-align: middle;">account_circle</i>
                            User Details
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    {{-- ================= BODY ================= --}}
                    <div class="modal-body px-4 py-4">

                        {{-- Profile summary --}}
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-3 mb-md-0 border-md-right">
                                @if($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->fullname }}"
                                         class="avatar-lg rounded-circle shadow-sm border mb-3">
                                @else
                                    <div class="avatar-initials shadow-sm mb-3">{{ $initials }}</div>
                                @endif

                                <h5 class="font-weight-bold text-dark mb-1">{{ $user->fullname }}</h5>
                                <span class="badge badge-info px-3 py-1">{{ $roleLabel }}</span>
                            </div>

                            <div class="col-md-8 pl-md-4">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <div class="info-label">Employee ID</div>
                                        <p class="info-value">{{ $user->employee_id }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <div class="info-label">Department</div>
                                        <p class="info-value">{{ $user->department ?: '—' }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="info-label">Email Address</div>
                                        <p class="info-value">{{ $user->email }}</p>
                                    </div>
                                    <div class="col-12">
                                        <div class="info-label mb-1">Status</div>
                                        @if($user->verified)
                                            <span class="badge badge-success px-2 py-1">Verified</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1">Unverified</span>
                                        @endif

                                        @if($isBlocked)
                                            <span class="badge badge-danger px-2 py-1">Blocked</span>
                                        @else
                                            <span class="badge badge-success px-2 py-1">Active</span>
                                        @endif

                                        @if($isOnline)
                                            <span class="badge badge-success px-2 py-1">● Online</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">● Offline</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Role / access --}}
                        <div class="section-title">Access &amp; Role</div>
                        <div class="role-card">
                            <label for="user_type-{{ $user->id }}"
                                   class="small font-weight-bold text-muted mb-2 d-block">User Type</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white">
                                        <i class="material-icons text-info" style="font-size: 18px;">badge</i>
                                    </span>
                                </div>

                                <select name="user_type" id="user_type-{{ $user->id }}"
                                        class="custom-select js-role-select"
                                        data-original="{{ $user->user_type }}"
                                        {{ $isSelf ? 'disabled' : '' }} required>

                                    @unless(array_key_exists($user->user_type, $roles))
                                        <option value="{{ $user->user_type }}" selected>{{ $roleLabel }} (current)</option>
                                    @endunless

                                    <option value="content_admin"  {{ $user->user_type === 'content_admin'  ? 'selected' : '' }}>Content Supervisor</option>
                                    <option value="creative_admin" {{ $user->user_type === 'creative_admin' ? 'selected' : '' }}>Creative Supervisor</option>
                                    <option value="content_team"   {{ $user->user_type === 'content_team'   ? 'selected' : '' }}>Content Team</option>
                                    <option value="creative_team"  {{ $user->user_type === 'creative_team'  ? 'selected' : '' }}>Creative Team</option>
                                    <option value="yearbook_team"  {{ $user->user_type === 'yearbook_team'  ? 'selected' : '' }}>Yearbook Team</option>
                                    <option value="admin"          {{ $user->user_type === 'admin'          ? 'selected' : '' }}>Admin</option>
                                    <option value="user"           {{ $user->user_type === 'user'           ? 'selected' : '' }}>Viewer</option>
                                </select>
                            </div>

                            <small class="form-text text-muted mt-2">
                                @if($isSelf)
                                    You cannot change your own role.
                                @else
                                    Changing the role updates this user's access immediately after saving.
                                @endif
                            </small>
                        </div>

                        <div class="d-flex flex-wrap mt-3 small text-muted">
                            <div class="mr-4">Created: <strong>{{ optional($user->created_at)->format('M d, Y') }}</strong></div>
                            <div>Last updated: <strong>{{ optional($user->updated_at)->format('M d, Y') }}</strong></div>
                        </div>

                        <hr class="my-4">

                        {{-- Task breakdown --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="section-title mb-0">Completed Tasks</div>
                            <span class="small text-muted">Total: <strong>{{ $totalDone }}</strong></span>
                        </div>

                        <div class="progress mb-2" style="height: 10px; border-radius: 5px;">
                            @if($totalDone > 0)
                                @foreach($tasks as $task)
                                    @if($task['count'] > 0)
                                        <div class="progress-bar bg-{{ $task['color'] }}"
                                             style="width: {{ round(($task['count'] / $totalDone) * 100, 2) }}%;"
                                             title="{{ $task['label'] }}: {{ $task['count'] }}"></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        @if($totalDone === 0)
                            <p class="small text-muted mb-3">No completed tasks yet.</p>
                        @endif

                        <div class="row text-center mt-3">
                            @foreach($tasks as $task)
                                <div class="col mb-2">
                                    <div class="stat-tile">
                                        <div class="stat-name">{{ $task['label'] }}</div>
                                        <div class="stat-num text-{{ $task['color'] }}">{{ $task['count'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ================= FOOTER ================= --}}
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info btn-sm px-4 js-save-role" disabled>
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endforeach

{{-- Enable "Save Changes" only when the role was actually changed --}}
<script>
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('js-role-select')) return;
        var form = e.target.closest('form');
        var saveBtn = form.querySelector('.js-save-role');
        saveBtn.disabled = (e.target.value === e.target.dataset.original);
    });
</script>