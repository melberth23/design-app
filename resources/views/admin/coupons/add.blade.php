@extends('layouts.app')

@section('title', 'Add Coupon')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Coupons</h1>
        <a href="{{route('coupons.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Coupon</h6>
        </div>
        <form method="POST" action="{{route('coupons.store')}}">
            @csrf
            <div class="card-body">
                <div class="form-group row">

                    {{-- Code --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="code"><span style="color:red;">*</span> Code</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('code') is-invalid @enderror" 
                            id="code"
                            name="code" 
                            value="{{ old('code') ?  old('code') : $code }}">

                        @error('code')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="discount_type">Discout Type</label>
                        <select id="discount_type" class="form-control form-control-user" name="discount_type">
                            <option value="fix" selected>Fix</option>
                            <option value="percent">Percent</option>
                        </select>
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="discount_amount"><span style="color:red;">*</span> Discout Amount</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('discount_amount') is-invalid @enderror" 
                            id="discount_amount"
                            name="discount_amount" 
                            value="{{ old('discount_amount') }}">

                        @error('discount_amount')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="description">Description</label>
                        <textarea id="description" class="form-control form-control-user" name="description">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-user float-right mb-3">Save</button>
                <a class="btn btn-primary float-right mr-3 mb-3" href="{{ route('users.index') }}">Cancel</a>
            </div>
        </form>
    </div>

</div>


@endsection