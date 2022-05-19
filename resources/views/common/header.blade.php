<div class="bg-white border-bottom mb-5">
    <nav class="navbar navbar-expand navbar-light topbar static-top">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 close-sidebar">
            <i class="fa fa-bars"></i>
            <i class="fa fa-times"></i>
        </button>

        <!-- Topbar Search -->
        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="{{ route('search') }}" method="get">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button class="btn btn-primary bg-light small border border-right-0 text-dark" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
                <input type="text" name="keyword" class="form-control bg-light small border border-left-0" placeholder="Search requests..."
                    aria-label="Search" aria-describedby="basic-addon2">
            </div>
        </form>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            
            <!-- Nav Item - User Information -->
            <li class="nav-item d-flex align-items-center">
                <img class="img-profile rounded-circle"
                        src="{{asset('images/bell.svg')}}">
            </li>
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="img-profile rounded-circle"
                        src="{{asset('admin/img/undraw_profile.svg')}}">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in py-0"
                    aria-labelledby="userDropdown">
                    <span class="dropdown-item mr-2 py-3 bg-primary border-bottom text-light rounded-top">
                        <span class="d-block">{{ Auth::user()->fullname }}</span>
                        <span class="img-limit text-light">{{ Auth::user()->email }}</span>
                    </span>
                    <a class="dropdown-item py-2" href="{{ route('profile.detail') }}">
                        <i class="fas fa-cog fa-fw mr-2 text-dark" aria-hidden="true"></i>
                        Settings
                    </a>
                    <a class="dropdown-item py-2" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-arrow-circle-right fa-fw mr-2 text-dark" aria-hidden="true"></i>
                        Log out
                    </a>
                </div>
            </li>

        </ul>

    </nav>
    
    <div class="d-md-none px-3 pb-2">
        <!-- Topbar Search -->
        <form class="form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="{{ route('search') }}" method="get">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button class="btn btn-primary bg-light small border border-right-0 text-dark" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
                <input type="text" name="keyword" class="form-control bg-light small border border-left-0" placeholder="Search requests..."
                    aria-label="Search" aria-describedby="basic-addon2">
            </div>
        </form>
    </div>
</div>