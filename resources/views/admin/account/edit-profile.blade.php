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
                                    <h4>Profile &amp; Privacy</h4>
                                    <div class="list-group list-group-form">
                                        <div class="list-group-item">
                                            <div class="form-group row align-items-center mb-0">
                                                <label class="col-form-label form-label col-sm-3">Your photo</label>
                                                <div class="col-sm-9 media align-items-center">
                                                    <a href=""
                                                       class="media-left mr-16pt">
                                                        <img src="assets/images/people/110/guy-3.jpg"
                                                             alt="people"
                                                             width="56"
                                                             class="rounded-circle" />
                                                    </a>
                                                    <div class="media-body">
                                                        <div class="custom-file">
                                                            <input type="file"
                                                                   class="custom-file-input"
                                                                   id="inputGroupFile01">
                                                            <label class="custom-file-label"
                                                                   for="inputGroupFile01">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="form-group row align-items-center mb-0">
                                                <label class="col-form-label form-label col-sm-3">Huma profile name</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           value="Huma.com/alexander"
                                                           placeholder="Your profile name ...">
                                                    <small class="form-text text-muted">Your profile name will be used as part of your public profile URL address.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="form-group row align-items-center mb-0">
                                                <label class="col-form-label form-label col-sm-3">About you</label>
                                                <div class="col-sm-9">
                                                    <textarea rows="3"
                                                              class="form-control"
                                                              placeholder="About you ..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       checked
                                                       id="customCheck1">
                                                <label class="custom-control-label"
                                                       for="customCheck1">Display your real name on your profile</label>
                                                <small class="form-text text-muted">If unchecked, your profile name will be displayed instead of your full name.</small>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       checked
                                                       id="customCheck2">
                                                <label class="custom-control-label"
                                                       for="customCheck2">Allow everyone to see your profile</label>
                                                <small class="form-text text-muted">If unchecked, your profile will be private and no one except you will be able to view it.</small>
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