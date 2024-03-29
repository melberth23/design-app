@extends('layouts.app')

@section('title', 'Active Brand Profiles')

@section('content')
    <div class="container">

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($brands->count() > 0)

            <!-- Page Heading -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800 page-heading">Brand profiles</h1>
                <div class="actions d-flex align-items-center justify-content-end">
                    <div class="dropdown m-1">
                      <button class="btn btn-outline-light text-dark border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-sliders" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Sort</span>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('adminbrand.index.sort', ['customer_id' => $customer_id, 'type' => 'date', 'sort' => 'asc']) }}">Date added (Ascending)</a>
                        <a class="dropdown-item" href="{{ route('adminbrand.index.sort', ['customer_id' => $customer_id, 'type' => 'date', 'sort' => 'desc']) }}">Date added (Descending)</a>
                        <a class="dropdown-item" href="{{ route('adminbrand.index.sort', ['customer_id' => $customer_id, 'type' => 'name', 'sort' => 'asc']) }}">Name (A-Z)</a>
                        <a class="dropdown-item" href="{{ route('adminbrand.index.sort', ['customer_id' => $customer_id, 'type' => 'name', 'sort' => 'desc']) }}">Name (Z-A)</a>
                      </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body py-0 px-1">
                    <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link py-3" id="allbrand-tab" href="{{ route('adminbrand.index', ['customer_id' => $customer_id]) }}">All brand</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 active" id="drafts-tab" href="{{ route('adminbrand.active', ['customer_id' => $customer_id]) }}">Active</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3" id="drafts-tab" href="{{ route('adminbrand.drafts', ['customer_id' => $customer_id]) }}">Drafts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3" id="archived-tab" href="{{ route('adminbrand.archived', ['customer_id' => $customer_id]) }}">Archived</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Brands -->
            @foreach ($brands as $brand)
                <div class="card mb-4 p-4">
                    <div class="row d-flex">
                        <div class="col-md-2 d-flex align-items-center justify-content-md-center justify-content-between pb-3">
                            <div class="top-main-logo">
                                <?php 
                                    echo (new \App\Lib\SystemHelper)->get_brand_logo($brand);
                                ?>
                            </div>
                            <div class="d-md-none">
                                <div class="brand-actions d-flex justify-content-end">
                                    <div class="dropdown mx-2">
                                        <button class="btn btn-outline-light action-icons rounded-circle border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="{{ asset('images/edit-brand.svg') }}" class="action-icon">
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'information', 'brand' => $brand->id]) }}">Brand Information</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'logo', 'brand' => $brand->id]) }}">Logo</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'colors', 'brand' => $brand->id]) }}">Colors</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'fonts', 'brand' => $brand->id]) }}">Fonts</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'images', 'brand' => $brand->id]) }}">Images</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'guidelines', 'brand' => $brand->id]) }}">Brand Guidelines</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'templates', 'brand' => $brand->id]) }}">Templates</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'inspirations', 'brand' => $brand->id]) }}">Brand Inspiration</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'social', 'brand' => $brand->id]) }}">Social Profile</a>
                                            <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'all', 'brand' => $brand->id]) }}">Edit All</a>
                                        </div>
                                    </div>
                                    <a class="btn btn-outline-light action-icons rounded-circle border" href="#">
                                        <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="brand-name">
                                <a href="{{ route('brand.view', ['brand' => $brand->id]) }}">{{ $brand->name }}</a>
                            </div>
                            <div class="extra-information">
                                <ul class="d-flex justify-content-start">
                                    <li>{{ $brand->industry }}</li>
                                    <li><a href="{{ $brand->website }}" target="_blank">{{ $brand->website }}</a></li>
                                    <li>
                                        @if ($brand->status == 0)
                                            <span class="badge badge-warning p-2">DRAFT</span>
                                        @elseif ($brand->status == 1)
                                            <span class="badge badge-success p-2">ACTIVE</span>
                                        @else
                                            <span class="badge badge-danger p-2">ARCHIVED</span>
                                        @endif
                                    </li>
                                </ul>
                                <p>{{ Str::limit($brand->description, 200, $end='.......') }}</p>
                                <div class="d-flex colors">
                                    <?php 
                                        echo (new \App\Lib\SystemHelper)->get_brand_assets($brand, 'color');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-none d-md-block">
                            <div class="brand-actions d-flex justify-content-end">
                                <div class="dropdown mx-2">
                                    <button class="btn btn-outline-light action-icons rounded-circle border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="{{ asset('images/edit-brand.svg') }}" class="action-icon">
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'information', 'brand' => $brand->id]) }}">Brand Information</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'logo', 'brand' => $brand->id]) }}">Logo</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'colors', 'brand' => $brand->id]) }}">Colors</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'fonts', 'brand' => $brand->id]) }}">Fonts</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'images', 'brand' => $brand->id]) }}">Images</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'guidelines', 'brand' => $brand->id]) }}">Brand Guidelines</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'templates', 'brand' => $brand->id]) }}">Templates</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'inspirations', 'brand' => $brand->id]) }}">Brand Inspiration</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'social', 'brand' => $brand->id]) }}">Social Profile</a>
                                        <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'all', 'brand' => $brand->id]) }}">Edit All</a>
                                    </div>
                                </div>
                                <!-- <a class="btn btn-outline-light action-icons rounded-circle border" href="#">
                                    <img src="{{ asset('images/download.svg') }}" class="action-icon">
                                </a> -->
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $brands->links() }}

        @include('brands.delete-modal')  
        @else

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="min-height-600 d-flex align-items-center justify-content-center">
                        <div class="no-record py-4 text-center">
                            <img src="{{ asset('images/designer_flatline.svg') }}">
                            <div class="pt-4">
                                <h2>You have not created any brand profiles yet.</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif 

    </div>

@endsection

@section('scripts')
    
@endsection
