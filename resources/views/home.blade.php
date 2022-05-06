@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="dashboard-welcome-label">Howdy, {{ Auth()->user()->first_name }}</h1>
        <div class="actions d-sm-flex align-items-center justify-content-between small">
            <form id="filter-dashboard" method="get">
                @csrf
                <div class="row mt-2">
                    <div class="filter-selector {{ $filter=='custom' ? 'col-md-3' : 'col-md-12' }} py-1">
                        <select name="filter" id="filter" class="form-control">
                            <option value="" {{ $filter=='' ? 'selected' : '' }}>All time</option>
                            <option value="today" {{ $filter=='today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $filter=='yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="last7" {{ $filter=='last7' ? 'selected' : '' }}>Last 7 days</option>
                            <option value="thismonth" {{ $filter=='thismonth' ? 'selected' : '' }}>This month</option>
                            <option value="custom" {{ $filter=='custom' ? 'selected' : '' }}>Custom date</option>
                        </select>
                    </div>
                    <div class="filter-date col-md-4 py-1" style="{{ $filter=='custom' ? 'display:block' : '' }}">
                        <input type="text" class="form-control datepicker" name="from" placeholder="From date" value="{{ $from }}">
                    </div>
                    <div class="filter-date col-md-4 py-1" style="{{ $filter=='custom' ? 'display:block' : '' }}">
                        <input type="text" class="form-control datepicker" name="to" placeholder="To date" value="{{ $to }}">
                    </div>
                    <div class="filter-date col-md-1 py-1" style="{{ $filter=='custom' ? 'display:block' : '' }}">
                        <button type="submit" class="btn btn-primary">Go</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Content Row -->
    @if ($payment_status == 'active')
    <div class="row">

        <!-- Number Of requests -->
        <div class="col span_1_of_5 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="dashboard-card-number">{{ $total_requests }}</div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('images/dash-request.svg') }}" class="dash-icons">
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="dashboard-card-label">Total request created</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Number of in progress -->
        <div class="col span_1_of_5 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="dashboard-card-number">{{ $inprogressreq }}</div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('images/dash-inprogress.svg') }}" class="dash-icons">
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="dashboard-card-label">In progress requests</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Number of for review -->
        <div class="col span_1_of_5 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="dashboard-card-number">{{ $reqforreview }}</div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('images/dash-for-review.svg') }}" class="dash-icons">
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="dashboard-card-label">For review requests</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Number of in queue -->
        <div class="col span_1_of_5 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="dashboard-card-number">{{ $reqqueue }}</div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('images/dash-queu.svg') }}" class="dash-icons">
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="dashboard-card-label">On queue requests</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Number of completed -->
        <div class="col span_1_of_5 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="dashboard-card-number">{{ $completed_req }}</div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('images/dash-completed.svg') }}" class="dash-icons">
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="dashboard-card-label">Completed request</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-5">
        <h2 class="dashboard-welcome-label">In progress requests</h2>
        <a href="{{ route('request.index') }}" class="btn btn-sm btn-primary">Manage Requests</a>
    </div>

    <div class="table-responsive">
        <table class="table bg-white" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th width="10%">REQUEST ID</th>
                    <th width="25%">REQUEST NAME</th>
                    <th width="25%">CATEGORY</th>
                    <th width="15%">DATE CREATED</th>
                    <th width="20%"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allinprogressreq as $progressreq)
                    <tr>
                        <td>#{{ $progressreq->id }}</td>
                        <td>{{ $progressreq->title }}</td>
                        @if(!empty($progressreq->designtype->name))
                        <td>{{ $progressreq->designtype->name }}</td>
                        @else
                        <td></td>
                        @endif
                        <td>{{ $progressreq->created_at->format('d F, Y') }}</td>
                        <td class="d-flex justify-content-end">
                            <a href="{{ route('request.view', ['requests' => $progressreq->id]) }}"
                                class="m-2 default-link">
                                <i class="fa fa-clock-o"></i>
                            </a>
                            @if ($progressreq->status == 1)
                                <a href="{{ route('request.status', ['request_id' => $progressreq->id, 'status' => 2]) }}"
                                    class="btn btn-success m-2">
                                    <i class="fa fa-check"></i>
                                </a>
                            @elseif ($progressreq->status == 2)
                                <a href="{{ route('request.status', ['request_id' => $progressreq->id, 'status' => 1]) }}"
                                    class="btn btn-danger m-2">
                                    <i class="fa fa-ban"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><a href="{{ route('request.create') }}"><i class="fa fa-plus"></i> Add New</a></td>
                </tr>
            </tfoot>
        </table>
    </div>

    @else

    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card text-center border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">You account is not fully activated. Please pay to continue use your account.</div>
                            <div class="mt-2">
                                <a href="{{ $payment_url }}" class="btn btn-primary" target="_blank">Click to pay</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif
@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        $('#filter').on('change', function(e) {
            var filter = $(this).val();

            if(filter == 'custom') {
                $('.filter-selector').removeClass('col-md-12');
                $('.filter-selector').addClass('col-md-3');
                $('.filter-date').show();
            } else {
                $('.filter-selector').removeClass('col-md-3');
                $('.filter-selector').addClass('col-md-12');
                $('.filter-date').hide();

                $('#filter-dashboard').submit();
            }
        });
    });
</script>
    
@endsection