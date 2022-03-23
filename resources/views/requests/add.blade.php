@extends('layouts.app')

@section('title', 'Add Request')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Request</h1>
        <a href="{{route('request.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Request</h6>
        </div>
        <form method="POST" action="{{route('request.store')}}" enctype="multipart/form-data">
            @csrf
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
                            value="{{ old('title') }}">

                        @error('title')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Design Type --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="designType"><span style="color:red;">*</span> Design Type</label>
                        <select id="designType" class="form-control form-control-user @error('design_type') is-invalid @enderror" name="design_type">
                            <option selected disabled>Select Type</option>
                            <option value="1">Logo Design</option>
                            <option value="2">Infographics</option>
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
                            <option value="landscape">Landscape</option>
                            <option value="square">Square</option>
                            <option value="portrait">Portrait</option>
                            <option value="custom">Custom</option>
                        </select>

                        @error('dimensions')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Format --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="format"><span style="color:red;">*</span> Format</label>
                        <select id="format" class="form-control form-control-user @error('format') is-invalid @enderror" name="format">
                            <option selected disabled>Select Format</option>
                            <option value="1">.MP4</option>
                            <option value="2">.AEP</option>
                        </select>

                        @error('format')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                        <label for="description"><span style="color:red;">*</span> Description</label>
                        <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

                        @error('description')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                        <label for="media">Media</label>
                        <input type="file" name="media" class="form-control-file" multiple >
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="priority">Set Priority</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user" 
                            id="priority"
                            name="priority" 
                            value="{{ old('priority') }}">
                    </div>

                    {{-- Status --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Status</label>
                        <select class="form-control form-control-user @error('status') is-invalid @enderror" name="status">
                            <option selected disabled>Select Status</option>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
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