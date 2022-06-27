@extends('layouts.frontnonav')

@section('title', 'Login')

@section('content')
<div class="full-height d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form class="form p-4 m-0" method="POST" action="{{ route('userlogin') }}">
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
                                <label for="password" class="col-form-label">{{ __('Password') }}</label>

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
                                <div class="col-md-12">
                                    <button type="submit" class="w-100 d-block btn btn-primary">
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
</div>
<style type="text/css">
    
</style>
@endsection