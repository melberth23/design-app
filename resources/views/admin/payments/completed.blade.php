@extends('layouts.app')

@section('title', 'Completed Payments')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Completed Payments</h1>
        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($payments->count() > 0)

        <!-- Requests -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Completed Payments</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="20%">Customer</th>
                                <th width="15%">Status</th>
                                <th width="10%">Plan</th>
                                <th width="10%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->user->first_name }} {{ $payment->user->last_name }}</td>
                                    <td><span class="badge badge-success">{{ __('Completed') }}</span></td>
                                    <td>{{ (new \App\Lib\SystemHelper)->getPlanInformation($payment->plan)['label'] }}</td>
                                    <td>{{ $payment->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $payments->links() }}
                </div>
            </div>
        </div>

        @else

            <div class="alert alert-danger" role="alert">
                No completed payments found! 
            </div>

        @endif  
    </div>

@endsection

@section('scripts')
    
@endsection
