@extends('layouts.app')

@section('title', 'Add Brand')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('brand.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">Add Brand Profile</span></h1>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <form method="POST" action="{{route('brand.store')}}" enctype="multipart/form-data" class="form-brand-profile">
        @csrf

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                <h3 class="text-dark">Brand Information</h3>

                <div class="text-dark">
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
            <div class="card-body py-0">

                <div class="tab-text-label text-dark py-3 border-bottom">
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
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="description"><span style="color:red;">*</span> Description</label>
                    <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

                    @error('description')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="targetIndustry"><span style="color:red;">*</span> Industry</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('industry') is-invalid @enderror" 
                        id="targetIndustry"
                        name="industry" 
                        value="{{ old('industry') }}">

                    @error('industry')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
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
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="servicesProvider"><span style="color:red;">*</span> Services Provider</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('services_provider') is-invalid @enderror" 
                        id="servicesProvider"
                        name="services_provider" 
                        value="{{ old('services_provider') }}">

                    @error('services_provider')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark py-3">
                    <label for="website"><span style="color:red;">*</span> Website</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('website') is-invalid @enderror" 
                        id="website"
                        name="website" 
                        value="{{ old('website') }}">

                    @error('website')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark">Logos</h3>
            </div>
            <div class="card-body py-0">
                <div class="py-4 border-bottom">
                    <h5 class="card-label text-dark">Primary Logo</h5>
                    <div class="d-flex logos">
                        <input type="file" name="logos[]" class="form-control-file" multiple >
                    </div>
                </div>
                <div class="py-4">
                    <h5 class="card-label text-dark">Secondary Logo</h5>
                    <div class="d-flex logos">
                        <input type="file" name="logos_second[]" class="form-control-file" multiple >
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light-custom">
                <em>Accepts JPG and PNG file only</em>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark">Colors</h3>
            </div>
            <div class="card-body">
                <div class="py-4 border-bottom">
                    <h5 class="card-label text-dark">Primary Colors</h5>
                    <div id="primary-colors" class="colors">
                        <div class="color-pick">
                            <input type="text" name="colors[]" class="form-control colorpicker col-md-3" >
                        </div>
                    </div>
                </div>
                <div class="py-4">
                    <h5 class="card-label text-dark">Secondary Colors</h5>
                    <div id="secondary-colors" class="colors">
                        <div class="color-pick">
                            <input type="text" name="colors_second[]" class="form-control colorpicker col-md-3" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" onclick="return addColor('primary');" class="btn btn-primary">Add Primary Color</button>
                <button type="button" onclick="return addColor('secondary');" class="btn btn-primary">Add Secondary Color</button>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark">Fonts</h3>
            </div>
            <div class="card-body">
                <div class="py-4 border-bottom">
                    <h5 class="card-label text-dark">Primary Fonts</h5>
                    <div class="d-flex fonts">
                        <input type="file" name="fonts[]" class="form-control-file" multiple >
                    </div>
                </div>
                <div class="py-4">
                    <h5 class="card-label text-dark">Secondary Fonts</h5>
                    <div class="d-flex fonts">
                        <input type="file" name="fonts_second[]" class="form-control-file" multiple >
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light-custom">
                <em>Accepts TTF, EOT and WOFF file only</em>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark">Images</h3>
            </div>
            <div class="card-body">
                <input type="file" name="pictures[]" class="form-control-file" multiple >
            </div>
            <div class="card-footer bg-light-custom">
                <em>Accepts JPG and PNG file only</em>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-dark">Brand Guidelines</h3>
            </div>
            <div class="card-body">
                <input type="file" name="guidelines[]" class="form-control-file" multiple >
            </div>
            <div class="card-footer bg-light-custom">
                <em>Accepts PDF file only</em>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-dark">Templates</h3>
            </div>
            <div class="card-body">
                <input type="file" name="templates[]" class="form-control-file" multiple >
            </div>
            <div class="card-footer bg-light-custom">
                <em>Accepts DOC, PDF, PDF and AI file only</em>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-dark">Brand Inspiration</h3>
            </div>
            <div class="card-body">
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="brandName"> Inspirations</label>
                    <input type="file" name="inspirations[]" class="form-control-file" multiple >
                </div>
                <div class="tab-text-label text-dark py-3">
                    <label for="other_inspirations"> Other Inspirations</label>
                    <textarea id="other_inspirations" class="form-control form-control-user @error('other_inspirations') is-invalid @enderror" name="other_inspirations">{{ old('other_inspirations') }}</textarea>
                </div>
            </div>
            <div class="card-footer bg-light-custom">
                <em>Accepts JPG and PNG file only</em>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-dark">Social Profile</h3>
            </div>
            <div class="card-body">
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="facebook"> Facebook</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('facebook') is-invalid @enderror" 
                        id="facebook"
                        name="facebook" 
                        value="{{ old('facebook') }}">
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="linkedin"> Linkedin</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('linkedin') is-invalid @enderror" 
                        id="linkedin"
                        name="linkedin" 
                        value="{{ old('linkedin') }}">
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="instagram"> Instagram</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('instagram') is-invalid @enderror" 
                        id="instagram"
                        name="instagram" 
                        value="{{ old('instagram') }}">
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="twitter"> Twitter</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('twitter') is-invalid @enderror" 
                        id="twitter"
                        name="twitter" 
                        value="{{ old('twitter') }}">
                </div>
                <div class="tab-text-label text-dark py-3">
                    <label for="youtube"> Youtube</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('youtube') is-invalid @enderror" 
                        id="youtube"
                        name="youtube" 
                        value="{{ old('youtube') }}">
                </div>
                <div class="tab-text-label text-dark py-3">
                    <label for="tiktok"> Tiktok</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('tiktok') is-invalid @enderror" 
                        id="tiktok"
                        name="tiktok" 
                        value="{{ old('tiktok') }}">
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <button type="submit" class="btn btn-primary btn-user float-right mb-3">Save</button>
            <a class="btn btn-default float-right mr-3 mb-3" href="{{ route('brand.index') }}">Cancel</a>
        </div>

    </form>

</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script>
    jQuery('.colorpicker').colorpicker();

    function addColor(color_type) {
        jQuery( "#"+ color_type +"-colors .color-pick:first-child" ).clone().appendTo( "#"+ color_type +"-colors" );

        jQuery('.colorpicker').colorpicker();
    }
</script>
@stop