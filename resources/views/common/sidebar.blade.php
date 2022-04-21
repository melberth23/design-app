<ul class="navbar-nav bg-white sidebar" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center border-bottom" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/logo-dark.svg') }}" class="">
    </a>

    @hasrole('User')
    <li class="nav-item">
        <a class="btn btn-primary d-block m-3 shadow" href="{{ route('request.create') }}">
            <i class="fas fa-plus"></i>
            <span>New Request</span>
        </a>
    </li>
    @endhasrole

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
        <a class="nav-link text-dark" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/dashboardicon.svg') }}" class="menu-icons">
            <span>Dashboard</span></a>
    </li>

    @hasrole('User')
        <!-- Brand Profiles -->
        <li class="nav-item {{ (request()->is('brands')) ? 'active' : '' }}">
            <a class="nav-link text-dark" href="{{ route('brand.index') }}">
                <img src="{{ asset('images/brand-icon.svg') }}" class="menu-icons">
                <span>My brand profiles</span>
            </a>
        </li>

        <!-- Requests -->
        <li class="nav-item {{ (request()->is('requests')) ? 'active' : '' }} {{ (request()->is('requests/queue')) ? 'active' : '' }} {{ (request()->is('requests/review')) ? 'active' : '' }} {{ (request()->is('requests/delivered')) ? 'active' : '' }}">
            <a class="nav-link text-dark {{ (request()->is('requests')) ? '' : 'collapsed' }} {{ (request()->is('requests/queue')) ? '' : 'collapsed' }} {{ (request()->is('requests/review')) ? '' : 'collapsed' }} {{ (request()->is('requests/delivered')) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#requestDropdown"
                aria-expanded="true" aria-controls="requestDropdown">
                <img src="{{ asset('images/myrequest.svg') }}" class="menu-icons">
                <span>My requests</span>
            </a>
            <div id="requestDropdown" class="{{ (request()->is('requests')) ? 'collapsed collapse show' : 'collapse' }} {{ (request()->is('requests/queue')) ? 'collapsed collapse show' : 'collapse' }} {{ (request()->is('requests/review')) ? 'collapsed collapse show' : 'collapse' }} {{ (request()->is('requests/delivered')) ? 'collapsed collapse show' : 'collapse' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ (request()->is('requests')) ? 'active' : '' }}" href="{{ route('request.index') }}">All Requests</a>
                    <a class="collapse-item {{ (request()->is('requests/queue')) ? 'active' : '' }}" href="{{ route('request.queue') }}">Queue Requests</a>
                    <a class="collapse-item {{ (request()->is('requests/review')) ? 'active' : '' }}" href="{{ route('request.review') }}">Review Requests</a>
                    <a class="collapse-item {{ (request()->is('requests/delivered')) ? 'active' : '' }}" href="{{ route('request.delivered') }}">Completed Requests</a>
                </div>
            </div>
        </li>
    @endhasrole

    @hasrole('Designer')

        <li class="nav-item {{ (request()->is('designers')) ? 'active' : '' }}">
            <a class="nav-link text-dark" href="{{ route('designer.index') }}">
                <i class="fa fa-tags"></i>
                <span>All Requests</span>
            </a>
        </li>

        <li class="nav-item {{ (request()->is('designers/queue')) ? 'active' : '' }}">
            <a class="nav-link text-dark" href="{{ route('designer.queue') }}">
                <i class="fa fa-list"></i>
                <span>Queue Requests</span>
            </a>
        </li>

        <li class="nav-item {{ (request()->is('designers/review')) ? 'active' : '' }}">
            <a class="nav-link text-dark" href="{{ route('designer.review') }}">
                <i class="fa fa-check-square-o"></i>
                <span>Review Requests</span>
            </a>
        </li>

        <li class="nav-item {{ (request()->is('designers/completed')) ? 'active' : '' }}">
            <a class="nav-link text-dark" href="{{ route('designer.completed') }}">
                <i class="fa fa-cloud-upload"></i>
                <span>Completed Requests</span>
            </a>
        </li>
        
    @endhasrole

    @hasrole('Admin')

        <!-- Nav Item - Request Types -->
        <li class="nav-item">
            <a class="nav-link text-dark collapsed" href="#" data-toggle="collapse" data-target="#requestTypesDropdown"
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
        
        <!-- Nav Item - Users -->
        <li class="nav-item">
            <a class="nav-link text-dark collapsed" href="#" data-toggle="collapse" data-target="#usersDropdown"
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
            <a class="nav-link text-dark collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Masters</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header text-dark">Role & Permissions</h6>
                    <a class="collapse-item" href="{{ route('roles.index') }}">Roles</a>
                    <a class="collapse-item" href="{{ route('permissions.index') }}">Permissions</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
    @endhasrole

</ul>