<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-university"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Designsowl</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @hasrole('User')
        <!-- Requests -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#requestDropdown"
                aria-expanded="true" aria-controls="requestDropdown">
                <i class="fas fa-tags" aria-hidden="true"></i>
                <span>Requests</span>
            </a>
            <div id="requestDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('request.index') }}">All Requests</a>
                    <a class="collapse-item" href="{{ route('request.queue') }}">Queue Requests</a>
                    <a class="collapse-item" href="{{ route('request.delivered') }}">Delivered Requests</a>
                    <a class="collapse-item" href="{{ route('request.create') }}">Add Request</a>
                </div>
            </div>
        </li>

        <!-- Brand Profiles -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#brandDropdown"
                aria-expanded="true" aria-controls="brandDropdown">
                <i class="fas fa-chalkboard-teacher" aria-hidden="true"></i>
                <span>Brand Profiles</span>
            </a>
            <div id="brandDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('brand.index') }}">All Brands</a>
                    <a class="collapse-item" href="{{ route('brand.create') }}">Add Brand</a>
                </div>
            </div>
        </li>
    @endhasrole
    @hasrole('Admin')

        <!-- Heading -->
        <div class="sidebar-heading">
            General Settings
        </div>

        <!-- Nav Item - Request Types -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#requestTypesDropdown"
                aria-expanded="true" aria-controls="requestTypesDropdown">
                <i class="fas fa-user-alt"></i>
                <span>Request Types</span>
            </a>
            <div id="requestTypesDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('requesttypes.index') }}">List</a>
                    <a class="collapse-item" href="{{ route('requesttypes.create') }}">Add New</a>
                </div>
            </div>
        </li>
        
        <!-- Heading -->
        <div class="sidebar-heading">
            User Management
        </div>

        <!-- Nav Item - Users -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#usersDropdown"
                aria-expanded="true" aria-controls="usersDropdown">
                <i class="fas fa-user-alt"></i>
                <span>Users</span>
            </a>
            <div id="usersDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('users.index') }}">List</a>
                    <a class="collapse-item" href="{{ route('users.create') }}">Add New</a>
                    <a class="collapse-item" href="{{ route('users.import') }}">Import Data</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Masters</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Role & Permissions</h6>
                    <a class="collapse-item" href="{{ route('roles.index') }}">Roles</a>
                    <a class="collapse-item" href="{{ route('permissions.index') }}">Permissions</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
    @endhasrole

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>