@php
    $overviewStatuses = [
        'in_progress' => ['label' => 'In Progress', 'color' => '#4F7D5C', 'icon' => 'autorenew'],
        'for_approval' => ['label' => 'For Approval', 'color' => '#C9A24B', 'icon' => 'check'],
        'pending' => ['label' => 'Pending', 'color' => '#B08968', 'icon' => 'schedule'],
        'rejected' => ['label' => 'Rejected', 'color' => '#A8574A', 'icon' => 'cancel'],
    ];
    $completedColor = '#1F3D2B';
    $overviewServices = [
        'socmed' => ['label' => 'Social Media', 'detail' => 'Socmed request information'],
        'layout' => ['label' => 'Layout', 'detail' => 'Publication · Layout'],
        'production' => ['label' => 'Production', 'detail' => 'Publication · Production'],
        'uzpr' => ['label' => 'UZ PR', 'detail' => 'Publication · UZ PR'],
        'uzpost' => ['label' => 'UZ POST', 'detail' => 'Publication · UZ POST'],
        'trademark' => ['label' => 'Trademark', 'detail' => 'Trademark request'],
        'softcopy' => ['label' => 'Soft Copy', 'detail' => 'Soft copy request'],
        'photo' => ['label' => 'Photo & Video', 'detail' => 'Photo / video request'],
    ];
    $overviewActiveTotal = $requestStatusSummary['in_progress']
        + $requestStatusSummary['for_approval']
        + $requestStatusSummary['pending']
        + $requestStatusSummary['rejected'];
    $overviewChartTotal = $overviewActiveTotal + $requestStatusSummary['completed'];
    $overviewOffset = 0;
    $completedPercentage = $overviewChartTotal > 0 ? ($requestStatusSummary['completed'] / $overviewChartTotal) * 100 : 0;
@endphp

<style>
    .request-overview {
        --ink: #152C1F;
        --muted: #6B8F71;
        --line: #DCE7D8;
        --line-soft: #EDF1E7;
        --surface: #FFFDF7;
        --soft: #F7F4EA;
        --ease: cubic-bezier(.22, 1, .36, 1);
        color: var(--ink);
    }

    /* ---------- Motion ---------- */
    @keyframes ro-rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    @keyframes ro-grow { from { width: 0; } }
    @keyframes ro-draw { from { stroke-dasharray: 0 100; } }
    @keyframes ro-fade { from { opacity: 0; } to { opacity: 1; } }
    @keyframes ro-spin { to { transform: rotate(360deg); } }

    .request-overview .anim { animation: ro-rise .6s var(--ease) both; animation-delay: calc(var(--i, 0) * 70ms); }

    /* ---------- Heading ---------- */
    .request-overview .overview-heading { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px 16px; margin-bottom: 20px; }
    .request-overview .overview-title { margin: 0; font-size: 18px; font-weight: 700; letter-spacing: -.01em; }
    .request-overview .overview-total { display: inline-flex; align-items: baseline; gap: 6px; padding: 6px 14px; border: 1px solid var(--line); border-radius: 999px; background: var(--surface); color: var(--muted); font-size: 13px; }
    .request-overview .overview-total strong { color: var(--ink); font-size: 18px; font-variant-numeric: tabular-nums; }

    /* ---------- Cards ---------- */
    .request-overview .overview-card { height: calc(100% - 24px); margin-bottom: 24px; border: 1px solid var(--line); border-radius: 14px; background: var(--surface); box-shadow: 0 1px 2px rgba(31, 42, 51, .04), 0 8px 24px -12px rgba(31, 42, 51, .08); }

    /* ---------- Status cards ---------- */
    .request-overview .status-card { position: relative; overflow: hidden; padding: 20px; transition: transform .25s var(--ease), box-shadow .25s var(--ease), border-color .25s var(--ease); }
    .request-overview .status-card::before { content: ''; position: absolute; inset: 0 auto 0 0; width: 4px; background: var(--status-color); }
    .request-overview .status-card:hover { transform: translateY(-3px); border-color: color-mix(in srgb, var(--status-color) 35%, var(--line)); box-shadow: 0 14px 30px -14px color-mix(in srgb, var(--status-color) 45%, transparent); }
    .request-overview .status-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .request-overview .status-label { color: var(--muted); font-size: 13px; font-weight: 600; }
    .request-overview .status-count { display: block; margin-top: 8px; font-size: clamp(26px, 3vw, 32px); font-weight: 700; line-height: 1; font-variant-numeric: tabular-nums; }
    .request-overview .status-icon { display: grid; width: 40px; height: 40px; flex: 0 0 auto; place-items: center; border-radius: 10px; background: color-mix(in srgb, var(--status-color) 12%, white); color: var(--status-color); font-size: 21px; transition: transform .4s var(--ease); }
    .request-overview .status-card:hover .status-icon { transform: rotate(-8deg) scale(1.08); }
    .request-overview .status-card.is-active .status-icon.spin { animation: ro-spin 3s linear infinite; }
    .request-overview .status-meta { display: flex; justify-content: space-between; gap: 8px; margin-top: 18px; color: var(--muted); font-size: 12px; }
    .request-overview .status-track { height: 5px; margin-top: 8px; overflow: hidden; border-radius: 10px; background: #EDF1E7; }
    .request-overview .status-fill { width: var(--status-percent); height: 100%; border-radius: inherit; background: var(--status-color); animation: ro-grow 1s var(--ease) both; animation-delay: calc(var(--i, 0) * 70ms + 250ms); }

    /* ---------- Panels ---------- */
    .request-overview .overview-panel-header { padding: 20px 22px 16px; border-bottom: 1px solid var(--line); }
    .request-overview .overview-panel-title { margin: 0; font-size: 16px; font-weight: 700; }
    .request-overview .overview-panel-subtitle { margin: 4px 0 0; color: var(--muted); font-size: 12.5px; }

    /* ---------- Donut ---------- */
    .request-overview .ro-panel { display: flex; flex-direction: column; }
    .request-overview .ro-dist-body { display: flex; flex: 1 1 auto; flex-wrap: wrap; align-items: center; justify-content: center; gap: 28px; padding: 28px 22px; }
    .request-overview .ro-donut { position: relative; width: clamp(150px, 20vw, 190px); aspect-ratio: 1; flex: 0 0 auto; }
    .request-overview .ro-donut svg { display: block; width: 100%; height: 100%; transform: rotate(-90deg); }
    .request-overview .ro-donut circle { fill: none; stroke-width: 5; transition: stroke-width .2s ease, opacity .2s ease; }
    .request-overview .ro-donut circle.segment { animation: ro-draw 1.1s var(--ease) both; animation-delay: calc(var(--i, 0) * 120ms + 300ms); cursor: pointer; }
    .request-overview .ro-donut svg:hover circle.segment { opacity: .45; }
    .request-overview .ro-donut svg circle.segment:hover { opacity: 1; stroke-width: 6; }
    .request-overview .ro-donut-center { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; flex-direction: column; pointer-events: none; animation: ro-fade .8s ease .5s both; }
    .request-overview .ro-donut-center strong { font-size: clamp(26px, 3vw, 32px); line-height: 1; font-variant-numeric: tabular-nums; }
    .request-overview .ro-donut-center span { margin-top: 6px; color: var(--muted); font-size: 12px; }

    /* !important on layout props: guards against theme rules that style generic lists/legends */
    .request-overview ul.ro-legend { position: static !important; display: block !important; float: none !important; flex: 1 1 200px; width: auto; min-width: 180px; max-width: 100%; margin: 0 !important; padding: 0 !important; list-style: none !important; }
    .request-overview ul.ro-legend > li { position: static !important; display: grid !important; grid-template-columns: auto minmax(0, 1fr) auto 3rem; align-items: center; column-gap: 10px; width: auto; margin: 0 !important; padding: 9px 10px; border-radius: 8px; font-size: 13px; transition: background .2s ease; animation: ro-rise .5s var(--ease) both; animation-delay: calc(var(--i, 0) * 70ms + 400ms); }
    .request-overview ul.ro-legend > li:hover { background: var(--soft); }
    .request-overview .ro-legend-dot { width: 9px; height: 9px; border-radius: 50%; background: var(--legend-color); }
    .request-overview .ro-legend-label { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #5E7461; }
    .request-overview .ro-legend-value { font-weight: 700; text-align: right; font-variant-numeric: tabular-nums; }
    .request-overview .ro-legend-percent { color: var(--muted); font-size: 12px; text-align: right; font-variant-numeric: tabular-nums; }

    /* ---------- Table ---------- */
    .request-overview .breakdown-table { width: 100%; margin: 0; border-collapse: collapse; }
    .request-overview .breakdown-table th { padding: 12px 14px; border-bottom: 1px solid var(--line); background: var(--soft); color: var(--muted); font-size: 12px; font-weight: 600; text-align: right; white-space: nowrap; }
    .request-overview .breakdown-table th:first-child { padding-left: 22px; text-align: left; }
    .request-overview .breakdown-table td { padding: 13px 14px; border-bottom: 1px solid var(--line-soft); font-size: 13px; text-align: right; vertical-align: middle; }
    .request-overview .breakdown-table td:first-child { padding-left: 22px; text-align: left; }
    .request-overview .breakdown-table tbody tr { transition: background .2s ease; animation: ro-rise .5s var(--ease) both; animation-delay: calc(var(--i, 0) * 50ms + 350ms); }
    .request-overview .breakdown-table tbody tr:hover { background: #FAF8F0; }
    .request-overview .breakdown-table tbody tr:last-child td { border-bottom: 0; }
    .request-overview .service-name { display: block; font-size: 13.5px; font-weight: 600; }
    .request-overview .service-detail { display: block; margin-top: 2px; color: #8FAE92; font-size: 11px; }
    .request-overview .number-pill { display: inline-block; min-width: 30px; padding: 3px 8px; border-radius: 6px; background: #F1F5EC; color: #9AAE9D; text-align: center; font-variant-numeric: tabular-nums; transition: transform .2s var(--ease); }
    .request-overview .number-pill.has-value { background: color-mix(in srgb, var(--number-color) 11%, white); color: var(--number-color); font-weight: 700; }
    .request-overview tr:hover .number-pill.has-value { transform: scale(1.1); }
    .request-overview .total-cell { font-weight: 700; font-variant-numeric: tabular-nums; }
    .request-overview .empty-overview { margin: 0; padding: 32px; color: var(--muted); text-align: center; }

    /* ---------- Responsive ---------- */
    @media (max-width: 575.98px) {

        /* Table turns into one card per service on phones, so nothing scrolls sideways */
        .request-overview .breakdown-table thead { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); }
        .request-overview .breakdown-table,
        .request-overview .breakdown-table tbody { display: block; }
        .request-overview .breakdown-table tbody tr { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px; padding: 16px 18px; border-bottom: 1px solid var(--line); }
        .request-overview .breakdown-table tbody tr:last-child { border-bottom: 0; }
        .request-overview .breakdown-table td { display: flex; align-items: center; justify-content: space-between; padding: 0; border: 0; text-align: left; }
        .request-overview .breakdown-table td:first-child { grid-column: 1 / -1; padding: 0 0 6px; }
        .request-overview .breakdown-table td[data-label]::before { content: attr(data-label); color: var(--muted); font-size: 12px; }
        .request-overview .breakdown-table td.total-cell { grid-column: 1 / -1; margin-top: 4px; padding-top: 10px; border-top: 1px dashed var(--line); }
    }

    /* ---------- Accessibility ---------- */
    @media (prefers-reduced-motion: reduce) {
        .request-overview *, .request-overview *::before { animation: none !important; transition: none !important; }
    }
    @supports not (background: color-mix(in srgb, red 10%, white)) {
        .request-overview .status-icon, .request-overview .number-pill.has-value { background: #F1F5EC; }
    }
</style>

<div class="request-overview">
    <div class="overview-heading anim" style="--i: 0">
        <h2 class="overview-title">Overview</h2>
        <div class="overview-total"><strong data-count="{{ $requestGrandTotal }}">{{ number_format($requestGrandTotal) }}</strong> total requests</div>
    </div>

    <div class="row">
        @foreach ($overviewStatuses as $status => $meta)
            @php
                $count = $requestStatusSummary[$status];
                $percentage = $requestGrandTotal > 0 ? round(($count / $requestGrandTotal) * 100, 1) : 0;
            @endphp
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card overview-card status-card anim {{ $status === 'in_progress' && $count > 0 ? 'is-active' : '' }}" style="--status-color: {{ $meta['color'] }}; --i: {{ $loop->iteration }}">
                    <div class="status-card-top">
                        <div>
                            <span class="status-label">{{ $meta['label'] }}</span>
                            <strong class="status-count" data-count="{{ $count }}">{{ number_format($count) }}</strong>
                        </div>
                        <span class="material-icons status-icon {{ $status === 'in_progress' ? 'spin' : '' }}" aria-hidden="true">{{ $meta['icon'] }}</span>
                    </div>
                    <div class="status-meta"><span>{{ $percentage }}% of all requests</span><span>{{ $count === 1 ? '1 request' : $count . ' requests' }}</span></div>
                    <div class="status-track" role="progressbar" aria-label="{{ $meta['label'] }} share of requests" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"><div class="status-fill" style="--status-percent: {{ $percentage }}%"></div></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12 col-lg-5">
            <section class="card overview-card ro-panel anim" style="--i: 5" aria-labelledby="request-distribution-title">
                <header class="overview-panel-header">
                    <h3 class="overview-panel-title" id="request-distribution-title">Request status distribution</h3>
                    <p class="overview-panel-subtitle">All request types, including completed requests</p>
                </header>
                @if ($overviewChartTotal > 0)
                    <div class="ro-dist-body">
                        <div class="ro-donut">
                            <svg viewBox="0 0 42 42" role="img" aria-label="Request status distribution: {{ $requestStatusSummary['in_progress'] }} in progress, {{ $requestStatusSummary['for_approval'] }} for approval, {{ $requestStatusSummary['pending'] }} pending, {{ $requestStatusSummary['rejected'] }} rejected, {{ $requestStatusSummary['completed'] }} completed">
                                <circle cx="21" cy="21" r="15.9155" pathLength="100" stroke="#EDF1E7"></circle>
                                @foreach ($overviewStatuses as $status => $meta)
                                    @php $percentage = ($requestStatusSummary[$status] / $overviewChartTotal) * 100; @endphp
                                    @if ($percentage > 0)
                                        <circle class="segment" style="--i: {{ $loop->index }}" cx="21" cy="21" r="15.9155" pathLength="100" stroke="{{ $meta['color'] }}" stroke-dasharray="{{ $percentage }} {{ 100 - $percentage }}" stroke-dashoffset="{{ -$overviewOffset }}"><title>{{ $meta['label'] }}: {{ $requestStatusSummary[$status] }}</title></circle>
                                        @php $overviewOffset += $percentage; @endphp
                                    @endif
                                @endforeach
                                @if ($completedPercentage > 0)
                                    <circle class="segment" style="--i: 4" cx="21" cy="21" r="15.9155" pathLength="100" stroke="{{ $completedColor }}" stroke-dasharray="{{ $completedPercentage }} {{ 100 - $completedPercentage }}" stroke-dashoffset="{{ -$overviewOffset }}"><title>Approved / Completed: {{ $requestStatusSummary['completed'] }}</title></circle>
                                @endif
                            </svg>
                            <div class="ro-donut-center"><strong data-count="{{ $overviewChartTotal }}">{{ number_format($overviewChartTotal) }}</strong><span>Total requests</span></div>
                        </div>
                        <ul class="ro-legend">
                            @foreach ($overviewStatuses as $status => $meta)
                                <li style="--legend-color: {{ $meta['color'] }}; --i: {{ $loop->index }}"><span class="ro-legend-dot" aria-hidden="true"></span><span class="ro-legend-label">{{ $meta['label'] }}</span><span class="ro-legend-value">{{ $requestStatusSummary[$status] }}</span><span class="ro-legend-percent">{{ round(($requestStatusSummary[$status] / $overviewChartTotal) * 100, 1) }}%</span></li>
                            @endforeach
                            <li style="--legend-color: {{ $completedColor }}; --i: 4"><span class="ro-legend-dot" aria-hidden="true"></span><span class="ro-legend-label">Approved / Completed</span><span class="ro-legend-value">{{ $requestStatusSummary['completed'] }}</span><span class="ro-legend-percent">{{ round($completedPercentage, 1) }}%</span></li>
                        </ul>
                    </div>
                @else
                    <p class="empty-overview">No requests yet. They will appear here once the first one is submitted.</p>
                @endif
            </section>
        </div>

        <div class="col-12 col-lg-7">
            <section class="card overview-card anim" style="--i: 6" aria-labelledby="request-breakdown-title">
                <header class="overview-panel-header">
                    <h3 class="overview-panel-title" id="request-breakdown-title">Requests by service</h3>
                    <p class="overview-panel-subtitle">Publication requests are separated by specification</p>
                </header>
                <div class="table-responsive">
                    <table class="breakdown-table">
                        <thead>
                            <tr><th scope="col">Service</th><th scope="col">In progress</th><th scope="col">For approval</th><th scope="col">Pending</th><th scope="col">Rejected</th><th scope="col">Completed</th><th scope="col">Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($overviewServices as $key => $service)
                                @php $counts = $requestTypeSummary[$key]; @endphp
                                <tr style="--i: {{ $loop->index }}">
                                    <td><span class="service-name">{{ $service['label'] }}</span><span class="service-detail">{{ $service['detail'] }}</span></td>
                                    @foreach ($overviewStatuses as $status => $meta)
                                        <td data-label="{{ $meta['label'] }}"><span class="number-pill {{ $counts[$status] > 0 ? 'has-value' : '' }}" style="--number-color: {{ $meta['color'] }}">{{ $counts[$status] }}</span></td>
                                    @endforeach
                                    <td data-label="Completed"><span class="number-pill {{ $counts['completed'] > 0 ? 'has-value' : '' }}" style="--number-color: {{ $completedColor }}">{{ $counts['completed'] }}</span></td>
                                    <td class="total-cell" data-label="Total">{{ $counts['total'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    // Count-up for the numbers. The server-rendered value stays in place if JS or motion is off.
    (function () {
        var root = document.currentScript.previousElementSibling;
        if (!root || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        root.querySelectorAll('[data-count]').forEach(function (el) {
            var target = parseInt(el.dataset.count, 10);
            if (!target || isNaN(target)) return;
            var duration = 900, start = null;
            el.textContent = '0';
            function tick(ts) {
                if (start === null) start = ts;
                var p = Math.min((ts - start) / duration, 1);
                var eased = 1 - Math.pow(1 - p, 4);
                el.textContent = Math.round(target * eased).toLocaleString();
                if (p < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });
    })();
</script>