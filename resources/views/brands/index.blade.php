@extends('layouts.app')

@section('title', 'Blogs List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Brands</h1>
            <a href="{{ route('brand.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($brands->count() > 0)

        <!-- Brands -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Brands</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="20%">Brand Name</th>
                                <th width="25%">Target audience</th>
                                <th width="15%">Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->target_audience }}</td>
                                    <td>
                                        @if ($brand->status == 0)
                                            <span class="badge badge-danger">Inactive</span>
                                        @elseif ($brand->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td style="display: flex">
                                        <a href="{{ route('brand.view', ['brand' => $brand->id]) }}"
                                            class="btn btn-info m-2">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ($brand->status == 0)
                                            <a href="{{ route('brand.status', ['brand_id' => $brand->id, 'status' => 1]) }}"
                                                class="btn btn-success m-2">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @elseif ($brand->status == 1)
                                            <a href="{{ route('brand.status', ['brand_id' => $brand->id, 'status' => 0]) }}"
                                                class="btn btn-danger m-2">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}"
                                            class="btn btn-primary m-2">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                        <a class="btn btn-danger m-2" href="#" data-toggle="modal" data-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $brands->links() }}
                </div>
            </div>
        </div>

        @include('brands.delete-modal')  
        @else

            <div class="alert alert-danger" role="alert">
                No brands found! 
            </div>

        @endif 

    </div>

@endsection

@section('scripts')
    
@endsection
