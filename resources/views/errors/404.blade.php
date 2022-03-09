@extends('layouts.frontnonav')

@section('title', 'Permission Error')

@section('content')
<div class="page-wrap d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <span class="display-1 d-block">404</span>
                <div class="mb-4 lead">The page you are looking for was not found.</div>
                <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .page-wrap {
        min-height: 100vh;
    }
</style>

@endsection