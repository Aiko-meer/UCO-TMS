<style>
    .members-chevron { vertical-align: middle; transition: transform .2s; }
    [aria-expanded="false"] .members-chevron { transform: rotate(-90deg); }
</style>

<div class="page-separator" role="button" data-toggle="collapse" data-target="#membersCollapse"
    aria-expanded="true" aria-controls="membersCollapse" style="cursor: pointer;">
    <div class="page-separator__text">
        Members
        <i class="material-icons text-50 members-chevron">keyboard_arrow_down</i>
    </div>
</div>

<div class="collapse show" id="membersCollapse">
    <div class="row card-group-row mb-lg-8pt">
        <!--<div class="col-lg-4 card-group-row__col">

            <div class="card card-group-row__card">
                <div class="card-header py-12pt d-flex align-items-center">
                    <div class="position-relative mr-16pt">
                        <div class="text-center fullbleed d-flex align-items-center justify-content-center flex-column z-0">
                            <small>14%</small>
                        </div>
                        <canvas width="48"
                                height="48"
                                class="chart-canvas position-relative z-1"
                                id="openProgressChart"
                                data-chart-line-background-color="yellow;gray"
                                data-chart-disable-tooltips="true"></canvas>
                    </div>
                    <strong class="flex">Open</strong>
                    <div class="text-50">4</div>
                </div>
            </div>

        </div>-->
        @php
            // Everyone except admins, ranked by documented count (ties share a rank)
            $ranked = $users
                ->where('user_type', '!=', 'admin')
                ->sortBy([['documented_count', 'desc'], ['fullname', 'asc']])
                ->values();
            $prevCount = null;
            $rank = 0;
        @endphp

        @foreach ($ranked as $i => $member)
            @php
                if ($member->documented_count != $prevCount) {
                    $rank = $i + 1;
                    $prevCount = $member->documented_count;
                }
            @endphp
            <div class="col-md-6">
                <div class="card card--raised">
                    <div class="card-header d-flex align-items-center">
                        <span class="badge badge-pill {{ $rank === 1 ? 'badge-warning' : 'badge-light' }} mr-8pt">
                            #{{ $rank }}
                        </span>
                        <div class="d-none d-sm-flex">
                            <img width="32" height="32" class="rounded-circle mr-8pt"
                                src="{{ $member->image ? asset('storage/' . $member->image) : 'https://via.placeholder.com/100' }}"
                                alt="account" />
                        </div>
                        <span class="flex d-flex flex-column mr-8pt">
                            <h4 class="card-title">{{ $member->fullname }}</h4>
                            <p class="card-subtitle">{{ $member->user_type }} </p>
                        </span>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-caret="false" data-toggle="dropdown">
                                <i class="material-icons">more_horiz</i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button type="button" class="btn btn-link text-50 p-0 dropdown-item"
                                    onclick="$('#viewModal-{{ $member->id }}').modal('show');">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="flex d-flex flex-row mr-8pt">
                            <strong class="flex">In Progress</strong>
                            <div class="text-50">{{ $member->in_progress_count }}</div>
                        </div>

                        <div class="flex d-flex flex-row mr-8pt">
                            <strong class="flex">Documented</strong>
                            <div class="text-50">{{ $member->documented_count }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    // Remembers whether Members was closed. Runs on "load" so jQuery from the footer is ready.
    window.addEventListener('load', function() {
        const $members = $('#membersCollapse');
        const key = 'photography:membersCollapsed';

        if (localStorage.getItem(key) === '1') {
            $members.removeClass('show');
            $('[data-target="#membersCollapse"]').attr('aria-expanded', 'false');
        }

        $members
            .on('hide.bs.collapse', function() { localStorage.setItem(key, '1'); })
            .on('show.bs.collapse', function() { localStorage.removeItem(key); });
    });
</script>