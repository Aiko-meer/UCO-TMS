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

                                        <th style="width: 70px";>
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-lead">Department</a>
                                        </th>

                                        <th style="width: 70px";>
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-lead">Event</a>
                                        </th>

                                        <th style="width: 48px;">
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-status">Status</a>
                                        </th>

                                        <th >
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-budget">Location </a>
                                        </th>

                                        <th style="width: 48px;">
                                            <a href="javascript:void(0)"
                                               class="sort"
                                               data-sort="js-lists-values-date">Date Event</a>
                                        </th>
                                        <th style="width: 24px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="list"
                                       id="projects">
                                @foreach($requests as $req)
                              @if ($req->information?->created_at && \Carbon\Carbon::parse($req->information->created_at)->isCurrentMonth())
                                    <tr>

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
                                                <!-- Hidden or separated text value specifically for List.js search matching the month -->
                                                <span class="d-none js-lists-values-date">{{ $req->information?->created_at?->format('F') }}</span>
                                                
                                                <!-- Visible date displayed to the user -->
                                                <small><strong>{{ $req->information?->created_at?->format('M d, Y h:i A') }}</strong></small>
                                                <small class="text-50">{{ $req->information?->created_at?->diffForHumans() }}</small>
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
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                               <div class="card-footer p-8pt">
                            <ul class="pagination justify-content-start pagination-xsm m-0">
                                <!-- Previous Page Link -->
                                <li class="page-item {{ $monthpagi->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $monthpagi->previousPageUrl() ?? '#' }}" aria-label="Previous">
                                        <span aria-hidden="true" class="material-icons">chevron_left</span>
                                        <span>Prev</span>
                                    </a>
                                </li>

                                <!-- Page Dropdown -->
                                <li class="page-item dropdown">
                                    <a class="page-link dropdown-toggle" data-toggle="dropdown" href="#" aria-label="Page">
                                        <span>{{ $monthpagi->currentPage() }}</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @foreach ($monthpagi->getUrlRange(1, $monthpagi->lastPage()) as $page => $url)
                                            <a href="{{ $url }}" class="dropdown-item {{ $page == $monthpagi->currentPage() ? 'active' : '' }}">
                                                {{ $page }}
                                            </a>
                                        @endforeach
                                    </div>
                                </li>

                                <!-- Next Page Link -->
                                <li class="page-item {{ $monthpagi->onLastPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $monthpagi->nextPageUrl() ?? '#' }}" aria-label="Next">
                                        <span>Next</span>
                                        <span aria-hidden="true" class="material-icons">chevron_right</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        </div>
                        <script>
    // Real-time auto-refresh interval (e.g., every 5 seconds)
    setInterval(function() {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
            let newTbody = doc.querySelector('#projects');
            
            if (newTbody) {
                // Keep track of current search value to prevent clearing user input
                let searchInput = document.querySelector('.search');
                let searchTerm = searchInput ? searchInput.value : '';

                // Replace table body content with fresh data
                document.querySelector('#projects').innerHTML = newTbody.innerHTML;

                // Re-trigger List.js search if search was active
                if (searchTerm && window.List && window.List.lists) {
                    // List.js handles re-initialization automatically if container matches
                }
            }
        })
        .catch(error => console.error('Error updating table:', error));
    }, 5000); // 5000ms = 5 seconds
</script>
