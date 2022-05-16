@extends('layouts.app')

@section('title', 'Edit Brand Profile')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<div class="container">
    <form method="POST" action="{{route('brand.update', ['brand' => $brand->id])}}" enctype="multipart/form-data" class="edit-brand-form">
        @csrf
        @method('PUT')

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{ route('brand.view', ['brand' => $brand->id]) }}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">Edit {{ $brand->name }}</span></h1>
            <div class="actions d-sm-flex align-items-center justify-content-between">
                <button type="submit" class="btn btn-primary btn-user">Update</button>
            </div>
        </div>

        {{-- Alert Messages --}}
        @include('common.alert')
   

        <div class="card mb-4 {{ ($section == 'information' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                <h3 class="text-dark mb-0">Brand Information</h3>

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
                    <label for="brandName">Brand Name</label>
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
                    <label for="description">Description</label>
                    <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') ? old('description') : $brand->description }}</textarea>

                    @error('description')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark py-3 border-bottom">
                    <label for="targetIndustry">Industry</label>
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
                    <label for="targetAudience">Target Audience</label>
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
                    <label for="servicesProvider">Services Provider</label>
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
                    <label for="website">Website</label>
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

        <div class="card mb-4 {{ ($section == 'logo' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark mb-0">Logo</h3>
            </div>
            <div class="card-body py-0">
                <div class="py-4">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <div class="logo-information">
                            <h5 class="card-label text-dark">Primary Logo</h5>
                            <p class="img-description mb-0">Your primary logo will also be use as your brand profile picture.</p>
                            <p class="img-limit">Acceptable file, PNG, JPEG, SVG, max file size 150mb</p>
                        </div>
                        <div class="logo-uploader">
                            <button type="button" onclick="getElementById('primary-logo').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none logos">
                        <input type="file" id="primary-logo" name="logos[]" class="form-control-file @error('logos') is-invalid @enderror" multiple >
                    </div>
                    @error('logos')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex logos mt-1 pt-1">
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
            </div>
            <div class="card-footer bg-light-custom py-4">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div class="logo-information">
                        <h5 class="card-label text-dark">Secondary Logo</h5>
                        <p class="img-description mb-0">Add a different variant of your logo.</p>
                        <p class="img-limit">Acceptable file, PNG, JPEG, SVG, max file size 150mb</p>
                    </div>
                    <div class="logo-uploader">
                        <button type="button" onclick="getElementById('secondary-logo').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                    </div>
                </div>
                <div class="d-none logos">
                    <input type="file" id="secondary-logo" name="logos_second[]" class="form-control-file" multiple >
                </div>
                @error('logos_second')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="d-flex logos mt-1 pt-1">
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

        <div class="card mb-4 {{ ($section == 'colors' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark mb-0">Colors</h3>
            </div>
            <div class="card-body">
                <div class="pb-4 border-bottom">
                    <h5 class="card-label text-dark">Primary Colors</h5>
                    <div id="primary-colors" class="colors">
                        <div class="color-pick">
                            <input type="text" name="colors[]" class="form-control colorpicker col-md-3" placeholder="Select Color" >
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
                </div>
                <div class="py-4">
                    <h5 class="card-label text-dark">Secondary Colors</h5>
                    <div id="secondary-colors" class="colors">
                        <div class="color-pick">
                            <input type="text" name="colors_second[]" class="form-control colorpicker col-md-3" placeholder="Select Color" >
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
                </div>
            </div>
            <div class="card-footer bg-light-custom">
                <button type="button" onclick="return addColor('primary');" class="btn btn-primary">Add Primary Color</button>
                <button type="button" onclick="return addColor('secondary');" class="btn btn-primary">Add Secondary Color</button>
            </div>
        </div>

        <div class="card mb-4 {{ ($section == 'fonts' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark mb-0">Fonts</h3>
            </div>
            <div class="card-body py-0">
                <div class="py-4">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <div class="font-information">
                            <h5 class="card-label text-dark">Primary Font</h5>
                            <p class="img-description mb-0">Set a primary font you use on your brand.</p>
                            <p class="img-limit">Acceptable file, TTF, EOT, WOFF, ZIP file, max file size 150mb.</p>
                        </div>
                        <div class="font-uploader">
                            <button type="button" onclick="getElementById('primary-fonts').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none fonts">
                        <input type="file" id="primary-fonts" name="fonts[]" class="form-control-file" multiple >
                    </div>
                    @error('fonts')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex fonts mt-1 pt-1">
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
            </div>
            <div class="card-footer bg-light-custom py-4">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div class="font-information">
                        <h5 class="card-label text-dark">Secondary Font</h5>
                        <p class="img-description mb-0">Set a secondary font to your brand.</p>
                        <p class="img-limit">Acceptable file, TTF, EOT, WOFF, ZIP file, max file size 150mb.</p>
                    </div>
                    <div class="font-uploader">
                        <button type="button" onclick="getElementById('secondary-fonts').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                    </div>
                </div>
                <div class="d-none fonts">
                    <input type="file" id="secondary-fonts" name="fonts_second[]" class="form-control-file" multiple >
                </div>
                @error('fonts_second')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="d-flex fonts mt-1 pt-1">
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

        <div class="card mb-4 {{ ($section == 'images' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark mb-0">Images</h3>
            </div>
            <div class="card-body py-0">
                <div class="py-4">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <div class="image-information">
                            <h5 class="card-label text-dark">Brand Images</h5>
                            <p class="img-description mb-0">Upload images or stock photos to be use on your brand.</p>
                            <p class="img-limit">Acceptable file, PNG, JPEG, JPG, max file size 150mb.</p>
                        </div>
                        <div class="image-uploader">
                            <button type="button" onclick="getElementById('pictures').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none">
                        <input type="file" id="pictures" name="pictures[]" class="form-control-file" multiple >
                    </div>
                    @error('pictures')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex pictures mt-1 pt-1">
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
            </div>
        </div>

        <div class="card mb-4 {{ ($section == 'guidelines' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark mb-0">Brand Guidelines</h3>
            </div>
            <div class="card-body py-0">
                <div class="py-4">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <div class="guideline-information">
                            <h5 class="card-label text-dark">Brand Guidelines</h5>
                            <p class="img-description mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                            <p class="img-limit">Acceptable file, PNG, JPEG, PDF, DOC, PPT, max file size 150mb.</p>
                        </div>
                        <div class="guideline-uploader">
                            <button type="button" onclick="getElementById('guidelines').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none">
                        <input type="file" id="guidelines" name="guidelines[]" class="form-control-file" multiple >
                    </div>
                    @error('guidelines')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex guidelines mt-1 pt-1">
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
            </div>
        </div>

        <div class="card mb-4 {{ ($section == 'templates' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark mb-0">Templates</h3>
            </div>
            <div class="card-body py-0">
                <div class="py-4">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <div class="template-information">
                            <h5 class="card-label text-dark">Templates</h5>
                            <p class="img-description mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                            <p class="img-limit">Acceptable file, PNG, JPEG, PDF, PSD, AI, max file size 150mb.</p>
                        </div>
                        <div class="template-uploader">
                            <button type="button" onclick="getElementById('templates').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none">
                        <input type="file" id="templates" name="templates[]" class="form-control-file" multiple >
                    </div>
                    @error('templates')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex templates mt-1 pt-1">
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
            </div>
        </div>

        <div class="card mb-4 {{ ($section == 'inspirations' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark mb-0">Brand Inspiration</h3>
            </div>
            <div class="card-body py-0">
                <div class="py-4">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <div class="inspiration-information">
                            <h5 class="card-label text-dark">Brand Inspiration</h5>
                            <p class="img-description mb-0">Upload your brand inspiration, it helps us determine the direction you like</p>
                            <p class="img-limit">Acceptable file, PNG, JPEG, PDF, max file size 150mb.</p>
                        </div>
                        <div class="inspiration-uploader">
                            <button type="button" onclick="getElementById('inspirations').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none">
                        <input type="file" id="inspirations" name="inspirations[]" class="form-control-file" multiple >
                    </div>
                    @error('inspirations')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex inspirations mt-1 pt-1">
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
                <div class="text-dark pt-1 pb-4">
                    <label class="img-description" for="other_inspirations"> Other Inspirations</label>
                    <textarea id="other_inspirations" class="form-control form-control-user" name="other_inspirations">{{ old('other_inspirations') ? old('other_inspirations') : $brand->other_inspirations }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4 {{ ($section == 'social' or $section == 'all') ? '' : 'd-none' }}">
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
            <a class="btn btn-default float-right mr-3 mb-3" href="{{ route('brand.view', ['brand' => $brand->id]) }}">Cancel</a>
            <button type="submit" class="btn btn-primary btn-user float-right mb-3">Update</button>
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