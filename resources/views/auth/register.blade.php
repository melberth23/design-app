@extends('layouts.frontnonav')

@section('title', 'Register')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 no-padding">
            <div id="leftside-content">
                <div class="container">
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <img src="{{ asset('images/logo.svg') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div id="rightside-content" class="row justify-content-center mt-5">
                <div class="col-md-6">
                    <h1>Welcome to DesignsOwl</h1>
                    <p>Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        @if (session('error'))
                          <span class="text-danger"> {{ session('error') }}</span>
                        @endif
                        <input type="hidden" name="plan" value="<?php echo !empty($_GET['plan'])?$_GET['plan']:''; ?>" />

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="col-form-label">{{ __('First Name') }}</label>
                                <div class="col-md-12">
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus>

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="col-form-label">{{ __('Last Name') }}</label>
                                <div class="col-md-12">
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name" autofocus>

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="company" class="col-form-label">{{ __('Company Name') }}</label>

                            <div class="col-md-12">
                                <input id="company" type="text" class="form-control @error('company') is-invalid @enderror" name="company" value="{{ old('company') }}" autocomplete="company" autofocus>

                                @error('company')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-form-label">{{ __('Email Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-form-label">{{ __('Password') }}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-form-label">{{ __('Confirm Password') }}</label>

                            <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label"><input type="checkbox" name="receive_promotion_email" value="1"> {{ __("I don't want to receive promotional email from DesignsOwl.") }}</label>
                        </div>

                        <div class="row mb-3">
                            <p>By clicking Create account, I agree that I have read and accepted the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create Account') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    #leftside-content {
        background-image: url("{{ asset('images/rm383-14@2x.png') }}");
        height: 100%;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: bottom left;
    }
    #rightside-content {
        min-height: 100vh;
        padding: 3% 0;
    }
</style>
@endsection
