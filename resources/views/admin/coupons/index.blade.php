@extends('layouts.app')

@section('title', 'Coupon Lists')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Coupons</h1>
            <a href="{{ route('coupons.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($coupons->count() > 0)

        <!-- Requests -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Coupons</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="20%">Code</th>
                                <th width="15%">Type</th>
                                <th width="15%">Amount</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->discount_type }}</td>
                                    <td>{{ $coupon->discount_amount }}</td>
                                    <td style="display: flex">
                                        <!-- <a href="{{ route('coupons.edit', ['coupon' => $coupon->id]) }}"
                                            class="btn btn-primary m-2">
                                            <i class="fa fa-pen"></i>
                                        </a> -->
                                        <a class="btn btn-danger m-2" href="#" data-toggle="modal" data-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $coupons->links() }}
                </div>
            </div>
        </div>

        @include('admin.coupons.delete-modal')  
        @else

            <div class="alert alert-danger" role="alert">
                No coupons found! 
            </div>

        @endif  
    </div>

@endsection

@section('scripts')
    
@endsection
