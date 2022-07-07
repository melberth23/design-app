@extends('layouts.frontnonav')

@section('title', 'Forgot password')

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
                    <h1>Forgot password</h1>
                    <p>Enter the email address you used when you joined and we’ll send you instructions to reset your password.</p>

                    {{-- Alert Messages --}}
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('forgot.password') }}">
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
                        
                        <button class="btn btn-primary btn-user btn-d-block">
                            {{ __('Send Reset Instruction') }}
                        </button>
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
    }
</style>
@endsection