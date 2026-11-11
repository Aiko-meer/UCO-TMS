                                                                     
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

            {{-- Export: follows the month / year selected above --}}
            @if (in_array(auth()->user()->user_type, ['admin', 'superadmin', 'creative_admin'], true))
                <a href="{{ route('uzpost.export') }}"
                   id="export-archive-btn"
                   data-url="{{ route('uzpost.export') }}"
                   class="btn btn-sm btn-success d-inline-flex align-items-center"
                   title="Download the archive for the selected month and year as an Excel file">
                    <i class="material-icons mr-1" style="font-size: 16px;">file_download</i>
                    Export Excel
                </a>
            @endif
        </div>
    </div>

    <table class="table mb-0 thead-border-top-0 table-nowrap" id="active" data-archive-table>
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
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const monthSelect = document.getElementById('filter-month');
    const yearSelect = document.getElementById('filter-year');
    // Scope to this archive table: every tab table reuses id="projects",
    // so a document-wide lookup would hide rows on the other tabs too.
    const archiveTable = document.querySelector('[data-archive-table]');

    function filterTable() {
        if (!archiveTable) return;

        const selectedMonth = monthSelect.value;
        const selectedYear = yearSelect.value;

        archiveTable.querySelectorAll('tbody > tr').forEach(row => {
            const rowMonth = row.getAttribute('data-month');
            const rowYear = row.getAttribute('data-year');

            let matchesMonth = !selectedMonth || rowMonth === selectedMonth;
            let matchesYear = !selectedYear || rowYear === selectedYear;

            row.style.display = matchesMonth && matchesYear ? "" : "none";
        });

        if (typeof window.refreshTablePagination === 'function') {
            window.refreshTablePagination(archiveTable, true);
        }
    }

    monthSelect.addEventListener('change', filterTable);
    yearSelect.addEventListener('change', filterTable);

    // Keep the month/year filter applied after List.js search or sort re-renders rows.
    const listContainer = archiveTable && archiveTable.closest('[data-toggle="lists"]');
    if (listContainer && listContainer.List && typeof listContainer.List.on === 'function') {
        listContainer.List.on('updated', filterTable);
    }

    // ----- Export button: always points at the currently selected month / year -----
    const exportBtn = document.getElementById('export-archive-btn');

    function updateExportLink() {
        if (!exportBtn) return;

        const params = new URLSearchParams();
        if (monthSelect.value) params.set('month', monthSelect.value);
        if (yearSelect.value) params.set('year', yearSelect.value);

        const qs = params.toString();
        exportBtn.href = exportBtn.dataset.url + (qs ? '?' + qs : '');
    }

    monthSelect.addEventListener('change', updateExportLink);
    yearSelect.addEventListener('change', updateExportLink);
    updateExportLink();
});
</script>                   