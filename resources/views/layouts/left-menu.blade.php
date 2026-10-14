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

                @canany(['Land Revenue Matters Access', 'Revenue Matters Access'])
                    <li class="nav-item pcoded-hasmenu {{ setActive(['revenue-matters*']) }}">
                        <a href="#" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-briefcase"></i></span>
                            <span class="pcoded-mtext">Land Revenue Matters</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li><a href="{{ route('revenue-matters.dashboard') }}">Dashboard</a></li>
                            @canany(['Land Revenue Matters Create', 'Revenue Matters Create'])
                                <li><a href="{{ route('revenue-matters.complaints.create') }}">Lodge New Complaint</a></li>
                            @endcanany
                            <li><a href="{{ route('revenue-matters.complaints.index', ['tab' => 'assigned']) }}">Assigned</a></li>
                            <li><a href="{{ route('revenue-matters.complaints.index', ['tab' => 'forwarded']) }}">Forwarded</a></li>
                            <li><a href="{{ route('revenue-matters.complaints.search') }}">Search</a></li>
                            @canany(['Land Revenue Matters View All', 'Revenue Matters View All'])
                                <li>
                                    <a href="{{ route('revenue-matters.complaints.index', ['tab' => 'all']) }}">
                                        All Complaints
                                        @php
                                            $allRevenueCount = \App\Models\RevenueComplaint::count();
                                        @endphp
                                        @if($allRevenueCount > 0)
                                            <span class="pcoded-badge badge bg-primary">{{ $allRevenueCount }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endcanany
                        </ul>
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

                @canany(['Officer Contacts Index'])
                    <li class="nav-item {{ setActive(['officer-contacts']) }}">
                        <a href="{{ route('officer-contacts.index') }}" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-phone-call"></i></span>
                            <span class="pcoded-mtext">Officer Contacts</span>
                        </a>
                    </li>
                @endcanany

                @canany(['Summary', 'Pending Complaints', 'Resolved Complaints'])
                    <li class="nav-item pcoded-hasmenu {{ setActive(['reports']) }}">
                        <a href="#" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                            <span class="pcoded-mtext">Reports</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('Charts')
                                <li><a href="{{ route('reports.charts') }}">Charts</a></li>
                            @endcan
                            @can('Summary')
                                <li><a href="{{ route('reports.summary') }}">Summary</a></li>
                            @endcan
                            @can('Pending Complaints')
                                <li><a href="{{ route('reports.pendingComplaints') }}">Pending Complaints</a></li>
                            @endcan
                            @can('Resolved Complaints')
                                <li><a href="{{ route('reports.resolvedComplaints') }}">Resolved Complaints</a></li>
                            @endcan
                            @can('Complaints Download')
                            <li><a href="{{ route('reports.download') }}">Complaints Download</a></li>
                             @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['Settings Index', 'Roles Index', 'Permissions Index', 'Permission Groups Index', 'Categories Index', 'Departments Index', 'Sources Index'])
                    <li class="nav-item pcoded-hasmenu {{ setActive(['settings', 'roles', 'permissions', 'permission-groups']) }}">
                        <a href="#" class="nav-link ">
                            <span class="pcoded-micon"><i class="feather icon-settings"></i></span>
                            <span class="pcoded-mtext">Settings</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('Settings Index')
                                <li><a href="{{ route('settings.index') }}">General Settings</a></li>
                            @endcan
                            @can('Departments Index')
                                <li><a href="{{ route('departments.index') }}">Departments</a></li>
                            @endcan
                            @can('Categories Index')
                                <li><a href="{{ route('categories.index') }}">Categories</a></li>
                            @endcan
                            @can('Sources Index')
                                <li><a href="{{ route('sources.index') }}">Sources</a></li>
                            @endcan
                            @can('Contact Categories Index')
                                <li><a href="{{ route('contact-categories.index') }}">Contact Categories</a></li>
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
