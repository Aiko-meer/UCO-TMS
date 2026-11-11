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
                                <h2 class="mb-0">New Requests</h2>
                                <ol class="breadcrumb p-0 m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Notifications</li>
                                </ol>
                            </div>
                        </div>

                        <div class="ml-auto d-flex align-items-center">
                            @if ($unread > 0)
                                <span class="badge badge-accent mr-2">{{ $unread }} unread</span>
                                <button type="button" id="whatsnew-mark-all" class="btn btn-outline-secondary btn-sm">
                                    Mark all as read
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="container-fluid page__container">
                    <div class="page-section">

                        <div class="d-flex flex-wrap mb-3">
                            <a href="{{ route('notifications.index') }}"
                               class="btn btn-sm mr-1 mb-1 {{ $activeType === null ? 'btn-accent' : 'btn-outline-secondary' }}">All</a>
                            @foreach ($types as $type)
                                <a href="{{ route('notifications.index', ['type' => $type]) }}"
                                   class="btn btn-sm mr-1 mb-1 {{ $activeType === $type ? 'btn-accent' : 'btn-outline-secondary' }}">
                                    {{ \App\Support\NotificationType::label($type) }}
                                </a>
                            @endforeach
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <strong>{{ $total }} request{{ $total === 1 ? '' : 's' }}</strong>
                            </div>

                            <div class="list-group list-group-flush">
                                @forelse ($items as $item)
                                    <a href="{{ $item->openUrl }}"
                                       data-notif-key="{{ $item->key }}"
                                       class="list-group-item list-group-item-action d-flex align-items-center {{ $item->read ? '' : 'unread' }}">
                                        <span class="avatar avatar-sm mr-3">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <i class="material-icons text-{{ $item->type === 'softcopy' ? 'warning' : ($item->type === 'trademark' ? 'success' : 'accent') }}">{{ $item->icon() }}</i>
                                            </span>
                                        </span>

                                        <span class="flex d-flex flex-column">
                                            <span class="font-weight-bold">{{ $item->fullname }}</span>
                                            <small class="text-muted">
                                                {{ $item->label() }}
                                                @if ($item->department) &middot; {{ $item->department }} @endif
                                                @if ($item->requestId) &middot; #{{ $item->requestId }} @endif
                                            </small>
                                        </span>

                                        <span class="ml-auto d-flex align-items-center">
                                            @if ($item->statusLabel())
                                                <span class="badge badge-soft-secondary mr-3">{{ $item->statusLabel() }}</span>
                                            @endif
                                            <small class="text-black-50 mr-2">{{ $item->timeAgo() }}</small>
                                            @unless ($item->read)
                                                <span class="unread-indicator bg-accent" title="Unread"></span>
                                            @endunless
                                        </span>
                                    </a>
                                @empty
                                    <div class="list-group-item text-center text-muted py-48pt">
                                        <i class="material-icons font-size-32pt d-block mb-2">notifications_off</i>
                                        <p class="mb-0">Nothing new right now.</p>
                                    </div>
                                @endforelse
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