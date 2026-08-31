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

                                            <form method="GET" action="" class="d-flex align-items-center gap-2">
                                                <select name="month" class="form-control form-control-sm" onchange="this.form.submit()">
                                                    @foreach(range(1, 12) as $m)
                                                        <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                                                            {{ Carbon\Carbon::create()->month($m)->format('F') }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                                                    @for($y = date('Y'); $y >= 2023; $y--)
                                                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                                            {{ $y }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </form>
                                        </div>
                             
                            <table class="table mb-0 thead-border-top-0 table-nowrap" id="archive">
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
                                @if ($req->information->status == 2)
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
                                                <!-- Hidden or separated text value specifically for List.js search matching the month -->
                                                <span class="d-none js-lists-values-date">{{ $req->information?->created_at?->format('F') }}</span>
                                                
                                                <!-- Visible date displayed to the user -->
                                                <small><strong>{{ $req->information?->created_at?->format('M d, Y h:i A') }}</strong></small>
                                                <small class="text-50">{{ $req->information?->created_at?->diffForHumans() }}</small>
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
                              
                        </div>

                       <div class="page-separator px-3">
                            <div class="page-separator__text mf-6">Folder</div>
                        </div>

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

                                            <form method="GET" action="" class="d-flex align-items-center gap-2">
                                                <select name="month" class="form-control form-control-sm" onchange="this.form.submit()">
                                                    @foreach(range(1, 12) as $m)
                                                        <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                                                            {{ Carbon\Carbon::create()->month($m)->format('F') }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                                                    @for($y = date('Y'); $y >= 2023; $y--)
                                                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                                            {{ $y }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </form>
                                        </div>
                             
                            <table class="table mb-0 thead-border-top-0 table-nowrap" id="archive">
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
                                @foreach($archived as $req)
                                @if ($req->information->status == 2)
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
                                                <!-- Hidden or separated text value specifically for List.js search matching the month -->
                                                <span class="d-none js-lists-values-date">{{ $req->information?->created_at?->format('F') }}</span>
                                                
                                                <!-- Visible date displayed to the user -->
                                                <small><strong>{{ $req->information?->created_at?->format('M d, Y h:i A') }}</strong></small>
                                                <small class="text-50">{{ $req->information?->created_at?->diffForHumans() }}</small>
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
<script>
    // Helper function to pull headers and only the visible rows data
   function getCleanTableData() {
    // Look for the table container or direct table
    let table = document.getElementById('active');
    if (!table) return { headers: [], rows: [] };

    let headers = [];
    let headerCells = table.querySelectorAll('thead th');
    for (let i = 0; i < headerCells.length - 1; i++) {
        let text = headerCells[i].innerText.trim();
        if (text) headers.push(text);
    }

    let rows = [];
    // Target rows inside the tbody specifically
    let trs = table.querySelectorAll('tbody tr');
    
    trs.forEach(tr => {
        let cells = tr.querySelectorAll('td');
        if (cells.length > 0) {
            let rowData = [];
            for (let i = 0; i < cells.length - 1; i++) {
                let text = cells[i].innerText.trim().replace(/\n/g, ' - ').replace(/\s+/g, ' ');
                rowData.push(text);
            }
            rows.push(rowData);
        }
    });

    return { headers, rows };
}

    // Export to Excel (CSV Format)
   function exportTableToExcel(tableID, filename = 'requests_report.csv') {
    let table = document.getElementById(tableID) || document.querySelector('table');
    if (!table) {
        alert("Table not found!");
        return;
    }

    let csvContent = [];
    let rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        let rowData = [];
        let cells = row.querySelectorAll('th, td');
        
        for (let i = 0; i < cells.length - 1; i++) {
            let text = cells[i].innerText.trim().replace(/\n/g, ' - ').replace(/"/g, '""');
            rowData.push(`"${text}"`);
        }
        if (rowData.length > 0) {
            csvContent.push(rowData.join(","));
        }
    });

    let blob = new Blob(["\ufeff" + csvContent.join("\n")], { type: 'text/csv;charset=utf-8;' });
    let link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

    // Print Filtered Table
    function printFilteredTable() {
        let data = getCleanTableData();
        if (data.rows.length === 0) {
            alert("No visible data to print.");
            return;
        }

        let printWindow = window.open('', '', 'height=600,width=800');
        
        let html = '<table border="1" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">';
        html += '<tr style="background-color: #f8f9fa;">';
        data.headers.forEach(h => html += `<th style="padding: 8px; border: 1px solid #ddd;">${h}</th>`);
        html += '</tr>';

        data.rows.forEach(row => {
            html += '<tr>';
            row.forEach(cell => html += `<td style="padding: 8px; border: 1px solid #ddd;">${cell}</td>`);
            html += '</tr>';
        });
        html += '</table>';

        printWindow.document.write('<!DOCTYPE html><html><head><title>Print Report</title>');
        printWindow.document.write('</head><body style="padding: 20px; font-family: Arial, sans-serif;">');
        printWindow.document.write('<h3 style="margin-bottom: 20px;">Filtered Requests Report</h3>');
        printWindow.document.write(html);
        printWindow.document.write('</body></html>');
        
        printWindow.document.close();
        printWindow.focus();
        
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 300);
    }
</script>                                                                      
                     