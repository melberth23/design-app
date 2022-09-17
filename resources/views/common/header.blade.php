<div class="bg-white border-bottom">
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
                <input type="text" name="keyword" value="{{ !empty(Request::get('keyword'))?Request::get('keyword'):'' }}" class="form-control bg-light small border border-left-0" placeholder="Search requests..."
                    aria-label="Search" aria-describedby="basic-addon2">
            </div>
        </form>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <?php 
                $notifications = (new \App\Lib\SystemHelper)->getNotifications();
            ?>
            <!-- Nav Item - User Information -->
            <li class="nav-item d-flex align-items-center">
                <div class="dropdown m-0">
                    <button class="btn btn-link p-0" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="img-profile rounded-circle" src="{{asset('images/bell.svg')}}"><span class="counter counter-lg bg-primary">{{ $notifications['counter'] }}</span>
                    </button>
                    @if(!empty($notifications['lists']))
                        <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="notificationDropdown">
                        @foreach($notifications['lists'] as $list)
                            <a class="dropdown-item" href="{{ route('request.view', ['requests' => $list['request_id']]) }}"><?php echo $list['text_message']; ?></a>
                        @endforeach
                        </div>
                    @endif
                </div>
            </li>
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(!empty(auth()->user()->profile_img))
                        <img class="img-profile rounded-circle" src="{{ Storage::disk('s3')->url(auth()->user()->profile_img) }}" id="profile-top-image">
                    @else
                        <img class="img-profile rounded-circle" src="{{asset('admin/img/undraw_profile.svg')}}">
                    @endif
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
                    <a class="dropdown-item py-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-arrow-circle-right fa-fw mr-2 text-dark" aria-hidden="true"></i>
                        Log out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
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

@if(auth()->user()->hasRole('Admin'))
    @if((str_contains(url()->current(), 'admin/subscribers')))
    <div class="slide-actions">
        @if(!empty($view))
        <!-- Filter -->
        <div id="filter-actions" class="side-container">
            <div class="filter-content">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav('filter-actions')">&times;</a>
                <div class="head-slide">
                    <h5 class="text-dark font-weight-bold">Filter</h5>
                </div>
                <hr>
                <div class="content-slide">
                    <form method="get" >
                        <ul class="d-none d-md-flex navbar-nav sidebar" id="accordionSidebar">
                            <li class="nav-item">
                                <a class="nav-link text-dark collapsed" href="#" data-toggle="collapse" data-target="#dateDropdown"
                                    aria-expanded="true" aria-controls="dateDropdown">
                                    <span>Date account created</span>
                                </a>
                                <div id="dateDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                                    <div class="bg-white py-2 collapse-inner">
                                       <label class="d-block"><input type="checkbox" name="date[]" value="3today" <?php echo (in_array('3today', $filterdate))?'checked="checked"':''; ?>> Today</label>
                                       <label class="d-block"><input type="checkbox" name="date[]" value="2yesterday" <?php echo (in_array('2yesterday', $filterdate))?'checked="checked"':''; ?>> Yesterday</label>
                                       <label class="d-block"><input type="checkbox" name="date[]" value="1last7" <?php echo (in_array('1last7', $filterdate))?'checked="checked"':''; ?>> Last 7 days</label>
                                       <label class="d-block"><input type="checkbox" name="date[]" value="4thismonth" <?php echo (in_array('4thismonth', $filterdate))?'checked="checked"':''; ?>> This month</label>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark collapsed" href="#" data-toggle="collapse" data-target="#PlantypeDropdown"
                                    aria-expanded="true" aria-controls="PlantypeDropdown">
                                    <span>Plan type</span>
                                </a>
                                <div id="PlantypeDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                                    <div class="bg-white py-2 collapse-inner">
                                       <label class="d-block"><input type="checkbox" name="plantype[]" value="basic" <?php echo (in_array('basic', $filterplantype))?'checked="checked"':''; ?>> Basic</label>
                                       <label class="d-block"><input type="checkbox" name="plantype[]" value="premium" <?php echo (in_array('premium', $filterplantype))?'checked="checked"':''; ?>> Premium</label>
                                       <label class="d-block"><input type="checkbox" name="plantype[]" value="royal" <?php echo (in_array('royal', $filterplantype))?'checked="checked"':''; ?>> Enterprise</label>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark collapsed" href="#" data-toggle="collapse" data-target="#PlanstatusDropdown"
                                    aria-expanded="true" aria-controls="PlanstatusDropdown">
                                    <span>Plan status</span>
                                </a>
                                <div id="PlanstatusDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                                    <div class="bg-white py-2 collapse-inner">
                                       <label class="d-block"><input type="checkbox" name="planstatus[]" value="active" <?php echo (in_array('active', $filterplanstatus))?'checked="checked"':''; ?>> Active</label>
                                       <label class="d-block"><input type="checkbox" name="planstatus[]" value="expired" <?php echo (in_array('expired', $filterplanstatus))?'checked="checked"':''; ?>> Expired</label>
                                       <label class="d-block"><input type="checkbox" name="planstatus[]" value="cancelled" <?php echo (in_array('cancelled', $filterplanstatus))?'checked="checked"':''; ?>> Cancelled</label>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item action-apply">
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <?php 
            $sub_id = 0;
            if(!empty($perid)) {
                $sub_id = $perid;
            }
            $activities = (new \App\Lib\SystemHelper)->getActivities($sub_id);
        ?>
        <!-- Activities -->
        <div id="activity-actions" class="side-container">
            <div class="filter-content">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav('activity-actions')">&times;</a>
                <div class="head-slide">
                    <h5 class="text-dark font-weight-bold">Activity</h5>
                    <select id="activity-filter" class="form-control" data-subid="{{ $sub_id }}">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="last7">Last 7 days</option>
                        <option value="thismonth">This Month</option>
                    </select>
                </div>
                <hr>
                <!-- Activity Lists -->
                <div id="activities-lists">
                    @if($activities->count() > 0)
                        @foreach($activities as $activity)
                           <div class="px-4">
                                <span>{{ $activity->activity_note }}</span>
                            </div>
                            <hr>
                        @endforeach
                    @else
                        <div class="px-4">
                            <span>No Activities</span>
                        </div>
                        <hr>
                    @endif
                </div>
            </div>
        </div>

        @if((str_contains(url()->current(), 'admin/subscribers/view')))
            <!-- Notes -->
            <div id="notes-actions" class="side-container">
                <div class="filter-content">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav('notes-actions')">&times;</a>
                    <div class="head-slide">
                        <h5 class="text-dark font-weight-bold">Notes</h5>
                    </div>
                    <hr>
                    <!-- Activity Lists -->
                    <div id="notes-lists">
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif
@endif

<div class="mb-5"></div>