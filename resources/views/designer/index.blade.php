@extends('layouts.app')

@section('title', 'Requests List')

@section('content')
    <div class="container-fluid">

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($requests->count() > 0)

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 page-heading">All requests</h1>
        </div>

        <div class="card">
            <div class="card-body py-0 px-1">
                <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                    <li class="nav-item {{ (request()->is('designers')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('designers')) ? 'active' : '' }}" id="allreq-tab" href="{{ route('designer.index') }}">All {{ $requests->count() }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('designers/queue')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('designers/queue')) ? 'active' : '' }}" id="queue-tab" href="{{ route('designer.queue') }}">Queue {{ $queue }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('designers/progress')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('designers/progress')) ? 'active' : '' }}" id="progress-tab" href="{{ route('designer.progress') }}">Progress {{ $progress }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('designers/review')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('designers/review')) ? 'active' : '' }}" id="review-tab" href="{{ route('designer.review') }}">Review {{ $review }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('designers/completed')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('designers/completed')) ? 'active' : '' }}" id="completed-tab" href="{{ route('designer.completed') }}">Completed {{ $completed }}</a>
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
                                        @if ($request->status == 0)
                                            <span class="badge badge-primary">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                        @elseif ($request->status == 2)
                                            <span class="badge badge-info">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                        @elseif ($request->status == 3)
                                            <span class="badge badge-success">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                        @elseif ($request->status == 4)
                                            <span class="badge badge-dark">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                        @endif
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
                                        @if ($request->status == 2)
                                            <a href="{{ route('designer.status', ['request_id' => $request->id, 'status' => 3]) }}"
                                                class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="Add to Progress">
                                                <i class="fa fa-list"></i>
                                            </a>
                                        @endif
                                        @if ($request->status == 3)
                                            <a class="text-dark mx-2" href="#" data-ref="{{ $request->id }}" data-toggle="modal" data-target="#reviewModal" >
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $requests->links() }}
            </div>
        </div>

        @include('designer.review-modal')  
        @else

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="min-height-600 d-flex align-items-center justify-content-center">
                        <div class="no-record py-4 text-center">
                            <img src="{{ asset('images/requests-empty.svg') }}">
                            <div class="pt-4">
                                <h2>No requests.</h2>
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
