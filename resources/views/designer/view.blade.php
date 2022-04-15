@extends('layouts.app')

@section('title', 'View Request')

@section('content')

<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="request-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('request.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">{{ $requests->title }}</span></h1>
        <div class="actions d-sm-flex align-items-center justify-content-between">
            <div class="dropdown m-1">
              <button class="btn btn-outline-light text-dark border dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                More Actions
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ route('brand.view', ['brand' => $requests->brand_id]) }}"><i class="fa fa-eye"></i> View Brand Profile</a>
                <a class="dropdown-item" href="{{ route('request.edit', ['requests' => $requests->id]) }}"><i class="fa fa-pen"></i> Edit Request</a>
                @if ($brand->status == 1)
                    <a class="dropdown-item" href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 2]) }}"><i class="fa fa-check"></i> Activate Request</a>
                @elseif ($brand->status == 2)
                    <a class="dropdown-item" href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 1]) }}"><i class="fa fa-ban"></i> Pending Request</a>
                @endif
              </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                    <h3 class="text-dark">Request Name</h3>
                </div>
                <div class="card-body py-0">

                    <div class="tab-text-label text-dark py-3">
                        {{ $requests->title }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                    <h3 class="text-dark">Design Type</h3>
                </div>
                <div class="card-body py-0">

                    <div class="tab-text-label text-dark py-3">
                        {{ $designtype->name }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
            <h3 class="text-dark">Dimensions</h3>
        </div>
        <div class="card-body py-0">

            <div class="row">
                <div class="col-sm-6">
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        {{ ucfirst($requests->dimensions) }}
                    </div>
                    <div class="tab-text-label text-dark py-3">
                        <label for="format">Format</label>
                        <p>
                            @if ($requests->format == 1)
                                {{ __('.MP4') }}
                            @else
                                {{ __('.AEP') }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-sm-6"> 
                    <div class="tab-text-label text-dark py-3">
                        <label for="dimensions">Additional Information</label>
                        <p>{{ $requests->dimensions_additional_info }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
            <h3 class="text-dark">Description</h3>
        </div>
        <div class="card-body py-0">

            <div class="tab-text-label text-dark py-3">
                {{ $requests->description }}
            </div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-light-custom">
            <h3 class="text-dark">Request Name</h3>
        </div>
        <div class="card-body py-0">
            <div class="tab-text-label text-dark py-3">
                <div class="row">
                    <div class="col-md-12">{{ $brand->name }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                    <h3 class="text-dark">Supporting materials</h3>
                </div>
                <div class="card-body py-0">

                    <div class="tab-text-label text-dark py-3">
                        <div class="d-flex pictures mt-3 pt-3 border-top">
                            @if ($medias->count() > 0)
                                @foreach ($medias as $media)
                                    <div id="media-{{ $media->id }}" class="mx-1 media media-container">
                                        <img src="{{ url('storage/media') }}/{{ auth()->user()->id }}/{{ $media->filename }}" class="picture-img">
                                        <div class="overlay">
                                            <a href="{{ route('request.download', ['asset' => $media->id]) }}" class="icon">
                                              <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p><em>-No images available</em></p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                    <h3 class="text-dark">Brand Profile</h3>
                </div>
                <div class="card-body py-0">

                    <div class="tab-text-label text-dark py-3">
                        <label for="brand_id">We will use the brand profile assets for your request</label>
                        <p><strong>{{ $brand->name }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                    <h3 class="text-dark">Brand Request Priority</h3>
                </div>
                <div class="card-body py-0">

                    <div class="tab-text-label text-dark py-3">
                        <label for="priority">This will identify which request is most priority</label>
                        <p>
                            <strong>
                                @if ($requests->format == 1)
                                    {{ __('First') }}
                                @else
                                    {{ __('Last') }}
                                @endif
                            </strong>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection