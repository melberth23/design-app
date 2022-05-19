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
                                        <img class="rounded-circle" width="130px" src="{{ url('storage/profiles') }}/{{ auth()->user()->profile_img }}">
                                    @else
                                        <img class="rounded-circle" width="130px" src="{{ asset('admin/img/undraw_profile.svg') }}">
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <form class="POST"  enctype="multipart/form-data" id="profile-image-uploader">
                                        @csrf

                                        <div class="d-none">
                                            <input type="file" name="profile_img" id="profile_img" >
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
                                        <i class="fas fa-angle-right" aria-hidden="true"></i>
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
                                        <i class="fas fa-angle-right" aria-hidden="true"></i>
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
                                        <i class="fas fa-angle-right" aria-hidden="true"></i>
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
                                        <i class="fas fa-angle-right" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-text-label text-dark py-3">
                            <div class="row">
                                <div class="col-md-3 single-label">Timezone</div>
                                <div class="col-md-9">
                                    @if(!empty(auth()->user()->time_zone))
                                        <span>{{ auth()->user()->time_zone }}</span>
                                    @else
                                        <em>- No details found</em>
                                    @endif
                                    <span class="float-right text-primary">
                                        <i class="fas fa-angle-right" aria-hidden="true"></i>
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
                        <div class="tab-text-label text-dark py-3 border-bottom">
                            <div class="row">
                                <div class="col-md-3 single-label">Language</div>
                                <div class="col-md-9">
                                    <span>English</span>
                                    <span class="float-right text-primary">
                                        <i class="fas fa-angle-right" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-text-label text-dark py-3">
                            <div class="row">
                                <div class="col-md-3 single-label">Currency</div>
                                <div class="col-md-9">
                                    <span>USD</span>
                                    <span class="float-right text-primary">
                                        <i class="fas fa-angle-right" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
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
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="sumit" >Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="deleteAccountSuccess" tabindex="-1" role="dialog" aria-labelledby="deleteAccountSuccessModalExample"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body py-5">
                    <h2 class="text-dark">Confirm your email address</h2>
                    <p class="text-dark">We’ve sent a message to {{ auth()->user()->email }} with a confirmation message to delete your account.</p>
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        $('#form-delete-account').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
               type:'POST',
               url:'{{ route('profile.delete') }}',
               data:'',
               success:function(data) {
                  $("#msg").html(data.msg);
               }
            });

            return false;
        });
    });
</script>
    
@endsection
