@extends('layouts.app')

@section('title', 'View Request')

@section('content')

<div class="container">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="request-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between page-heading"><a href="{{ $backurl }}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">{{ $requests->title }}</span>
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
            @if ($requests->status == 4 && !auth()->user()->hasRole('Designer'))
            <a href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 0]) }}" class="mx-2 d-sm-inline-block btn btn-sm btn-outline-success"><i class="fas fa-check" aria-hidden="true"></i> Mark Complete</a>
            @endif
        </h1>
        <div class="actions d-sm-flex align-items-center justify-content-between">
            @if (auth()->user()->hasRole('Designer') && $requests->status == 2)
                <a href="{{ route('designer.status', ['request_id' => $requests->id, 'status' => 3]) }}" class="mx-2 d-sm-inline-block btn btn-sm btn-outline-success"><i class="fa fa-list"></i> Move to progress</a>
            @endif
            @if (auth()->user()->hasRole('Designer') && $requests->status == 3)
                <a href="#" data-toggle="modal" data-target="#movereviewModal" class="mx-2 d-sm-inline-block btn btn-sm btn-outline-dark"><i class="fas fa-check" aria-hidden="true"></i> Move to review</a>
            @endif
            <div class="dropdown m-1">
                <button class="btn btn-outline-light text-dark border" id="dropdownUpdate{{ $requests->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-clock-o"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="dropdownUpdate{{ $requests->id }}">
                    <span>Last Updated: {{ $requests->updated_at->format('d F, Y, h:i:s A') }}</span>
                </div>
            </div>
            @if(!auth()->user()->hasRole('Designer'))
            <div class="dropdown m-1">
              <button class="btn btn-outline-light text-dark border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                @if ($requests->status == 1)
                    <a class="dropdown-item" href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 2]) }}"><i class="fa fa-check"></i> Submit Request</a>
                @elseif ($requests->status == 2)
                    <a class="dropdown-item" href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 1]) }}"><i class="fa fa-ban"></i> Move to draft</a>
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
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body py-0 px-1">
            <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link py-3 {{ (str_contains(url()->current(), 'requests/view/')) ? 'active' : '' }}" id="details-tab" href="{{ route('request.view', ['requests' => $requests->id]) }}">Request Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 {{ (str_contains(url()->current(), 'requests/files/')) ? 'active' : '' }}" id="files-tab" href="{{ route('request.files', ['requests' => $requests->id]) }}"><span class="d-inline-block">Files</span><span class="counter counter-lg bg-primary">{{ $filenotifications->count() }}</span></a>
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
                <!-- <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Delivery Date</div>
                        <div class="col-md-9">{{ $requests->created_at->format('d F, Y') }}</div>
                    </div>
                </div> -->
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
                        <div class="col-md-9"><?php echo nl2br($requests->description); ?></div>
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <div class="row">
                        <div class="col-md-3 single-label">Text to include on the design</div>
                        <div class="col-md-9"><?php echo nl2br($requests->included_text_description); ?></div>
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
                            <div class="d-flex flex-wrap pictures">
                                @if ($medias->count() > 0)
                                    @foreach ($medias as $media)
                                        <div id="media-{{ $media->id }}" class="mx-1 media media-container">
                                            <img src="{{ Storage::disk('s3')->url($media->filename) }}" class="picture-img">
                                            <div class="overlay">
                                                <div class="full-height d-flex align-items-center justify-content-center">
                                                    <a href="{{ route('request.download', ['asset' => $media->id]) }}" class="action-icon">
                                                      <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                    <a href="{{ route('request.delete', ['asset' => $media->id]) }}" class="action-icon">
                                                      <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                </div>
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
                        <?php 
                        $reference_link = $requests->reference_link;
                        if (strpos($reference_link, "http") === false){
                            $reference_link = 'http://'. $requests->reference_link;
                        }
                        ?>
                        <div class="col-md-9"><a href="{{ $reference_link }}" target="_blank">{{ $requests->reference_link }}</a></div>
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

@if(auth()->user()->hasRole('Designer'))
<div class="modal fade" id="movereviewModal" tabindex="-1" role="dialog" aria-labelledby="movereviewModalExample"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="movereviewModalExample">Please upload all your works before moving for review.</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('designer.addfilereview') }}" enctype="multipart/form-data" id="form-review-request">
                    @csrf

                    <input type="hidden" id="request_ref_id" name="id" value="{{ $requests->id }}">
                    <div class="text-dark py-3">
                        <label for="media">Add Images</label>
                        <input type="file" name="media[]" class="form-control-file" multiple >
                    </div>

                    <hr>

                    <div class="text-dark py-3">
                        <label for="documents">Add Documents</label>
                        <input type="file" name="documents[]" class="form-control-file" multiple >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-success" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('form-review-request').submit();">
                    Review
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection