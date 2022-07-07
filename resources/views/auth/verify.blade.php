@extends('layouts.frontnonav')

@section('title', 'Register')

@section('content')
<div class="container-fluid">
    <div class="row pt-3">
        <div class="col-md-12">
            <img src="{{ asset('images/logo-dark.svg') }}">
        </div>
    </div>
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-3">
            <h1>Verify your email address</h1>
            <p>We’ve sent a message to {{ $email }} with a code to verify your email address.</p>

            <div id="msg"></div>

            <form id="verify-form" method="POST" action="{{ route('user.check') }}">
                @csrf
                <input type="hidden" value="{{ $token }}" name="token">

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="col-md-12">
                            <input id="code1" type="text" class="form-control @if (session('error')) is-invalid @endif" name="code1" value="{{ old('code1') }}" maxlength="1" autofocus>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="col-md-12">
                            <input id="code2" type="text" class="form-control @if (session('error')) is-invalid @endif" name="code2" value="{{ old('code2') }}" maxlength="1" autofocus>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="col-md-12">
                            <input id="code3" type="text" class="form-control @if (session('error')) is-invalid @endif" name="code3" value="{{ old('code3') }}" maxlength="1" autofocus>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="col-md-12">
                            <input id="code4" type="text" class="form-control @if (session('error')) is-invalid @endif" name="code4" value="{{ old('code4') }}" maxlength="1" autofocus>
                        </div>
                    </div>
                </div>

                @if (session('error'))
                <div class="row mb-2">
                    <div class="col-md-12 text-start">
                        <span class="text-danger"> {{ session('error') }}</span>
                    </div>
                </div>
                @endif

                <div class="row mb-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary col-md-12">
                            {{ __('Verify Code') }}
                        </button>
                    </div>
                </div>

            </form>

            <form id="resend-code-form" method="POST">
                @csrf

                <input type="hidden" value="{{ $token }}" name="token">

                <div class="row mb-0">
                    <div class="col-md-12">
                        <p>{{ __("Didn't received a code?") }} <button type="submit" class="btn btn-link">{{ __("Resend") }}</button></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style type="text/css">
    #verify-form input {
        min-height: 140px;
        font-size: 40px;
        text-align: center;
        background: none;
        padding: 0;
        border-width: 2px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function($) {
        $('#resend-code-form').on('submit', function(e) {
            e.preventDefault();

            $("#msg").html('');

            $.ajax({
               type:'POST',
               url:'{{ route('user.resendcode') }}',
               data: $(this).serialize(),
               success:function(data) {
                if(data.error == 1) {
                    $("#msg").html('<p class="alert alert-danger">'+ data.msg +'</p>');
                } else {
                    // Success
                    $("#msg").html('<p class="alert alert-success">'+ data.msg +'</p>');
                }
               }
            });

            return false;
        });
    });
</script>
@endsection
