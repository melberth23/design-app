@extends('layouts.app')

@section('title', 'Messages')

@section('content')

<div class="container">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
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
            <a href="{{ route('request.status', ['request_id' => $requests->id, 'status' => 0]) }}" class="mx-2 d-sm-inline-block btn btn-sm btn-outline-success"><i class="fas fa-check" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Mark Complete</span></a>
            @endif
        </h1>
        <div class="actions d-flex align-items-center justify-content-end">
            <div class="dropdown m-1 d-none d-md-inline-block">
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
            @else
                @if ($requests->status == 3)
                    <div class="dropdown m-1">
                      <button class="btn btn-outline-light text-dark border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#movereviewModal"><i class="fa fa-check"></i> Move to review</a>
                      </div>
                    </div>
                @endif
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
                <h3 class="text-dark mb-0">Files</h3>
            </div>
            <div class="card-body p4">

                @if ($medias->count() > 0 || $manualmedias->count() > 0)
                    <h5 class="card-label text-dark">Media Files</h5>

                    <div class="d-flex flex-wrap pictures">
                        @foreach ($medias as $media)
                            <div id="media-{{ $media->id }}">
                                <div class="mx-1 media media-container">
                                    <img src="{{ Storage::disk('s3')->url($media->filename) }}" class="picture-img">
                                </div>
                                <label class="mt-1">{{ basename($media->filename) }}</label><br>
                                <a class="btn btn-outline-light action-icons rounded-circle border" href="{{ route('comment.download', ['asset' => $media->id]) }}">
                                    <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                </a>
                                @if(auth()->user()->hasRole('Designer'))
                                <a href="{{ route('comment.delete', ['asset' => $media->id]) }}" class="btn btn-outline-light action-icons rounded-circle border">
                                  <img src="{{ asset('images/delete-media.svg') }}" class="action-icon">
                                </a>
                                @endif
                            </div>
                        @endforeach

                        @foreach ($manualmedias as $manualmedia)
                            <div id="media-{{ $manualmedia->id }}">
                                <div class="mx-1 media media-container">
                                    <img src="{{ Storage::disk('s3')->url($manualmedia->filename) }}" class="picture-img">
                                </div>
                                <label class="mt-1">{{ basename($manualmedia->filename) }}</label><br>
                                <a class="btn btn-outline-light action-icons rounded-circle border" href="{{ route('comment.download', ['asset' => $manualmedia->id]) }}">
                                    <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                </a>
                                @if(auth()->user()->hasRole('Designer'))
                                <a href="{{ route('comment.delete', ['asset' => $manualmedia->id]) }}" class="btn btn-outline-light action-icons rounded-circle border">
                                  <img src="{{ asset('images/delete-media.svg') }}" class="action-icon">
                                </a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                @else

                    <div class="d-block text-center p-5">
                        <img src="{{ asset('images/no-media.svg') }}" class="no-media">
                        <h5 class="text-dark pt-4 font-weight-bold">No media files available</h5>
                        <p class="text-dark">Our designer will upload the media files you are looking soon.</p>
                    </div>

                @endif

                @if(auth()->user()->hasRole('Designer'))
                <form method="POST" action="{{route('request.fileupload')}}" enctype="multipart/form-data" class="form-files-request">
                    @csrf

                    <input type="hidden" name="id" value="{{ $requests->id }}">
                    <input type="hidden" id="tempfile_media_code" name="tempfile_code" value="<?php echo time(); ?>">

                    <div class="file-uploader pt-4">
                        <div id="pictures-preview" class="d-flex flex-wrap pictures">
                            <!-- Preview Images -->
                        </div>
                        <div class="request-assets tab-text-label text-dark pt-3">
                            <label for="media">Upload media files here.</label>
                            <input type="file" id="media-files" name="media[]" class="form-control-file" multiple data-multiple-caption="{count} files selected" accept=".jpg,.png,.jpeg">
                            <div id="media-uploader" class="asset-uploader rounded bg-light py-5 text-center">
                                <div class="py-1 upload-icon"><i class="fa fa-cloud-upload" aria-hidden="true"></i></div>
                                <p id="media-label" class="img-limit mb-0"></p>
                                <p class="img-description mb-0">Drag and drop or <a href="javascript:void(0);" onclick="getElementById('media-files').click();">click here</a> to attach</p>
                                <p class="img-limit">Acceptable file, PNG, JPEG, JPG max file size 150mb.</p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Upload</button>
                </form>
                @endif
            </div>
            <div class="card-footer bg-light-custom p4">

                @if ($adobes->count() > 0 || $manualadobes->count() > 0)
                    <h5 class="card-label text-dark">Adobe Files</h5>

                    <div class="d-flex flex-wrap templates">
                        @foreach ($adobes as $adobe)
                            <div id="media-{{ $adobe->id }}">
                                <div class="mx-1 template media-container media-documents">
                                    <img src="{{ asset('images/template-img-') }}{{ $adobe->file_type }}.png" class="template-img">
                                </div>
                                <label class="mt-1">{{ basename($adobe->filename) }}</label><br>
                                <a class="btn btn-outline-light action-icons rounded-circle border" href="{{ route('comment.download', ['asset' => $adobe->id]) }}">
                                    <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                </a>
                                @if(auth()->user()->hasRole('Designer'))
                                <a href="{{ route('comment.delete', ['asset' => $adobe->id]) }}" class="btn btn-outline-light action-icons rounded-circle border">
                                  <img src="{{ asset('images/delete-media.svg') }}" class="action-icon">
                                </a>
                                @endif
                            </div>
                        @endforeach

                        @foreach ($manualadobes as $manualadobe)
                            <div id="media-{{ $manualadobe->id }}">
                                <div class="mx-1 template media-container media-documents">
                                    <img src="{{ asset('images/template-img-') }}{{ $manualadobe->file_type }}.png" class="template-img">
                                </div>
                                <label class="mt-1">{{ basename($manualadobe->filename) }}</label><br>
                                <a class="btn btn-outline-light action-icons rounded-circle border" href="{{ route('comment.download', ['asset' => $manualadobe->id]) }}">
                                    <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                </a>
                                @if(auth()->user()->hasRole('Designer'))
                                <a href="{{ route('comment.delete', ['asset' => $manualadobe->id]) }}" class="btn btn-outline-light action-icons rounded-circle border">
                                  <img src="{{ asset('images/delete-media.svg') }}" class="action-icon">
                                </a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                @else

                    <div class="d-block text-center p-5">
                        <img src="{{ asset('images/no-adobe.svg') }}" class="no-media">
                        <h5 class="text-dark pt-4 font-weight-bold">No adobe files available</h5>
                        <p class="text-dark">Our designer will upload adobe files you are looking soon.</p>
                    </div>

                @endif

                @if(auth()->user()->hasRole('Designer'))
                <form method="POST" action="{{route('request.fileupload')}}" enctype="multipart/form-data" class="form-adobe-request">
                    @csrf

                    <input type="hidden" name="id" value="{{ $requests->id }}">
                    <input type="hidden" id="tempfile_adobe_code" name="tempfile_code" value="<?php echo time(); ?>">

                    <div class="file-uploader pt-4">
                        <div id="adobe-previews" class="d-flex flex-wrap templates">
                            <!-- Preview files -->
                        </div>
                        <div class="request-assets tab-text-label text-dark pt-3">
                            <label for="media">Upload adobe files here.</label>
                            <input type="file" id="adobe-files" name="documents[]" class="form-control-file" multiple data-multiple-caption="{count} files selected" accept=".psd,.ai,.indd,.pdf">
                            <div id="adobe-uploader" class="asset-uploader rounded bg-light py-5 text-center">
                                <div class="py-1 upload-icon"><i class="fa fa-cloud-upload" aria-hidden="true"></i></div>
                                <p id="adobe-label" class="img-limit mb-0"></p>
                                <p class="img-description mb-0">Drag and drop or <a href="javascript:void(0);" onclick="getElementById('adobe-files').click();">click here</a> to attach</p>
                                <p class="img-limit">Acceptable file, PSD, AI, INDD, PDF max file size 150mb.</p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Upload</button>
                </form>
                @endif
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

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        $("html").on("dragover", function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

        // Media
        var $parent    = $('#media-uploader'),
            $input    = $('#media-files'),
            $label    = $('#media-label'),
            showFiles = function(files) {
              $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
            };

        $parent.on('drop', function(e) {
          droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
          showFiles( droppedFiles );
          showPreview(droppedFiles);
        });

        $input.on('change', function(e) {
          showFiles(e.target.files);
          showPreview(e.target.files);
        });

        // Adobe
        var $adobeparent    = $('#adobe-uploader'),
            $adobeinput    = $('#adobe-files'),
            $adobelabel    = $('#adobe-label'),
            showAdobeFiles = function(files) {
              $adobelabel.text(files.length > 1 ? ($adobeinput.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
            };

        $adobeparent.on('drop', function(e) {
          droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
          showAdobeFiles( droppedFiles );
          showAdobePreview(droppedFiles);
        });

        $adobeinput.on('change', function(e) {
          showAdobeFiles(e.target.files);
          showAdobePreview(e.target.files);
        });
    });

    function showPreview(files)
    {
        $.each( files, function( i, file ) {
            var form_data = new FormData();
            form_data.append("_token", $('.form-files-request').find("input[name=_token]").val());
            form_data.append("comment_media", file);
            form_data.append("tempfile_code", $('#tempfile_adobe_code').val());
            form_data.append("module", "comment_media");

            $.ajax({
                url:'{{ route('tempfiles') }}',
                method:'POST',
                data:form_data,
                contentType:false,
                cache:false,
                processData:false,
                success: function(data) {
                    $('#pictures-preview').append('<div id="media-preview-'+ data.file.picture_id +'" class="mx-1 picture media-container"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.picture_id +');"><i class="fas fa-times"></i></a><img src="'+ data.file.path +'" class="picture-img" /></div>');
                }
            });
        });
    }

    function showAdobePreview(files)
    {
        $.each( files, function( i, file ) {
            var form_data = new FormData();
                form_data.append("_token", $('.form-adobe-request').find("input[name=_token]").val());
                form_data.append("comment_document", file);
                form_data.append("tempfile_code", $('#tempfile_adobe_code').val());
                form_data.append("module", "comment_document");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        var filename = data.file.path;
                        var fileExt = filename.split('.').pop();

                        $('#adobe-previews').append('<div id="media-preview-'+ data.file.adobe_id +'"><div class="mx-1 template media-container media-documents"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.adobe_id +');"><i class="fas fa-times"></i></a><img src="<?php echo asset('images/template-img-'); ?>'+ fileExt +'.png" class="template-img" /></div></div>');
                    }
                });
        });
    }

    function removeTempFile(id)
    {
        var form_data = new FormData();
        form_data.append("_token", jQuery('.form-files-request').find("input[name=_token]").val());
        form_data.append("fid", id);

        jQuery.ajax({
            url:'{{ route('delete.tempfiles') }}',
            method:'POST',
            data:form_data,
            contentType:false,
            cache:false,
            processData:false,
            success: function(data) {
                jQuery('#media-preview-'+ data.fid).remove();
            }
        });
    }
</script>
@endsection