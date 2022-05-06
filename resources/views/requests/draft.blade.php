@extends('layouts.app')

@section('title', 'Draft Requests List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Draft Requests</h1>
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
                <h6 class="m-0 font-weight-bold text-primary">All Draft Requests</h6>
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
                                        <span class="badge badge-warning">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
                                    </td>
                                    <td style="display: flex">
                                        <a href="{{ route('request.view', ['requests' => $request->id]) }}"
                                            class="btn btn-info m-2" data-toggle="tooltip" data-placement="top" title="View Request">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('request.status', ['request_id' => $request->id, 'status' => 2]) }}"
                                            class="btn btn-success m-2" data-toggle="tooltip" data-placement="top" title="Submit Request">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $requests->links() }}
                </div>
            </div>
        </div>

        @else

            <div class="alert alert-danger" role="alert">
                No requests found! 
            </div>

        @endif  
    </div>

@endsection

@section('scripts')
    
@endsection
