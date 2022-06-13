@extends('layouts.app')

@section('title', 'View Request')

@section('content')

<div class="container">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="request-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between page-heading"><a href="{{route('request.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">{{ $requests->title }}</span>
            @if ($requests->status == 0)
                <span class="badge badge-primary py-2">{{ (new \App\Lib\SystemHelper)->statusLabel($requests->status) }}</span>
            @elseif ($requests->status == 1)
                <span class="badge badge-warning py-2">{{ (new \App\Lib\SystemHelper)->statusLabel($requests->status) }}</span>
            @elseif ($requests->status == 2)
                <span class="badge badge-info py-2">{{ (new \App\Lib\SystemHelper)->statusLabel($requests->status) }}</span>
            @elseif ($requests->status == 3)
                <span class="badge badge-success py-2">{{ (new \App\Lib\SystemHelper)->statusLabel($requests->status) }}</span>
            @elseif ($requests->status == 4)
                <span class="badge badge-dark py-2">{{ (new \App\Lib\SystemHelper)->statusLabel($requests->status) }}</span>
            @endif
            @if ($requests->status == 4)
            <a href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 0]) }}" class="mx-2 d-sm-inline-block btn btn-sm btn-outline-success"><i class="fas fa-check" aria-hidden="true"></i> Mark Complete</a>
            @endif
        </h1>
        <div class="actions d-sm-flex align-items-center justify-content-between">
            <div class="dropdown m-1">
                <button class="btn btn-outline-light text-dark border" id="dropdownUpdate{{ $requests->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-clock-o"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="dropdownUpdate{{ $requests->id }}">
                    <span>Last Updated: {{ $requests->updated_at->format('d F, Y, h:i:s A') }}</span>
                </div>
            </div>
            <div class="dropdown m-1">
              <button class="btn btn-outline-light text-dark border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                @if ($requests->status == 1)
                    <a class="dropdown-item" href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 2]) }}"><i class="fa fa-check"></i> Activate Request</a>
                @elseif ($requests->status == 2)
                    <a class="dropdown-item" href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 1]) }}"><i class="fa fa-ban"></i> Pending Request</a>
                @endif
                @if ($requests->status == 1 || $requests->status == 2)
                    <a class="dropdown-item" href="{{ route('request.edit', ['requests' => $requests->id]) }}"><i class="fa fa-pen"></i> Edit Request</a>
                    <a class="dropdown-item" href="{{ route('request.edit', ['requests' => $requests->id]) }}"><i class="fa fa-trash"></i> Delete Request</a>
                @endif
                @if ($requests->status == 3)
                    <div class="px-2">
                        <span><i class="fa fa-info-circle" aria-hidden="true"></i> No actions available if your request is already in progress.</span>
                    </div>
                @endif
                @if ($requests->status == 4)
                    <a class="dropdown-item" href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 0]) }}">
                        <i class="fas fa-check-circle" aria-hidden="true"></i> Complete Request
                    </a>
                @endif
                @if ($requests->status == 0)
                    <div class="px-2">
                        <span><i class="fa fa-info-circle" aria-hidden="true"></i> No actions available if your request is already completed.</span>
                    </div>
                @endif
              </div>
            </div>
            <div class="btn-group m-1" role="group" aria-label="Actions">
                @if ($previous)
                    <a href="{{ route('request.view', ['requests' => $previous]) }}" class="btn btn-outline-light text-dark border"><i class="fas fa-angle-left fa-sm"></i></a>
                @else
                    <a href="javascript:void(0);" class="btn btn-outline-light text-dark border disabled"><i class="fas fa-angle-left fa-sm"></i></a>
                @endif
                @if ($next)
                    <a href="{{ route('request.view', ['requests' => $next]) }}" class="btn btn-outline-light text-dark border"><i class="fas fa-angle-right fa-sm"></i></a>
                @else
                    <a href="javascript:void(0);" class="btn btn-outline-light text-dark border disabled"><i class="fas fa-angle-right fa-sm"></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body py-0 px-1">
            <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link py-3 {{ (str_contains(url()->current(), 'requests/view/')) ? 'active' : '' }}" id="details-tab" href="{{ route('request.view', ['requests' => $requests->id]) }}">Request Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 {{ (str_contains(url()->current(), 'requests/files/')) ? 'active' : '' }}" id="files-tab" href="{{ route('request.files', ['requests' => $requests->id]) }}">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 {{ (str_contains(url()->current(), 'requests/comment/')) ? 'active' : '' }}" id="comments-tab" href="{{ route('request.comment', ['requests' => $requests->id]) }}"><span class="d-inline-block">Comments</span><span class="counter counter-lg bg-primary">{{ $notifications->count() }}</span></a>
                </li>
            </ul>
        </div>
    </div>

    <div id="brand-tab-contents">
        <div class="card mb-4">
            <div class="card-header bg-light-custom py-4">
                <h3 class="text-dark mb-0">Request details</h3>
            </div>
            <div class="card-body py-0">
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Request Created</div>
                        <div class="col-md-9">{{ $requests->created_at->format('d F, Y') }}</div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Delivery Date</div>
                        <div class="col-md-9">{{ $requests->created_at->format('d F, Y') }}</div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Project Category</div>
                        <div class="col-md-9">
                            @if(!empty($requests->designtype->name))
                                {{ $requests->designtype->name }}
                            @else
                                <!-- Empty -->
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Project Name</div>
                        <div class="col-md-9">{{ $requests->title }}</div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Project Brief</div>
                        <div class="col-md-9">{{ $requests->description }}</div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Text to include on the design</div>
                        <div class="col-md-9">{{ $requests->included_text_description }}</div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Design Dimension</div>
                        <div class="col-md-9">{{ ($requests->dimensions == 'custom') ? $requests->custom_dimension : $requests->dimensions }}</div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Brand Profile</div>
                        <div class="col-md-9"><a class="text-decoration-none" href="{{ route('brand.view', ['brand' => $requests->brand_id]) }}">{{ $requests->brand->name }}</a></div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label"><span class="text-dark font-weight-bold">Assets</span></div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Design assets</div>
                        <div class="col-md-9">
                            <div class="d-flex pictures">
                                @if ($medias->count() > 0)
                                    @foreach ($medias as $media)
                                        <div id="media-{{ $media->id }}" class="mx-1 media media-container">
                                            <img src="{{ url('storage/media') }}/{{ $requests->user_id }}/{{ $media->filename }}" class="picture-img">
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
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Reference Links</div>
                        <div class="col-md-9">{{ $requests->reference_link }}</div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label"><span class="text-dark font-weight-bold">File Types</span></div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Files Needed</div>
                        <div class="col-md-9">{{ $requests->format }}</div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Adobe Files</div>
                        <div class="col-md-9">{{ $requests->adobe_format }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection