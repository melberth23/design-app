@extends('layouts.app')

@section('title', 'Queue Requests List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Queue Requests</h1>
            <a href="{{ route('request.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($requests->count() > 0)

        <!-- Requests -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Requests</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="20%">Title</th>
                                <th width="15%">Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td>{{ $request->title }}</td>
                                    <td>
                                        @if ($request->status == 1)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($request->status == 2)
                                            <span class="badge badge-info">Active</span>
                                        @elseif ($request->status == 3)
                                            <span class="badge badge-success">Progress</span>
                                        @endif
                                    </td>
                                    <td style="display: flex">
                                        @if ($request->status == 3)
                                            <a href="{{ route('request.comment', ['requests' => $request->id]) }}"
                                                class="btn btn-primary m-2">
                                                <i class="fa fa-comments"></i>
                                            </a>
                                        @endif
                                        @if ($request->status == 2)
                                            <a href="{{ route('request.edit', ['requests' => $request->id]) }}"
                                                class="btn btn-primary m-2">
                                                <i class="fa fa-pen"></i>
                                            </a>
                                            <a class="btn btn-danger m-2" href="#" data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('requests.delete-modal')  
        @else

            <div class="alert alert-danger" role="alert">
                No requests found! 
            </div>

        @endif  
    </div>

@endsection

@section('scripts')
    
@endsection
