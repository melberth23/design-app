@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('request.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">Edit {{ $requests->title }}</span></h1>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')

    <form method="POST" action="{{route('request.update', ['requests' => $requests->id])}}" enctype="multipart/form-data" class="form-brand-request">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark">Request Name</h3>
                    </div>
                    <div class="card-body py-0">

                        <div class="tab-text-label text-dark py-3">
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
                                    <option value="{{$designtype->id}}" {{old('design_type') ? ((old('design_type') == $designtype->id) ? 'selected' : '') : (($designtype->id == $requests->design_type) ? 'selected' : '')}}>{{$designtype->name}}</option>
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
                                <option value="landscape" {{old('dimensions') ? ((old('dimensions') == 'landscape') ? 'selected' : '') : (($requests->dimensions == 'landscape') ? 'selected' : '')}}>Landscape</option>
                                <option value="square" {{old('dimensions') ? ((old('dimensions') == 'square') ? 'selected' : '') : (($requests->dimensions == 'square') ? 'selected' : '')}}>Square</option>
                                <option value="portrait" {{old('dimensions') ? ((old('dimensions') == 'portrait') ? 'selected' : '') : (($requests->dimensions == 'portrait') ? 'selected' : '')}}>Portrait</option>
                                <option value="custom" {{old('dimensions') ? ((old('dimensions') == 'custom') ? 'selected' : '') : (($requests->dimensions == 'custom') ? 'selected' : '')}}>Custom</option>
                            </select>

                            @error('dimensions')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="tab-text-label text-dark py-3">
                            <label for="format">Format</label>
                            <select id="format" class="form-control form-control-user" name="format">
                                <option selected disabled>Select Format</option>
                                <option value="1" {{old('format') ? ((old('format') == 1) ? 'selected' : '') : (($requests->format == 1) ? 'selected' : '')}}>.MP4</option>
                                <option value="2" {{old('format') ? ((old('format') == 2) ? 'selected' : '') : (($requests->format == 2) ? 'selected' : '')}}>.AEP</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6"> 
                        <div class="tab-text-label text-dark py-3">
                            <label for="dimensions">Additional Information</label>
                            <textarea id="dimensions_additional_info" class="form-control form-control-user" name="dimensions_additional_info">{{ old('dimensions_additional_info') ?  old('dimensions_additional_info') : $requests->dimensions_additional_info}}</textarea>
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
                    <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') ?  old('description') : $requests->description}}</textarea>

                    @error('description')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark">Supporting materials</h3>
                    </div>
                    <div class="card-body py-0">

                        <div class="tab-text-label text-dark py-3">
                            <label for="media">Add files</label>
                            <input type="file" name="media[]" class="form-control-file" multiple >
                            <div class="d-flex pictures mt-3 pt-3 border-top">
                                @if ($medias->count() > 0)
                                    @foreach ($medias as $media)
                                        <div id="media-{{ $media->id }}" class="mx-1 media media-container">
                                            <img src="{{ url('storage/media') }}/{{ auth()->user()->id }}/{{ $media->filename }}" class="picture-img">
                                            <div class="overlay">
                                                <a href="javascript:void(0);" onclick="return deleteAsset({{ $media->id }}, {{ $media->request_id }}, '{{ $media->type }}');" class="icon" title="Delete asset">
                                                  <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p><em>-No images available</em></p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-3">
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
                                    <option value="{{$brand->id}}" {{old('brand_id') ? ((old('brand_id') == $brand->id) ? 'selected' : '') : (($brand->id == $requests->brand_id) ? 'selected' : '')}}>{{$brand->name}}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark">Brand Request Priority</h3>
                    </div>
                    <div class="card-body py-0">

                        <div class="tab-text-label text-dark py-3">
                            <label for="priority">This will identify which request is most priority</label>
                            <select class="form-control form-control-user @error('priority') is-invalid @enderror" name="priority">
                                <option selected disabled>Select priority</option>
                                <option value="1" {{old('priority') ? ((old('priority') == 1) ? 'selected' : '') : (($requests->priority == 1) ? 'selected' : '')}}>First</option>
                                <option value="2" {{old('priority') ? ((old('priority') == 2) ? 'selected' : '') : (($requests->priority == 2) ? 'selected' : '')}}>Last</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <button type="submit" class="btn btn-primary btn-user float-right mb-3">Update</button>
            <a class="btn btn-default float-right mr-3 mb-3" href="{{ route('request.index') }}">Cancel</a>
        </div>

    </form>

</div>


@endsection

@section('scripts')
<script>
    function deleteAsset(assetid, requestid, ftype) {
        if(confirm("Deleting this asset cannot be undone. Continue?")) {
            jQuery.ajax({
                url: "{{ route('request.destroyassets') }}",
                type:"POST",
                data:{
                    request:requestid,
                    asset:assetid,
                    ftype:ftype,
                    _token: jQuery('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    jQuery('#media-'+ assetid).remove();
                }
            });
        }
    }
</script>
@stop