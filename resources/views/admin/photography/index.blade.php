@include ('admin.assets.headers')

    <body class="layout-app layout-sticky-subnav ">

        <div class="preloader">
            <div class="sk-chase">
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
            </div>
        </div>
 @include ('admin.photography.newtaskmodal')
 <!--pop-up for active  -->
                      @include ('admin.photography.viewpop')
                       @include ('admin.socmed.useroverview')    
        <div class="mdk-drawer-layout js-mdk-drawer-layout"
             data-push
             data-responsive-width="992px">
            <div class="mdk-drawer-layout__content page-content">

                <!-- Header -->
                 @include ('admin.assets.headnavbar')
                <!-- // END Header -->

                <div class="border-bottom-2 py-32pt position-relative z-1">
                    <div class="container-fluid page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
                        <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                            <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                                <h2 class="mb-0">Tasks</h2>

                                <ol class="breadcrumb p-0 m-0">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>

                                    <li class="breadcrumb-item ">

                                        Content Team 

                                    </li>

                                     <li class="breadcrumb-item active">

                                        Social Media Service 

                                    </li>

                                </ol>

                            </div>
                        </div>
                        
                        <div class="row" role="tablist">
                            <div class="col-auto border-left">
                                <button type="button"
                                        class="btn btn-accent"
                                        data-toggle="modal"
                                        data-target="#photoModal">
                                    New Task
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="container-fluid page__container">
                    <div class="page-section">

                    <!--overview-->
                    @include ('admin.photography.overview')
                    <!--endoverview-->

                      <!--overview-->
                    @include ('admin.photography.table.team')
                    <!--endoverview-->

                        <div class="page-separator">
                            <div class="page-separator__text">Tasks</div>
                        </div>

                        <div class="card mb-0 p-relative o-hidden">
                            <div class="card-header py-12pt d-flex align-items-center">
                                <strong>Request</strong>
                                <a href="#"
                                   class="d-inline-block mx-16pt"><i class="material-icons text-50">more_horiz</i></a>
                                <div class="text-50">{{$totalCount}}</div>
                                <div class="flex"></div>
                                <a href="#"><i class="material-icons text-20">keyboard_arrow_down</i></a>
                            </div>
                        </div>

                        <div id="socmed-live">
                         <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                        <div class="card-header p-0 nav">
                            <div class="row no-gutters"
                                 role="tablist">
                                <div class="col-auto">
                                    <a href="#active"
                                       data-toggle="tab"
                                       role="tab"
                                       aria-selected="true"
                                       class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                                        <span class="h2 mb-0 mr-3">{{$inProgressCount}}</span>
                                        <span class="flex d-flex flex-column">
                                            <strong class="card-title">Active</strong>
                                            <small class="card-subtitle text-50">Ongoing Projects</small>
                                        </span>
                                    </a>
                                </div>
                                <div class="col-auto border-left border-right">
                                    <a href="#archive"
                                       data-toggle="tab"
                                       role="tab"
                                       aria-selected="false"
                                       class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                        <span class="h2 mb-0 mr-3">{{$postedCount}}</span>
                                        <span class="flex d-flex flex-column">
                                            <strong class="card-title">Archived</strong>
                                            <small class="card-subtitle text-50">Archived</small>
                                        </span>
                                    </a>
                                </div>
                                <div class="col-auto border-left border-right">
                                    <a href="#month"
                                       data-toggle="tab"
                                       role="tab"
                                       aria-selected="false"
                                       class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                        <span class="h2 mb-0 mr-3">{{$monthCount}}</span>
                                        <span class="flex d-flex flex-column">
                                            <strong class="card-title">This month</strong>
                                            <small class="card-subtitle text-50">Past Projects</small>
                                        </span>
                                    </a>
                                </div>
                                 <div class="col-auto border-left border-right">
                                    <a href="#list"
                                       data-toggle="tab"
                                       role="tab"
                                       aria-selected="false"
                                       class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                        <span class="h2 mb-0 mr-3">{{$totalCount}}</span>
                                        <span class="flex d-flex flex-column">
                                            <strong class="card-title">List</strong>
                                            <small class="card-subtitle text-50">All Projects</small>
                                        </span>
                                    </a>
                                </div>
                                 
                            </div>
                        </div>
                        <div class="tab-content">

    {{-- ACTIVE PROJECTS --}}
    <div class="tab-pane fade show active"
         id="active"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.photography.table.active')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade show fade"
         id="month"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.photography.table.month')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade show fade"
         id="month"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.photography.table.event')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade show fade"
         id="list"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.photography.table.list')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade"
         id="archive"
         role="tabpanel">

        <div class="table-responsive">
           @include ('admin.photography.table.archive')
        </div>

    </div>
</div>

                       
                        

                        <div class="card-footer p-8pt">

                            <ul class="pagination justify-content-start pagination-xsm m-0">
                                <li class="page-item disabled">
                                    <a class="page-link"
                                       href="#"
                                       aria-label="Previous">
                                        <span aria-hidden="true"
                                              class="material-icons">chevron_left</span>
                                        <span>Prev</span>
                                    </a>
                                </li>
                                <li class="page-item dropdown">
                                    <a class="page-link dropdown-toggle"
                                       data-toggle="dropdown"
                                       href="#"
                                       aria-label="Page">
                                        <span>1</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href=""
                                           class="dropdown-item active">1</a>
                                        <a href=""
                                           class="dropdown-item">2</a>
                                        <a href=""
                                           class="dropdown-item">3</a>
                                        <a href=""
                                           class="dropdown-item">4</a>
                                        <a href=""
                                           class="dropdown-item">5</a>
                                    </div>
                                </li>
                                <li class="page-item">
                                    <a class="page-link"
                                       href="#"
                                       aria-label="Next">
                                        <span>Next</span>
                                        <span aria-hidden="true"
                                              class="material-icons">chevron_right</span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    </div>

                    </div>
                </div>
 
            </div>
            <!-- // END drawer-layout__content -->

            <!-- drawer -->
             @include ('admin.assets.sidebar')
            <!-- // END drawer -->
        </div>
        <!-- // END drawer-layout -->

        <!-- App Settings FAB -->
         @include ('admin.assets.footer')
          <script>
document.addEventListener("DOMContentLoaded", function () {
    const saved = localStorage.getItem('activeDashboardTab');
    if (saved) {
        const trigger = document.querySelector('a[href="' + saved + '"][data-toggle="tab"]');
        if (trigger) trigger.click();
    }
    // delegated, so it still works after the container is refreshed
    document.addEventListener('click', function (e) {
        const tab = e.target.closest('.dashboard-area-tabs__tab');
        if (tab) localStorage.setItem('activeDashboardTab', tab.getAttribute('href'));
    });
});

let refreshTimer = null;
let pendingRefresh = false;

function refreshSocmedTable() {
    console.log('[socmed] refresh called');

    if (document.querySelector('.modal.show')) {
        console.log('[socmed] modal open, deferring');
        pendingRefresh = true;
        return;
    }
    pendingRefresh = false;

    fetch(window.location.href, {
        cache: 'no-store',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => {
        console.log('[socmed] fetch status', r.status);
        if (!r.ok) throw new Error('Server returned ' + r.status);
        return r.text();
    })
    .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const fresh = doc.getElementById('socmed-live');
        const current = document.getElementById('socmed-live');
        console.log('[socmed] found containers', !!fresh, !!current);
        if (!fresh || !current) return;

        const activeHref = current.querySelector('.dashboard-area-tabs__tab.active')?.getAttribute('href');
        current.innerHTML = fresh.innerHTML;
        if (activeHref) current.querySelector('a[href="' + activeHref + '"][data-toggle="tab"]')?.click();
        if (window.domFactory?.handler) domFactory.handler.upgradeAll();
        console.log('[socmed] table updated');
    })
    .catch(err => console.error('[socmed] refresh failed', err));
}

$(document).on('hidden.bs.modal', '.modal', function () {
    if (pendingRefresh) refreshSocmedTable();
});

window.addEventListener('load', function () {
    window.Echo.channel('admin.socmed').listen('.socmed.updated', () => {
        console.log('[socmed] event received');
        clearTimeout(refreshTimer);
        refreshTimer = setTimeout(refreshSocmedTable, 300);
    });
    console.log('[socmed] listener registered');
});
</script>
@if(session('success') || session('error') || $errors->any())
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Good job!',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        @elseif(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Saving Failed',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @elseif($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                html: '{!! implode("<br>", $errors->all()) !!}',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endif
    </body>

</html>