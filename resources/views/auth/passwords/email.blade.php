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

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group mb-4 mt-4">
                            <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email Address.">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        
                        <button class="btn btn-primary btn-user btn-block">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </form>
                </div>

                <div class="card-footer text-muted">
                  <a class="small" href="{{route('login')}}">Already know your password? Login Here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection