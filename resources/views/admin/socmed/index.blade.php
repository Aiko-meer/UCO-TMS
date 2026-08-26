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

            <!-- <div class="sk-bounce">
    <div class="sk-bounce-dot"></div>
    <div class="sk-bounce-dot"></div>
  </div> -->

            <!-- More spinner examples at https://github.com/tobiasahlin/SpinKit/blob/master/examples.html -->
        </div>

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

                         <div class="row"
                             role="tablist">
                            <div class="col-auto border-left">
                                <a href=""
                                   class="btn btn-accent">New Task</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid page__container">
                    <div class="page-section">

                    <!--overview-->
                    @include ('admin.socmed.overview')
                    <!--endoverview-->

                    <!--overview-->
                    @include ('admin.socmed.table.team')
                    <!--endoverview-->

                        <div class="page-separator">
                            <div class="page-separator__text">Tasks</div>
                        </div>

                        <div class="card mb-0 p-relative o-hidden">
                            <div class="card-header py-12pt d-flex align-items-center">
                                <strong>Request</strong>
                                <a href="#"
                                   class="d-inline-block mx-16pt"><i class="material-icons text-50">more_horiz</i></a>
                                <div class="text-50">14</div>
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
                                        <span class="h2 mb-0 mr-3">3</span>
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
                                        <span class="h2 mb-0 mr-3">2</span>
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
                                        <span class="h2 mb-0 mr-3">2</span>
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
                                        <span class="h2 mb-0 mr-3">2</span>
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
            @include ('admin.socmed.table.active')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade show fade"
         id="month"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.socmed.table.month')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade show fade"
         id="list"
         role="tabpanel">

        <div class="table-responsive">
            @include ('admin.socmed.table.list')
        </div>

    </div>
    {{-- ARCHIVED PROJECTS --}}
    <div class="tab-pane fade"
         id="archive"
         role="tabpanel">

        <div class="table-responsive">
           @include ('admin.socmed.table.archive')
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
            <!-- // END drawer-layout__content -->

            <!-- drawer -->
             @include ('admin.assets.sidebar')
            <!-- // END drawer -->
        </div>
        <!-- // END drawer-layout -->

        <!-- App Settings FAB -->
         @include ('admin.assets.footer')
    </body>

</html>