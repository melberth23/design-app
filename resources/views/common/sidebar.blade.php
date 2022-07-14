<ul class="d-none d-md-flex navbar-nav bg-white sidebar" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center border-bottom" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/logo-dark.svg') }}" class="">
    </a>

    @if(str_contains(url()->current(), 'profile'))

        <li class="nav-item">
            <a class="btn btn-primary m-3 shadow menu-btn-link" href="{{ route('dashboard') }}">
                <i class="fas fa-arrow-left fa-sm"></i>
                <span>Back</span>
            </a>
        </li>

        <li class="nav-item {{ (request()->is('profile')) ? 'active' : '' }}">
            <a class="nav-link text-dark" href="{{ route('profile.detail') }}">
                <img src="{{ asset('images/baseline-account_circle-24px.svg') }}" class="menu-icons">
                <span>Account Information</span></a>
        </li>

        <li class="nav-item {{ (request()->is('profile/security')) ? 'active' : '' }}">
            <a class="nav-link text-dark" href="{{ route('profile.security') }}">
                <img src="{{ asset('images/baseline-https-24px.svg') }}" class="menu-icons">
                <span>Security and Log in</span></a>
        </li>
        @hasrole('User')
            @if(auth()->user()->payments->status == 'active' && auth()->user()->payments->plan_status == 0)
            <li class="nav-item {{ (request()->is('profile/upgrade')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('profile.upgrade') }}">
                    <img src="{{ asset('images/upgrade.svg') }}" class="menu-icons">
                    <span>{{ (auth()->user()->payments->plan=='royal')?'Upgraded':'Upgrade' }}</span></a>
            </li>
            @elseif(auth()->user()->payments->status == 'scheduled')
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ auth()->user()->payments->payment_url }}">
                    <img src="{{ asset('images/upgrade.svg') }}" class="menu-icons">
                    <span>Pay</span></a>
            </li>
            @else
            <li class="nav-item {{ (request()->is('profile/subscription')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('profile.subscription') }}">
                    <img src="{{ asset('images/upgrade.svg') }}" class="menu-icons">
                    <span>Select Plan</span></a>
            </li>
            @endif

            <li class="nav-item {{ (request()->is('profile/invoices')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('profile.invoices') }}">
                    <img src="{{ asset('images/Invoices.svg') }}" class="menu-icons">
                    <span>Invoices</span></a>
            </li>

            <li class="nav-item {{ (request()->is('profile/paymentmethods')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('profile.paymentmethods') }}">
                    <img src="{{ asset('images/baseline-payment-24px.svg') }}" class="menu-icons">
                    <span>Payment Method</span></a>
            </li>

            <li class="nav-item {{ (request()->is('profile/notifications')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('profile.notifications') }}">
                    <img src="{{ asset('images/notifications.svg') }}" class="menu-icons">
                    <span>Notifications</span></a>
            </li>
        @endhasrole

    @else

        @hasrole('User')
        <li class="nav-item">
            <a class="btn btn-primary d-block m-3 shadow menu-btn-link" href="{{ route('request.create') }}">
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
            <li class="nav-item {{ (request()->is('requests')) ? 'active' : '' }} {{ (request()->is('requests/queue')) ? 'active' : '' }} {{ (request()->is('requests/progress')) ? 'active' : '' }} {{ (request()->is('requests/review')) ? 'active' : '' }} {{ (request()->is('requests/delivered')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('request.index') }}">
                    <img src="{{ asset('images/myrequest.svg') }}" class="menu-icons">
                    <span>My requests</span>
                </a>
            </li>

            <li class="nav-item {{ (request()->is('requests/draft')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('request.draft') }}">
                    <img src="{{ asset('images/draft.svg') }}" class="menu-icons">
                    <span>Saved drafts</span>
                </a>
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
                    <i class="fa fa-tags"></i>
                    <span>On Queue</span>
                </a>
            </li>

            <li class="nav-item {{ (request()->is('designers/progress')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('designer.progress') }}">
                    <i class="fa fa-tags"></i>
                    <span>In Progress</span>
                </a>
            </li>

            <li class="nav-item {{ (request()->is('designers/review')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('designer.review') }}">
                    <i class="fa fa-tags"></i>
                    <span>For Review</span>
                </a>
            </li>

            <li class="nav-item {{ (request()->is('designers/completed')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('designer.completed') }}">
                    <i class="fa fa-tags"></i>
                    <span>Completed</span>
                </a>
            </li>
            
        @endhasrole

        @hasrole('Admin')

            <!-- Subscribers -->
            <li class="nav-item {{ (request()->is('admin/subscribers')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('subscribers.index') }}">
                    <img src="{{ asset('images/subscribers.svg') }}" class="menu-icons">
                    <span>Subscribers</span>
                </a>
            </li>

            <!-- Requests -->
            <li class="nav-item {{ (request()->is('admin/requests')) ? 'active' : '' }} {{ (request()->is('admin/requests/queue')) ? 'active' : '' }} {{ (request()->is('admin/requests/review')) ? 'active' : '' }} {{ (request()->is('admin/requests/delivered')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('adminrequest.index') }}">
                    <img src="{{ asset('images/myrequest.svg') }}" class="menu-icons">
                    <span>Requests</span>
                </a>
            </li>

            <!-- Brand Profiles -->
            <li class="nav-item {{ (request()->is('admin/brand')) ? 'active' : '' }}">
                <a class="nav-link text-dark" href="{{ route('adminbrand.index') }}">
                    <img src="{{ asset('images/brand-icon.svg') }}" class="menu-icons">
                    <span>Brand Profiles</span>
                </a>
            </li>

            <!-- Nav Item - Payments -->
            <li class="nav-item {{ (request()->is('admin/payments')) ? 'active' : '' }} {{ (request()->is('admin/payments/pending')) ? 'active' : '' }} {{ (request()->is('admin/payments/completed')) ? 'active' : '' }}">
                <a class="nav-link text-dark {{ (request()->is('admin/payments')) ? '' : 'collapsed' }} {{ (request()->is('admin/payments/pending')) ? '' : 'collapsed' }} {{ (request()->is('admin/payments/completed')) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#paymentsDropdown"
                    aria-expanded="true" aria-controls="paymentsDropdown">
                    <img src="{{ asset('images/payments.svg') }}" class="menu-icons">
                    <span>Payment History</span>
                </a>
                <div id="paymentsDropdown" class="{{ (request()->is('admin/payments')) ? 'collapsed collapse show' : 'collapse' }} {{ (request()->is('admin/payments/pending')) ? 'collapsed collapse show' : 'collapse' }} {{ (request()->is('admin/payments/completed')) ? 'collapsed collapse show' : 'collapse' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ (request()->is('admin/payments')) ? 'active' : '' }}" href="{{ route('adminpayment.index') }}">All Payments</a>
                        <a class="collapse-item {{ (request()->is('admin/payments/pending')) ? 'active' : '' }}" href="{{ route('adminpayment.pending') }}">Pending Payments</a>
                        <a class="collapse-item {{ (request()->is('admin/payments/completed')) ? 'active' : '' }}" href="{{ route('adminpayment.completed') }}">Completed Payments</a>
                    </div>
                </div>
            </li>
            
            <!-- Nav Item - Users -->
            <li class="nav-item {{ (request()->is('admin/users')) ? 'active' : '' }} {{ (request()->is('admin/users/create')) ? 'active' : '' }}">
                <a class="nav-link text-dark {{ (request()->is('admin/users')) ? '' : 'collapsed' }} {{ (request()->is('admin/users/create')) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#usersDropdown"
                    aria-expanded="true" aria-controls="usersDropdown">
                    <img src="{{ asset('images/user-lists.svg') }}" class="menu-icons">
                    <span>Users</span>
                </a>
                <div id="usersDropdown" class="{{ (request()->is('admin/users')) ? 'collapsed collapse show' : 'collapse' }} {{ (request()->is('admin/users/create')) ? 'collapsed collapse show' : 'collapse' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ (request()->is('admin/users')) ? 'active' : '' }}" href="{{ route('users.index') }}">List</a>
                        <a class="collapse-item {{ (request()->is('admin/users/create')) ? 'active' : '' }}" href="{{ route('users.create') }}">Add New</a>
                        <!-- <a class="collapse-item" href="{{ route('users.import') }}">Import Data</a> -->
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ (request()->is('roles')) ? 'active' : '' }} {{ (request()->is('permissions')) ? 'active' : '' }}">
                <a class="nav-link text-dark {{ (request()->is('roles')) ? '' : 'collapsed' }} {{ (request()->is('permissions')) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Masters</span>
                </a>
                <div id="collapsePages" class="{{ (request()->is('roles')) ? 'collapsed collapse show' : 'collapse' }} {{ (request()->is('permissions')) ? 'collapsed collapse show' : 'collapse' }} {{ (request()->is('admin/requesttypes')) ? 'collapsed collapse show' : 'collapse' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header text-dark">Role & Permissions</h6>
                        <a class="collapse-item {{ (request()->is('roles')) ? 'active' : '' }}" href="{{ route('roles.index') }}">Roles</a>
                        <a class="collapse-item {{ (request()->is('permissions')) ? 'active' : '' }}" href="{{ route('permissions.index') }}">Permissions</a>
                    </div>
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header text-dark">Others</h6>
                        <a class="collapse-item {{ (request()->is('admin/requesttypes')) ? 'active' : '' }}" href="{{ route('requesttypes.index') }}">List</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
        @endhasrole

    @endif

</ul>