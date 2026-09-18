<div class="table-responsive"
                             data-toggle="lists"
                             data-lists-sort-by="js-lists-values-date"
                             data-lists-sort-desc="true"
                             data-lists-values='["js-lists-values-lead", "js-lists-values-project", "js-lists-values-status", "js-lists-values-budget", "js-lists-values-date"]'>

                             <div class="card-header">
                                            <div class="search-form">
                                                <input type="text"
                                                       class="form-control search"
                                                       placeholder="Search ...">
                                                <button class="btn"
                                                        type="button"
                                                        role="button"><i class="material-icons">search</i></button>
                                            </div>
                                        </div>
                            <table class="table mb-0 thead-border-top-0 table-nowrap" id="active">
                                <thead>
                                    <tr>

                                        <th style="width: 18px;"
                                            class="pr-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input js-toggle-check-all"
                                                       data-target="#projects"
                                                       id="customCheckAll">
                                                <label class="custom-control-label"
                                                       for="customCheckAll"><span class="text-hide">Toggle all</span></label>
                                            </div>
                                        </th>

                                        <th style="width: 150px;">
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-project">Requestor</a>
                                        </th>

                                        <th>
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-lead">Department</a>
                                        </th>

                                        <th style="width: 48px;">
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-status">Status</a>
                                        </th>

                                        <th style="width: 48px;">
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-budget">Date requested </a>
                                        </th>

                                        <th style="width: 48px;">
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-date">Due</a>
                                        </th>
                                        <th style="width: 24px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="list"
                                       id="projects">
                                @foreach($requests as $req)
                                @if ($req->information->status == 0)
                                    <tr>

                                        <td class="pr-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input js-check-selected-row"
                                                       id="customCheck1_1">
                                                <label class="custom-control-label"
                                                       for="customCheck1_1"><span class="text-hide">Check</span></label>
                                            </div>
                                        </td>

                                        <td>

                                            <div class="media flex-nowrap align-items-center"
                                                 style="white-space: nowrap;">
                                                
                                                <div class="media-body">
                                                    <div class="d-flex flex-column">
                                                        <small class="js-lists-values-project"><strong>{{ $req->fullname}}</strong></small>
                                                        <small class="js-lists-values-location text-50">{{ $req->email}}</small>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>

                                        <td>

                                            <div class="media flex-nowrap align-items-center"
                                                 style="white-space: nowrap;">
                                                <div class="media-body">

                                                    <div class="d-flex align-items-center">
                                                        <div class="flex d-flex flex-column">
                                                            <p class="mb-0"><strong class="js-lists-values-lead">{{ $req->department}}</strong></p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-status text-50 mb-4pt">{{ $req->information?->purpose ?? 'No Data' }}</small>
                                                <span class="indicator-line rounded bg-warning"></span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-date"><strong>{{ $req->information?->created_at?->format('M d, Y h:i A') }}</strong></small>
                                                <small class="text-50">18 days ago</small>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-date"><strong>{{ $req->information?->date_needed ? \Carbon\Carbon::parse($req->information->date_needed)->format('M d, Y') : 'N/A' }}</strong></small>
                                                <small class="text-50">2 days</small>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-link text-50 p-0" onclick="$('#viewModal-{{ $req->request_id }}').modal('show');">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                              <div class="card-footer p-8pt">
    <ul class="pagination justify-content-start pagination-xsm m-0">
        <!-- Previous Page Link -->
        <li class="page-item {{ $actviepagi->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $actviepagi->withQueryString()->previousPageUrl() ?? '0' }}" aria-label="Previous">
                <span aria-hidden="true" class="material-icons">chevron_left</span>
                <span>Prev</span>
            </a>
        </li>

        <!-- Page Dropdown -->
        <li class="page-item dropdown">
            <a class="page-link dropdown-toggle" data-toggle="dropdown" href="#" aria-label="Page">
                <span>{{ $actviepagi->currentPage() }}</span>
            </a>
            <div class="dropdown-menu">
                @foreach ($actviepagi->getUrlRange(1, $actviepagi->lastPage()) as $page => $url)
                    @php
                        // Manually append the query string to each dropdown item URL to preserve other active table states
                        $parsedUrl = $url . '&' . http_build_query(request()->except($actviepagi->getPageName()));
                    @endphp
                    <a href="{{ $parsedUrl }}" class="dropdown-item {{ $page == $actviepagi->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach
            </div>
        </li>

        <!-- Next Page Link -->
        <li class="page-item {{ $actviepagi->onLastPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $actviepagi->withQueryString()->nextPageUrl() ?? '0' }}" aria-label="Next">
                <span>Next</span>
                <span aria-hidden="true" class="material-icons">chevron_right</span>
            </a>
        </li>
    </ul>
</div>
                        </div>

                     