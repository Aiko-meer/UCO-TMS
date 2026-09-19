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
                            <li class="sidebar-menu-item {{ request()->routeIs('admin.home') ? 'active' : '' }} ">
                                <a class="sidebar-menu-button "
                                   href="{{route ('admin.home')}}">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">insert_chart_outlined</span>
                                    <span class="sidebar-menu-text">Dashboard</span>
                                </a>
                            </li>
                          
                        </ul>

                        <div class="sidebar-heading">UCO-TEAM</div>
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item {{ request()->routeIs('socmed.home', 'photo.home', 'video.home') ? 'open' : '' }}">
                                <a class="sidebar-menu-button js-sidebar-collapse"
                                   data-toggle="collapse"
                                   href="#enterprise_menu">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">camera</span>
                                    Content Team
                                    <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                </a>
                                <ul class="sidebar-submenu collapse sm-indent"
                                    id="enterprise_menu">
                                    <li class="sidebar-menu-item {{ request()->routeIs('socmed.home') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('socmed.home')}}">
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
                            <li class="sidebar-menu-item {{ request()->routeIs('production.home','layout.home', 'publishing.home','uzpost.home','uzpr.home') ? 'open' : '' }}">
                                <a class="sidebar-menu-button"
                                   data-toggle="collapse"
                                   href="#productivity_menu">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">brush</span>
                                    Creative Team
                                    <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                </a>
                                <ul class="sidebar-submenu collapse show sm-indent"
                                    id="productivity_menu">
                                    <li class="sidebar-menu-item {{ request()->routeIs('layout.home') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('layout.home')}}">
                                            <span class="sidebar-menu-text">Layout Services </span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item {{ request()->routeIs('production.home') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('production.home')}}">
                                            <span class="sidebar-menu-text">Production Services </span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item {{ request()->routeIs('publishing.home') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('publishing.home')}}">
                                            <span class="sidebar-menu-text">Publishing Services</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item {{ request()->routeIs('uzpost.home') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button "
                                           href="{{route ('uzpost.home')}}">
                                            <span class="sidebar-menu-text">UZ POST</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item {{ request()->routeIs('uzpr.home') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button "
                                           href="{{route ('uzpr.home')}}">
                                            <span class="sidebar-menu-text">UZ PR</span>
                                        </a>
                                    </li>
                                 
                                </ul>
                            </li>
                           
                            <li class="sidebar-menu-item {{ request()->routeIs('creativeuser.home','contentuser.home','user.home') ? 'open' : '' }}">
                                <a class="sidebar-menu-button"
                                   data-toggle="collapse"
                                   href="#account_menu">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_box</span>
                                    Account
                                    <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                </a>
                                <ul class="sidebar-submenu collapse sm-indent"
                                    id="account_menu">
                                    <li class="sidebar-menu-item {{ request()->routeIs('creativeuser.table') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('creativeuser.table')}}">
                                            <span class="sidebar-menu-text">Creatives</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item {{ request()->routeIs('contentuser.table') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('contentuser.table')}}">
                                            <span class="sidebar-menu-text">Content</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item {{ request()->routeIs('user.table') ? 'active' : '' }}">
                                        <a class="sidebar-menu-button"
                                           href="{{route ('user.table')}}">
                                            <span class="sidebar-menu-text">User</span>
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