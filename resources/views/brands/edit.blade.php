@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Users</h1>
        <a href="{{route('users.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit User</h6>
        </div>
        <form method="POST" action="{{route('brand.update', ['brand' => $brand->id])}}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="form-group row">

                    {{-- Brand Name --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="brandName"><span style="color:red;">*</span> Brand Name</label>
                        <input 
                            type="text" 
                            class="form-control form-control-brand @error('name') is-invalid @enderror" 
                            id="brandName"
                            name="name" 
                            value="{{ old('name') ? old('name') : $brand->name }}">

                        @error('name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Target Audience --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <label for="targetAudience"><span style="color:red;">*</span> Target Audience</label>
                        <input 
                            type="text" 
                            class="form-control form-control-brand @error('target_audience') is-invalid @enderror" 
                            id="targetAudience"
                            name="target_audience" 
                            value="{{ old('target_audience') ? old('target_audience') : $brand->target_audience }}">

                        @error('target_audience')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                        <label for="description"><span style="color:red;">*</span> Description</label>
                        <textarea id="description" class="form-control form-control-brand @error('description') is-invalid @enderror" name="description">{{ old('description') ? old('description') : $brand->description }}</textarea>

                        @error('description')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Status</label>
                        <select class="form-control form-control-brand @error('status') is-invalid @enderror" name="status">
                            <option selected disabled>Select Status</option>
                            <option value="1" {{old('role_id') ? ((old('role_id') == 1) ? 'selected' : '') : (($brand->status == 1) ? 'selected' : '')}}>Active</option>
                            <option value="0" {{old('role_id') ? ((old('role_id') == 0) ? 'selected' : '') : (($brand->status == 0) ? 'selected' : '')}}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-user float-right mb-3">Update</button>
                <a class="btn btn-primary float-right mr-3 mb-3" href="{{ route('brand.index') }}">Cancel</a>
            </div>
        </form>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script>
    jQuery('.colorpicker').colorpicker();
</script>
@stop