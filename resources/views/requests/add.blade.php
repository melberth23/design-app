@extends('layouts.app')

@section('title', 'Add Request')

@section('content')

<div class="container">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><span class="mx-2">Create Request</span></h1>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')

    <div class="row">
        @foreach ($designtypes as $designtype)
            <div class="col-md-3 my-2">
                <a class="text-decoration-none" href="{{ route('request.requesttype', ['type' => $designtype->id]) }}">
                    <div class="card rounded text-center min-height-150 d-flex align-items-center justify-content-center">
                        <div class="p-3">
                            <img src="{{ asset('images/request') }}/{{$designtype->name}}.svg" class="width-30 request-img py-3">
                            <h5 class="request-type-label text-dark">{{$designtype->name}}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

</div>


@endsection