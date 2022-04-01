@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Users</h1>
        <a href="{{route('request.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit User</h6>
        </div>
        <form method="POST" action="{{route('request.update', ['requests' => $requests->id])}}">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="form-group row">

                    {{-- Title --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="title"><span style="color:red;">*</span> Title</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('title') is-invalid @enderror" 
                            id="title"
                            name="title" 
                            value="{{ old('title') ?  old('title') : $requests->title}}">

                        @error('title')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Design Type --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="designType"><span style="color:red;">*</span> Design Type</label>
                        <select id="designType" class="form-control form-control-user @error('design_type') is-invalid @enderror" name="design_type">
                            <option selected disabled>Select Type</option>
                            @foreach ($designtypes as $designtype)
                                <option value="{{$designtype->id}}" {{old('design_type') ? ((old('design_type') == $designtype->id) ? 'selected' : '') : (($designtype->id == $requests->design_type) ? 'selected' : '')}}>{{$designtype->name}}</option>
                            @endforeach
                        </select>

                        @error('design_type')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Dimensions --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="dimensions"><span style="color:red;">*</span> Dimensions</label>
                        <select id="dimensions" class="form-control form-control-user @error('dimensions') is-invalid @enderror" name="dimensions">
                            <option selected disabled>Select Dimensions</option>
                            <option value="landscape" {{old('dimensions') ? ((old('dimensions') == 'landscape') ? 'selected' : '') : (($requests->dimensions == 'landscape') ? 'selected' : '')}}>Landscape</option>
                            <option value="square" {{old('dimensions') ? ((old('dimensions') == 'square') ? 'selected' : '') : (($requests->dimensions == 'square') ? 'selected' : '')}}>Square</option>
                            <option value="portrait" {{old('dimensions') ? ((old('dimensions') == 'portrait') ? 'selected' : '') : (($requests->dimensions == 'portrait') ? 'selected' : '')}}>Portrait</option>
                            <option value="custom" {{old('dimensions') ? ((old('dimensions') == 'custom') ? 'selected' : '') : (($requests->dimensions == 'custom') ? 'selected' : '')}}>Custom</option>
                        </select>

                        @error('dimensions')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Format --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="format">Format</label>
                        <select id="format" class="form-control form-control-user" name="format">
                            <option selected disabled>Select Format</option>
                            <option value="1" {{old('format') ? ((old('format') == 1) ? 'selected' : '') : (($requests->format == 1) ? 'selected' : '')}}>.MP4</option>
                            <option value="2" {{old('format') ? ((old('format') == 2) ? 'selected' : '') : (($requests->format == 2) ? 'selected' : '')}}>.AEP</option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                        <label for="description"><span style="color:red;">*</span> Description</label>
                        <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') ?  old('description') : $requests->description}}</textarea>

                        @error('description')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Media --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="media">Media</label>
                        <input type="file" name="media[]" class="form-control-file" multiple >
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Brand</label>
                        <select class="form-control form-control-user @error('brand_id') is-invalid @enderror" name="brand_id">
                            <option selected disabled>Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{$brand->id}}" {{old('brand_id') ? ((old('brand_id') == $brand->id) ? 'selected' : '') : (($brand->id == $requests->brand_id) ? 'selected' : '')}}>{{$brand->name}}</option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="priority">Priority</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user" 
                            id="priority"
                            name="priority" 
                            value="{{ old('priority') ?  old('priority') : $requests->priority }}" disabled>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-user float-right mb-3">Update</button>
                <a class="btn btn-primary float-right mr-3 mb-3" href="{{ route('users.index') }}">Cancel</a>
            </div>
        </form>
    </div>

</div>


@endsection