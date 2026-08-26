<div class="mdk-drawer js-mdk-drawer"
                 id="default-drawer">
                <div class="mdk-drawer__content">
                    <div class="sidebar sidebar-dark sidebar-left"
                         data-perfect-scrollbar>

                        <a href="index.html"
                           class="sidebar-brand ">
                            <img class="sidebar-brand-icon"
                                 src="{{ asset('assets/images/logo/accent-teal-100@2x.png') }}"
                                 alt="">
                            <span>UCO-TMS</span>
                        </a>

                        <div class="sidebar-heading">Overview</div>
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item {{ request()->routeIs('contentuser.socmed') ? 'active' : '' }} ">
                                <a class="sidebar-menu-button "
                                   href="{{route ('contentuser.socmed')}}">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">insert_chart_outlined</span>
                                    <span class="sidebar-menu-text">Dashboard</span>
                                </a>
                            </li>
                          
                        </ul>

                        <div class="sidebar-heading">UCO-TEAM</div>
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item {{ request()->routeIs('contentuser.socmedtable','photo.home', 'video.home') ? 'open' : '' }}">
                                <a class="sidebar-menu-button js-sidebar-collapse"
                                   data-toggle="collapse"
                                   href="#enterprise_menu">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">camera</span>
                                    Content Team
                                    <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                </a>
                                <ul class="sidebar-submenu collapse sm-indent"
                                    id="enterprise_menu">
                                    <li class="sidebar-menu-item {{ request()->routeIs('contentuser.socmedtable') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('contentuser.socmedtable')}}">
                                            <span class="sidebar-menu-text">Social Media Services</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item {{ request()->routeIs('photo.home') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('photo.home')}}">
                                            <span class="sidebar-menu-text">Photography Services</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item {{ request()->routeIs('video.home') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('video.home')}}">
                                            <span class="sidebar-menu-text">Video Services</span>
                                        </a>
                                    </li>

                                    
                                </ul>
                            </li>

                            
                        </ul>           
                                </ul>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>