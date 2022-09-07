@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="container">

        {{-- Alert Messages --}}
        @include('common.alert')

        {{-- Page Content --}}
        <div class="row">
            <div id="brand-tab-contents" class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-light-custom py-4">
                        <h3 class="text-dark mb-0">Profile Information</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="tab-text-label text-dark py-3 border-bottom">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-md-2 single-label">
                                    @if(!empty(auth()->user()->profile_img))
                                        <img class="rounded-circle" width="130px" src="{{ Storage::disk('s3')->url(auth()->user()->profile_img) }}" id="profile-image">
                                    @else
                                        <img class="rounded-circle" width="130px" src="{{ asset('admin/img/undraw_profile.svg') }}" id="profile-image">
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <form class="POST"  enctype="multipart/form-data" id="profile-image-uploader">
                                        @csrf

                                        <div class="d-none">
                                            <input type="file" name="profile_img" id="profile_img" accept=".jpg,.png">
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="getElementById('profile_img').click();">Update Photo</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-text-label text-dark py-3 border-bottom">
                            <div class="row">
                                <div class="col-md-3 single-label">Full Name</div>
                                <div class="col-md-9">
                                    <span>{{ auth()->user()->full_name }}</span>
                                    <span class="float-right text-primary">
                                        <a href="#" data-toggle="modal" data-target="#fullnameModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-text-label text-dark py-3 border-bottom">
                            <div class="row">
                                <div class="col-md-3 single-label">Email Address</div>
                                <div class="col-md-6">{{ auth()->user()->email }}</div>
                                <div class="col-md-3">
                                    @if(auth()->user()->is_email_verified)
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
                                <div class="col-md-6">{{ auth()->user()->mobile_number }}</div>
                                <div class="col-md-3">
                                    @if(auth()->user()->is_phone_verified)
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
                                    @if(!empty(auth()->user()->address_1))
                                        <span>
                                            {{ auth()->user()->address_1 }} {{ auth()->user()->address_2 }}, {{ auth()->user()->city }} {{ auth()->user()->state }}, {{ auth()->user()->zip }} {{ auth()->user()->country }}
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
                                    @if(!empty((new \App\Lib\SystemHelper)->getUserSetting(auth()->user()->id, 'time_zone')))
                                        <span>{{ (new \App\Lib\SystemHelper)->getUserSetting(auth()->user()->id, 'time_zone') }}</span>
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
                <div class="card mb-4">
                    <div class="card-header bg-light-custom py-4">
                        <h3 class="text-dark mb-0">Global Preferences</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="tab-text-label text-dark py-3">
                            <div class="row">
                                <div class="col-md-3 single-label">Language</div>
                                <div class="col-md-9">
                                    @if(!empty((new \App\Lib\SystemHelper)->getLanguage()))
                                        <span>{{ (new \App\Lib\SystemHelper)->getLanguage() }}</span>
                                    @else
                                        <span>English</span>
                                    @endif
                                    <span class="float-right text-primary">
                                        <a href="#" data-toggle="modal" data-target="#languageModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="tab-text-label text-dark py-3">
                            <div class="row">
                                <div class="col-md-3 single-label">Currency</div>
                                <div class="col-md-9">
                                    @if(!empty((new \App\Lib\SystemHelper)->getCurrency()))
                                        <span>{{ (new \App\Lib\SystemHelper)->getCurrency() }}</span>
                                    @else
                                        <span>USD</span>
                                    @endif
                                    <span class="float-right text-primary">
                                        <a href="#" data-toggle="modal" data-target="#currencyModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header bg-light-custom py-4">
                        <h3 class="text-dark mb-0">Danger Zone</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="tab-text-label text-dark py-3 border-bottom">
                            <div class="row">
                                <div class="col-md-3 single-label">Member Since</div>
                                <div class="col-md-9">
                                    <span>{{ auth()->user()->created_at->format('F d, Y') }}</span>
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

    <div class="modal fade" id="deleteAccountSuccess" tabindex="-1" role="dialog" aria-labelledby="deleteAccountSuccessModalExample"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body py-5 text-center">
                    <img src="{{ asset('images/mail.svg') }}" class="mail-icon">
                    <h2 class="text-dark pt-4 pb-3">Confirm your email address</h2>
                    <p class="text-dark">We’ve sent a message to <strong>{{ auth()->user()->email }}</strong> with a confirmation message to delete your account.</p>
                    <a href="{{ route('profile.delete.account') }}" class="btn btn-primary">Done</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullname -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-name-account">
        @csrf
        <input type="hidden" name="action" value="fullname" >
        <div class="modal fade" id="fullnameModal" tabindex="-1" role="dialog" aria-labelledby="fullnameModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="fullnameModalExample">Update your full name</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="first_name">First name</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="first_name" 
                                    name="first_name" 
                                    value="{{ (old('first_name')) ? old('first_name') : auth()->user()->first_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="last_name">Last name</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="last_name" 
                                    name="last_name" 
                                    value="{{ (old('last_name')) ? old('last_name') : auth()->user()->last_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Email -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-email-account">
        @csrf
        <input type="hidden" name="action" value="email" >
        <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="emailModalExample">Update your email address</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <p class="text-dark">Updating your email address will require verification code.</p>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="email">Email address</label>
                                <input 
                                    type="email" 
                                    class="form-control form-control-user" 
                                    id="email" 
                                    name="email" 
                                    value="{{ (old('email')) ? old('email') : auth()->user()->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="dismissemailUpdateModal" class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Email Verification -->
    <form method="POST" action="{{ route('profile.emailverify') }}" id="form-verify-email-account">
        @csrf
        <input type="hidden" name="action" value="email" >
        <div class="modal fade" id="emailVerifyModal" tabindex="-1" role="dialog" aria-labelledby="emailVerifyModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="emailVerifyModalExample">Verify your email address</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <p class="text-dark">Enter the code we just sent to <span id="new-email" class="font-weight-bold">{{ (new \App\Lib\SystemHelper)->getUserSetting(auth()->user()->id, 'new_email') }}</span></p>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="email_code">Enter code</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="email_code" 
                                    name="email_code" 
                                    value="{{ old('email_code') }}">
                            </div>
                        </div>
                        <p class="text-dark">Didn't received a code? <a href="javascript:void(0);" id="resend-email-code" class="text-primary">Resend</a></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary d-none" type="button" id="showemailVerifyModal" data-toggle="modal" data-target="#emailVerifyModal">Verify Email</button>
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Back</button>
                        <button class="btn btn-primary" type="submit" >Verify</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Phone Number -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-phone-account">
        @csrf
        <input type="hidden" name="action" value="phone" >
        <div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labelledby="phoneModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="phoneModalExample">{{ (empty(auth()->user()->mobile_number)) ? 'Add' : 'Update' }} your phone number</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <div class="form-group d-none">
                            <div class="text-dark pt-3">
                                <label for="country_code">Country code</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="country_code" 
                                    name="country_code" 
                                    value="{{ (old('country_code')) ? old('country_code') : auth()->user()->country_code }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="mobile_number">Phone number</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="mobile_number" 
                                    name="mobile_number" 
                                    value="{{ (old('mobile_number')) ? old('mobile_number') : auth()->user()->mobile_number }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="dismissphoneUpdateModal" class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="sumit" >{{ (empty(auth()->user()->mobile_number)) ? 'Add' : 'Update' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Verify Phone Number -->
    <form method="POST" action="{{ route('profile.phoneverify') }}" id="form-verify-phone-account">
        @csrf
        <input type="hidden" name="action" value="phone" >
        <div class="modal fade" id="phoneVerifyModal" tabindex="-1" role="dialog" aria-labelledby="phoneVerifyModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="phoneVerifyModalExample">Verify your phone number</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="country_code">Country code</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="country_code" 
                                    name="country_code" 
                                    value="{{ (old('country_code')) ? old('country_code') : auth()->user()->country_code }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="mobile_number">Phone number</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="mobile_number" 
                                    name="mobile_number" 
                                    value="{{ (old('mobile_number')) ? old('mobile_number') : auth()->user()->mobile_number }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary d-none" type="button" id="showphoneVerifyModal" data-toggle="modal" data-target="#phoneVerifyModal">Verify Phone</button>
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="sumit" >{{ (empty(auth()->user()->mobile_number)) ? 'Add' : 'Update' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Address -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-address-account">
        @csrf
        <input type="hidden" name="action" value="address" >
        <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="addressModalExample">{{ (empty(auth()->user()->address_1)) ? 'Add' : 'Update' }} your location</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="address_1">Address line 1</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="address_1" 
                                    name="address_1" 
                                    value="{{ (old('address_1')) ? old('address_1') : auth()->user()->address_1 }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="address_2">Address line 2</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="address_2" 
                                    name="address_2" 
                                    value="{{ (old('address_2')) ? old('address_2') : auth()->user()->address_2 }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="city">City</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="city" 
                                    name="city" 
                                    value="{{ (old('city')) ? old('city') : auth()->user()->city }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="state">State/Region/Province</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="state" 
                                    name="state" 
                                    value="{{ (old('state')) ? old('state') : auth()->user()->state }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="zip">Zip</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="zip" 
                                    name="zip" 
                                    value="{{ (old('zip')) ? old('zip') : auth()->user()->zip }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="country">Country</label>
                                <select class="form-control" name="country">
                                    @foreach($countries as $country)
                                        <option value="{{ $country->name }}" {{ auth()->user()->country == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >{{ (empty(auth()->user()->address_1)) ? 'Add' : 'Update' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Time Zone -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-timezone-account">
        @csrf
        <input type="hidden" name="action" value="timezone" >
        <div class="modal fade" id="timezoneModal" tabindex="-1" role="dialog" aria-labelledby="timezoneModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="timezoneModalExample">Update your timezone</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="time_zone">Timezone</label>
                                <select class="form-control" name="time_zone">
                                    @foreach((new \App\Lib\SystemHelper)->timezones() as $timezone)
                                        <option value="{{ $timezone }}" {{ ((new \App\Lib\SystemHelper)->getUserSetting(auth()->user()->id, 'time_zone') == $timezone) ? 'selected' : '' }}>{{ $timezone }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Language -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-language-account">
        @csrf
        <input type="hidden" name="action" value="language" >
        <div class="modal fade" id="languageModal" tabindex="-1" role="dialog" aria-labelledby="languageModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="languageModalExample">Update your preferred language</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <p class="text-dark">Choosing your preferred language for communication.</p>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="language">Language</label>
                                <select class="form-control" name="language">
                                    @foreach((new \App\Lib\SystemHelper)->getLanguages() as $lcode => $language)
                                        <option value="{{ $lcode }}" {{ ((new \App\Lib\SystemHelper)->getUserSetting(auth()->user()->id, 'language') == $lcode) ? 'selected' : '' }}>{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Currency -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-currency-account">
        @csrf
        <input type="hidden" name="action" value="currency" >
        <div class="modal fade" id="currencyModal" tabindex="-1" role="dialog" aria-labelledby="currencyModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="currencyModalExample">Update your preferred currency</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="currency">Currency</label>
                                <select class="form-control" name="currency">
                                    @foreach((new \App\Lib\SystemHelper)->getCurrencies() as $ccode => $currency)
                                        <option value="{{ $ccode }}" {{ ((new \App\Lib\SystemHelper)->getUserSetting(auth()->user()->id, 'currency') == $ccode) ? 'selected' : '' }}>({{ $currency['symbol'] }}){{ $currency['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        $('#form-delete-account').on('submit', function(e) {
            e.preventDefault();

            $("#msg").html('');

            $.ajax({
               type:'POST',
               url:'{{ route('profile.delete') }}',
               data: $(this).serialize(),
               success:function(data) {
                if(data.error == 1) {
                    $("#msg").html('<p class="alert alert-danger">'+ data.msg +'</p>');
                } else {
                    // Success
                    $('#dismissDeleteModal').click();
                    $('#showSuccessModal').click();
                }
               }
            });

            return false;
        });

        $('#form-update-name-account, #form-update-email-account, #form-update-phone-account, #form-update-address-account, #form-update-timezone-account, #form-update-language-account, #form-update-currency-account').on('submit', function(e) {
            e.preventDefault();

            var dis = $(this);

            dis.find(".msg").html('');

            $.ajax({
               type:'POST',
               url:'{{ route('profile.update') }}',
               data: $(this).serialize(),
               success:function(data) {
                if(data.error == 1) {
                    let errorMessages = '';
                    $.each(data.msg, function (i, message) {
                        errorMessages += '<span>'+ message +'</span><br>';
                    });

                    dis.find(".msg").html('<p class="alert alert-danger">'+ errorMessages +'</p>');
                } else {
                    if(data.key == 0) {
                        window.location.reload(true);
                    } else {
                        $('#dismiss'+ data.key +'UpdateModal').click();
                        $('#show'+ data.key +'VerifyModal').click();
                        $('#new-'+ data.key).html(data.value);
                    }
                }
               }
            });

            return false;
        });

        $('#form-verify-email-account').on('submit', function(e) {
            e.preventDefault();

            var dis = $(this);

            dis.find(".msg").html('');

            $.ajax({
               type:'POST',
               url:'{{ route('profile.emailverify') }}',
               data: $(this).serialize(),
               success:function(data) {
                if(data.error == 1) {
                    let errorMessages = '';
                    $.each(data.msg, function (i, message) {
                        errorMessages += '<span>'+ message +'</span><br>';
                    });
                    dis.find(".msg").html('<p class="alert alert-danger">'+ errorMessages +'</p>');
                } else {
                    window.location.reload(true);
                }
               }
            });

            return false;
        });

        $('#form-verify-phone-account').on('submit', function(e) {
            e.preventDefault();

            var dis = $(this);

            dis.find(".msg").html('');

            $.ajax({
               type:'POST',
               url:'{{ route('profile.phoneverify') }}',
               data: $(this).serialize(),
               success:function(data) {
                if(data.error == 1) {
                    let errorMessages = '';
                    $.each(data.msg, function (i, message) {
                        errorMessages += '<span>'+ message +'</span><br>';
                    });
                    dis.find(".msg").html('<p class="alert alert-danger">'+ errorMessages +'</p>');
                } else {
                    window.location.reload(true);
                }
               }
            });

            return false;
        });

        $('#resend-email-code').on('click', function() {
            var dis = $('#form-verify-email-account');

            $.ajax({
               type:'POST',
               url:'{{ route('profile.resendcode') }}',
               data: {'_token':$('#form-update-name-account').find("input[name=_token]").val(), 'type':'email'},
               success:function(data) {
                if(data.error == 1) {
                    dis.find(".msg").html('<p class="alert alert-danger">'+ data.msg +'</p>');
                } else {
                    dis.find(".msg").html('<p class="alert alert-success">'+ data.msg +'</p>');
                }
               }
            });
        });

        $('#profile_img').on('change', function(e) {
            var file = e.target.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = readerEvent => {
                var content = readerEvent.target.result; // this is the content!
                $('#profile-image').attr('src', content);
                $('#profile-top-image').attr('src', content);
            }

            var form_data = new FormData();
            form_data.append("_token", $('#form-update-name-account').find("input[name=_token]").val());
            form_data.append("file", file);
            $.ajax({
                url:'{{ route('profile.updateprofileimage') }}',
                method:'POST',
                data:form_data,
                contentType:false,
                cache:false,
                processData:false,
                beforeSend:function(){
                    
                },
                success:function(data){
                    
                }
            });
        });
    });
</script>
    
@endsection
