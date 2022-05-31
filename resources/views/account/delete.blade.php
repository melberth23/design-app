@extends('layouts.app')

@section('title', 'Delete Account')

@section('content')
    <div class="container">

        {{-- Alert Messages --}}
        @include('common.alert')

        {{-- Page Content --}}
        <div class="row justify-content-md-center">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body p-5">
                        <img src="{{ asset('images/info.svg') }}" class="info-icon">
                        <h1 class="text-dark py-3"><strong>Delete Account</strong></h1>
                        <p class="text-dark">This action cannot be undone. This will permanently delete your entire account. There will be no way to restore your account.</p>
                        <form method="POST" action="{{ route('profile.confirm.delete') }}">
                            @csrf

                            <div class="single-label text-dark pb-3"><strong>Please provide reasons for account deletion:</strong></div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="trouble" name="reason" class="custom-control-input" value="trouble">
                                <label class="custom-control-label" for="trouble">Trouble getting started</label>
                            </div>
                            <div class="custom-control custom-radio pt-1">
                                <input type="radio" id="distracting" name="reason" class="custom-control-input" value="distracting">
                                <label class="custom-control-label" for="distracting">Too busy/too distracting</label>
                            </div>
                            <div class="custom-control custom-radio pt-1">
                                <input type="radio" id="privacy" name="reason" class="custom-control-input" value="privacy">
                                <label class="custom-control-label" for="privacy">Privacy Concerns</label>
                            </div>
                            <div class="custom-control custom-radio pt-1">
                                <input type="radio" id="second_account" name="reason" class="custom-control-input" value="second_account">
                                <label class="custom-control-label" for="second_account">Created a second account</label>
                            </div>
                            <div class="custom-control custom-radio pt-1">
                                <input type="radio" id="notification" name="reason" class="custom-control-input" value="notification">
                                <label class="custom-control-label" for="notification">Too many email notification</label>
                            </div>
                            <div class="custom-control custom-radio pt-1">
                                <input type="radio" id="break" name="reason" class="custom-control-input" value="break">
                                <label class="custom-control-label" for="break">Just need a break</label>
                            </div>
                            <div class="custom-control custom-radio pt-1">
                                <input type="radio" id="not_to_say" name="reason" class="custom-control-input" value="not_to_say">
                                <label class="custom-control-label" for="not_to_say">Prefer not to say</label>
                            </div>

                            @error('reason')
                                <div class="pt-2"><span class="text-danger">{{$message}}</span></div>
                            @enderror

                            <div class="pt-3">
                                <button type="submit" class="btn btn-danger">Permanently Delete My Account</button>
                                <a href="{{ route('profile.detail') }}" class="btn btn-outline-dark">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection