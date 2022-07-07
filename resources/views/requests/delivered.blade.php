@extends('layouts.app')

@section('title', 'Delivered Requests List')

@section('content')
    <div class="container-fluid">

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($requests->count() > 0)

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 page-heading">My requests</h1>
            <div class="actions d-flex align-items-center justify-content-between">
                <a href="{{ route('request.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <span class="d-none d-md-inline-block">New</span>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body py-0 px-1">
                <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                    <li class="nav-item {{ (request()->is('requests')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('requests')) ? 'active' : '' }}" id="queue-tab" href="{{ route('request.index') }}">All {{ $all }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('requests/queue')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('requests/queue')) ? 'active' : '' }}" id="queue-tab" href="{{ route('request.queue') }}">Queue {{ $queue }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('requests/progress')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('requests/progress')) ? 'active' : '' }}" id="progress-tab" href="{{ route('request.progress') }}">Progress {{ $progress }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('requests/review')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('requests/review')) ? 'active' : '' }}" id="review-tab" href="{{ route('request.review') }}">Review {{ $review }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('requests/delivered')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('requests/delivered')) ? 'active' : '' }}" id="delivered-tab" href="{{ route('request.delivered') }}">Completed {{ $requests->count() }}</a>
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-light-custom p-0">
                <div class="table-responsive">
                    <table class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="10%" class="border-left-0 border-right-0">REQUEST ID</th>
                                <th width="25%" class="border-left-0 border-right-0">REQUEST NAME</th>
                                <th width="25%" class="border-left-0 border-right-0">CATEGORY</th>
                                <th width="10%" class="border-left-0 border-right-0">STATUS</th>
                                <th width="10%" class="border-left-0 border-right-0">DATE CREATED</th>
                                <th width="20%" class="border-left-0 border-right-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td class="border-left-0 border-right-0 text-primary">#{{ $request->id }}</td>
                                    <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('request.view', ['requests' => $request->id]) }}">{{ $request->title }}</a></td>
                                    @if(!empty($request->designtype->name))
                                    <td class="border-left-0 border-right-0">{{ $request->designtype->name }}</td>
                                    @else
                                    <td class="border-left-0 border-right-0"></td>
                                    @endif
                                    <td class="border-left-0 border-right-0">
                                        <span class="badge badge-primary">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                    </td>
                                    <td class="border-left-0 border-right-0">{{ $request->created_at->format('d F, Y') }}</td>
                                    <td class="d-flex justify-content-end border-left-0 border-right-0">
                                        <a href="{{ route('request.view', ['requests' => $request->id]) }}"
                                            class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="View Request">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('request.comment', ['requests' => $request->id]) }}"
                                            class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="Messages">
                                            <i class="fa fa-comments"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-2 py-3">
                    <a class="text-decoration-none" href="{{ route('request.create') }}"><i class="fa fa-plus"></i> Add New</a>
                </div>
                
                {{ $requests->links() }}
            </div>
        </div>

        @else

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="min-height-600 d-flex align-items-center justify-content-center">
                        <div class="no-record py-4 text-center">
                            <img src="{{ asset('images/requests-empty.svg') }}">
                            <div class="pt-4">
                                <h2>You have no requests.</h2>
                            </div>
                            <div class="pt-4">
                                <h5>Create your request now.</h5>
                            </div>
                            <div class="pt-4">
                                <a href="{{ route('request.create') }}" class="btn btn-primary">Create New Request</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif  
    </div>

@endsection

@section('scripts')
    
@endsection
