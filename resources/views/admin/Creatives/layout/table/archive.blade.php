                                                                     
<div class="table-responsive"
     data-toggle="lists"
     data-lists-sort-by="js-lists-values-date"
     data-lists-sort-desc="true"
     data-lists-values='["js-lists-values-lead", "js-lists-values-project", "js-lists-values-status", "js-lists-values-budget", "js-lists-values-date"]'>

    <!-- Card Header with Search & Archive Month/Year Filters -->
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        
        <!-- Search Bar -->
        <div class="search-form mb-0">
            <input type="text"
                   class="form-control search"
                   placeholder="Search ...">
            <button class="btn" type="button" role="button">
                <i class="material-icons">search</i>
            </button>
        </div>

        <!-- Month & Year Archive Filters -->
        <div class="d-flex align-items-center gap-2">
            <!-- Month Filter -->
            <select id="filter-month" class="form-control form-control-sm" style="width: 140px;">
                <option value="">All Months</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>

            <!-- Year Filter -->
            <select id="filter-year" class="form-control form-control-sm" style="width: 110px;">
                <option value="">All Years</option>
                <!-- Dynamically generate past 5 years or list them manually -->
                @php
                    $currentYear = date('Y');
                @endphp
                @for ($y = $currentYear; $y >= $currentYear - 4; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
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
                                        <th style="width: 24px;">Action</th>
                                    </tr>
                                </thead>
       <tbody class="list" id="projects">
    @foreach($archived as $req)
        <!-- Added data attributes for exact YYYY-MM matching -->
               <tr data-month="{{ $req->information?->created_at ? \Carbon\Carbon::parse($req->information->created_at)->format('m') : '' }}"
                    data-year="{{ $req->information?->created_at ? \Carbon\Carbon::parse($req->information->created_at)->format('Y') : '' }}">

            <td>
                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                    <div class="media-body">
                        <div class="d-flex flex-column">
                            <small class="js-lists-values-project"><strong>{{ $req->fullname }}</strong></small>
                            <a href="mailto:{{ $req->email }}">{{ $req->email }}</a>
                        </div>
                    </div>
                </div>
            </td>

            <td>
                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                    <div class="media-body">
                        <div class="d-flex align-items-center">
                            <div class="flex d-flex flex-column">
                                <p class="mb-0"><strong class="js-lists-values-lead">{{ $req->department }}</strong></p>
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
                    @if ($req->information?->status == 0)
                        <small class="js-lists-values-status text-50 mb-4pt">Pending</small>
                        <span class="indicator-line rounded bg-warning"></span>
                    @endif
                    @if ($req->information?->status == 1)
                        <small class="js-lists-values-status text-50 mb-4pt">Approve</small>
                        <span class="indicator-line rounded bg-success"></span>
                    @endif
                    @if ($req->information?->status == 2)
                        <small class="js-lists-values-status text-50 mb-4pt">Done</small>
                        <span class="indicator-line rounded bg-info"></span>
                    @endif 
                    @if ($req->information?->status == 3 || $req->information?->status == 4)
                        <small class="js-lists-values-status text-50 mb-4pt">Status {{ $req->information->status }}</small>
                        <span class="indicator-line rounded bg-danger"></span>
                    @endif
                </div>
            </td>

            <td>
                <div class="d-flex flex-column">
                    <span class="d-none js-lists-values-date">{{ $req->information?->created_at?->format('F') }}</span>
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
                <div class="dropdown">
                    <button type="button" class="btn btn-link text-50 p-0 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button type="button" class="dropdown-item" onclick="$('#viewModal-{{ $req->request_id }}').modal('show');">
                            <i class="material-icons font-size-16pt text-accent mr-2">visibility</i> View Details
                        </button>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#sendModal-{{ $req->request_id }}">
                            <i class="material-icons text-muted mr-2" style="font-size: 16px;">send</i> Send Approval
                        </button>
                        @if($req->information?->status != 3)
                        <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#denyModal-{{ $req->request_id }}">
                            <i class="material-icons text-danger mr-2" style="font-size: 16px;">cancel</i> Deny Request
                        </button>
                        @endif
                        <form action="{{ route('layout.destroy', $req->request_id) }}" method="POST" class="m-0 delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="dropdown-item text-danger" onclick="openDeleteModal('{{ route('layout.destroy', $req->request_id) }}')">
                                <i class="material-icons text-danger mr-2" style="font-size: 16px;">delete</i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>
    </table>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const monthSelect = document.getElementById('filter-month');
    const yearSelect = document.getElementById('filter-year');
    const rows = document.querySelectorAll('#projects tr');

    function filterTable() {
        const selectedMonth = monthSelect.value;
        const selectedYear = yearSelect.value;

        rows.forEach(row => {
            const rowMonth = row.getAttribute('data-month');
            const rowYear = row.getAttribute('data-year');

            let matchesMonth = !selectedMonth || rowMonth === selectedMonth;
            let matchesYear = !selectedYear || rowYear === selectedYear;

            if (matchesMonth && matchesYear) {
                row.style.display = ""; // Show row
            } else {
                row.style.display = "none"; // Hide row
            }
        });
    }

    monthSelect.addEventListener('change', filterTable);
    yearSelect.addEventListener('change', filterTable);
});
</script>                 