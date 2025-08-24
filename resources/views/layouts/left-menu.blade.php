<nav class="pcoded-navbar menupos-fixed menu-light ">
    <div class="navbar-wrapper  ">
        <div class="navbar-content scroll-div ">

            <ul class="nav pcoded-inner-navbar ">
                @canany(['Dashboard'])
                    <li class="nav-item {{ setActive(['dashboard']) }}">
                        <a href="{{ route('dashboard') }}" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                            <span class="pcoded-mtext">Dashboard</span>
                        </a>
                    </li>
                @endcanany

                @canany(['Complaints Index'])
                    <li class="nav-item {{ setActive(['complaints']) }}">
                        <a href="{{ route('complaints.index') }}" class="nav-link ">
                            <span class="pcoded-micon"><i class="fa fa-exclamation-triangle"></i></span>
                            <span class="pcoded-mtext">Complaints</span>
                        </a>
                    </li>
                @endcanany
                
                @canany(['Users Index', 'User Profile'])
                    <li class="nav-item {{ setActive(['users']) }}">
                        <a href="{{ route('users.index') }}" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                            <span class="pcoded-mtext">Users</span>
                        </a>
                    </li>
                @endcanany
                
                @canany(['Departments Index'])
                    <li class="nav-item {{ setActive(['departments']) }}">
                        <a href="{{ route('departments.index') }}" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                            <span class="pcoded-mtext">Departments</span>
                        </a>
                    </li>
                @endcanany
                
                @canany(['Categories Index'])
                    <li class="nav-item {{ setActive(['categories']) }}">
                        <a href="{{ route('categories.index') }}" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-sliders"></i></span>
                            <span class="pcoded-mtext">Categories</span>
                        </a>
                    </li>
                @endcanany
               
                @canany(['Sources Index'])
                    <li class="nav-item {{ setActive(['sources']) }}">
                        <a href="{{ route('sources.index') }}" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-sliders"></i></span>
                            <span class="pcoded-mtext">Sources</span>
                        </a>
                    </li>
                @endcanany

                @canany(['Pending Complaints', 'Resolved Complaints'])
                    <li class="nav-item pcoded-hasmenu {{ setActive(['reports']) }}">
                        <a href="#" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                            <span class="pcoded-mtext">Reports</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('Pending Complaints')
                                <li><a href="{{ route('reports.pendingComplaints') }}">Pending Complaints</a></li>
                            @endcan
                            @can('Resolved Complaints')
                                <li><a href="{{ route('reports.resolvedComplaints') }}">Resolved Complaints</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['Settings Index', 'Roles Index', 'Permissions Index', 'Permission Groups Index'])
                    <li class="nav-item pcoded-hasmenu {{ setActive(['settings', 'roles', 'permissions', 'permission-groups']) }}">
                        <a href="#" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-settings"></i></span>
                            <span class="pcoded-mtext">Settings</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('Settings Index')
                                <li><a href="{{ route('settings.index') }}">General Settings</a></li>
                            @endcan
                            @can('Roles Index')
                                <li><a href="{{ route('roles.index') }}">Roles</a></li>
                            @endcan
                            @can('Permissions Index')
                                <li><a href="{{ route('permissions.index') }}">Permissions</a></li>
                            @endcan
                            @can('Permission Groups Index')
                                <li><a href="{{ route('permission-groups.index') }}">Permission Groups</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>

        </div>
    </div>
</nav>
