@extends('layouts.app')

@section('title', 'Edit Brand Profile')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('brand.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">Edit {{ $brand->name }}</span></h1>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <form method="POST" action="{{route('brand.update', ['brand' => $brand->id])}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                <h3 class="text-dark">Brand Information</h3>

                <div class="text-dark">
                    <select class="form-control form-control-user @error('status') is-invalid @enderror" name="status">
                        <option selected disabled>Select Status</option>
                        <option value="1" {{old('status') ? ((old('status') == 1) ? 'selected' : '') : (($brand->status == 1) ? 'selected' : '')}}>Active</option>
                        <option value="0" {{old('status') ? ((old('status') == 0) ? 'selected' : '') : (($brand->status == 0) ? 'selected' : '')}}>Inactive</option>
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
                        value="{{ old('name') ? old('name') : $brand->name }}">

                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="description"><span style="color:red;">*</span> Description</label>
                    <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') ? old('description') : $brand->description }}</textarea>

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
                        value="{{ old('industry') ? old('industry') : $brand->industry }}">

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
                        value="{{ old('target_audience') ? old('target_audience') : $brand->target_audience }}">

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
                        value="{{ old('services_provider') ? old('services_provider') : $brand->services_provider }}">

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
                        value="{{ old('website') ? old('website') : $brand->website }}">

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
                        <input type="file" name="logos[]" class="form-control-file @error('logos') is-invalid @enderror" multiple >
                    </div>
                    @error('logos')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex logos mt-3 pt-3 border-top">
                        @if ($logos->count() > 0)
                            @foreach ($logos as $logo)
                                <div id="media-{{ $logo->id }}" class="mx-1 logo media-container">
                                    <img src="{{ url('storage/logos') }}/{{ auth()->user()->id }}/{{ $logo->filename }}" class="logo-img" />
                                    <div class="overlay">
                                        <a href="javascript:void(0);" onclick="return deleteAsset({{ $logo->id }}, {{ $logo->brand_id }}, '{{ $logo->type }}');" class="icon" title="Delete asset">
                                          <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No primary logo available</em></p>
                        @endif 
                    </div>
                </div>
                <div class="py-4">
                    <h5 class="card-label text-dark">Secondary Logo</h5>
                    <div class="d-flex logos">
                        <input type="file" name="logos_second[]" class="form-control-file" multiple >
                    </div>
                    @error('logos_second')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex logos mt-3 pt-3 border-top">
                        @if ($secondary_logos->count() > 0)
                            @foreach ($secondary_logos as $secondary_logo)
                                <div id="media-{{ $secondary_logo->id }}" class="mx-1 logo media-container">
                                    <img src="{{ url('storage/logos') }}/{{ auth()->user()->id }}/{{ $secondary_logo->filename }}" class="logo-img" />
                                    <div class="overlay">
                                        <a href="javascript:void(0);" onclick="return deleteAsset({{ $secondary_logo->id }}, {{ $secondary_logo->brand_id }}, '{{ $secondary_logo->type }}');" class="icon" title="Delete asset">
                                          <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No secondary logo available</em></p>
                        @endif
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
                    <div id="media-color" class="d-flex colors mt-3 pt-3 border-top">
                        @if ($colors->count() > 0)
                            @foreach ($colors as $color)
                                <div class="mx-1 color" style="background-color: {{ $color->filename }};border-radius: 50px; width: 40px; height: 40px;"></div>
                            @endforeach
                        @else
                            <p><em>-No primary colors available</em></p>
                        @endif
                    </div>
                    @if ($colors->count() > 0)
                    <div id="delete-color-set-btn" class="mt-1">
                        <a href="javascript:void(0);" onclick="return deleteAsset('color', {{ $brand->id }}, 'color');" title="Delete asset"><i class="fas fa-trash"></i> Delete set</a>
                    </div>
                    @endif
                </div>
                <div class="py-4">
                    <h5 class="card-label text-dark">Secondary Colors</h5>
                    <div id="secondary-colors" class="colors">
                        <div class="color-pick">
                            <input type="text" name="colors_second[]" class="form-control colorpicker col-md-3" >
                        </div>
                    </div>
                    <div id="media-secondcolor" class="d-flex colors mt-3 pt-3 border-top">
                        @if ($secondary_colors->count() > 0)
                            @foreach ($secondary_colors as $secondary_color)
                                <div class="mx-1 color" style="background-color: {{ $secondary_color->filename }};border-radius: 50px; width: 40px; height: 40px;"></div>
                            @endforeach
                        @else
                            <p><em>-No secondary colors available</em></p>
                        @endif
                    </div>
                    @if ($secondary_colors->count() > 0)
                    <div id="delete-secondcolor-set-btn" class="mt-1">
                        <a href="javascript:void(0);" onclick="return deleteAsset('secondcolor', {{ $brand->id }}, 'color_second');" title="Delete asset"><i class="fas fa-trash"></i> Delete set</a>
                    </div>
                    @endif
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
                    @error('fonts')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex fonts mt-3 pt-3 border-top">
                        @if ($fonts->count() > 0)
                            @foreach ($fonts as $font)
                                <div id="media-{{ $font->id }}">
                                    <div class="mx-1 font media-container media-documents">
                                        <img src="{{ asset('images/font-img-') }}{{ $font->file_type }}.png" class="font-img">
                                        <div class="overlay">
                                            <a href="javascript:void(0);" onclick="return deleteAsset({{ $font->id }}, {{ $font->brand_id }}, '{{ $font->type }}');" class="icon" title="Delete asset">
                                              <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <label class="mt-1">{{ $font->filename }}</label>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No primary fonts available</em></p>
                        @endif
                    </div>
                </div>
                <div class="py-4">
                    <h5 class="card-label text-dark">Secondary Fonts</h5>
                    <div class="d-flex fonts">
                        <input type="file" name="fonts_second[]" class="form-control-file" multiple >
                    </div>
                    @error('fonts_second')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex fonts mt-3 pt-3 border-top">
                        @if ($secondary_fonts->count() > 0)
                            @foreach ($secondary_fonts as $secondary_font)
                                <div id="media-{{ $secondary_font->id }}">
                                    <div class="mx-1 font media-container media-documents">
                                        <img src="{{ asset('images/font-img-') }}{{ $secondary_font->file_type }}.png" class="font-img">
                                        <div class="overlay">
                                            <a href="javascript:void(0);" onclick="return deleteAsset({{ $secondary_font->id }}, {{ $secondary_font->brand_id }}, '{{ $secondary_font->type }}');" class="icon" title="Delete asset">
                                              <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <label class="mt-1">{{ $font->filename }}</label>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No secondary fonts available</em></p>
                        @endif
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
                @error('pictures')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="d-flex pictures mt-3 pt-3 border-top">
                    @if ($images->count() > 0)
                        @foreach ($images as $image)
                            <div id="media-{{ $image->id }}" class="mx-1 picture media-container">
                                <img src="{{ url('storage/pictures') }}/{{ auth()->user()->id }}/{{ $image->filename }}" class="picture-img">
                                <div class="overlay">
                                    <a href="javascript:void(0);" onclick="return deleteAsset({{ $image->id }}, {{ $image->brand_id }}, '{{ $image->type }}');" class="icon" title="Delete asset">
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
                @error('guidelines')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="d-flex guidelines mt-3 pt-3 border-top">
                    @if ($guidelines->count() > 0)
                        @foreach ($guidelines as $guideline)
                            <div id="media-{{ $guideline->id }}">
                                <div class="mx-1 guideline media-container media-documents">
                                    <img src="{{ asset('images/guidelines-img-pdf.png') }}" class="guideline-img">
                                    <div class="overlay">
                                        <a href="javascript:void(0);" onclick="return deleteAsset({{ $guideline->id }}, {{ $guideline->brand_id }}, '{{ $guideline->type }}');" class="icon" title="Delete asset">
                                          <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <label class="mt-1">{{ $guideline->filename }}</label>
                            </div>
                        @endforeach
                    @else
                        <p><em>-No guidelines available</em></p>
                    @endif
                </div>
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
                @error('templates')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="d-flex templates mt-3 pt-3 border-top">
                    @if ($templates->count() > 0)
                        @foreach ($templates as $template)
                            <div id="media-{{ $template->id }}">
                                <div class="mx-1 template media-container media-documents">
                                    <img src="{{ asset('images/template-img-') }}{{ $template->file_type }}.png" class="template-img">
                                    <div class="overlay">
                                        <a href="javascript:void(0);" onclick="return deleteAsset({{ $template->id }}, {{ $template->brand_id }}, '{{ $template->type }}');" class="icon" title="Delete asset">
                                          <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <label class="mt-1">{{ $template->filename }}</label>
                            </div>
                        @endforeach
                    @else
                        <p><em>-No templates available</em></p>
                    @endif
                </div>
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
                    @error('inspirations')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex inspirations mt-3 pt-3 border-top">
                        @if ($inspirations->count() > 0)
                            @foreach ($inspirations as $inspiration)
                                <div id="media-{{ $inspiration->id }}" class="mx-1 inspiration media-container">
                                    <img src="{{ url('storage/inspirations') }}/{{ auth()->user()->id }}/{{ $inspiration->filename }}" class="inspiration-img">
                                    <div class="overlay">
                                        <a href="javascript:void(0);" onclick="return deleteAsset({{ $inspiration->id }}, {{ $inspiration->brand_id }}, '{{ $inspiration->type }}');" class="icon" title="Delete asset">
                                          <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No inspirations available</em></p>
                        @endif
                    </div>
                </div>
                <div class="tab-text-label text-dark py-3">
                    <label for="other_inspirations"> Other Inspirations</label>
                    <textarea id="other_inspirations" class="form-control form-control-user" name="other_inspirations">{{ old('other_inspirations') ? old('other_inspirations') : $brand->other_inspirations }}</textarea>
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
                        value="{{ old('facebook') ? old('facebook') : $brand->facebook }}">
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="linkedin"> Linkedin</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('linkedin') is-invalid @enderror" 
                        id="linkedin"
                        name="linkedin" 
                        value="{{ old('linkedin') ? old('linkedin') : $brand->linkedin }}">
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="instagram"> Instagram</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('instagram') is-invalid @enderror" 
                        id="instagram"
                        name="instagram" 
                        value="{{ old('instagram') ? old('instagram') : $brand->instagram }}">
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="twitter"> Twitter</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('twitter') is-invalid @enderror" 
                        id="twitter"
                        name="twitter" 
                        value="{{ old('twitter') ? old('twitter') : $brand->twitter }}">
                </div>
                <div class="tab-text-label text-dark py-3">
                    <label for="youtube"> Youtube</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('youtube') is-invalid @enderror" 
                        id="youtube"
                        name="youtube" 
                        value="{{ old('youtube') ? old('youtube') : $brand->youtube }}">
                </div>
                <div class="tab-text-label text-dark py-3">
                    <label for="tiktok"> Tiktok</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('tiktok') is-invalid @enderror" 
                        id="tiktok"
                        name="tiktok" 
                        value="{{ old('tiktok') ? old('tiktok') : $brand->tiktok }}">
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <button type="submit" class="btn btn-primary btn-user float-right mb-3">Update</button>
            <a class="btn btn-default float-right mr-3 mb-3" href="{{ route('brand.index') }}">Cancel</a>
        </div>

    </form>

</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script>
    jQuery('.colorpicker').colorpicker();

    function deleteAsset(assetid, brandid, ftype) {
        if(confirm("Deleting this asset cannot be undone. Continue?")) {
            jQuery.ajax({
                url: "{{ route('brand.destroyassets') }}",
                type:"POST",
                data:{
                    brand:brandid,
                    asset:assetid,
                    ftype:ftype,
                    _token: jQuery('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    if(assetid == 'color' || assetid == 'secondcolor') {
                        jQuery('#delete-'+ assetid +'-set-btn').remove();
                    }
                    jQuery('#media-'+ assetid).remove();
                }
            });
        }
    }


    function addColor(color_type) {
        jQuery( "#"+ color_type +"-colors .color-pick:first-child" ).clone().appendTo( "#"+ color_type +"-colors" );

        jQuery('.colorpicker').colorpicker();
    }
</script>
@stop