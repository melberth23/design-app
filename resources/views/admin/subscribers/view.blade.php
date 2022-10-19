@extends('layouts.app')

@section('title', 'View Brand')

@section('content')

<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{ route('subscribers.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">{{ $subscriber->first_name }} {{ $subscriber->last_name }}</span> 

            @if ($subscriber->payments->status == 'cancelled')
                <span class="badge badge-warning">Cancelled</span>
            @elseif ($subscriber->payments->status == 'active')
                <span class="badge badge-success">Active</span>
            @elseif ($subscriber->payments->status == 'expired')
                <span class="badge badge-danger">Expired</span>
            @endif
        </h1>
        <div class="actions d-flex align-items-center justify-content-end">
            <!-- <button class="btn btn-outline-light text-dark border m-1" type="button" >
                <i class="fa fa-file-text" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Notes</span>
            </button> -->
            <button class="btn btn-outline-light text-dark border m-1" type="button" onclick="openNav('activity-actions');">
                <i class="fa fa-list-ul" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Activities</span>
            </button>
            <!-- <button class="btn btn-primary m-1" type="button" >
                <i class="fa fa-plus" aria-hidden="true"></i> <span class="d-none d-md-inline-block">New</span>
            </button> -->
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-center">
                        @if(!empty($subscriber->profile_img))
                            <img class="rounded-circle" width="80px" src="{{ Storage::disk('s3')->url($subscriber->profile_img) }}">
                        @else
                            <img class="rounded-circle" width="80px" src="{{ asset('admin/img/undraw_profile.svg') }}">
                        @endif
                    </div>
                    <div class="text-center mt-2">
                        <h3 class="customer-name">{{ $subscriber->first_name }} {{ $subscriber->last_name }}</h3>
                        <div class="customer-id">Customer ID: DS{{ $subscriber->id }}</div>
                        <div class="customer-plan">
                            @if ($subscriber->payments->plan == 'basic')
                                <span class="badge badge-info">Basic</span>
                            @elseif ($subscriber->payments->plan == 'premium')
                                <span class="badge badge-primary">Premium</span>
                            @elseif ($subscriber->payments->plan == 'royal')
                                <span class="badge badge-warning">Enterprise</span>
                            @endif
                        </div>
                        <div class="d-sm-flex align-items-center justify-content-center customer-contact my-4">
                            <ul>
                                <li><a href="tel:{{ $subscriber->mobile_number }}" class="contact-icon rounded-circle"><i class="fa fa-phone" aria-hidden="true"></i></a></li>
                                <li><a href="mailto:{{ $subscriber->email }}" class="contact-icon rounded-circle"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                                <li><a href="#" class="contact-icon rounded-circle"><i class="fa fa-video-camera" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-center customer-account mt-3">
                        <a href="{{ route('subscribers.accountsettings', ['subscriber' => $subscriber->id]) }}" class="btn btn-primary m-1">Customer Account Settings</a>
                    </div>
                    <hr>
                    <div class="d-sm-flex align-items-center justify-content-between mt-2">
                        <span>Member Since</span>
                        <span class="font-weight-bold">{{ $subscriber->created_at->format('F d, Y') }}</span>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mt-3">
                        <span>Subscribe Since</span>
                        <span class="font-weight-bold">{{ $subscriber->firstpayments->created_at->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="card my-4">
                <div class="card-header bg-light-custom">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-dark mb-0 font-weight-bold">Requests</span>
                        <select class="form-control width-40percent">
                            <option value="">All time</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-between mt-3">
                        <span>Total Requests</span>
                        <span class="font-weight-bold">{{ $requests->count() }}</span>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mt-3">
                        <span>On Queue Requests</span>
                        <span class="font-weight-bold">{{ $queue->count() }}</span>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mt-3">
                        <span>In Progress Requests</span>
                        <span class="font-weight-bold">{{ $progress->count() }}</span>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mt-3">
                        <span>For Review Requests</span>
                        <span class="font-weight-bold">{{ $review->count() }}</span>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mt-3">
                        <span>Completed Requests</span>
                        <span class="font-weight-bold">{{ $completed->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="card mb-4">
                <div class="card-body py-0 px-1">
                    <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link py-3 active" id="account-profile-tab" data-toggle="tab" href="#profile-tab" role="tab" aria-controls="profile-tab" aria-selected="true" data-area="account-profile">Account Information</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link py-3" id="account-requests-tab" data-toggle="tab" href="#requests-tab" role="tab" aria-controls="requests-tab" aria-selected="false" data-area="account-profile">Requests {{ $requests->count() }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link py-3" id="account-brands-tab" data-toggle="tab" href="#brands-tab" role="tab" aria-controls="brands-tab" aria-selected="false" data-area="account-profile">Brand Profile {{ $brands->count() }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link py-3" id="account-subscription-tab" data-toggle="tab" href="#subscription-tab" role="tab" aria-controls="subscription-tab" aria-selected="false" data-area="account-profile">Subscription</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link py-3" id="account-transaction-tab" data-toggle="tab" href="#transaction-tab" role="tab" aria-controls="transaction-tab" aria-selected="false" data-area="account-profile">Transaction</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="account-tab-contents">
                <div class="tab-pane fade show active" id="profile-tab" role="tabpanel" aria-labelledby="account-profile-tab">
                    <div class="admin-subscriber-account card mb-4">
                        <div class="card-header bg-light-custom py-4">
                            <h3 class="text-dark mb-0">Profile Information</h3>
                        </div>
                        <div class="card-body py-0">
                            <div class="tab-text-label text-dark py-3 border-bottom">
                                <div class="row">
                                    <div class="col-md-3 single-label">Full Name</div>
                                    <div class="col-md-9">
                                        <span>{{ $subscriber->full_name }}</span>
                                        <span class="float-right text-primary">
                                            <a href="#" data-toggle="modal" data-target="#fullnameModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-text-label text-dark py-3 border-bottom">
                                <div class="row">
                                    <div class="col-md-3 single-label">Email Address</div>
                                    <div class="col-md-6">{{ $subscriber->email }}</div>
                                    <div class="col-md-3">
                                        @if($subscriber->is_email_verified)
                                            <i class="fa fa-check-circle text-success" aria-hidden="true"></i> Verified
                                        @endif
                                        <span class="float-right text-primary">
                                            <a href="#" data-toggle="modal" data-target="#emailModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-text-label text-dark py-3 border-bottom">
                                <div class="row">
                                    <div class="col-md-3 single-label">Phone Number</div>
                                    <div class="col-md-6">{{ $subscriber->mobile_number }}</div>
                                    <div class="col-md-3">
                                        @if($subscriber->is_phone_verified)
                                            <i class="fa fa-check-circle text-success" aria-hidden="true"></i> Verified
                                        @endif
                                        <span class="float-right text-primary">
                                            <a href="#" data-toggle="modal" data-target="#phoneModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-text-label text-dark py-3 border-bottom">
                                <div class="row">
                                    <div class="col-md-3 single-label">Address</div>
                                    <div class="col-md-9">
                                        @if(!empty($subscriber->address_1))
                                            <span>
                                                {{ $subscriber->address_1 }} {{ $subscriber->address_2 }}, {{ $subscriber->city }} {{ $subscriber->state }}, {{ $subscriber->zip }} {{ $subscriber->country }}
                                            </span>
                                        @else
                                            <em>- No details found</em>
                                        @endif
                                        <span class="float-right text-primary">
                                            <a href="#" data-toggle="modal" data-target="#addressModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-text-label text-dark py-3">
                                <div class="row">
                                    <div class="col-md-3 single-label">Timezone</div>
                                    <div class="col-md-9">
                                        @if(!empty((new \App\Lib\SystemHelper)->getUserSetting($subscriber->id, 'time_zone')))
                                            <span>{{ (new \App\Lib\SystemHelper)->getUserSetting($subscriber->id, 'time_zone') }}</span>
                                        @else
                                            <em>- No details found</em>
                                        @endif
                                        <span class="float-right text-primary">
                                            <a href="#" data-toggle="modal" data-target="#timezoneModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="admin-subscriber-account card mb-4">
                        <div class="card-header bg-light-custom py-4">
                            <h3 class="text-dark mb-0">Global Preferences</h3>
                        </div>
                        <div class="card-body py-0">
                            <div class="tab-text-label text-dark py-3">
                                <div class="row">
                                    <div class="col-md-3 single-label">Language</div>
                                    <div class="col-md-9">
                                        @if(!empty((new \App\Lib\SystemHelper)->getLanguage($subscriber->id)))
                                            <span>{{ (new \App\Lib\SystemHelper)->getLanguage($subscriber->id) }}</span>
                                        @else
                                            <span>English</span>
                                        @endif
                                        <span class="float-right text-primary">
                                            <a href="#" data-toggle="modal" data-target="#languageModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="admin-subscriber-account card mb-4">
                        <div class="card-header bg-light-custom py-4">
                            <h3 class="text-dark mb-0">Danger Zone</h3>
                        </div>
                        <div class="card-body py-0">
                            <div class="tab-text-label text-dark py-3 border-bottom">
                                <div class="row">
                                    <div class="col-md-3 single-label">Member Since</div>
                                    <div class="col-md-9">
                                        <span>{{ $subscriber->created_at->format('F d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-text-label text-dark pt-3">
                                <div class="row">
                                    <div class="col-md-12 single-label text-dark"><strong>Delete Account</strong></div>
                                </div>
                            </div>
                            <div class="tab-text-label text-dark pb-3">
                                <div class="row">
                                    <div class="col-md-9 single-label">Keep in mind that upon deleting your account all of your account information will be deleted without the posibility of restoration.</div>
                                    <div class="col-md-3 text-right">
                                        <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#deleteAccountModal" >Delete Account</a>
                                    </div>
                                </div>
                            </div>
                            @if($subscriber->manual == 1)
                            <div class="tab-text-label text-dark pt-3">
                                <div class="row">
                                    <div class="col-md-12 single-label text-dark"><strong>{{ !empty($subscriber->status)?"Hold":"Renew" }} Account</strong></div>
                                </div>
                            </div>
                            <div class="tab-text-label text-dark pb-3">
                                <div class="row">
                                    <div class="col-md-9 single-label">Admin can make control this account manually.</div>
                                    <div class="col-md-3 text-right">
                                        @if ($subscriber->status == 0)
                                            <a href="{{ route('users.status', ['user_id' => $subscriber->id, 'status' => 1]) }}"
                                                class="btn btn-success m-2">
                                                <i class="fa fa-check"></i> Renew
                                            </a>
                                        @elseif ($subscriber->status == 1)
                                            <a href="{{ route('users.status', ['user_id' => $subscriber->id, 'status' => 0]) }}"
                                                class="btn btn-danger m-2">
                                                <i class="fa fa-ban"></i> Hold
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="requests-tab" role="tabpanel" aria-labelledby="account-requests-tab">
                    <h5 class="text-dark pb-2 font-weight-bold">Requests</h5>
                    <div class="card">
                        <div class="card-body py-0 px-1">
                            <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                                <li class="nav-item border-bottom">
                                    <a class="nav-link py-3 active" id="account-allrequests-tab" data-toggle="tab" href="#allrequests-tab" role="tab" aria-controls="profile-tab" aria-selected="true" data-area="account-profile">All {{ $requests->count() }}</a>
                                </li>
                                <li class="nav-item border-bottom">
                                    <a class="nav-link py-3" id="account-queue-tab" data-toggle="tab" href="#queue-tab" role="tab" aria-controls="profile-tab" aria-selected="true" data-area="account-profile">Queue {{ $queue->count() }}</a>
                                </li>
                                <li class="nav-item border-bottom">
                                    <a class="nav-link py-3" id="account-progress-tab" data-toggle="tab" href="#progress-tab" role="tab" aria-controls="profile-tab" aria-selected="true" data-area="account-profile">Progress {{ $progress->count() }}</a>
                                </li>
                                <li class="nav-item border-bottom">
                                    <a class="nav-link py-3" id="account-review-tab" data-toggle="tab" href="#review-tab" role="tab" aria-controls="profile-tab" aria-selected="true" data-area="account-profile">Review {{ $review->count() }}</a>
                                </li>
                                <li class="nav-item border-bottom">
                                    <a class="nav-link py-3" id="account-delivered-tab" data-toggle="tab" href="#delivered-tab" role="tab" aria-controls="profile-tab" aria-selected="true" data-area="account-profile">Completed {{ $completed->count() }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-light-custom p-0">
                            <div class="tab-content" id="account-tab-contents">
                                <div class="tab-pane fade show active" id="allrequests-tab" role="tabpanel" aria-labelledby="account-allrequests-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th width="10%" class="border-left-0 border-right-0">REQUEST ID</th>
                                                    <th width="25%" class="border-left-0 border-right-0">REQUEST NAME</th>
                                                    <th width="25%" class="border-left-0 border-right-0">CATEGORY</th>
                                                    <th width="10%" class="border-left-0 border-right-0">STATUS</th>
                                                    <th width="20%" class="border-left-0 border-right-0">DATE CREATED</th>
                                                    <th width="10%" class="border-left-0 border-right-0"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($requests as $request)
                                                    <tr>
                                                        <td class="border-left-0 border-right-0 text-primary">#{{ $request->id }}</td>
                                                        <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('request.view', ['requests' => $request->id]) }}">{{ $request->title }}</a></td>
                                                        @if(!empty($request->designtype->name))
                                                        <td class="border-left-0 border-right-0">{{ $request->designtype->name }}</td>
                                                        @else
                                                        <td class="border-left-0 border-right-0"></td>
                                                        @endif
                                                        <td class="border-left-0 border-right-0">
                                                            @if ($request->status == 0)
                                                                <span class="badge badge-primary">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                                            @elseif ($request->status == 1)
                                                                <span class="badge badge-warning">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                                            @elseif ($request->status == 2)
                                                                <span class="badge badge-info">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                                            @elseif ($request->status == 3)
                                                                <span class="badge badge-success">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                                            @elseif ($request->status == 4)
                                                                <span class="badge badge-dark">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="border-left-0 border-right-0">{{ $request->created_at->format('d F, Y') }}</td>
                                                        <td class="d-flex justify-content-end border-left-0 border-right-0">
                                                            <div class="dropdown mx-2">
                                                                <a href="javascript:void(0);"class="text-dark" id="dropdownUpdate{{ $request->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="dropdownUpdate{{ $request->id }}">
                                                                    <span>Last Updated: {{ $request->updated_at->format('d F, Y, h:i:s A') }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <a href="{{ route('request.view', ['requests' => $request->id]) }}"
                                                                class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="View Request">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <div class="dropdown mx-2">
                                                                <button class="text-dark btn btn-link border-0 p-0" type="button" id="dropdownActionButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownActionButton">
                                                                    @if ($request->status != 1)
                                                                        <a href="{{ route('request.comment', ['requests' => $request->id]) }}"
                                                                            class="dropdown-item">
                                                                            <i class="fa fa-commenting-o"></i> Messages
                                                                        </a>
                                                                    @endif
                                                                    @if ($request->status == 1)
                                                                        <a href="{{ route('request.status', ['request_id' => $request->id, 'status' => 2]) }}"
                                                                            class="dropdown-item">
                                                                            <i class="fa fa-check"></i> Submit Request
                                                                        </a>
                                                                    @elseif ($request->status == 2)
                                                                        <a href="{{ route('request.status', ['request_id' => $request->id, 'status' => 1]) }}"
                                                                            class="dropdown-item">
                                                                            <i class="fa fa-ban"></i> Move to Draft
                                                                        </a>
                                                                    @elseif ($request->status == 4)
                                                                        @if(!empty($subscriber->review->id))
                                                                            <a href="{{ route('request.status', ['request_id' => $request->id, 'status' => 0]) }}"
                                                                                class="dropdown-item">
                                                                                <i class="fas fa-check-circle" aria-hidden="true"></i> Complete Request
                                                                            </a>
                                                                        @else
                                                                            <a class="dropdown-item" href="#" data-ref="{{ $request->id }}" data-toggle="modal" data-target="#leaveReviewModal" >
                                                                                <i class="fas fa-check-circle" aria-hidden="true"></i> Move for review
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                    @if ($request->status == 1 || $request->status == 2)
                                                                        <a href="{{ route('request.edit', ['requests' => $request->id]) }}"
                                                                            class="dropdown-item">
                                                                            <i class="fa fa-pen"></i> Edit Request
                                                                        </a>
                                                                        <a class="dropdown-item" href="#" data-ref="{{ $request->id }}" data-toggle="modal" data-target="#deleteRequestModal" >
                                                                            <i class="fas fa-trash"></i> Delete Request
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="queue-tab" role="tabpanel" aria-labelledby="account-queue-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th width="10%" class="border-left-0 border-right-0">REQUEST ID</th>
                                                    <th width="25%" class="border-left-0 border-right-0">REQUEST NAME</th>
                                                    <th width="25%" class="border-left-0 border-right-0">CATEGORY</th>
                                                    <th width="10%" class="border-left-0 border-right-0">STATUS</th>
                                                    <th width="20%" class="border-left-0 border-right-0">DATE CREATED</th>
                                                    <th width="10%" class="border-left-0 border-right-0"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($queue as $que)
                                                    <tr>
                                                        <td class="border-left-0 border-right-0 text-primary">#{{ $que->id }}</td>
                                                        <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('request.view', ['requests' => $que->id]) }}">{{ $que->title }}</a></td>
                                                        @if(!empty($que->designtype->name))
                                                        <td class="border-left-0 border-right-0">{{ $que->designtype->name }}</td>
                                                        @else
                                                        <td class="border-left-0 border-right-0"></td>
                                                        @endif
                                                        <td class="border-left-0 border-right-0">
                                                            <span class="badge badge-info">{{ (new \App\Lib\SystemHelper)->statusLabel($que->status) }}</span>
                                                        </td>
                                                        <td class="border-left-0 border-right-0">{{ $que->created_at->format('d F, Y') }}</td>
                                                        <td class="d-flex justify-content-end border-left-0 border-right-0">
                                                            <div class="dropdown mx-2">
                                                                <a href="javascript:void(0);"class="text-dark" id="dropdownUpdate{{ $que->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="dropdownUpdate{{ $que->id }}">
                                                                    <span>Last Updated: {{ $que->updated_at->format('d F, Y, h:i:s A') }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <a href="{{ route('request.view', ['requests' => $que->id]) }}"
                                                                class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="View Request">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <div class="dropdown mx-2">
                                                                <button class="text-dark btn btn-link border-0 p-0" type="button" id="dropdownActionButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownActionButton">
                                                                    <a href="{{ route('request.comment', ['requests' => $que->id]) }}"
                                                                        class="dropdown-item">
                                                                        <i class="fa fa-commenting-o"></i> Messages
                                                                    </a>
                                                                    <a href="{{ route('request.status', ['request_id' => $que->id, 'status' => 1]) }}" class="dropdown-item">
                                                                        <i class="fa fa-ban"></i> Move to Draft
                                                                    </a>
                                                                    <a href="{{ route('request.edit', ['requests' => $que->id]) }}" class="dropdown-item">
                                                                        <i class="fa fa-pen"></i> Edit Request
                                                                    </a>
                                                                    <a class="dropdown-item" href="#" data-ref="{{ $que->id }}" data-toggle="modal" data-target="#deleteRequestModal" >
                                                                        <i class="fas fa-trash"></i> Delete Request
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="progress-tab" role="tabpanel" aria-labelledby="account-progress-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th width="10%" class="border-left-0 border-right-0">REQUEST ID</th>
                                                    <th width="25%" class="border-left-0 border-right-0">REQUEST NAME</th>
                                                    <th width="25%" class="border-left-0 border-right-0">CATEGORY</th>
                                                    <th width="10%" class="border-left-0 border-right-0">STATUS</th>
                                                    <th width="20%" class="border-left-0 border-right-0">DATE CREATED</th>
                                                    <th width="10%" class="border-left-0 border-right-0"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($progress as $prog)
                                                    <tr>
                                                        <td class="border-left-0 border-right-0 text-primary">#{{ $prog->id }}</td>
                                                        <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('request.view', ['requests' => $prog->id]) }}">{{ $prog->title }}</a></td>
                                                        @if(!empty($prog->designtype->name))
                                                        <td class="border-left-0 border-right-0">{{ $prog->designtype->name }}</td>
                                                        @else
                                                        <td class="border-left-0 border-right-0"></td>
                                                        @endif
                                                        <td class="border-left-0 border-right-0">
                                                            <span class="badge badge-success">{{ (new \App\Lib\SystemHelper)->statusLabel($prog->status) }}</span>
                                                        </td>
                                                        <td class="border-left-0 border-right-0">{{ $prog->created_at->format('d F, Y') }}</td>
                                                        <td class="d-flex justify-content-end border-left-0 border-right-0">
                                                            <div class="dropdown mx-2">
                                                                <a href="javascript:void(0);"class="text-dark" id="dropdownUpdate{{ $prog->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="dropdownUpdate{{ $prog->id }}">
                                                                    <span>Last Updated: {{ $prog->updated_at->format('d F, Y, h:i:s A') }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <a href="{{ route('request.view', ['requests' => $prog->id]) }}"
                                                                class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="View Request">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <div class="dropdown mx-2">
                                                                <button class="text-dark btn btn-link border-0 p-0" type="button" id="dropdownActionButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownActionButton">
                                                                    <a href="{{ route('request.comment', ['requests' => $prog->id]) }}" class="dropdown-item">
                                                                        <i class="fa fa-commenting-o"></i> Messages
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="review-tab" role="tabpanel" aria-labelledby="account-review-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th width="10%" class="border-left-0 border-right-0">REQUEST ID</th>
                                                    <th width="25%" class="border-left-0 border-right-0">REQUEST NAME</th>
                                                    <th width="25%" class="border-left-0 border-right-0">CATEGORY</th>
                                                    <th width="10%" class="border-left-0 border-right-0">STATUS</th>
                                                    <th width="20%" class="border-left-0 border-right-0">DATE CREATED</th>
                                                    <th width="10%" class="border-left-0 border-right-0"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($review as $rev)
                                                    <tr>
                                                        <td class="border-left-0 border-right-0 text-primary">#{{ $rev->id }}</td>
                                                        <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('request.view', ['requests' => $rev->id]) }}">{{ $rev->title }}</a></td>
                                                        @if(!empty($rev->designtype->name))
                                                        <td class="border-left-0 border-right-0">{{ $rev->designtype->name }}</td>
                                                        @else
                                                        <td class="border-left-0 border-right-0"></td>
                                                        @endif
                                                        <td class="border-left-0 border-right-0">
                                                            <span class="badge badge-dark">{{ (new \App\Lib\SystemHelper)->statusLabel($rev->status) }}</span>
                                                        </td>
                                                        <td class="border-left-0 border-right-0">{{ $rev->created_at->format('d F, Y') }}</td>
                                                        <td class="d-flex justify-content-end border-left-0 border-right-0">
                                                            <div class="dropdown mx-2">
                                                                <a href="javascript:void(0);"class="text-dark" id="dropdownUpdate{{ $rev->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="dropdownUpdate{{ $rev->id }}">
                                                                    <span>Last Updated: {{ $rev->updated_at->format('d F, Y, h:i:s A') }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <a href="{{ route('request.view', ['requests' => $rev->id]) }}"
                                                                class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="View Request">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <div class="dropdown mx-2">
                                                                <button class="text-dark btn btn-link border-0 p-0" type="button" id="dropdownActionButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownActionButton">
                                                                    @if(!empty($subscriber->review->id))
                                                                        <a href="{{ route('request.status', ['request_id' => $rev->id, 'status' => 0]) }}" class="dropdown-item">
                                                                            <i class="fas fa-check-circle" aria-hidden="true"></i> Complete Request
                                                                        </a>
                                                                    @else
                                                                        <a class="dropdown-item" href="#" data-ref="{{ $rev->id }}" data-toggle="modal" data-target="#leaveReviewModal" >
                                                                            <i class="fas fa-check-circle" aria-hidden="true"></i> Complete Request
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="delivered-tab" role="tabpanel" aria-labelledby="account-delivered-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th width="10%" class="border-left-0 border-right-0">REQUEST ID</th>
                                                    <th width="25%" class="border-left-0 border-right-0">REQUEST NAME</th>
                                                    <th width="25%" class="border-left-0 border-right-0">CATEGORY</th>
                                                    <th width="10%" class="border-left-0 border-right-0">STATUS</th>
                                                    <th width="20%" class="border-left-0 border-right-0">DATE CREATED</th>
                                                    <th width="10%" class="border-left-0 border-right-0"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($completed as $complete)
                                                    <tr>
                                                        <td class="border-left-0 border-right-0 text-primary">#{{ $complete->id }}</td>
                                                        <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('request.view', ['requests' => $complete->id]) }}">{{ $complete->title }}</a></td>
                                                        @if(!empty($complete->designtype->name))
                                                        <td class="border-left-0 border-right-0">{{ $complete->designtype->name }}</td>
                                                        @else
                                                        <td class="border-left-0 border-right-0"></td>
                                                        @endif
                                                        <td class="border-left-0 border-right-0">
                                                            <span class="badge badge-primary">{{ (new \App\Lib\SystemHelper)->statusLabel($complete->status) }}</span>
                                                        </td>
                                                        <td class="border-left-0 border-right-0">{{ $complete->created_at->format('d F, Y') }}</td>
                                                        <td class="d-flex justify-content-end border-left-0 border-right-0">
                                                            <div class="dropdown mx-2">
                                                                <a href="javascript:void(0);"class="text-dark" id="dropdownUpdate{{ $complete->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="dropdownUpdate{{ $complete->id }}">
                                                                    <span>Last Updated: {{ $complete->updated_at->format('d F, Y, h:i:s A') }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <a href="{{ route('request.view', ['requests' => $complete->id]) }}"
                                                                class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="View Request">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <div class="dropdown mx-2">
                                                                <button class="text-dark btn btn-link border-0 p-0" type="button" id="dropdownActionButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownActionButton">
                                                                    <a href="{{ route('request.comment', ['requests' => $complete->id]) }}" class="dropdown-item">
                                                                        <i class="fa fa-commenting-o"></i> Messages
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="brands-tab" role="tabpanel" aria-labelledby="account-brands-tab">
                    <h5 class="text-dark pb-2 font-weight-bold">Brand Profile</h5>
                    <!-- Brands -->
                    @foreach ($brands as $brand)
                        <div class="card mb-1 p-4">
                            <div class="row d-flex">
                                <div class="col-md-2 d-flex align-items-center justify-content-md-center justify-content-between pb-3">
                                    <div class="top-main-logo">
                                        <?php 
                                            echo (new \App\Lib\SystemHelper)->get_brand_logo($brand);
                                        ?>
                                    </div>
                                    <div class="d-md-none">
                                        <div class="brand-actions d-flex justify-content-end">
                                            <div class="dropdown mx-2">
                                                <button class="btn btn-outline-light action-icons rounded-circle border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <img src="{{ asset('images/edit-brand.svg') }}" class="action-icon">
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'information', 'brand' => $brand->id]) }}">Brand Information</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'logo', 'brand' => $brand->id]) }}">Logo</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'colors', 'brand' => $brand->id]) }}">Colors</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'fonts', 'brand' => $brand->id]) }}">Fonts</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'images', 'brand' => $brand->id]) }}">Images</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'guidelines', 'brand' => $brand->id]) }}">Brand Guidelines</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'templates', 'brand' => $brand->id]) }}">Templates</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'inspirations', 'brand' => $brand->id]) }}">Brand Inspiration</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'social', 'brand' => $brand->id]) }}">Social Profile</a>
                                                    <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'all', 'brand' => $brand->id]) }}">Edit All</a>
                                                </div>
                                            </div>
                                            <a class="btn btn-outline-light action-icons rounded-circle border" href="#">
                                                <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="brand-name">
                                        <a href="{{ route('brand.view', ['brand' => $brand->id]) }}">{{ $brand->name }}</a>
                                    </div>
                                    <div class="extra-information">
                                        <ul class="d-flex justify-content-start">
                                            <li>{{ $brand->industry }}</li>
                                            <li><a href="{{ $brand->website }}" target="_blank">{{ $brand->website }}</a></li>
                                            <li>
                                                @if ($brand->status == 0)
                                                    <span class="badge badge-warning p-2">DRAFT</span>
                                                @elseif ($brand->status == 1)
                                                    <span class="badge badge-success p-2">ACTIVE</span>
                                                @else
                                                    <span class="badge badge-danger p-2">ARCHIVED</span>
                                                @endif
                                            </li>
                                        </ul>
                                        <p>{{ Str::limit($brand->description, 200, $end='.......') }}</p>
                                        <div class="d-flex colors">
                                            <?php 
                                                echo (new \App\Lib\SystemHelper)->get_brand_assets($brand, 'color');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <div class="brand-actions d-flex justify-content-end">
                                        <div class="dropdown mx-2">
                                            <button class="btn btn-outline-light action-icons rounded-circle border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <img src="{{ asset('images/edit-brand.svg') }}" class="action-icon">
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'information', 'brand' => $brand->id]) }}">Brand Information</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'logo', 'brand' => $brand->id]) }}">Logo</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'colors', 'brand' => $brand->id]) }}">Colors</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'fonts', 'brand' => $brand->id]) }}">Fonts</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'images', 'brand' => $brand->id]) }}">Images</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'guidelines', 'brand' => $brand->id]) }}">Brand Guidelines</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'templates', 'brand' => $brand->id]) }}">Templates</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'inspirations', 'brand' => $brand->id]) }}">Brand Inspiration</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'social', 'brand' => $brand->id]) }}">Social Profile</a>
                                                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'all', 'brand' => $brand->id]) }}">Edit All</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <?php 
                    $duration = $subscriber->payments->duration;
                    $durationlabel = !empty($duration)?$duration:'monthly';
                ?>
                <div class="tab-pane fade" id="subscription-tab" role="tabpanel" aria-labelledby="account-subscription-tab">
                    <div class="row justify-content-center mt-5 text-center">
                        <div class="col-md-3">
                            <div id="plan-toggle" class="btn-group btn-group-toggle btn-light" data-toggle="buttons">
                                <label id="monthly-plan" class="btn btn-light active">
                                    <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off" {{ ($durationlabel == 'monthly')?'checked':'' }}> Monthly
                                </label>
                                <label id="yearly-plan" class="btn btn-light">
                                    <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off" {{ ($durationlabel == 'yearly')?'checked':'' }}> Yearly
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mt-5 pricing-table">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 mt-2">
                                    <div class="shadow bg-white rounded card no-header">
                                        <div class="card-body pt-5 pb-5 text-dark">
                                            <h6 class="card-title">Basic</h6>
                                            <h4 class="monthly-amount {{ ($durationlabel == 'yearly')?'hide-amount':'' }}">S$399 <span class="month-label">per month</span></h4>
                                            <h4 class="yearly-amount {{ ($durationlabel == 'monthly')?'hide-amount':'' }}">S$359 <span class="month-label">per month</span></h4>
                                            <p class="card-text">The perfect starter plan for all your basic design needs.</p>
                                            <div class="yearly-per-duration {{ ($durationlabel == 'monthly')?'hide-per-duration':'' }} pb-2">Save 10%</div>
                                            @if($subscriber->payments->plan == 'basic')
                                                <div class="monthly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'yearly')?'hide-label':'d-block' }}">Monthly plan, paid monthly</div>
                                                <div class="yearly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'monthly')?'hide-label':'d-block' }}">Yearly plan, paid yearly</div>
                                            @else
                                                <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'yearly')?'hide-label':'d-inline-block' }}">Monthly</div>
                                                <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'monthly')?'hide-label':'d-inline-block' }}">Yearly</div>
                                            @endif
                                            <ul class="pricing-details">
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">1 Ongoing Request</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">1 Brand Profile</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Dedicated Designer</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Unlimited Design Requests</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Unlimited Revisions</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">48 Hours Turnaround</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                                </li>
                                            </ul>
                                            @if($subscriber->payments->plan == 'basic')
                                                <button type="button" class="btn btn-outline-success btn-block text-success mt-3" disabled>
                                                    {{ __('Active') }}
                                                </button>
                                            @elseif((new \App\Lib\SystemHelper)->planPositionChecker('basic', $subscriber->payments->plan))
                                            <form method="POST" action="{{ route('profile.upgradeplan') }}" class="mt-3">
                                                @csrf
                                                <input type="hidden" name="userid" value="{{ $subscriber->id }}" />
                                                <input type="hidden" name="plan" value="basic" />
                                                <input type="hidden" name="duration" class="duration" value="{{ $durationlabel }}" />
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    {{ __('Upgrade') }}
                                                </button>
                                            </form>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary btn-block mt-3" disabled>
                                                    {{ __('Included') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <div class="shadow bg-white rounded card">
                                        <div class="card-body pt-5 pb-5 text-dark">
                                            <h6 class="card-title">Premium</h6>
                                            <h4 class="monthly-amount {{ ($durationlabel == 'yearly')?'hide-amount':'' }}">S$599 <span class="month-label">per month</span></h4>
                                            <h4 class="yearly-amount {{ ($durationlabel == 'monthly')?'hide-amount':'' }}">S$539 <span class="month-label">per month</span></h4>
                                            <p class="card-text">Get double the output and crush all your design needs.</p>
                                            <div class="yearly-per-duration {{ ($durationlabel == 'monthly')?'hide-per-duration':'' }} pb-2">Save 10%</div>
                                            @if($subscriber->payments->plan == 'premium')
                                                <div class="monthly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'yearly')?'hide-label':'d-block' }}">Monthly plan, paid monthly</div>
                                                <div class="yearly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'monthly')?'hide-label':'d-block' }}">Yearly plan, paid yearly</div>
                                            @else
                                                <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'yearly')?'hide-label':'d-inline-block' }}">Monthly</div>
                                                <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'monthly')?'hide-label':'d-inline-block' }}">Yearly</div>
                                            @endif
                                            <ul class="pricing-details">
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">2 Ongoing Requests</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">2 Brand Profiles</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Dedicated Designer</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Unlimited Design Requests</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Unlimited Revisions</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">24 Hours Turnaround</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                                </li>
                                            </ul>
                                            @if($subscriber->payments->plan == 'premium')
                                                <button type="button" class="btn btn-outline-success btn-block text-success mt-3" disabled>
                                                    {{ __('Active') }}
                                                </button>
                                            @elseif((new \App\Lib\SystemHelper)->planPositionChecker('premium', $subscriber->payments->plan))
                                            <form method="POST" action="{{ route('profile.upgradeplan') }}" class="mt-3">
                                                @csrf
                                                <input type="hidden" name="userid" value="{{ $subscriber->id }}" />
                                                <input type="hidden" name="plan" value="premium" />
                                                <input type="hidden" name="duration" class="duration" value="{{ $durationlabel }}" />
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    {{ __('Upgrade') }}
                                                </button>
                                            </form>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary btn-block mt-3" disabled>
                                                    {{ __('Included') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <div class="shadow bg-white rounded card no-header">
                                        <div class="card-body pt-5 pb-5 text-dark">
                                            <h6 class="card-title">Enterprise</h6>
                                            <h4 class="monthly-amount {{ ($durationlabel == 'yearly')?'hide-amount':'' }}">S$1199 <span class="month-label">per month</span></h4>
                                            <h4 class="yearly-amount {{ ($durationlabel == 'monthly')?'hide-amount':'' }}">S$1079 <span class="month-label">per month</span></h4>
                                            <p class="card-text">Level up your content with the ultimate creative plan.</p>
                                            <div class="yearly-per-duration {{ ($durationlabel == 'monthly')?'hide-per-duration':'' }} pb-2">Save 10%</div>
                                            @if($subscriber->payments->plan == 'royal')
                                                <div class="monthly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'yearly')?'hide-label':'d-block' }}">Monthly plan, paid monthly</div>
                                                <div class="yearly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'monthly')?'hide-label':'d-block' }}">Yearly plan, paid yearly</div>
                                            @else
                                                <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'yearly')?'hide-label':'d-inline-block' }}">Monthly</div>
                                                <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'monthly')?'hide-label':'d-inline-block' }}">Yearly</div>
                                            @endif
                                            <ul class="pricing-details">
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">2 Ongoing Requests</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Unlimited Brand Profiles</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Dedicated Designer</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Unlimited Design Requests</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Unlimited Revisions</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">24 Hours Turnaround</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                                </li>
                                                <li>
                                                    <div class="md-v-line"></div>
                                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                                    <span class="c-black">Dedicated Account Manager</span>
                                                </li>
                                            </ul>
                                            @if($subscriber->payments->plan == 'royal')
                                                <button type="button" class="btn btn-outline-success btn-block text-success mt-3" disabled>
                                                    {{ __('Active') }}
                                                </button>
                                            @elseif((new \App\Lib\SystemHelper)->planPositionChecker('royal', $subscriber->payments->plan))
                                            <form method="POST" action="{{ route('profile.upgradeplan') }}" class="mt-3">
                                                @csrf
                                                <input type="hidden" name="userid" value="{{ $subscriber->id }}" />
                                                <input type="hidden" name="plan" value="royal" />
                                                <input type="hidden" name="duration" class="duration" value="{{ $durationlabel }}" />
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    {{ __('Upgrade') }}
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="transaction-tab" role="tabpanel" aria-labelledby="account-transaction-tab">
                    <h5 class="text-dark pb-2 font-weight-bold">Transactions</h5>
                    @if ($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table bg-white" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="10%">ORDER NUMBER</th>
                                        <th width="25%">DATE</th>
                                        <th width="25%">PLAN</th>
                                        <th width="15%">AMOUNT</th>
                                        <th width="20%">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td class="text-primary"><a href="{{ route('view.invoice', ['invoice' => $invoice->id]) }}" target="_blank">DO{{ $invoice->id }}</a></td>
                                            <td>{{ $invoice->created_at->format('d F, Y') }}</td>
                                            <td>DesignsOwl, {{ (new \App\Lib\SystemHelper)->getPlanInformation($invoice->plan)['label'] }}</td>
                                            <td>S${{ number_format($invoice->amount) }}</td>
                                            <td class="d-flex   justify-content-start">
                                                <a href="{{ route('view.invoice', ['invoice' => $invoice->id]) }}" target="_blank" class="btn btn-outline-light action-icons rounded-circle border p-1 mx-1">
                                                    <img src="{{ asset('images/inv-open.svg') }}" class="action-icon">
                                                </a>
                                                <a href="{{ route('generate.invoice', ['invoice' => $invoice->id]) }}" class="btn btn-outline-light action-icons rounded-circle border p-1 mx-1">
                                                    <img src="{{ asset('images/inv-donwload.svg') }}" class="action-icon">
                                                </a>
                                                <a href="#" class="btn btn-outline-light action-icons rounded-circle border p-1 mx-1" data-ref="{{ $invoice->id }}" data-toggle="modal" data-target="#sendInvoiceModal">
                                                    <img src="{{ asset('images/inv-mail.svg') }}" class="action-icon">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form method="POST" action="{{ route('send.invoice') }}" enctype="multipart/form-data" id="form-send_invoice-request">
                            @csrf
                            <div class="modal fade" id="sendInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="sendInvoiceModalExample"
                            aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-dark font-weight-bold" id="sendInvoiceModalExample">Email invoice</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="msg"></div>
                                            <input type="hidden" id="invoice_ref_id" name="invoiceid" value="">
                                            <p class="text-dark">Would you like to send a copy of this document to your email address, <strong>{{ $subscriber->email }}</strong></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="showInvoiceSuccess" class="d-none" type="button" data-toggle="modal" data-target="#sendInvoiceSuccess">Invoice Sent</button>
                                            <button id="dismissInvoiceModal" class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                                            <button class="btn btn-primary" type="submit" >Email Invoice</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal fade" id="sendInvoiceSuccess" tabindex="-1" role="dialog" aria-labelledby="sendInvoiceSuccessModalExample"
                            aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body py-5 text-center">
                                        <img src="{{ asset('images/sent.svg') }}" class="mail-icon">
                                        <h2 class="text-dark pt-4 pb-3">Invoice Sent</h2>
                                        <p class="text-dark">We have successfully send your invoice. Please check your email</p>
                                        <button class="btn btn-primary" type="button" data-dismiss="modal">Done</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @else

                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="min-height-600 d-flex align-items-center justify-content-center">
                                        <div class="no-record py-4 text-center">
                                            <img src="{{ asset('images/requests-empty.svg') }}">
                                            <div class="pt-4">
                                                <h2>You have no invoices.</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif 
                </div>
            </div>
        </div>
    </div>

</div>

<form method="POST" action="{{ route('profile.delete') }}" id="form-delete-account">
    @csrf
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalExample"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold" id="reviewModalExample">Delete account</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-dark">Please enter your email address to confirm it's you requesting to delete your account. We'll make sure that your account is not use by someone or it's an accident request for deletion.</p>
                    <div id="msg"></div>
                    <div class="form-group">
                        <div class="text-dark pt-3">
                            <label for="email_confirm">Email address</label>
                            <input 
                                type="email" 
                                class="form-control form-control-user @error('email') is-invalid @enderror" 
                                id="email_confirm" 
                                name="email" 
                                value="{{ old('email') }}">

                            @error('email')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="showSuccessModal" class="d-none" type="button" data-toggle="modal" data-target="#deleteAccountSuccess">Message</button>
                    <button id="dismissDeleteModal" class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" >Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<style type="text/css">
    .pricing-table .card-text {
        min-height: 65px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function($) {
        $('#yearly-plan').on('click', function() {
            $('#monthly-plan').removeClass('active');
            $('.yearly-amount').removeClass('hide-amount');
            $('.monthly-amount').addClass('hide-amount');
            $('.yearly-per-duration').removeClass('hide-per-duration');
            $('.monthly-per-duration').addClass('hide-per-duration');
            $('.yearly-label').removeClass('hide-label');
            $('.yearly-label').addClass('d-inline-block');
            $('.monthly-label').removeClass('d-inline-block');
            $('.monthly-label').addClass('hide-label');
            $('.yearly-block-label').removeClass('hide-label');
            $('.yearly-block-label').addClass('d-block');
            $('.monthly-block-label').removeClass('d-block');
            $('.monthly-block-label').addClass('hide-label');
            $(this).addClass('active');
            $('.duration').val('yearly');
        });
        $('#monthly-plan').on('click', function() {
            $('#yearly-plan').removeClass('active');
            $('.monthly-amount').removeClass('hide-amount');
            $('.yearly-amount').addClass('hide-amount');
            $('.monthly-per-duration').removeClass('hide-per-duration');
            $('.yearly-per-duration').addClass('hide-per-duration');
            $('.monthly-label').removeClass('hide-label');
            $('.monthly-label').addClass('d-inline-block');
            $('.yearly-label').removeClass('d-inline-block');
            $('.yearly-label').addClass('hide-label');
            $('.monthly-block-label').removeClass('hide-label');
            $('.monthly-block-label').addClass('d-block');
            $('.yearly-block-label').removeClass('d-block');
            $('.yearly-block-label').addClass('hide-label');
            $(this).addClass('active');
            $('.duration').val('monthly');
        });
    });
</script>
@endsection