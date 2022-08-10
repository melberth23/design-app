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
                        <a class="dropdown-item" href="{{ route('brand.drafts.sort', ['type' => 'date', 'sort' => 'asc']) }}">Date added (Ascending)</a>
                        <a class="dropdown-item" href="{{ route('brand.drafts.sort', ['type' => 'date', 'sort' => 'desc']) }}">Date added (Descending)</a>
                        <a class="dropdown-item" href="{{ route('brand.drafts.sort', ['type' => 'name', 'sort' => 'asc']) }}">Name (A-Z)</a>
                        <a class="dropdown-item" href="{{ route('brand.drafts.sort', ['type' => 'name', 'sort' => 'desc']) }}">Name (Z-A)</a>
                      </div>
                    </div>
                    @if($limit)
                    <a href="{{ route('brand.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <span class="d-none d-md-inline-block">New</span>
                    </a>
                    @else
                    <a href="#" data-toggle="modal" data-target="#limitModal" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <span class="d-none d-md-inline-block">New</span>
                    </a>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body py-0 px-1">
                    <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link py-3 {{ (request()->is('brands')) ? 'active' : '' }}" id="allbrand-tab" href="{{ route('brand.index') }}">All brand</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 {{ (request()->is('brands/drafts')) ? 'active' : '' }}" id="drafts-tab" href="{{ route('brand.drafts') }}">Drafts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 {{ (request()->is('brands/archived')) ? 'active' : '' }}" id="archived-tab" href="{{ route('brand.archived') }}">Archived</a>
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
                                        <span class="badge badge-warning p-2">DRAFT</span>
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

            <div class="card mb-4 p-2 bg-transparent">
                <div class="card-body py-0 px-1 text-center">
                    @if($limit)
                    <a href="{{ route('brand.create') }}" class="text-decoration-none">
                        <i class="fas fa-plus"></i>
                        <span>Add New Brand Profile</span>
                    </a>
                    @else
                    <a href="#" data-toggle="modal" data-target="#limitModal" class="text-decoration-none">
                        <i class="fas fa-plus"></i>
                        <span>Add New Brand Profile</span>
                    </a>
                    @endif
                </div>
            </div>

            <div class="modal fade" id="limitModal" tabindex="-1" role="dialog" aria-labelledby="limitModalExample"
            aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body py-5 text-center">
                            <img src="{{ asset('images/designer-life-pana.svg') }}" class="limit-icon">
                            <h5 class="text-dark pt-4 pb-3 font-weight-bold">Brand Limit Reached</h5>
                            <p class="text-dark">You've reached the maximum amount of brand profiles for your plan. Do you want to upgrade your plan?</p>
                            <div class=""><a href="{{ route('profile.upgrade') }}" class="btn btn-primary">Yes, Upgrade</a></div>
                            <div class=""><a href="#" data-dismiss="modal" class="btn btn-link">No, Thanks</a></div>
                        </div>
                    </div>
                </div>
            </div>

            {{ $brands->links() }}

        @include('brands.delete-modal')  
        @else

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="min-height-600 d-flex align-items-center justify-content-center">
                        <div class="no-record py-4 text-center">
                            <img src="{{ asset('images/designer_flatline.svg') }}">
                            <div class="pt-4">
                                <h2>You don't have draft brand profiles.</h2>
                            </div>
                            <div class="pt-4">
                                <h5>Create brand profile now.</h5>
                            </div> 
                            <div class="pt-4">
                                <a href="{{ route('brand.create') }}" class="btn btn-primary">Create Brand Profile</a>
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
