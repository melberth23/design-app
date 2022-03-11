@extends('layouts.frontnonav')

@section('title', 'Login')

@section('header')

<!-- Header-->
<header class="masthead d-flex align-items-center">
    <div class="container px-4 px-lg-5 text-center">
        <div class="mb-4">
            <a href="{{ route('home') }}"><img src="{{ asset('images/icon.png') }}" width="100" id="icon" alt="User Icon" /></a>
        </div>
        <h1 class="mb-1">Start your awesome graphic design adventure!</h1>
    </div>
</header>

@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">

                    @if (session('error'))
                        <span class="text-danger"> {{ session('error') }}</span>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="form-group">
                            <input id="email" type="email"
                                class="form-control form-control-user @error('email') is-invalid @enderror"
                                name="email" value="{{ $email ?? old('email') }}" required
                                autocomplete="email" autofocus placeholder="Enter Email Address.">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="password" type="password"
                                class="form-control form-control-user @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password" placeholder="New Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="password-confirm" type="password" class="form-control form-control-user"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Confirm Password">
                        </div>

                        <button class="btn btn-primary btn-user btn-block" type="submit">
                            {{ __('Reset Password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection