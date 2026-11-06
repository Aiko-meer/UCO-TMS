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
                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-project">Requestor</a>
                </th>
                <th style="width: 1%; white-space: nowrap;">
                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-lead">Department</a>
                </th>
                <th style="width: 48px;">
                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Status</a>
                </th>
                <th style="width: 48px;">
                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-budget">Date requested</a>
                </th>
                <th style="width: 24px;">Action</th>
            </tr>
        </thead>
        <tbody class="list" id="projects">
           @foreach ($softCopies as $soft)
                <!-- Added data attributes for exact YYYY-MM matching -->
                <tr data-month="{{ $soft->created_at ? \Carbon\Carbon::parse($soft->created_at)->format('m') : '' }}"
                    data-year="{{ $soft->created_at ? \Carbon\Carbon::parse($soft->created_at)->format('Y') : '' }}">

                    <td>
                        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                            <div class="media-body">
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-project">
                                        <strong>{{ trim($soft->f_name . ' ' . ($soft->middle_name ?? '') . ' ' . $soft->l_name) }}</strong>
                                    </small>
                                    <a href="mailto:{{ $soft->email }}" class="text-muted small">
                                        {{ $soft->email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
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
                            <button type="button" class="btn btn-link text-50 p-0 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#viewModal-{{ $soft->id }}">
                                    <i class="material-icons text-muted mr-2" style="font-size: 16px;">visibility</i> View Details
                                </button>
                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#sendModal-{{ $soft->id }}">
                                    <i class="material-icons text-muted mr-2" style="font-size: 16px;">send</i> Send Soft Copy
                                </button>
                                <div class="dropdown-divider"></div>
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