<aside class="main-sidebar sidebar-dark-primary elevation-4"> 
    <a href="{{ url('admin/dashboard')}}" class="brand-link"> 
        <img src="{{ url('admin/images/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" > 
        <span class="brand-text font-weight-light">T2B</span> 
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{-- @if(Session::get('page') == 'Dashboard')
                    @php $active = "active" @endphp
                @else
                    @php $active = "" @endphp
                @endif --}}
                <li class="nav-item"> 
                    <a href="{{ url('admin/dashboard')}}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}"> <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>
                {{-- <li class="nav-item"> 
                    <a href="#" class="nav-link"> <i class="nav-icon fas fa-copy"></i>
                        <p> Users <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> 
                            <a href="clinic_users.php" class="nav-link"> <i class="far fa-circle nav-icon"></i>
                                <p> Clinic User </p>
                            </a> 
                        </li>
                        <li class="nav-item"> 
                            <a href="patient_users.php" class="nav-link"> <i class="far fa-circle nav-icon"></i>
                                <p> Patient Users </p>
                            </a> 
                        </li>
                    </ul>
                </li> --}}
                <li class="nav-item menu-is-opening menu-open"> 
                    <a href="#" class="nav-link {{ Request::is('admin/change_password') || Request::is('admin/view_profile') || Request::is('admin/edit_profile')? 'active' : ''}}"> <i class="fa-solid fa-gear"></i>
                        <p> Settings <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- <li class="nav-item"> 
                            <a href="{{ url('admin/view_profile')}}" class="nav-link {{ Request::is('admin/view_profile') || Request::is('admin/edit_profile') ? 'active' : ''}}"> <i class="far fa-circle nav-icon"></i>
                                <p> Edit Profile </p>
                            </a> 
                        </li> --}}
                        <li class="nav-item"> 
                            <a href="{{ url('admin/change_password')}}" class="nav-link {{ Request::is('admin/change_password') ? 'active' : ''}}"> <i class="far fa-circle nav-icon"></i>
                                <p> Change Password </p>
                            </a> 
                        </li>
                        {{-- <li class="nav-item"> 
                            <a href="deactive_account.php" class="nav-link"> <i class="far fa-circle nav-icon"></i>
                                <p> Deactivate Account </p>
                            </a> 
                        </li> --}}
                    </ul>
                </li>
                <li class="nav-item"> 
                    <a href="{{ url('admin/language')}}" class="nav-link {{ Request::is('admin/language') || Request::is('admin/language/add') ? 'active' : ''}}"> <i class="fa-solid fa-language"></i>&nbsp;&nbsp;
                        <p>Languages</p>
                    </a> 
                </li>
                <li class="nav-item"> 
                    <a href="{{ url('admin/logout')}}" class="nav-link"> <i class="nav-icon fas fa-th"></i>
                        <p> Logout </p>
                    </a> 
                </li>
            </ul>
        </nav>
    </div>
</aside>