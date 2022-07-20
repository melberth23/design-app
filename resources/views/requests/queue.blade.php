@extends('layouts.app')

@section('title', 'Queue Requests List')

@section('content')
    <div class="container-fluid">

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($requests->count() > 0)

        <!-- Page Heading -->
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
                        <a class="nav-link py-3 {{ (request()->is('requests/queue')) ? 'active' : '' }}" id="queue-tab" href="{{ route('request.queue') }}">Queue {{ $requests->count() }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('requests/progress')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('requests/progress')) ? 'active' : '' }}" id="progress-tab" href="{{ route('request.progress') }}">Progress {{ $progress }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('requests/review')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('requests/review')) ? 'active' : '' }}" id="review-tab" href="{{ route('request.review') }}">Review {{ $review }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('requests/delivered')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('requests/delivered')) ? 'active' : '' }}" id="delivered-tab" href="{{ route('request.delivered') }}">Completed {{ $completed }}</a>
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-light-custom p-0">
                <div class="table-responsive">
                    <table id="queue-request" class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="2%" class="border-left-0 border-right-0"></th>
                                <th width="8%" class="border-left-0 border-right-0">REQUEST ID</th>
                                <th width="25%" class="border-left-0 border-right-0">REQUEST NAME</th>
                                <th width="20%" class="border-left-0 border-right-0">CATEGORY</th>
                                <th width="5%" class="border-left-0 border-right-0">PRIORITY</th>
                                <th width="10%" class="border-left-0 border-right-0">STATUS</th>
                                <th width="10%" class="border-left-0 border-right-0">DATE CREATED</th>
                                <th width="20%" class="border-left-0 border-right-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr id="{{ $request->id }}">
                                    <td class="border-left-0 border-right-0 text-primary"><img src="{{ asset('images/ellipses.svg') }}"></td>
                                    <td class="border-left-0 border-right-0 text-primary">#{{ $request->id }}</td>
                                    <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('request.view', ['requests' => $request->id]) }}">{{ $request->title }}</a></td>
                                    @if(!empty($request->designtype->name))
                                    <td class="border-left-0 border-right-0">{{ $request->designtype->name }}</td>
                                    @else
                                    <td class="border-left-0 border-right-0"></td>
                                    @endif
                                    <td id="priority" class="border-left-0 border-right-0 text-primary">{{ (!is_null($request->priority) ? $request->priority+1 : '') }}</td>
                                    <td class="border-left-0 border-right-0">
                                        <span class="badge badge-info">{{ (new \App\Lib\SystemHelper)->statusLabel($request->status) }}</span>
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
                                            <a href="{{ route('request.status', ['request_id' => $request->id, 'status' => 1]) }}"
                                                class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="Move to Draft">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                            <a href="{{ route('request.edit', ['requests' => $request->id]) }}"
                                                class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="Edit Request">
                                                <i class="fa fa-pen"></i>
                                            </a>
                                            <a class="text-dark mx-2" href="#" data-ref="{{ $request->id }}" data-toggle="modal" data-target="#deleteRequestModal">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-2 pt-3">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        You can sort your Queue request per priority.
                    </div>
                </div>

                <div class="px-3 py-3">
                    <a class="text-decoration-none" href="{{ route('request.create') }}"><i class="fa fa-plus"></i> Add New</a>
                </div>
                
            </div>
        </div>

        @include('requests.delete-modal')  
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    // Initialise the table
    jQuery("#queue-request").tableDnD({
        onDragClass: "request-drag",
        onDrop: function(table, row) {
            var rows = table.tBodies[0].rows;
            console.log(rows.length);
            var sortkeys = [];
            for (var i=0; i < rows.length; i++) {
                sortkeys.push(rows[i].id);
                jQuery('#'+ rows[i].id).find('#priority').html(i+1);
            }
            
            jQuery.ajax({
                type:'POST',
                url:"{{ route('request.sort') }}",
                data: {
                    sortkeys:sortkeys,
                    _token: jQuery('meta[name="csrf-token"]').attr('content')
                },
                success:function(data) {
                    $('#dimensions').html(data.dimensions);
                }
            });
        },
        onDragStart: function(table, row) {
            console.log(row.id);
        }
    });
});
</script> 
@endsection
