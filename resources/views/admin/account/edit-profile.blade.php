@php
    $account = auth()->user();
    $hasPhoto = $account->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($account->image);
    $nameParts = preg_split('/\s+/', trim($account->fullname));
    $accountInitials = strtoupper(
        mb_substr($nameParts[0] ?? '', 0, 1) . mb_substr(end($nameParts) ?: '', 0, 1)
    );
@endphp
@include ('admin.assets.headers')

    <body class="layout-app layout-sticky-subnav ">

        <style>
            .account-avatar,
            .account-avatar-initials {
                width: 56px;
                height: 56px;
            }

            .account-avatar {
                object-fit: cover;
            }

            .account-avatar-initials {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background: linear-gradient(135deg, #4F7D5C, #152C1F);
                color: #E3C98A;
                font-family: 'Fraunces', Georgia, serif;
                font-size: 18px;
                font-weight: 700;
                line-height: 1;
                box-shadow: 0 0 0 2px #FBF9F2, 0 0 0 3px rgba(201, 162, 75, .45);
            }
        </style>

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
                    <form method="POST" action="{{ route('admin.account.update-profile') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-9 pr-lg-0">

                                <div class="page-section">
                                    @include('admin.account.partials.alerts')
                                    <h4>Profile &amp; Privacy</h4>
                                    <div class="list-group list-group-form">
                                        <div class="list-group-item">
                                            <div class="form-group row align-items-center mb-0">
                                                <label class="col-form-label form-label col-sm-3">Your photo</label>
                                                <div class="col-sm-9 media align-items-center">
                                                    <span class="media-left mr-16pt">
                                                        <img id="profile-photo-preview"
                                                             src="{{ $hasPhoto ? asset('storage/' . $account->image) : '' }}"
                                                             alt="{{ $account->fullname }}"
                                                             width="56"
                                                             height="56"
                                                             class="rounded-circle account-avatar {{ $hasPhoto ? '' : 'd-none' }}" />
                                                        <span id="profile-photo-initials"
                                                              class="account-avatar-initials {{ $hasPhoto ? 'd-none' : '' }}">{{ $accountInitials }}</span>
                                                    </span>
                                                    <div class="media-body">
                                                        <div class="custom-file">
                                                            <input type="file"
                                                                   class="custom-file-input @error('image') is-invalid @enderror"
                                                                   id="profilePhoto"
                                                                   name="image"
                                                                   accept="image/png,image/jpeg">
                                                            <label class="custom-file-label"
                                                                   for="profilePhoto">Choose file</label>
                                                        </div>
                                                        @error('image')
                                                            <div class="form-text text-danger">{{ $message }}</div>
                                                        @enderror
                                                        <small class="form-text text-muted">JPG or PNG, up to 2 MB.</small>
                                                    </div>
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
        <script>
            (function () {
                var input = document.getElementById('profilePhoto');
                if (!input) return;

                input.addEventListener('change', function () {
                    var file = this.files && this.files[0];
                    if (!file) return;

                    var label = this.nextElementSibling;
                    if (label) label.textContent = file.name;

                    var preview = document.getElementById('profile-photo-preview');
                    var initials = document.getElementById('profile-photo-initials');

                    if (preview) {
                        preview.src = URL.createObjectURL(file);
                        preview.classList.remove('d-none');
                    }
                    if (initials) initials.classList.add('d-none');
                });
            })();
        </script>

        @include ('admin.assets.footer')
    </body>

</html>