@extends('layouts.app')

@section('title', 'Add Brand')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Brand</h1>
        <a href="{{route('brand.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Brand</h6>
        </div>
        <form method="POST" action="{{route('brand.store')}}">
            @csrf
            <div class="card-body">
                <div class="form-group row">

                    {{-- Brand Name --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="brandName"><span style="color:red;">*</span> Brand Name</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('name') is-invalid @enderror" 
                            id="brandName"
                            name="name" 
                            value="{{ old('name') }}">

                        @error('name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Target Audience --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="targetAudience"><span style="color:red;">*</span> Target Audience</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('target_audience') is-invalid @enderror" 
                            id="targetAudience"
                            name="target_audience" 
                            value="{{ old('target_audience') }}">

                        @error('target_audience')
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

                    {{-- Brand Assets --}}
                    <div class="col-sm-12 mb-3 mt-5 mb-sm-0">
                        <h4>Brand Assets</h4>
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="picture">Pictures</label>
                        <input type="file" name="pictures" class="form-control-file" multiple >
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="picture">Fonts</label>
                        <input type="file" name="pictures" class="form-control-file" multiple >
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="picture">Colors</label>
                        <input type="file" name="pictures" class="form-control-file" multiple >
                    </div>

                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="picture">Inspirations</label>
                        <input type="file" name="pictures" class="form-control-file" multiple >
                    </div>

                    {{-- Status --}}
                    <div class="col-sm-12 mb-3 mt-5 mb-sm-0">
                        <h4>Brand Status</h4>
                    </div>

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