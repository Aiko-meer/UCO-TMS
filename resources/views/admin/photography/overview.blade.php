 <div class="page-separator">
                            <div class="page-separator__text">Overview</div>
                        </div>

                        <div class="row card-group-row mb-lg-8pt">
                            <!--<div class="col-lg-4 card-group-row__col">

                                <div class="card card-group-row__card">
                                    <div class="card-header py-12pt d-flex align-items-center">
                                        <div class="position-relative mr-16pt">
                                            <div class="text-center fullbleed d-flex align-items-center justify-content-center flex-column z-0">
                                                <small>14%</small>
                                            </div>
                                            <canvas width="48"
                                                    height="48"
                                                    class="chart-canvas position-relative z-1"
                                                    id="openProgressChart"
                                                    data-chart-line-background-color="yellow;gray"
                                                    data-chart-disable-tooltips="true"></canvas>
                                        </div>
                                        <strong class="flex">Open</strong>
                                        <div class="text-50">4</div>
                                    </div>
                                </div>

                            </div>-->
                          <!-- 1. In Progress Chart -->
<div class="col-lg-4 card-group-row__col">
    <div class="card card-group-row__card">
        <div class="card-header py-12pt d-flex align-items-center">
            <div class="position-relative mr-16pt">
                <div class="text-center fullbleed d-flex align-items-center justify-content-center flex-column z-0">
                    <small>{{ $inProgressPercentage }}%</small>
                </div>
                <canvas width="48"
                        height="48"
                        class="position-relative z-1"
                        id="dynamicInProgressChart"></canvas>
            </div>
            <strong class="flex">In Progress</strong>
            <div class="text-50">{{ $inProgressCount }}</div>
        </div>
    </div>
</div>

<!-- 2. Posted Chart -->
<div class="col-lg-4 card-group-row__col">
    <div class="card card-group-row__card">
        <div class="card-header py-12pt d-flex align-items-center">
            <div class="position-relative mr-16pt">
                <div class="text-center fullbleed d-flex align-items-center justify-content-center flex-column z-0">
                    <small>{{ $postedPercentage }}%</small>
                </div>
                <canvas width="48"
                        height="48"
                        class="position-relative z-1"
                        id="dynamicPostedChart"></canvas>
            </div>
            <strong class="flex">Posted</strong>
            <div class="text-50">{{ $postedCount }}</div>
        </div>
    </div>
</div>

<!-- 3. Approval Chart -->
<div class="col-lg-4 card-group-row__col">
    <div class="card card-group-row__card">
        <div class="card-header py-12pt d-flex align-items-center">
            <div class="position-relative mr-16pt">
                <div class="text-center fullbleed d-flex align-items-center justify-content-center flex-column z-0">
                    <small>{{ $approvalPercentage }}%</small>
                </div>
                <canvas width="48"
                        height="48"
                        class="position-relative z-1"
                        id="dynamicApprovalChart"></canvas>
            </div>
            <strong class="flex">Approval</strong>
            <div class="text-50">{{ $approvalCount }}</div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Helper function to create charts cleanly
        function createProgressChart(elementId, percentage, color) {
            var ctx = document.getElementById(elementId).getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [percentage, 100 - percentage],
                        backgroundColor: [color, '#e9ecef'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    tooltips: { enabled: false },
                    responsive: false,
                    maintainAspectRatio: false
                }
            });
        }

        // Initialize each chart with its specific color
        createProgressChart('dynamicInProgressChart', {{ $inProgressPercentage }}, '#ffc107'); // Accent / Yellow
        createProgressChart('dynamicPostedChart', {{ $postedPercentage }}, '#007bff');       // Primary / Blue
        createProgressChart('dynamicApprovalChart', {{ $approvalPercentage }}, '#28a745');   // Success / Green
    });
</script>
                        </div>