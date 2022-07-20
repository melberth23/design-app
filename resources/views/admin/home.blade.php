@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="dashboard-welcome-label">Hello {{ Auth()->user()->first_name }}, here's your requests activity.</h1>
    </div>

    <!-- Content Row -->
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
                            <div class="dashboard-card-label">Total request</div>
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

    <div class="row">
        <div class="col">
            <div class="card shadow py-2">
                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-between m-0">
                        <h2 class="page-heading text-dark m-0">Request Activity</h2>
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
                    <!-- Canvas Chart -->
                    <canvas id="dashboard-chart" width="400" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')

<script>
const ctx = document.getElementById('dashboard-chart').getContext('2d');
const data = {
    labels: <?php echo json_encode($chartlabels); ?>,
    datasets: [
        {
            label: 'Added Requests',
            data: <?php echo json_encode($queuealldata); ?>,
            borderColor: '#9672FB',
            fill:false
        },
        {
            label: 'Completed Requests',
            data: <?php echo json_encode($overalldata); ?>,
            borderColor: '#388D3C',
            fill:false
        }
    ]
};
const myChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'chartArea',
            },
            title: {
                display: true,
                text: 'Request Chart'
            }
        }
    },
});
</script>
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