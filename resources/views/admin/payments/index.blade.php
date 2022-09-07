@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')

    @if ($invoices->count() > 0)

    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 page-heading">Payment History</h1>
    </div>

    <div class="table-responsive">
        <table class="table bg-white" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th width="10%">ORDER NUMBER</th>
                    <th width="25%">NAME</th>
                    <th width="25%">DATE</th>
                    <th width="25%">PLAN</th>
                    <th width="15%">AMOUNT</th>
                    <th width="20%">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td class="text-primary"><a href="{{ route('view.invoice', ['invoice' => $invoice->id]) }}" target="_blank">DO{{ $invoice->id }}</a></td>
                        <td>{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</td>
                        <td>{{ $invoice->created_at->format('d F, Y') }}</td>
                        <td>DesignsOwl, {{ (new \App\Lib\SystemHelper)->getPlanInformation($invoice->plan)['label'] }}</td>
                        <td>S${{ number_format($invoice->amount) }}</td>
                        <td class="d-flex   justify-content-start">
                            <a href="{{ route('view.invoice', ['invoice' => $invoice->id]) }}" target="_blank" class="btn btn-outline-light action-icons rounded-circle border p-1 mx-1">
                                <img src="{{ asset('images/inv-open.svg') }}" class="action-icon">
                            </a>
                            <a href="{{ route('generate.invoice', ['invoice' => $invoice->id]) }}" class="btn btn-outline-light action-icons rounded-circle border p-1 mx-1">
                                <img src="{{ asset('images/inv-donwload.svg') }}" class="action-icon">
                            </a>
                            <a href="#" class="btn btn-outline-light action-icons rounded-circle border p-1 mx-1" data-ref="{{ $invoice->id }}" data-toggle="modal" data-target="#sendInvoiceModal">
                                <img src="{{ asset('images/inv-mail.svg') }}" class="action-icon">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $invoices->links() }}
    </div>

    @else

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="min-height-600 d-flex align-items-center justify-content-center">
                    <div class="no-record py-4 text-center">
                        <img src="{{ asset('images/requests-empty.svg') }}">
                        <div class="pt-4">
                            <h2>No Payments.</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif 

</div>

@endsection