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
         <!--New Task Model-->
                        @include ('admin.creatives.production.newtaskmodal')
         <!--pop-up for active  -->
                      @include ('admin.creatives.production.viewpop')


                        
               
        

            <!-- <div class="sk-bounce">
    <div class="sk-bounce-dot"></div>
    <div class="sk-bounce-dot"></div>
  </div> -->

            <!-- More spinner examples at https://github.com/tobiasahlin/SpinKit/blob/master/examples.html -->
        

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
                                        data-target="#newTaskModal">
                                    New Task
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="container-fluid page__container">
                    <div class="page-section">

                    <!--overview-->
                    @include ('admin.creatives.production.overview')
                    <!--endoverview-->

                    <!--overview-->
                    @include ('admin.creatives.production.table.team')
                    <!--endoverview-->

                        <div class="page-separator">
                            <div class="page-separator__text">Tasks</div>
                        </div>

                        <div class="card mb-0 p-relative o-hidden">
                            <div class="card-header py-12pt d-flex align-items-center">
                                <strong>Request</strong>
                                <div class="text-50">Total:</div>
                                <div class="flex"></div>
                                <a href="#"><i class="material-icons text-20">keyboard_arrow_down</i></a>
                            </div>
                            <div class="progress rounded-0"
                                 style="height: 4px;">
                                <div class="progress-bar bg-warning"
                                     role="progressbar"
                                     style="width: 50%;"
                                     aria-valuenow="50"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>

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
                                        <span class="h2 mb-0 mr-3"></span>
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
                                        <span class="h2 mb-0 mr-3"></span>
                                        <span class="flex d-flex flex-column">
                                            <strong class="card-title">Archived</strong>
                                            <small class="card-subtitle text-50">Projects Data</small>
                                        </span>
                                    </a>
                                </div>
                                <div class="col-auto border-left border-right">
                                    <a href="#month"
                                       data-toggle="tab"
                                       role="tab"
                                       aria-selected="false"
                                       class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                        <span class="h2 mb-0 mr-3"></span>
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
                                        <span class="h2 mb-0 mr-3"></span>
                                        <span class="flex d-flex flex-column">
                                            <strong class="card-title">List</strong>
                                            <small class="card-subtitle text-50">All Projects</small>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Check if there's a saved tab in localStorage and activate it
        let activeTab = localStorage.getItem('activeDashboardTab');
        if (activeTab) {
            let tabTrigger = document.querySelector(`a[href="${activeTab}"]`);
            if (tabTrigger) {
                // Use Bootstrap's tab trigger if available, or fallback to click()
                if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                    var tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                } else {
                    tabTrigger.click();
                }
            }
        }

        // 2. Save the tab href to localStorage whenever a tab is clicked
        const tabs = document.querySelectorAll('.dashboard-area-tabs__tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                let targetID = this.getAttribute('href');
                localStorage.setItem('activeDashboardTab', targetID);
            });
        });
    });
</script>
                        </div>

                        <div class="tab-content">

    {{-- ACTIVE PROJECTS --}}
    <div class="tab-pane fade show active"
         id="active"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.creatives.production.table.active')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade show fade"
         id="month"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.creatives.production.table.month')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade show fade"
         id="list"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.creatives.production.table.list')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade"
         id="archive"
         role="tabpanel">

        <div class="table-responsive">
          
        </div>

    </div>
   <script>
    // Real-time auto-refresh interval (e.g., every 5 seconds)
    setInterval(function() {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
            let newTbody = doc.querySelector('#projects');
            
            if (newTbody) {
                // Keep track of current search value to prevent clearing user input
                let searchInput = document.querySelector('.search');
                let searchTerm = searchInput ? searchInput.value : '';

                // Replace table body content with fresh data
                document.querySelector('#projects').innerHTML = newTbody.innerHTML;

                // Re-trigger List.js search if search was active
                if (searchTerm && window.List && window.List.lists) {
                    // List.js handles re-initialization automatically if container matches
                }
            }
        })
        .catch(error => console.error('Error updating table:', error));
    }, 5000); // 5000ms = 5 seconds
</script>
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
    </body>
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
</html>