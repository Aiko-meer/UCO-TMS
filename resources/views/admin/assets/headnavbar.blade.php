<div class="navbar navbar-expand navbar-shadow px-0  pl-lg-16pt navbar-light bg-body"
                     id="default-navbar"
                     data-primary>

                    <!-- Navbar toggler -->
                    <button class="navbar-toggler d-block d-lg-none rounded-0"
                            type="button"
                            data-toggle="sidebar">
                        <span class="material-icons">menu</span>
                    </button>

                    <!-- Navbar Brand -->
                    <a href="{{ route('admin.home') }}"
                       class="navbar-brand mr-16pt d-lg-none">
                        <img class="navbar-brand-icon mr-0 mr-lg-8pt"
                             src="{{ asset('assets/images/logo/accent-teal-100@2x.png') }}"
                             width="32"
                             alt="Huma">
                        <span class="d-none d-lg-block">UCO-TMS</span>
                    </a>

                    <!-- <button class="btn navbar-btn mr-16pt" data-toggle="modal" data-target="#apps">Apps <i class="material-icons">arrow_drop_down</i></button> -->

                    

                    <div class="flex"></div>

                   

                    <div class="nav navbar-nav flex-nowrap d-flex ml-0 mr-16pt">
                        <div class="nav-item dropdown d-none d-sm-flex">
                            <a href="#"
                               class="nav-link d-flex align-items-center dropdown-toggle"
                               data-toggle="dropdown">
                                @auth
                                    @php($currentUser = auth()->user())
                                    <img width="32"
                                         height="32"
                                         class="rounded-circle mr-8pt"
                                         src="{{ asset('storage/' . $currentUser->image) ?? asset('assets/images/people/50/guy-3.jpg') }}"
                                         alt="{{ $currentUser->fullname }}" />
                                    <span class="flex d-flex flex-column mr-8pt">
                                        <span class="navbar-text-100">{{ $currentUser->fullname }}</span>
                                        <small class="navbar-text-50">{{ $currentUser->user_type ?: 'User' }}</small>
                                    </span>
                                @else
                                    <img width="32"
                                         height="32"
                                         class="rounded-circle mr-8pt"
                                         src="{{ asset('assets/images/people/50/guy-3.jpg') }}"
                                         alt="account" />
                                    <span class="navbar-text-100">Guest</span>
                                @endauth
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                @auth
                                    <div class="dropdown-header">
                                        <strong>{{ auth()->user()->fullname }}</strong>
                                        <div class="text-muted">{{ auth()->user()->email }}</div>
                                        <div class="text-muted">Employee ID: {{ auth()->user()->employee_id }}</div>
                                        <div class="text-muted">Role: {{ auth()->user()->user_type ?: 'User' }}</div>
                                    </div>
                                @else
                                    <div class="dropdown-header"><strong>Account</strong></div>
                                @endauth
                                <a class="dropdown-item"
                                   href="{{ route('admin.account')}}">Edit Account</a>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </div>
                        </div>

                        <!-- Notifications dropdown -->
                        @include('admin.assets.notif')
                        <!-- // END Notifications dropdown -->

                        <!-- Notifications dropdown -->
                        <!--<div class="nav-item ml-16pt dropdown dropdown-notifications">
                            <button class="nav-link btn-flush dropdown-toggle"
                                    type="button"
                                    data-toggle="dropdown"
                                    data-dropdown-disable-document-scroll
                                    data-caret="false">
                                <i class="material-icons icon-24pt">mail_outline</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div data-perfect-scrollbar
                                     class="position-relative">
                                    <div class="dropdown-header"><strong>Messages</strong></div>
                                    <div class="list-group list-group-flush mb-0">

                                        <a href="javascript:void(0);"
                                           class="list-group-item list-group-item-action unread">
                                            <span class="d-flex align-items-center mb-1">
                                                <small class="text-black-50">5 minutes ago</small>

                                                <span class="ml-auto unread-indicator bg-accent"></span>

                                            </span>
                                            <span class="d-flex">
                                                <span class="avatar avatar-xs mr-2">
                                                    <img src="{{ asset('assets/images/people/110/woman-5.jpg') }}"
                                                         alt="people"
                                                         class="avatar-img rounded-circle">
                                                </span>
                                                <span class="flex d-flex flex-column">
                                                    <strong class="text-black-100">Michelle</strong>
                                                    <span class="text-black-70">Clients loved the new design.</span>
                                                </span>
                                            </span>
                                        </a>

                                        <a href="javascript:void(0);"
                                           class="list-group-item list-group-item-action">
                                            <span class="d-flex align-items-center mb-1">
                                                <small class="text-black-50">5 minutes ago</small>

                                            </span>
                                            <span class="d-flex">
                                                <span class="avatar avatar-xs mr-2">
                                                    <img src="{{ asset('assets/images/people/110/woman-5.jpg') }}"
                                                         alt="people"
                                                         class="avatar-img rounded-circle">
                                                </span>
                                                <span class="flex d-flex flex-column">
                                                    <strong class="text-black-100">Michelle</strong>
                                                    <span class="text-black-70">🔥 Superb job..</span>
                                                </span>
                                            </span>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- // END Notifications dropdown -->
                    </div>

                    

                </div>