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

                                        <th style="width: 1%; white-space: nowrap;">
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

                                        <th style="width: 24px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="list"
                                       id="projects">
                               @foreach ($softCopies as $soft)
                               @if ($soft->created_at && \Carbon\Carbon::parse($soft->created_at)->isCurrentMonth())
                                    <tr>

                                        <td>

                                            <div class="media flex-nowrap align-items-center"
                                                 style="white-space: nowrap;">
                                                
                                                <div class="media-body">
                                                <div class="d-flex flex-column">
                                                    <!-- Added proper spacing and cleaned up name concatenation -->
                                                    <small class="js-lists-values-project">
                                                        <strong>{{ trim($soft->f_name . ' ' . ($soft->middle_name ?? '') . ' ' . $soft->l_name) }}</strong>
                                                    </small>
                                                    
                                                    <!-- Added email to the href and link text -->
                                                    <a href="mailto:{{ $soft->email }}" class="text-muted small">
                                                        {{ $soft->email }}
                                                    </a>
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
                                                            <p class="mb-0"><strong class="js-lists-values-lead">{{ $soft->program }}</strong></p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        // Map your numeric status to text and badge colors
                                                        $statusText = 'Pending';
                                                        $badgeColor = 'bg-warning';

                                                        if ($soft->status == 1) {
                                                            $statusText = 'Done';
                                                            $badgeColor = 'bg-success';
                                                        } elseif ($soft->status == 2) {
                                                            $statusText = 'Rejected';
                                                            $badgeColor = 'bg-danger';
                                                        }
                                                    @endphp

                                                    <!-- Dynamic indicator line and human-readable text -->
                                                    <span class="indicator-line rounded {{ $badgeColor }} mr-2"></span>
                                                    <span class="font-weight-semibold">{{ $statusText }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                               <small class="js-lists-values-date">
                                                    <strong>{{ $soft->created_at ? \Carbon\Carbon::parse($soft->created_at)->format('M d, Y') : 'N/A' }}</strong>
                                                </small>
                                                <small class="text-50">
                                                    {{ $soft->created_at ? \Carbon\Carbon::parse($soft->created_at)->diffForHumans() : '' }}
                                                </small>
                                            </div>
                                        </td>

                                       <td class="text-center">
                                            <div class="dropdown">
                                                <!-- Dropdown Toggle Button -->
                                                <button type="button" class="btn btn-link text-50 p-0 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    
                                                    <!-- 1. View Action (Opens dynamic modal) -->
                                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#viewModal-{{ $soft->id }}">
                                                        <i class="material-icons text-muted mr-2" style="font-size: 16px;">visibility</i> View Details
                                                    </button>

                                                    <!-- 2. Send Soft Copy Action -->
                                                   <button type="button" class="dropdown-item" data-toggle="modal" data-target="#sendModal-{{ $soft->id }}">
                                                        <i class="material-icons text-muted mr-2" style="font-size: 16px;">send</i> Send Soft Copy
                                                    </button>

                                                    <div class="dropdown-divider"></div>

                                                    <!-- 3. Delete Action (Protected by a form and confirmation) -->
                                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="material-icons text-danger mr-2" style="font-size: 16px;">delete</i> Delete
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                               @endforeach
                                </tbody>
                            </table>
                            
                        </div>