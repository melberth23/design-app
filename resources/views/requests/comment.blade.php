@extends('layouts.app')

@section('title', 'Messages')

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
                <h3 class="text-dark mb-0">Comments</h3>
            </div>
            <div class="card-body p4">
                <div class="py-2">
                    <form method="post" action="{{ route('request.addcomment') }}" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" name="id" value="{{ $requests->id }}">
                        <div class="py-1">
                            <textarea id="comment" rows="4" cols="50" placeholder="Add comment..." class="form-control form-control-user @error('comment') is-invalid @enderror" name="comment">{{ old('comment') }}</textarea>

                            @error('comment')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="d-flex align-items-center justify-content-end py-1">
                            <div class="d-none text-dark py-3">
                                <label>Attach File</label>
                                <input type="file" id="attachments" name="attachments[]" class="form-control-file" multiple accept="image/png, image/gif, image/jpeg, application/pdf">
                            </div>
                            <a class="btn btn-link text-dark" href="javascript:void(0);" onclick="getElementById('attachments').click();" >
                                <i class="fa fa-paperclip" aria-hidden="true"></i>
                            </a>
                            <button type="submit" class="btn btn-primary btn-user">Post</button>
                        </div>
                    </form>

                    <div id="comment-media-preview" class="d-flex pictures" data-toggle="tooltip" data-placement="left" title="All files are in preview to replace select new sets of files">
                        <!-- Preview Files -->
                    </div>
                </div>
                <div class="py-2">
                    @if ($comments->count() > 0)

                        <ul class="comments list-unstyled">

                            @foreach ($comments as $comment)

                                <li class="media py-3">
                                    <img class="width-64 img-thumbnail border-0 mr-3 rounded-circle" src="{{ asset('admin/img') }}/{{ $comment->user->roles->first()->name }}.png" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <h5 class="mt-0">{{ $comment->user->first_name }} <span class="comment-date">{{ $comment->created_at->format('D, d F, Y') }}</span></h5>
                                        @if($comment->user->roles->first()->name == 'Designer')
                                        <div class="role"><span class="text-dark">DesignsOwl Designer</span></div>
                                        @endif
                                        @if($comment->user->roles->first()->name == 'Admin')
                                        <div class="role"><span class="text-dark">DesignsOwl Admin</span></div>
                                        @endif
                                        <p class="comment pt-2">{{ $comment->comments }}</p>
                                        <div class="attachments">
                                            @foreach (App\Models\CommentsAssets::where('comments_id', $comment->id)->get() as $asset)
                                                <a class="mx-2" href="{{ route('comment.download', ['asset' => $asset->id]) }}"><i class="fa fa-download" aria-hidden="true"></i> {{ $asset->filename }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>

                            @endforeach

                        </ul>

                    @else

                        <div class="d-flex align-items-center justify-content-center">
                            <div class="no-record py-4 text-center text-dark">
                                <img src="{{ asset('images/no-comments.svg') }}">
                                <div class="pt-4">
                                    <h2>No comment available</h2>
                                </div>
                                <div class="pt-4">
                                    <h5>We are looking for your feedback, please let the designers know your thoughts.</h5>
                                </div>
                            </div>
                        </div>

                    @endif
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

@section('scripts')
<script>
    jQuery(function($) {
        $('#attachments').on('change', function(e) {
            var files = e.target.files;
            $('#comment-media-preview').html('');
            $.each( files, function( i, file ) {
                var filename = file.name;
                var fileExt = filename.split('.').pop();
                if(fileExt == 'pdf') {
                   $('#comment-media-preview').append('<div><div class="mx-1 template media-container media-documents"><img src="<?php echo asset('images/template-img-pdf.png'); ?>" class="font-img" /></div><label class="mt-1">'+ filename +'</label></div>');
                } else {
                    var reader = new FileReader();
                    reader.readAsDataURL(file);

                    reader.onload = readerEvent => {
                        var content = readerEvent.target.result; // this is the content!
                        $('#comment-media-preview').append('<div><div class="mx-1 media media-container"><img src="'+ content +'" class="picture-img" /></div></div>');
                    }
                }
            });
        });
    });
</script>
@endsection