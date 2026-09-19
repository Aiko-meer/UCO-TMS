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
                                    <tr>

                                        <td>

                                            <div class="media flex-nowrap align-items-center"
                                                 style="white-space: nowrap;">
                                                
                                                <div class="media-body">
                                                    <div class="d-flex flex-column">
                                                        <small class="js-lists-values-project"><strong>{{ $req->fullname}}</strong></small>
                                                        <a href="mailto:{{$req->email}}">{{$req->email}}</a>
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
                                               @if ($req->information->status == 0)
                                                <small class="js-lists-values-status text-50 mb-4pt">Pending</small>
                                                <span class="indicator-line rounded bg-warning"></span>
                                                @endif
                                                 @if ($req->information->status == 1)
                                                <small class="js-lists-values-status text-50 mb-4pt">Approve</small>
                                                <span class="indicator-line rounded bg-success"></span>
                                                @endif
                                                 @if ($req->information->status == 2)
                                                <small class="js-lists-values-status text-50 mb-4pt">Done</small>
                                                <span class="indicator-line rounded bg-info"></span>
                                                @endif 
                                                @if ($req->information->status == 3)
                                                <small class="js-lists-values-status text-50 mb-4pt">Done</small>
                                                <span class="indicator-line rounded bg-danger"></span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                               <small class="js-lists-values-date">
                                                    <strong>{{ $req->information?->created_at ? \Carbon\Carbon::parse($req->information->created_at)->format('M d, Y') : 'N/A' }}</strong>
                                                </small>
                                                <small class="text-50">
                                                    {{ $req->information?->created_at ? \Carbon\Carbon::parse($req->information->created_at)->diffForHumans() : '' }}
                                                </small>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-date">
                                                    <strong>{{ $req->information?->date_needed ? \Carbon\Carbon::parse($req->information->date_needed)->format('M d, Y') : 'N/A' }}</strong>
                                                </small>
                                                <small class="text-50">
                                                    {{ $req->information?->date_needed ? \Carbon\Carbon::parse($req->information->date_needed)->diffForHumans() : '' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-link text-50 p-0" onclick="$('#viewModal-{{ $req->request_id }}').modal('show');">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                             <div class="card-footer p-8pt">
                            <ul class="pagination justify-content-start pagination-xsm m-0">
                                <!-- Previous Page Link -->
                                <li class="page-item {{ $listpagi->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $listpagi->previousPageUrl() ?? '#' }}" aria-label="Previous">
                                        <span aria-hidden="true" class="material-icons">chevron_left</span>
                                        <span>Prev</span>
                                    </a>
                                </li>

                                <!-- Page Dropdown -->
                                <li class="page-item dropdown">
                                    <a class="page-link dropdown-toggle" data-toggle="dropdown" href="#" aria-label="Page">
                                        <span>{{ $listpagi->currentPage() }}</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @foreach ($listpagi->getUrlRange(1, $listpagi->lastPage()) as $page => $url)
                                            <a href="{{ $url }}" class="dropdown-item {{ $page == $listpagi->currentPage() ? 'active' : '' }}">
                                                {{ $page }}
                                            </a>
                                        @endforeach
                                    </div>
                                </li>

                                <!-- Next Page Link -->
                                <li class="page-item {{ $listpagi->onLastPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $listpagi->nextPageUrl() ?? '#' }}" aria-label="Next">
                                        <span>Next</span>
                                        <span aria-hidden="true" class="material-icons">chevron_right</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        </div>