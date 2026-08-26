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
                        <div class="flex d-flex flex-column flex-sm-row align-items-center">

                            <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                                <h2 class="mb-0">Account</h2>

                                <ol class="breadcrumb p-0 m-0">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>

                                    <li class="breadcrumb-item">

                                        <a href="">Account</a>

                                    </li>

                                    <li class="breadcrumb-item active">

                                        Edit Account

                                    </li>

                                </ol>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="container-fluid page__container">
                    <form action="edit-account.html">
                        <div class="row">
                            <div class="col-lg-9 pr-lg-0">

                                <div class="page-section">
                                    <h4>Change Password</h4>

                                    <!--<div class="alert alert-soft-warning">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="mr-8pt">
                                                <i class="material-icons">check_circle</i>
                                            </div>
                                            <div class="flex"
                                                 style="min-width: 180px">
                                                <small class="text-100">
                                                    An email with password reset instructions has been sent to your email address, if it exists on our system.
                                                </small>
                                            </div>
                                        </div>
                                    </div>-->

                                    <div class="list-group list-group-form">
                                        <div class="list-group-item">
                                            <div class="form-group row mb-0">
                                                <label class="col-form-label col-sm-3">New password</label>
                                                <div class="col-sm-9">
                                                    <input type="password"
                                                           class="form-control"
                                                           placeholder="Password ...">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="form-group row mb-0">
                                                <label class="col-form-label col-sm-3">Confirm password</label>
                                                <div class="col-sm-9">
                                                    <input type="password"
                                                           class="form-control"
                                                           placeholder="Confirm password ...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            @include ('admin.account.sidenav')
                        </div>
                    </form>
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