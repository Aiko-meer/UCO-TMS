<div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                        <div class="table-responsive"
                                         data-toggle="lists"
                                         data-lists-sort-by="js-lists-values-employee-name"
                                         data-lists-values='["js-lists-values-employee-name", "js-lists-values-employer-name", "js-lists-values-projects", "js-lists-values-activity", "js-lists-values-earnings"]'>

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

                                        <table class="table mb-0 thead-border-top-0 table-nowrap">
                                            <thead>
                                                <tr>

                                                    <th>
                                                        <a href="javascript:void(0)"
                                                           class="sort"
                                                           data-sort="js-lists-values-employee-name">Employee</a>
                                                    </th>
                                                    <th>Id</th>
                                                    <th style="width: 37px;">User Type</th>
                                                    
                                                    
                                                    <th style="width: 24px;"
                                                        class="pl-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list"
                                                   id="search">
                                            @foreach ($user as $user)
                                                <tr>

                                                    <td>

                                                        <div class="d-flex flex-column">
                                                            <p class="mb-0"><strong class="js-lists-values-employee-name">{{$user->fullname}}</strong></p>
                                                            <small class="js-lists-values-employee-email text-50">{{$user->email}}</small>
                                                        </div>

                                                    </td>

                                                    <td>
                                                         <p class="mb-0"><strong class="js-lists-values-employee-name">{{$user->employee_id}}</strong></p>
                                                    </td>

                                                    <td>

                                                        <p 
                                                           class="chip chip-outline-secondary">{{$user->user_type}}</p>

                                                    </td>
                                                     <td class="text-right">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="$('#viewModal-{{ $user->id }}').modal('show');">
                                                            <i class="fas fa-eye mr-1"></i>
                                                        </button>
                                                        <!-- Action Button for Password Change -->
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="$('#changePasswordModal-{{ $user->id }}').modal('show');">
                                                            <i class="fas fa-key mr-1"></i> 
                                                        </button>
                                                        <!-- Delete Trigger Button -->
                                                        <button type="button" class="btn btn-sm btn-outline-danger  " data-toggle="modal" onclick="openDeleteModal('{{ $user->id }}')">
                                                            <i class="fas fa-trash-alt mr-1"></i> 
                                                        </button>
                                                       
                                                    </td>
                                                </tr>
                                               
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                       
                        

                        <div class="card-footer p-8pt">

                            <ul class="pagination justify-content-start pagination-xsm m-0">
                                <li class="page-item disabled">
                                    <a class="page-link"
                                       href="#"
                                       aria-label="Previous">
                                        <span aria-hidden="true"
                                              class="material-icons">chevron_left</span>
                                        <span>Prev</span>
                                    </a>
                                </li>
                                <li class="page-item dropdown">
                                    <a class="page-link dropdown-toggle"
                                       data-toggle="dropdown"
                                       href="#"
                                       aria-label="Page">
                                        <span>1</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href=""
                                           class="dropdown-item active">1</a>
                                        <a href=""
                                           class="dropdown-item">2</a>
                                        <a href=""
                                           class="dropdown-item">3</a>
                                        <a href=""
                                           class="dropdown-item">4</a>
                                        <a href=""
                                           class="dropdown-item">5</a>
                                    </div>
                                </li>
                                <li class="page-item">
                                    <a class="page-link"
                                       href="#"
                                       aria-label="Next">
                                        <span>Next</span>
                                        <span aria-hidden="true"
                                              class="material-icons">chevron_right</span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                       <script>
function openPasswordModal() {
    // 1. Hide the User Details modal first
    $('#userDetailsModal').modal('hide'); // Replace #userDetailsModal with your actual User Details modal ID

    // 2. Open the Change Password modal smoothly after a brief delay
    setTimeout(function() {
        $('#changePasswordModal').modal('show');
    }, 300); // 300ms delay lets the first modal close cleanly
}
</script>