@extends('layouts.app')

@section('title', 'Blogs List')

@section('content')
    <div class="container">

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($brands->count() > 0)

            <!-- Page Heading -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800 page-heading">My brand profile</h1>
                <div class="actions d-flex align-items-center justify-content-end">
                    <div class="dropdown m-1">
                      <button class="btn btn-outline-light text-dark border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-sliders" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Sort</span>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('adminbrand.archived.sort', ['type' => 'date', 'sort' => 'asc']) }}">Date added (Ascending)</a>
                        <a class="dropdown-item" href="{{ route('adminbrand.archived.sort', ['type' => 'date', 'sort' => 'desc']) }}">Date added (Descending)</a>
                        <a class="dropdown-item" href="{{ route('adminbrand.archived.sort', ['type' => 'name', 'sort' => 'asc']) }}">Name (A-Z)</a>
                        <a class="dropdown-item" href="{{ route('adminbrand.archived.sort', ['type' => 'name', 'sort' => 'desc']) }}">Name (Z-A)</a>
                      </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body py-0 px-1">
                    <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link py-3 {{ (request()->is('admin/brands')) ? 'active' : '' }}" id="allbrand-tab" href="{{ route('adminbrand.index') }}">All brand</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 {{ (request()->is('admin/brands/drafts')) ? 'active' : '' }}" id="drafts-tab" href="{{ route('adminbrand.drafts') }}">Drafts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 {{ (request()->is('admin/brands/archived')) ? 'active' : '' }}" id="archived-tab" href="{{ route('adminbrand.archived') }}">Archived</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Brands -->
            @foreach ($brands as $brand)
                <div class="card mb-4 p-4">
                    <div class="row d-flex">
                        <div class="col-md-2 d-flex justify-content-center">
                            <div class="top-main-logo">
                                <?php 
                                    echo (new \App\Lib\SystemHelper)->get_brand_logo($brand);
                                ?>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="brand-name">
                                <a href="{{ route('brand.view', ['brand' => $brand->id]) }}">{{ $brand->name }}</a>
                            </div>
                            <div class="extra-information">
                                <ul class="d-flex justify-content-start">
                                    <li>{{ $brand->industry }}</li>
                                    <li>{{ $brand->website }}</li>
                                    <li>
                                        <span class="badge badge-danger p-2">ARCHIVED</span>
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
                        <div class="col-md-2">
                            
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
                                <h2>You don't have archived brand profiles.</h2>
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
