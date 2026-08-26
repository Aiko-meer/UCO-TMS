 <div class="page-separator">
                            <div class="page-separator__text">Overview</div>
                        </div>

                        <div class="row card-group-row mb-lg-8pt">
                            <div class="col-lg-4 card-group-row__col">

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

                            </div>
                            <div class="col-lg-4 card-group-row__col">

                                <div class="card card-group-row__card">
                                    <div class="card-header py-12pt d-flex align-items-center">
                                        <div class="position-relative mr-16pt">
                                            <div class="text-center fullbleed d-flex align-items-center justify-content-center flex-column z-0">
                                                <small>35%</small>
                                            </div>
                                            <canvas width="48"
                                                    height="48"
                                                    class="chart-canvas position-relative z-1 js-update-chart-progress-accent"
                                                    id="inProgressChart"
                                                    data-chart-line-background-color="accent;gray"
                                                    data-chart-disable-tooltips="true"></canvas>
                                        </div>
                                        <strong class="flex">In Progress</strong>
                                        <div class="text-50">10</div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 card-group-row__col">

                                <div class="card card-group-row__card">
                                    <div class="card-header py-12pt d-flex align-items-center">
                                        <div class="position-relative mr-16pt">
                                            <div class="text-center fullbleed d-flex align-items-center justify-content-center flex-column z-0">
                                                <small>50%</small>
                                            </div>
                                            <canvas width="48"
                                                    height="48"
                                                    class="chart-canvas position-relative z-1 js-update-chart-progress-primary"
                                                    id="closedProgressChart"
                                                    data-chart-line-background-color="primary;gray"
                                                    data-chart-disable-tooltips="true"></canvas>
                                        </div>
                                        <strong class="flex">Closed</strong>
                                        <div class="text-50">14</div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 card-group-row__col">

                                <div class="card card-group-row__card">
                                    <div class="card-header py-12pt d-flex align-items-center">
                                        <div class="position-relative mr-16pt">
                                            <div class="text-center fullbleed d-flex align-items-center justify-content-center flex-column z-0">
                                                <small>25%</small>
                                            </div>
                                            <canvas width="48"
                                                    height="48"
                                                    class="chart-canvas position-relative z-1 js-update-chart-progress-success"
                                                    id="approvalProgressChart"
                                                    data-chart-line-background-color="success;gray"
                                                    data-chart-disable-tooltips="true">
                                            </canvas>
                                        </div>
                                        <strong class="flex">Approval</strong>
                                        <div class="text-50">10</div>
                                    </div>
                                </div>

                            </div>
                        </div>