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

        <div class="mdk-drawer-layout js-mdk-drawer-layout"
             data-push
             data-responsive-width="992px">
            <div class="mdk-drawer-layout__content page-content">

                @include ('admin.assets.headnavbar')

                <div class="border-bottom-2 py-32pt position-relative z-1">
                    <div class="container-fluid page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
                        <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">
                            <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                                <h2 class="mb-0">{{ $item->label() }}</h2>
                                <ol class="breadcrumb p-0 m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('notifications.index') }}">Notifications</a></li>
                                    <li class="breadcrumb-item active">#{{ $item->requestId }}</li>
                                </ol>
                            </div>
                        </div>

                        @if ($item->dashboardUrl)
                            <div class="ml-auto">
                                <a href="{{ $item->dashboardUrl }}" class="btn btn-outline-secondary btn-sm">
                                    Open dashboard
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="container-fluid page__container">
                    <div class="page-section">

                        <div class="card">
                            <div class="card-header">
                                <strong>Request details</strong>
                                @if ($item->statusLabel())
                                    <span class="badge badge-soft-secondary ml-2">{{ $item->statusLabel() }}</span>
                                @endif
                            </div>

                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-3">Request ID</dt>
                                    <dd class="col-sm-9">{{ $item->requestId }}</dd>

                                    <dt class="col-sm-3">Requestor</dt>
                                    <dd class="col-sm-9">{{ $item->fullname }}</dd>

                                    @if ($item->department)
                                        <dt class="col-sm-3">Department</dt>
                                        <dd class="col-sm-9">{{ $item->department }}</dd>
                                    @endif

                                    @if ($item->email)
                                        <dt class="col-sm-3">Email</dt>
                                        <dd class="col-sm-9">{{ $item->email }}</dd>
                                    @endif

                                    <dt class="col-sm-3">Submitted</dt>
                                    <dd class="col-sm-9">{{ $item->createdAt?->format('M d, Y g:i A') ?? '—' }}</dd>

                                    @if ($requestor)
                                        @php($child = $requestor->publishing ?? $requestor->photo ?? $requestor->socmed)
                                        @if ($child)
                                            @if (!empty($child->purpose))
                                                <dt class="col-sm-3">Purpose</dt>
                                                <dd class="col-sm-9">{{ $child->purpose }}</dd>
                                            @endif
                                            @if (!empty($child->date_needed))
                                                <dt class="col-sm-3">Date needed</dt>
                                                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($child->date_needed)->format('M d, Y') }}</dd>
                                            @endif
                                            @if (!empty($child->category))
                                                <dt class="col-sm-3">Category</dt>
                                                <dd class="col-sm-9">{{ $child->category }}</dd>
                                            @endif
                                            @if (!empty($child->venue))
                                                <dt class="col-sm-3">Venue</dt>
                                                <dd class="col-sm-9">{{ $child->venue }}</dd>
                                            @endif
                                            @if (!empty($child->event))
                                                <dt class="col-sm-3">Event</dt>
                                                <dd class="col-sm-9">{{ $child->event }}</dd>
                                            @endif
                                            @if (!empty($child->content_information))
                                                <dt class="col-sm-3">Details</dt>
                                                <dd class="col-sm-9">{{ $child->content_information }}</dd>
                                            @endif
                                        @endif
                                    @endif
                                </dl>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- // END drawer-layout__content -->

            @include ('admin.assets.sidebar')
        </div>

    </body>
</html>
