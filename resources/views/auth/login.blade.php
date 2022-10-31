@extends('layouts.frontnonav')

@section('title', 'Login')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="d-none d-md-block col-md-3 no-padding">
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
            <div id="rightside-content" class="row justify-content-center">
                <div class="col-md-6">
                    <div class="d-block d-sm-none">
                        <div class="row pt-3 pb-5">
                            <div class="col-md-12">
                                <img src="{{ asset('images/logo-dark.svg') }}">
                            </div>
                        </div>
                    </div>
                    <h1>Welcome back!</h1>

                    <p>New User? <a href="{{ route('register') }}" class="text-decoration-none">Create Account</a></p>

                    <form class="form mb-4 mt-4" method="POST" action="{{ route('login') }}">
                        @csrf

                        @if (session('error'))
                          <span class="text-danger"> {{ session('error') }}</span>
                        @endif
                        
                        <div class="row mb-3">
                            <label for="email" class="col-form-label">{{ __('Email Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                 <label for="password" class="col-form-label">{{ __('Password') }}</label>
                                 @if (Route::has('password.request'))
                                <span>
                                    <a class="text-decoration-none" href="{{ route('password.request') }}">
                                      {{ __('Forgot Your Password?') }}
                                    </a>
                                </span>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="custom-control-label" for="customCheck"><input class="custom-control-input" type="checkbox" name="remember" id="customCheck" {{ old('remember') ? 'checked' : '' }}> Remember Me</label>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-3">
                                <button type="submit" class="d-inline-block btn btn-primary w-100">
                                    {{ __('Login') }}
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
        display: flex;
        align-items: center;
    }
</style>
@endsection