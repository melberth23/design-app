@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')

    @if ($payments->count() > 0)

    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 page-heading">Payment History</h1>
    </div>

    <div class="card">
        <div class="card-body py-0 px-1">
            <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                <li class="nav-item border-bottom">
                    <a class="nav-link py-3" id="all-tab" href="{{ route('adminpayment.index') }}">All {{ $all }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3" id="pending-tab" href="{{ route('adminpayment.pending') }}">Pending {{ $pending }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 active" id="completed-tab" href="{{ route('adminpayment.completed') }}">Completed {{ $payments->count() }}</a>
                </li>
            </ul>
        </div>
        <div class="card-footer bg-light-custom p-0">
            <div class="table-responsive">
                <table class="table bg-white" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40%">Customer</th>
                            <th width="20%">Status</th>
                            <th width="15%">Plan</th>
                            <th width="25%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->user->first_name }} {{ $payment->user->last_name }}</td>
                                <td>
                                    @if($payment->status == 'active')
                                        <span class="badge badge-success">{{ __('Completed') }}</span>
                                    @elseif($payment->status == 'scheduled')
                                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                                    @endif
                                </td>
                                <td>{{ (new \App\Lib\SystemHelper)->getPlanInformation($payment->plan)['label'] }}</td>
                                <td>{{ $payment->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                {{ $payments->links() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @else

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="min-height-600 d-flex align-items-center justify-content-center">
                    <div class="no-record py-4 text-center">
                        <img src="{{ asset('images/requests-empty.svg') }}">
                        <div class="pt-4">
                            <h2>No Completed Payments.</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif 

</div>

@endsection