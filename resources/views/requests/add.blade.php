@extends('layouts.app')

@section('title', 'Add Request')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('request.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">Add Brand Request</span></h1>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')

    <form method="POST" action="{{route('request.store')}}" enctype="multipart/form-data" class="form-brand-request">
        @csrf

        <div class="row">
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark">Request Name</h3>
                    </div>
                    <div class="card-body py-0">

                        <div class="tab-text-label text-dark py-3">
                            <label for="title">Add descriptive name</label>
                            <input 
                                type="text" 
                                class="form-control form-control-user @error('title') is-invalid @enderror" 
                                id="title"
                                name="title" 
                                value="{{ old('title') }}">

                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark">Design Type</h3>
                    </div>
                    <div class="card-body py-0">

                        <div class="tab-text-label text-dark py-3">
                            <label for="designType">Select type of design you are looking for</label>
                            <select id="designType" class="form-control form-control-user @error('design_type') is-invalid @enderror" name="design_type">
                                <option selected disabled>Select Type</option>
                                @foreach ($designtypes as $designtype)
                                    <option value="{{$designtype->id}}" {{ old('design_type') == $designtype->id ? 'selected' : '' }}>{{$designtype->name}}</option>
                                @endforeach
                            </select>

                            @error('design_type')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                <h3 class="text-dark">Dimensions</h3>
            </div>
            <div class="card-body py-0">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="tab-text-label text-dark py-3 border-bottom">
                            <label for="dimensions">Select size of the design and format</label>
                            <select id="dimensions" class="form-control form-control-user @error('dimensions') is-invalid @enderror" name="dimensions">
                                <option selected disabled>Select Dimensions</option>
                                <option value="landscape" {{ old('dimensions') == 'landscape' ? 'selected' : '' }}>Landscape</option>
                                <option value="square" {{ old('dimensions') == 'square' ? 'selected' : '' }}>Square</option>
                                <option value="portrait" {{ old('dimensions') == 'portrait' ? 'selected' : '' }}>Portrait</option>
                                <option value="custom" {{ old('dimensions') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>

                            @error('dimensions')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="tab-text-label text-dark py-3">
                            <label for="format">Format</label>
                            <select id="format" class="form-control form-control-user" name="format">
                                <option selected disabled>Select Format</option>
                                @foreach ($types['files'] as $ftype => $ftypelbl)
                                    <option value="{{ $ftype }}" {{ old('design_type') == $ftype ? 'selected' : '' }}>{{ $ftypelbl }}</option>
                                @endforeach
                                @foreach ($types['adobe'] as $atype => $atypelbl)
                                    <option value="{{ $atype }}" {{ old('design_type') == $atype ? 'selected' : '' }}>{{ $atypelbl }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6"> 
                        <div class="tab-text-label text-dark py-3">
                            <label for="dimensions">Additional Information</label>
                            <textarea id="dimensions_additional_info" class="form-control form-control-user @error('dimensions_additional_info') is-invalid @enderror" name="dimensions_additional_info">{{ old('dimensions_additional_info') }}</textarea>

                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                <h3 class="text-dark">Description</h3>
            </div>
            <div class="card-body py-0">

                <div class="tab-text-label text-dark py-3">
                    <label for="description">Provide your request information and tell us what's on your mind.</label>
                    <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

                    @error('description')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark">Supporting materials</h3>
                    </div>
                    <div class="card-body py-0">

                        <div class="tab-text-label text-dark py-3">
                            <label for="media">Add files</label>
                            <input type="file" name="media[]" class="form-control-file" multiple >
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark">Brand Profile</h3>
                    </div>
                    <div class="card-body py-0">

                        <div class="tab-text-label text-dark py-3">
                            <label for="brand_id">We will use the brand profile assets for your request</label>
                            <select class="form-control form-control-user @error('brand_id') is-invalid @enderror" name="brand_id">
                                <option selected disabled>Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{$brand->id}}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{$brand->name}}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark">Brand Request Priority</h3>
                    </div>
                    <div class="card-body py-0">

                        <div class="tab-text-label text-dark py-3">
                            <label for="priority">This will identify which request is most priority</label>
                            <select class="form-control form-control-user @error('priority') is-invalid @enderror" name="priority">
                                <option selected disabled>Select priority</option>
                                <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>First</option>
                                <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>Last</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <button type="submit" class="btn btn-primary btn-user float-right mb-3">Save</button>
            <a class="btn btn-default float-right mr-3 mb-3" href="{{ route('request.index') }}">Cancel</a>
        </div>

    </form>

</div>


@endsection