@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="container">

    {{-- Alert Messages --}}
    @include('common.alert')

    @if ($invoices->count() > 0)

    <!-- Page Heading -->
    <div class="mb-4">
        <h1 class="text-dark">Invoices and billing history</h1>
        <p class="text-dark">Review your billing history and manage your invoices.</p>
    </div>

    <div class="table-responsive">
        <table class="table bg-white" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th width="10%">ORDER NUMBER</th>
                    <th width="25%">DATE</th>
                    <th width="25%">PLAN</th>
                    <th width="15%">AMOUNT</th>
                    <th width="20%">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td class="text-primary"><a href="{{ route('view.invoice', ['invoice' => $invoice->id]) }}" target="_blank">DO{{ $invoice->number }}</a></td>
                        <td>{{ $invoice->created_at->format('d F, Y') }}</td>
                        <td>Designs Owl, {{ (new \App\Lib\SystemHelper)->getPlanInformation($invoice->plan)['label'] }}</td>
                        <td>{{ (new \App\Lib\SystemHelper)->getCurrency('', 'symbol') }}{{ number_format($invoice->amount) }}</td>
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
            <tfoot>
                <tr>
                    <td colspan="5">
                        {{ $invoices->links() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <form method="POST" action="{{ route('send.invoice') }}" enctype="multipart/form-data" id="form-send_invoice-request">
        @csrf
        <div class="modal fade" id="sendInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="sendInvoiceModalExample"
        aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="sendInvoiceModalExample">Email invoice</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <input type="hidden" id="invoice_ref_id" name="invoiceid" value="">
                        <p class="text-dark">Would you like to send a copy of this document to your email address, <strong>{{ auth()->user()->email }}</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button id="showInvoiceSuccess" class="d-none" type="button" data-toggle="modal" data-target="#sendInvoiceSuccess">Invoice Sent</button>
                        <button id="dismissInvoiceModal" class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >Email Invoice</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal fade" id="sendInvoiceSuccess" tabindex="-1" role="dialog" aria-labelledby="sendInvoiceSuccessModalExample"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body py-5 text-center">
                    <img src="{{ asset('images/sent.svg') }}" class="mail-icon">
                    <h2 class="text-dark pt-4 pb-3">Invoice Sent</h2>
                    <p class="text-dark">We have successfully send your invoice. Please check your email</p>
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Done</button>
                </div>
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
                            <h2>You have no invoices.</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif 

</div>

@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        $('#form-send_invoice-request').on('submit', function(e) {
            e.preventDefault();

            $("#msg").html('');

            $.ajax({
               type:'POST',
               url:'{{ route('send.invoice') }}',
               data: $(this).serialize(),
               success:function(data) {
                if(data.error == 1) {
                    $("#msg").html('<p class="alert alert-danger">'+ data.msg +'</p>');
                } else {
                    // Success
                    $('#dismissInvoiceModal').click();
                    $('#showInvoiceSuccess').click();
                }
               }
            });

            return false;
        });
    });
</script>
    
@endsection