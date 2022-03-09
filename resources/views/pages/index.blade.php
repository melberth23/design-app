@extends('layouts.front')

@section('title', 'Home Page')

@section('header')

<!-- Mashead header-->
<header class="masthead">
    <div class="container px-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6">
                <!-- Mashead text and app badges-->
                <div class="mb-5 mb-lg-0 text-center text-lg-start">
                    <h1 class="display-1 lh-1 mb-3">Simplify the way your design work gets done.</h1>
                    <p class="lead fw-normal text-muted mb-5">If You Can Imagine It, We Can Design It</p>
                    <div class="d-flex flex-column flex-lg-row align-items-center">
                        <a class="btn btn-primary btn-lg active me-lg-3 mb-4 mb-lg-0" href="{{ route('plans') }}">Get Started <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Masthead device mockup feature-->
                <div class="masthead-device-mockup">
                    <svg class="circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="circleGradient" gradientTransform="rotate(45)">
                                <stop class="gradient-start-color" offset="0%"></stop>
                                <stop class="gradient-end-color" offset="100%"></stop>
                            </linearGradient>
                        </defs>
                        <circle cx="50" cy="50" r="50"></circle></svg
                    ><svg class="shape-1 d-none d-sm-block" viewBox="0 0 240.83 240.83" xmlns="http://www.w3.org/2000/svg">
                        <rect x="-32.54" y="78.39" width="305.92" height="84.05" rx="42.03" transform="translate(120.42 -49.88) rotate(45)"></rect>
                        <rect x="-32.54" y="78.39" width="305.92" height="84.05" rx="42.03" transform="translate(-49.88 120.42) rotate(-45)"></rect></svg
                    ><svg class="shape-2 d-none d-sm-block" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="50"></circle></svg>
                    <div class="device-wrapper">
                        <div class="device" data-device="iPhoneX" data-orientation="portrait" data-color="black">
                            <div class="screen bg-black">
                                <video muted="muted" autoplay="" loop="" style="max-width: 100%; height: 100%"><source src="./images/demo-screen.mp4" type="video/mp4" /></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@endsection

@section('content')

@endsection