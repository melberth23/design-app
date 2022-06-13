@extends('layouts.app')

@section('title', 'Messages')

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
                <h3 class="text-dark mb-0">Files</h3>
            </div>
            <div class="card-body p4">
                <h5 class="card-label text-dark">Media Files</h5>

                @if ($medias->count() > 0)

                    <div class="d-flex pictures">
                        @foreach ($medias as $media)
                            <div id="media-{{ $media->id }}">
                                <div class="mx-1 media media-container">
                                    <img src="{{ url('storage/comments') }}/{{ $media->comments->user_id }}/{{ $media->filename }}" class="picture-img">
                                </div>
                                <label class="mt-1">{{ $media->filename }}</label>
                                <a class="btn btn-outline-light action-icons rounded-circle border" href="{{ route('comment.download', ['asset' => $media->id]) }}">
                                    <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                </a>
                            </div>
                        @endforeach
                    </div>

                @endif

            </div>
            <div class="card-footer bg-light-custom p4">
                <h5 class="card-label text-dark">Adobe Files</h5>

                @if ($adobes->count() > 0)

                    <div class="d-flex templates">
                        @foreach ($adobes as $adobe)
                            <div id="media-{{ $adobe->id }}">
                                <div class="mx-1 template media-container media-documents">
                                    <img src="{{ asset('images/template-img-') }}{{ $adobe->file_type }}.png" class="template-img">
                                </div>
                                <label class="mt-1">{{ $adobe->filename }}</label>
                                <a class="btn btn-outline-light action-icons rounded-circle border" href="{{ route('comment.download', ['asset' => $adobe->id]) }}">
                                    <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                </a>
                            </div>
                        @endforeach
                    </div>

                @endif
            </div>
        </div>
    </div>

</div>


@endsection