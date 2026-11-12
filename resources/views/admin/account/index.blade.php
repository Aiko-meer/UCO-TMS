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
                                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>

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
                    <form method="POST" action="{{ route('admin.account.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-9 pr-lg-0">

                                <div class="page-section">
                                    @include('admin.account.partials.alerts')
                                    <h4>Basic Information</h4>
                                    <div class="list-group list-group-form">
                                        <div class="list-group-item">
                                            <div class="form-group row align-items-center mb-0">
                                                <label class="form-label col-form-label col-sm-3" for="fullname">Full name</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="form-control @error('fullname') is-invalid @enderror"
                                                           id="fullname"
                                                           name="fullname"
                                                           value="{{ old('fullname', auth()->user()->fullname) }}"
                                                           placeholder="Your full name ..."
                                                           maxlength="255"
                                                           autocomplete="name"
                                                           required>
                                                    @error('fullname')
                                                        <div class="form-text text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="form-group row align-items-center mb-0">
                                                <label class="form-label col-form-label col-sm-3" for="email">Email address</label>
                                                <div class="col-sm-9">
                                                    <input type="email"
                                                           class="form-control @error('email') is-invalid @enderror"
                                                           id="email"
                                                           name="email"
                                                           value="{{ old('email', auth()->user()->email) }}"
                                                           placeholder="Your email address ..."
                                                           maxlength="255"
                                                           autocomplete="email"
                                                           required>
                                                    @error('email')
                                                        <div class="form-text text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Note that if you change your email, you will have to confirm it again.</small>
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