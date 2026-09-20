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

                                        <th style="width: 18px;"
                                            class="pr-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input js-toggle-check-all"
                                                       data-target="#projects"
                                                       id="customCheckAll">
                                                <label class="custom-control-label"
                                                       for="customCheckAll"><span class="text-hide">Toggle all</span></label>
                                            </div>
                                        </th>

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

                                    <tr>

                                        <td class="pr-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input js-check-selected-row"
                                                       id="customCheck1_1">
                                                <label class="custom-control-label"
                                                       for="customCheck1_1"><span class="text-hide">Check</span></label>
                                            </div>
                                        </td>

                                        <td>

                                            <div class="media flex-nowrap align-items-center"
                                                 style="white-space: nowrap;">
                                                
                                                <div class="media-body">
                                                    <div class="d-flex flex-column">
                                                        <small class="js-lists-values-project"><strong>Juan dela cruz</strong></small>
                                                        <small class="js-lists-values-location text-50">juan.delacruz@uz.edu.ph</small>
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
                                                            <p class="mb-0"><strong class="js-lists-values-lead">UCO</strong></p>
                                                            <small class="js-lists-values-email text-50">Content Associate</small>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-status text-50 mb-4pt">Pending</small>
                                                <span class="indicator-line rounded bg-warning"></span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-date"><strong>Teatro estrellia</strong></small>
                                                <small class="text-50"></small>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-date"><strong>17/08/2019</strong></small>
                                                <small class="text-50">2 days</small>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <a href=""
                                               class="text-50"><i class="material-icons">more_vert</i></a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>