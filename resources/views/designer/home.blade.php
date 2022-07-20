@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')
        
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

</div>
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