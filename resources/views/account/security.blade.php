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
                        <h3 class="text-dark mb-0">Password</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="tab-text-label text-dark py-3">
                            <div class="row">
                                <div class="col-md-3 single-label">Current Password</div>
                                <div class="col-md-9">
                                    <span>••••••••••••••</span>
                                    <span class="float-right text-primary">
                                        <a href="#" data-toggle="modal" data-target="#passwordModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header bg-light-custom py-4">
                        <h3 class="text-dark mb-0">Two factor authentication</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="tab-text-label text-dark py-3 border-bottom">
                            <div class="row">
                                <div class="col-md-3 single-label">Verify with email address</div>
                                <div class="col-md-9">
                                    <span>{{ auth()->user()->email }}</span>
                                    <span class="float-right text-primary">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="verify_email">
                                            <label class="custom-control-label" for="verify_email"></label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-text-label text-dark py-3">
                            <div class="row">
                                <div class="col-md-3 single-label">Verify with sms</div>
                                <div class="col-md-9">
                                    <span>{{ auth()->user()->mobile_number }}</span>
                                    <span class="float-right text-primary d-none">
                                        <div class="custom-control custom-switch pt-3">
                                            <input type="checkbox" class="custom-control-input" id="verify_phone">
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Password -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-password-account">
        @csrf
        <input type="hidden" name="action" value="password" >
        <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="passwordModalExample">Update your password</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="current_password">Current password</label>
                                <input 
                                    type="password" 
                                    class="form-control form-control-user" 
                                    id="current_password" 
                                    name="current_password" 
                                    value="{{ old('current_password') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="new_password">New password</label>
                                <input 
                                    type="password" 
                                    class="form-control form-control-user" 
                                    id="new_password" 
                                    name="new_password" 
                                    value="{{ old('new_password') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark pt-3">
                                <label for="new_confirm_password">Confirm new password</label>
                                <input 
                                    type="password" 
                                    class="form-control form-control-user" 
                                    id="new_confirm_password" 
                                    name="new_confirm_password" 
                                    value="{{ old('new_confirm_password') }}">
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
        $('#form-update-password-account').on('submit', function(e) {
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
                    window.location.reload(true);
                }
               }
            });

            return false;
        });
    });
</script>
    
@endsection
