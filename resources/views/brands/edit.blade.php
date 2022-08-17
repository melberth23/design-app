@extends('layouts.app')

@section('title', 'Edit Brand Profile')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<div class="container">
    <form method="POST" action="{{route('brand.update', ['brand' => $brand->id])}}" enctype="multipart/form-data" class="edit-brand-form">
        @csrf
        @method('PUT')

        <input type="hidden" id="tempfile_code" name="tempfile_code" value="<?php echo time(); ?>">

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

                <div class="tab-text-label text-dark pt-3 pb-3">
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
                <div class="tab-text-label text-dark pb-3">
                    <label for="description">Description</label>
                    <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') ? old('description') : $brand->description }}</textarea>

                    @error('description')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark pb-3">
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
                <div class="tab-text-label text-dark pb-3">
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
                <div class="tab-text-label text-dark pb-3">
                    <label for="servicesProvider">Services/Products</label>
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
                <div class="tab-text-label text-dark pb-3">
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
                        <input type="file" id="primary-logo" name="logos[]" class="form-control-file @error('logos') is-invalid @enderror" multiple accept=".jpg,.png,.svg">
                    </div>
                    @error('logos')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex flex-wrap logos mt-1 pt-1">
                        @if ($logos->count() > 0)
                            @foreach ($logos as $logo)
                                <div id="media-{{ $logo->id }}" class="mx-1 logo media-container">
                                    <img src="{{ Storage::disk('s3')->url($logo->filename) }}" class="logo-img" />
                                    <div class="overlay">
                                        <div class="full-height d-flex align-items-center justify-content-center">
                                            <a href="{{ route('download', ['asset' => $logo->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                            <a href="{{ route('delete', ['asset' => $logo->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No primary logo available</em></p>
                        @endif 
                    </div>

                    <div id="primary-logos" class="d-flex flex-wrap logos">
                        <!-- Preview Logos -->
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
                    <input type="file" id="secondary-logo" name="logos_second[]" class="form-control-file" multiple accept=".jpg,.png,.svg">
                </div>
                @error('logos_second')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="d-flex flex-wrap logos mt-1 pt-1">
                    @if ($secondary_logos->count() > 0)
                        @foreach ($secondary_logos as $secondary_logo)
                            <div id="media-{{ $secondary_logo->id }}" class="mx-1 logo media-container">
                                <img src="{{ Storage::disk('s3')->url($secondary_logo->filename) }}" class="logo-img" />
                                <div class="overlay">
                                    <div class="full-height d-flex align-items-center justify-content-center">
                                        <a href="{{ route('download', ['asset' => $secondary_logo->id]) }}" class="action-icon">
                                          <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                        </a>
                                        <a href="{{ route('delete', ['asset' => $secondary_logo->id]) }}" class="action-icon">
                                          <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p><em>-No secondary logo available</em></p>
                    @endif
                </div>

                <div id="secondary-logos" class="d-flex flex-wrap logos">
                    <!-- Preview Logos -->
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
                                <div id="media-{{ $color->id }}" class="mx-1 color">
                                    <div style="background-color: {{ $color->filename }};border-radius: 50px; width: 40px; height: 40px;"></div>
                                    <div class="hover-item">
                                        <a href="javascript:void(0)" class="remove-color-item" onclick="deleteAsset({{ $color->id }});"><i class="fa fa-times" aria-hidden="true"></i></a>
                                        <div class="color-label"><span>{{ $color->filename }}</span></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No primary colors available</em></p>
                        @endif
                    </div>
                    <div class="pt-2">
                        <button type="button" onclick="return addColor('primary');" class="btn btn-primary">Add Primary Color</button>
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
                                <div id="media-{{ $secondary_color->id }}" class="mx-1 color">
                                    <div style="background-color: {{ $secondary_color->filename }};border-radius: 50px; width: 40px; height: 40px;"></div>
                                    <div class="hover-item">
                                        <a href="javascript:void(0)" class="remove-color-item" onclick="deleteAsset({{ $secondary_color->id }});"><i class="fa fa-times" aria-hidden="true"></i></a>
                                        <div class="color-label"><span>{{ $secondary_color->filename }}</span></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No secondary colors available</em></p>
                        @endif
                    </div>
                    <div class="pt-2">
                        <button type="button" onclick="return addColor('secondary');" class="btn btn-primary">Add Secondary Color</button>
                    </div>
                </div>
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
                            <p class="img-limit">Acceptable file, TTF, EOT, WOFF file, max file size 150mb.</p>
                        </div>
                        <div class="font-uploader">
                            <button type="button" onclick="getElementById('primary-fonts').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none fonts">
                        <input type="file" id="primary-fonts" name="fonts[]" class="form-control-file" multiple accept=".ttf,.eot,.woof">
                    </div>
                    @error('fonts')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex flex-wrap fonts mt-1 pt-1">
                        @if ($fonts->count() > 0)
                            @foreach ($fonts as $font)
                                <div id="media-{{ $font->id }}">
                                    <div class="mx-1 font media-container media-documents">
                                        <img src="{{ asset('images/font-img-') }}{{ $font->file_type }}.png" class="font-img">
                                        <div class="overlay">
                                            <div class="full-height d-flex align-items-center justify-content-center">
                                                <a href="{{ route('download', ['asset' => $font->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="{{ route('delete', ['asset' => $font->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="mt-1">{{ basename($font->filename) }}</label>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No primary fonts available</em></p>
                        @endif
                    </div>

                    <div id="primary-fonts-preview" class="d-flex flex-wrap fonts">
                        <!-- Preview Fonts -->
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light-custom py-4">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div class="font-information">
                        <h5 class="card-label text-dark">Secondary Font</h5>
                        <p class="img-description mb-0">Set a secondary font to your brand.</p>
                        <p class="img-limit">Acceptable file, TTF, EOT, WOFF file, max file size 150mb.</p>
                    </div>
                    <div class="font-uploader">
                        <button type="button" onclick="getElementById('secondary-fonts').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                    </div>
                </div>
                <div class="d-none fonts">
                    <input type="file" id="secondary-fonts" name="fonts_second[]" class="form-control-file" multiple accept=".ttf,.eot,.woof">
                </div>
                @error('fonts_second')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="d-flex flex-wrap fonts mt-1 pt-1">
                    @if ($secondary_fonts->count() > 0)
                        @foreach ($secondary_fonts as $secondary_font)
                            <div id="media-{{ $secondary_font->id }}">
                                <div class="mx-1 font media-container media-documents">
                                    <img src="{{ asset('images/font-img-') }}{{ $secondary_font->file_type }}.png" class="font-img">
                                    <div class="overlay">
                                        <div class="full-height d-flex align-items-center justify-content-center">
                                            <a href="{{ route('download', ['asset' => $secondary_font->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                            <a href="{{ route('delete', ['asset' => $secondary_font->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <label class="mt-1">{{ basename($font->filename) }}</label>
                            </div>
                        @endforeach
                    @else
                        <p><em>-No secondary fonts available</em></p>
                    @endif
                </div>

                <div id="secondary-fonts-preview" class="d-flex flex-wrap fonts">
                    <!-- Preview Fonts -->
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
                        <input type="file" id="pictures" name="pictures[]" class="form-control-file" multiple accept=".png,.jpg,.jpeg">
                    </div>
                    @error('pictures')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex flex-wrap pictures mt-1 pt-1">
                        @if ($images->count() > 0)
                            @foreach ($images as $image)
                                <div id="media-{{ $image->id }}" class="mx-1 picture media-container">
                                    <img src="{{ Storage::disk('s3')->url($image->filename) }}" class="picture-img">
                                    <div class="overlay">
                                        <div class="full-height d-flex align-items-center justify-content-center">
                                            <a href="{{ route('download', ['asset' => $image->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                            <a href="{{ route('delete', ['asset' => $image->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No images available</em></p>
                        @endif
                    </div>

                    <div id="preview-pictures" class="d-flex flex-wrap pictures">
                        <!-- Preview pictures -->
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
                            <p class="img-limit">Acceptable file DOC, DOCX, PDF, JPG, PNG max file size 150mb.</p>
                        </div>
                        <div class="guideline-uploader">
                            <button type="button" onclick="getElementById('guidelines-item').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none">
                        <input type="file" id="guidelines-item" name="guidelines[]" class="form-control-file" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                    </div>
                    @error('guidelines')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex flex-wrap guidelines mt-1 pt-1">
                        @if ($guidelines->count() > 0)
                            @foreach ($guidelines as $guideline)
                                <?php
                                    $previewimage = asset('images/guidelines-img-') . $guideline->file_type .'.png';
                                    $withguideimg = '';
                                    if($guideline->file_type == 'jpg' || $guideline->file_type == 'png') {
                                        $previewimage = Storage::disk('s3')->url($guideline->filename);
                                        $withguideimg = 'guide-with-image';
                                    }
                                ?>
                                <div id="media-{{ $guideline->id }}">
                                    <div class="mx-1 guideline {{ $withguideimg }} media-container media-documents">
                                        <img src="{{ $previewimage }}" class="guideline-img">
                                        <div class="overlay">
                                            <div class="full-height d-flex align-items-center justify-content-center">
                                                <a href="{{ route('download', ['asset' => $guideline->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="{{ route('delete', ['asset' => $guideline->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="mt-1">{{ basename($guideline->filename) }}</label>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No guidelines available</em></p>
                        @endif
                    </div>

                    <div id="guidelines-preview" class="d-flex flex-wrap guidelines">
                        <!-- Preview guidelines -->
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
                            <p class="img-limit">Acceptable file PNG, JPG, PDF, PSD, AI, INDD, DOC, DOCX max file size 150mb.</p>
                        </div>
                        <div class="template-uploader">
                            <button type="button" onclick="getElementById('templates-item').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none">
                        <input type="file" id="templates-item" name="templates[]" class="form-control-file" multiple accept=".doc,.docx,.indd,.pdf,.psd,.ai,.jpg,.png">
                    </div>
                    @error('templates')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex flex-wrap templates mt-1 pt-1">
                        @if ($templates->count() > 0)
                            @foreach ($templates as $template)
                                <?php
                                    $previewtemimage = asset('images/template-img-') . $template->file_type .'.png';
                                    $withtemimg = '';
                                    if($template->file_type == 'jpg' || $template->file_type == 'png') {
                                        $previewtemimage = Storage::disk('s3')->url($template->filename);
                                        $withtemimg = 'template-with-image';
                                    }
                                ?>
                                <div id="media-{{ $template->id }}">
                                    <div class="mx-1 template {{ $withtemimg }} media-container media-documents">
                                        <img src="{{ $previewtemimage }}" class="template-img">
                                        <div class="overlay">
                                            <div class="full-height d-flex align-items-center justify-content-center">
                                                <a href="{{ route('download', ['asset' => $template->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="{{ route('delete', ['asset' => $template->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="mt-1">{{ basename($template->filename) }}</label>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No templates available</em></p>
                        @endif
                    </div>

                    <div id="templates-preview" class="d-flex flex-wrap templates">
                        <!-- Preview templates -->
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
                            <p class="img-limit">Acceptable file, PNG, JPEG, max file size 150mb.</p>
                        </div>
                        <div class="inspiration-uploader">
                            <button type="button" onclick="getElementById('inspiration-field').click();" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Upload</button>
                        </div>
                    </div>
                    <div class="d-none">
                        <input type="file" id="inspiration-field" name="inspirations[]" class="form-control-file" multiple accept=".png,.jpg">
                    </div>
                    @error('inspirations')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="d-flex flex-wrap inspirations mt-1 pt-1">
                        @if ($inspirations->count() > 0)
                            @foreach ($inspirations as $inspiration)
                                <div id="media-{{ $inspiration->id }}" class="mx-1 inspiration media-container">
                                    <img src="{{ Storage::disk('s3')->url($inspiration->filename) }}" class="inspiration-img">
                                    <div class="overlay">
                                        <div class="full-height d-flex align-items-center justify-content-center">
                                            <a href="{{ route('download', ['asset' => $inspiration->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                            <a href="{{ route('delete', ['asset' => $inspiration->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No inspirations available</em></p>
                        @endif
                    </div>

                    <div id="inspirations-preview" class="d-flex flex-wrap inspirations">
                        <!-- Preview templates -->
                    </div>
                </div>
                <div class="text-dark pt-1 pb-4">
                    <label class="img-description" for="other_inspirations"> Other Inspirations</label>
                    <textarea id="other_inspirations" class="form-control form-control-user" name="other_inspirations">{{ old('other_inspirations') ? old('other_inspirations') : $brand->other_inspirations }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4 {{ ($section == 'social' or $section == 'all') ? '' : 'd-none' }}">
            <div class="card-header bg-light-custom">
                <h3 class="text-dark mb-0">Social Profile</h3>
            </div>
            <div class="card-body">
                <div class="tab-text-label text-dark pt-3 pb-3">
                    <label for="facebook"> Facebook</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('facebook') is-invalid @enderror" 
                        id="facebook"
                        name="facebook" 
                        value="{{ old('facebook') ? old('facebook') : $brand->facebook }}">
                </div>
                <div class="tab-text-label text-dark pb-3">
                    <label for="linkedin"> Linkedin</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('linkedin') is-invalid @enderror" 
                        id="linkedin"
                        name="linkedin" 
                        value="{{ old('linkedin') ? old('linkedin') : $brand->linkedin }}">
                </div>
                <div class="tab-text-label text-dark pb-3">
                    <label for="instagram"> Instagram</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('instagram') is-invalid @enderror" 
                        id="instagram"
                        name="instagram" 
                        value="{{ old('instagram') ? old('instagram') : $brand->instagram }}">
                </div>
                <div class="tab-text-label text-dark pb-3">
                    <label for="twitter"> Twitter</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('twitter') is-invalid @enderror" 
                        id="twitter"
                        name="twitter" 
                        value="{{ old('twitter') ? old('twitter') : $brand->twitter }}">
                </div>
                <div class="tab-text-label text-dark pb-3">
                    <label for="youtube"> Youtube</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('youtube') is-invalid @enderror" 
                        id="youtube"
                        name="youtube" 
                        value="{{ old('youtube') ? old('youtube') : $brand->youtube }}">
                </div>
                <div class="tab-text-label text-dark pb-3">
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

    function deleteAsset(assetid) {
        if(confirm("Deleting this asset cannot be undone. Continue?")) {
            jQuery.ajax({
                url: "{{ route('brand.destroyassets') }}",
                type:"POST",
                data:{
                    asset:assetid,
                    _token: jQuery('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    jQuery('#media-'+ assetid).remove();
                }
            });
        }
    }

    function addColor(color_type) {
        jQuery( "#"+ color_type +"-colors .color-pick:first-child" ).clone().find("input").val("").appendTo( "#"+ color_type +"-colors" );

        jQuery('.colorpicker').colorpicker();
    }


    jQuery(function($) {
        // Primary logo previews
        $('#primary-logo').on('change', function(e) {
            var files = e.target.files;
            // $('#primary-logos').html('');
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.edit-brand-form').find("input[name=_token]").val());
                form_data.append("logos", file);
                form_data.append("tempfile_code", $('#tempfile_code').val());
                form_data.append("module", "logo");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        $('#primary-logos').append('<div id="media-preview-'+ data.file.logo_id +'" class="mx-1 logo media-container"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.logo_id +');"><i class="fas fa-times"></i></a><img src="'+ data.file.path +'" class="logo-img" /></div>');
                    }
                });
            });

        });
        // Secondary logo previews
        $('#secondary-logo').on('change', function(e) {
            var files = e.target.files;
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.edit-brand-form').find("input[name=_token]").val());
                form_data.append("logos_second", file);
                form_data.append("tempfile_code", $('#tempfile_code').val());
                form_data.append("module", "logo_second");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        $('#secondary-logos').append('<div id="media-preview-'+ data.file.logo_id +'" class="mx-1 logo media-container"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.logo_id +');"><i class="fas fa-times"></i></a><img src="'+ data.file.path +'" class="logo-img" /></div>');
                    }
                });
            });
        });
        // Primary fonts previews
        $('#primary-fonts').on('change', function(e) {
            var files = e.target.files;
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.edit-brand-form').find("input[name=_token]").val());
                form_data.append("fonts", file);
                form_data.append("tempfile_code", $('#tempfile_code').val());
                form_data.append("module", "font");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        var filename = data.file.path;
                        var fileExt = filename.split('.').pop();

                        $('#primary-fonts-preview').append('<div id="media-preview-'+ data.file.font_id +'"><div class="mx-1 font media-container media-documents"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.font_id +');"><i class="fas fa-times"></i></a><img src="<?php echo asset('images/font-img-'); ?>'+ fileExt +'.png" class="font-img" /></div></div>');
                    }
                });
            });
        });
        // Secondary fonts previews
        $('#secondary-fonts').on('change', function(e) {
            var files = e.target.files;
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.edit-brand-form').find("input[name=_token]").val());
                form_data.append("fonts", file);
                form_data.append("tempfile_code", $('#tempfile_code').val());
                form_data.append("module", "font_second");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        var filename = data.file.path;
                        var fileExt = filename.split('.').pop();

                        $('#secondary-fonts-preview').append('<div id="media-preview-'+ data.file.font_id +'"><div class="mx-1 font media-container media-documents"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.font_id +');"><i class="fas fa-times"></i></a><img src="<?php echo asset('images/font-img-'); ?>'+ fileExt +'.png" class="font-img" /></div></div>');
                    }
                });
            });
        });
        // Pictures previews
        $('#pictures').on('change', function(e) {
            var files = e.target.files;
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.edit-brand-form').find("input[name=_token]").val());
                form_data.append("pictures", file);
                form_data.append("tempfile_code", $('#tempfile_code').val());
                form_data.append("module", "picture");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        $('#preview-pictures').append('<div id="media-preview-'+ data.file.picture_id +'" class="mx-1 picture media-container"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.picture_id +');"><i class="fas fa-times"></i></a><img src="'+ data.file.path +'" class="picture-img" /></div>');
                    }
                });
            });
        });
        // Guidelines previews
        $('#guidelines-item').on('change', function(e) {
            var files = e.target.files;
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.edit-brand-form').find("input[name=_token]").val());
                form_data.append("guidelines", file);
                form_data.append("tempfile_code", $('#tempfile_code').val());
                form_data.append("module", "guideline");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        var filename = data.file.path;
                        var fileExt = filename.split('.').pop();
                        var previewpath = "<?php echo asset('images/guidelines-img-'); ?>"+ fileExt +".png";
                        var withimage = '';
                        if(fileExt == 'jpg' || fileExt == 'png') {
                            previewpath = filename;
                            withimage = 'guide-with-image';
                        }

                        $('#guidelines-preview').append('<div id="media-preview-'+ data.file.guideline_id +'"><div class="mx-1 guideline '+ withimage +' media-container media-documents"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.guideline_id +');"><i class="fas fa-times"></i></a><img src="'+ previewpath +'" class="guideline-img" /></div></div>');
                    }
                });
            });
        });
        // Templates previews
        $('#templates-item').on('change', function(e) {
            var files = e.target.files;
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.edit-brand-form').find("input[name=_token]").val());
                form_data.append("templates", file);
                form_data.append("tempfile_code", $('#tempfile_code').val());
                form_data.append("module", "template");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        var filename = data.file.path;
                        var fileExt = filename.split('.').pop();
                        var previewpath = "<?php echo asset('images/template-img-'); ?>"+ fileExt +".png";
                        var withimage = '';
                        if(fileExt == 'jpg' || fileExt == 'png') {
                            previewpath = filename;
                            withimage = 'template-with-image';
                        }

                        $('#templates-preview').append('<div id="media-preview-'+ data.file.template_id +'"><div class="mx-1 template '+ withimage +' media-container media-documents"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.template_id +');"><i class="fas fa-times"></i></a><img src="'+ previewpath +'" class="template-img" /></div></div>');
                    }
                });
            });
        });
        // Inspiration previews
        $('#inspiration-field').on('change', function(e) {
            var files = e.target.files;
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.edit-brand-form').find("input[name=_token]").val());
                form_data.append("inspirations", file);
                form_data.append("tempfile_code", $('#tempfile_code').val());
                form_data.append("module", "inspiration");

                $.ajax({
                    url:'{{ route('tempfiles') }}',
                    method:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success: function(data) {
                        $('#inspirations-preview').append('<div id="media-preview-'+ data.file.inspiration_id +'" class="mx-1 inspiration media-container"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.inspiration_id +');"><i class="fas fa-times"></i></a><img src="'+ data.file.path +'" class="inspiration-img" /></div>');
                    }
                });
            });
        });
    });

    function removeColor(e)
    {
        if(jQuery('#primary-colors').find('.color-pick').length > 1) {
            e.parentNode.parentNode.removeChild(e.parentNode);

            if(jQuery('#primary-colors').find('.color-pick').length == 1) {
               jQuery('#primary-colors').find('.primary-remove-btn').hide(); 
            }
        } else {
            jQuery('#primary-colors').find('.primary-remove-btn').hide();
        }
    }

    function removeTempFile(id)
    {
        var form_data = new FormData();
        form_data.append("_token", jQuery('.edit-brand-form').find("input[name=_token]").val());
        form_data.append("fid", id);

        jQuery.ajax({
            url:'{{ route('delete.tempfiles') }}',
            method:'POST',
            data:form_data,
            contentType:false,
            cache:false,
            processData:false,
            success: function(data) {
                jQuery('#media-preview-'+ data.fid).remove();
            }
        });
    }
</script>
@stop