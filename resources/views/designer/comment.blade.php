@extends('layouts.app')

@section('title', 'Messages')

@section('content')

<div class="container">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Messages</h1>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Messages</h6>
        </div>
        <div class="card-body p4">
            @if ($comments->count() > 0)

                <ul class="chat-list">

                    @foreach ($comments as $comment)

                        @if($comment->user->id == Auth::id())
                            <li class="out">
                                <div class="chat-img">
                                    <img alt="Avtar" src="{{ asset('admin/img/avatar1.png') }}">
                                </div>
                                <div class="chat-body">
                                    <div class="chat-message">
                                        <h5>{{ $comment->user->first_name }}</h5>
                                        <p>{{ $comment->comments }}</p>
                                        <p>
                                        @foreach (App\Models\CommentsAssets::where('comments_id', $comment->id)->get() as $asset)
                                            <a href="{{ route('comment.download', ['asset' => $asset->id]) }}"><i class="fa fa-download" aria-hidden="true"></i> {{ __('view') }}</a>
                                        @endforeach
                                        </p>
                                        <p><span>{{ $comment->created_at->format('D, d F, Y') }}</span></p>
                                    </div>
                                </div>
                            </li>
                        @else
                            <li class="in">
                                <div class="chat-img">
                                    <img alt="Avtar" src="{{ asset('admin/img/avatar6.png') }}">
                                </div>
                                <div class="chat-body">
                                    <div class="chat-message">
                                        <h5>{{ $comment->user->first_name }}</h5>
                                        <p>{{ $comment->comments }}</p>
                                        <p>
                                        @foreach (App\Models\CommentsAssets::where('comments_id', $comment->id)->get() as $asset)
                                            <a href="{{ route('comment.download', ['asset' => $asset->id]) }}"><i class="fa fa-download" aria-hidden="true"></i> {{ __('view') }}</a>
                                        @endforeach
                                        </p>
                                        <p><span>{{ $comment->created_at->format('D, d F, Y') }}</span></p>
                                    </div>
                                </div>
                            </li>
                        @endif

                    @endforeach

                </ul>

            @else

                <div class="alert alert-danger" role="alert">
                    No comments
                </div>

            @endif

        </div>
        <div class="card-footer bg-light-custom">
            <form method="post" action="{{ route('designer.addcomment') }}" enctype="multipart/form-data" >
                @csrf

                <input type="hidden" name="id" value="{{ $requests->id }}">
                <div class="card mb-4">
                    <div class="card-body p4">
                        <div class="text-dark py-3 border-bottom">
                            <textarea id="comment" placeholder="Enter message here" class="form-control form-control-user @error('comment') is-invalid @enderror" name="comment">{{ old('comment') }}</textarea>

                            @error('comment')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="text-dark py-3">
                            <label>Attach File</label>
                            <input type="file" name="attachments[]" class="form-control-file" multiple >
                        </div>
                    </div>
                    <div class="card-footer p0">
                        <button type="submit" class="btn btn-primary btn-user mb-3">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>


@endsection