                                                                     
<div class="table-responsive"
     data-toggle="lists"
     data-lists-sort-by="js-lists-values-date"
     data-lists-sort-desc="true"
     data-lists-values='["js-lists-values-lead", "js-lists-values-project", "js-lists-values-status", "js-lists-values-budget", "js-lists-values-date"]'>

    <!-- Card Header with Search & Archive Month/Year Filters -->
    <div class="card-header d-flex flex-wrap align-items-center">

    {{-- Search --}}
    <div class="search-form mb-2 mb-md-0 mr-md-3" style="flex: 1 1 220px; max-width: 320px;">
        <input type="text" class="form-control search" placeholder="Search ...">
        <button class="btn" type="button" role="button">
            <i class="material-icons">search</i>
        </button>
    </div>

        {{-- Filters: choosing a value reloads the page, and the Export button follows it --}}
    <div class="d-flex align-items-center mb-2 mb-md-0 ml-md-auto">
        <select id="filter-month" class="custom-select custom-select-sm mr-2" style="width: 140px;">
            <option value="">All Months</option>
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @selected($month === $m)>
                    {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                </option>
            @endfor
        </select>

        <select id="filter-year" class="custom-select custom-select-sm" style="width: 110px;">
            <option value="">All Years</option>
            @for ($y = now()->year; $y >= now()->year - 4; $y--)
                <option value="{{ $y }}" @selected($year === $y)>{{ $y }}</option>
            @endfor
        </select>
    </div>

    {{-- Export (admin and content admin only): exports exactly what is filtered above --}}
    @if (in_array(auth()->user()->user_type, ['admin', 'superadmin', 'content_admin']))
        @php
            // Only pass the filters that are actually chosen. status 2 = Done, the archive.
            $exportParams = array_filter(
                ['month' => $month, 'year' => $year, 'status' => 2],
                fn ($value) => $value !== null && $value !== ''
            );
        @endphp

        <a href="{{ route('photo.export', $exportParams) }}"
        class="btn btn-success btn-sm d-flex align-items-center ml-md-3 mb-2 mb-md-0"
        title="Download the rows currently shown in this table">
            <i class="material-icons mr-1" style="font-size: 18px;">download</i>
            Export
        </a>
    @endif
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
                                               data-sort="js-lists-values-status">Purpose</a>
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
                                @foreach($archived as $req)
                                @if ($req->information->status == 2)
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
                                                <small class="js-lists-values-status text-50 mb-4pt">{{ $req->information?->event ?? 'No Data' }}</small>
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
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const monthSelect = document.getElementById('filter-month');
    const yearSelect  = document.getElementById('filter-year');

    function applyFilter() {
        const params = new URLSearchParams(window.location.search);
        params.set('month', monthSelect.value);   // empty value = "All"
        params.set('year',  yearSelect.value);
        window.location.href = window.location.pathname + '?' + params.toString() + window.location.hash;
    }

    monthSelect.addEventListener('change', applyFilter);
    yearSelect.addEventListener('change', applyFilter);
});
</script>    
                         <script>
function openDeleteModal(actionUrl) {
    // Set the form action URL dynamically to the specific request route
    document.getElementById('activeDeleteForm').action = actionUrl;
    
    // Show the Bootstrap modal
    $('#deleteConfirmModal').modal('show');
}
</script>              