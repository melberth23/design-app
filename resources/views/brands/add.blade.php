@extends('layouts.app')

@section('title', 'Add Brand Profile')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<div class="container">
    <form method="POST" action="{{route('brand.store')}}" enctype="multipart/form-data" class="form-brand-profile">
        @csrf

        <input type="hidden" id="tempfile_code" name="tempfile_code" value="<?php echo time(); ?>">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('brand.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">Create brand profile</span></h1>
            <div class="actions d-sm-flex align-items-center justify-content-between">
                <button type="submit" class="btn btn-primary btn-user">Save Brand Profile</button>
            </div>
        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <div class="card mb-4">
            <div class="card-body py-0 px-1">
                <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active py-3 text-dark" id="information-tab" data-toggle="tab" href="#information" role="tab" aria-controls="information" aria-selected="true" data-area="add-brand">Brand Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 text-dark" id="assets-tab" data-toggle="tab" href="#assets" role="tab" aria-controls="assets" aria-selected="false" data-area="add-brand">Assets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 text-dark" id="guidelines-tab" data-toggle="tab" href="#guidelines" role="tab" aria-controls="guidelines" aria-selected="false" data-area="add-brand">Brand Guidelines</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 text-dark" id="templates-tab" data-toggle="tab" href="#templates" role="tab" aria-controls="templates" aria-selected="false" data-area="add-brand">Templates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 text-dark" id="inspirations-tab" data-toggle="tab" href="#inspirations" role="tab" aria-controls="inspirations" aria-selected="false" data-area="add-brand">Brand Inspiration</a>
                    <li class="nav-item">
                        <a class="nav-link py-3 text-dark" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false" data-area="add-brand">Social Profile</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="brand-tab-contents">
            <div class="tab-pane fade show active" id="information" role="tabpanel" aria-labelledby="information-tab">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                        <h3 class="text-dark mb-0">Brand Information</h3>

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

                        <div class="tab-text-label text-dark pt-3 pb-3">
                            <label for="brandName">Brand Name</label>
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
                        <div class="tab-text-label text-dark pb-3">
                            <label for="description">Description</label>
                            <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

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
                                value="{{ old('industry') }}">

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
                                value="{{ old('target_audience') }}">

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
                                value="{{ old('services_provider') }}">

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
                                value="{{ old('website') }}">

                            @error('website')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-sm-flex align-items-center justify-content-end mb-4">
                    <a class="btn btn-primary btn-user mb-3 continue-btn" data-toggle="tab" href="#assets" role="tab" aria-controls="assets" aria-selected="false">Continue</a>
                </div>
            </div>

            <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="assets-tab">
                <div class="card mb-4">
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
                            <div id="primary-logos" class="d-flex flex-wrap logos">
                                <!-- Preview Logos -->
                            </div>
                            <div class="d-none logos">
                                <input type="file" id="primary-logo" name="logos[]" class="form-control-file @error('logos') is-invalid @enderror" multiple accept=".jpg,.png,.svg">
                            </div>
                            @error('logos')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
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
                        <div id="secondary-logos" class="d-flex flex-wrap logos">
                            <!-- Preview Logos -->
                        </div>
                        <div class="d-none logos">
                            <input type="file" id="secondary-logo" name="logos_second[]" class="form-control-file" multiple accept=".jpg,.png,.svg">
                        </div>
                        @error('logos_second')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light-custom">
                        <h3 class="text-dark mb-0">Colors</h3>
                    </div>
                    <div class="card-body">
                        <div class="py-4 border-bottom">
                            <h5 class="card-label text-dark">Primary Colors</h5>
                            <div id="primary-colors" class="colors">
                                <div class="d-flex color-pick">
                                    <input type="text" name="colors[]" class="form-control colorpicker col-md-3" >
                                    <a class="primary-remove-btn btn btn-link" href="javascript:void(0)" onclick="removeColor(this);" style="display: none;"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @error('colors.*')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                            <div class="pt-2">
                                <button type="button" onclick="return addColor('primary');" class="btn btn-primary">Add Primary Color</button>
                            </div>
                        </div>
                        <div class="pt-4">
                            <h5 class="card-label text-dark">Secondary Colors</h5>
                            <div id="secondary-colors" class="colors">
                                <div class="d-flex color-pick">
                                    <input type="text" name="colors_second[]" class="form-control colorpicker col-md-3" >
                                    <a class="secondary-remove-btn btn btn-link" href="javascript:void(0)" onclick="removeSecondaryColor(this);" style="display: none;"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @error('colors_second.*')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                            <div class="pt-2">
                                <button type="button" onclick="return addColor('secondary');" class="btn btn-primary">Add Secondary Color</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
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
                            <div id="primary-fonts-preview" class="d-flex flex-wrap fonts">
                                <!-- Preview Fonts -->
                            </div>
                            <div class="d-none fonts">
                                <input type="file" id="primary-fonts" name="fonts[]" class="form-control-file" multiple accept=".ttf,.eot,.woof">
                            </div>
                            @error('fonts')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
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
                        <div id="secondary-fonts-preview" class="d-flex flex-wrap fonts">
                            <!-- Preview Fonts -->
                        </div>
                        <div class="d-none fonts">
                            <input type="file" id="secondary-fonts" name="fonts_second[]" class="form-control-file" multiple accept=".ttf,.eot,.woof">
                        </div>
                        @error('fonts_second')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light-custom">
                        <h3 class="text-dark mb-0">Images</h3>
                    </div>
                    <div class="card-body">
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
                            <div id="preview-pictures" class="d-flex flex-wrap pictures">
                                <!-- Preview pictures -->
                            </div>
                            <div class="d-none">
                                <input type="file" id="pictures" name="pictures[]" class="form-control-file" multiple accept=".png,.jpg,.jpeg">
                            </div>
                            @error('pictures')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-sm-flex align-items-center justify-content-end mb-4">
                    <a class="btn btn-primary btn-user mb-3 continue-btn" data-toggle="tab" href="#guidelines" role="tab" aria-controls="guidelines" aria-selected="false">Continue</a>
                </div>
            </div>

            <div class="tab-pane fade" id="guidelines" role="tabpanel" aria-labelledby="guidelines-tab">
                <div class="card mb-4">
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
                            <div id="guidelines-preview" class="d-flex flex-wrap guidelines">
                                <!-- Preview guidelines -->
                            </div>
                            <div class="d-none">
                                <input type="file" id="guidelines-item" name="guidelines[]" class="form-control-file" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                            </div>
                            @error('guidelines')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-sm-flex align-items-center justify-content-end mb-4">
                    <a class="btn btn-primary btn-user mb-3 continue-btn" data-toggle="tab" href="#templates" role="tab" aria-controls="templates" aria-selected="false">Continue</a>
                </div>
            </div>

            <div class="tab-pane fade" id="templates" role="tabpanel" aria-labelledby="templates-tab">
                <div class="card mb-4">
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
                            <div id="templates-preview" class="d-flex flex-wrap templates">
                                <!-- Preview templates -->
                            </div>
                            <div class="d-none">
                                <input type="file" id="templates-item" name="templates[]" class="form-control-file" multiple accept=".doc,.docx,.indd,.pdf,.psd,.ai,.jpg,.png">
                            </div>
                            @error('templates')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-sm-flex align-items-center justify-content-end mb-4">
                    <a class="btn btn-primary btn-user mb-3 continue-btn" data-toggle="tab" href="#inspirations" role="tab" aria-controls="inspirations" aria-selected="false">Continue</a>
                </div>
            </div>

            <div class="tab-pane fade" id="inspirations" role="tabpanel" aria-labelledby="inspirations-tab"> 
                <div class="card mb-4">
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
                            <div id="inspirations-preview" class="d-flex flex-wrap inspirations">
                                <!-- Preview templates -->
                            </div>
                            <div class="d-none">
                                <input type="file" id="inspiration-field" name="inspirations[]" class="form-control-file" multiple accept=".png,.jpg">
                            </div>
                            @error('inspirations')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="text-dark pt-1 pb-4">
                            <label class="img-description" for="other_inspirations"> Other Inspirations</label>
                            <textarea id="other_inspirations" class="form-control form-control-user @error('other_inspirations') is-invalid @enderror" name="other_inspirations">{{ old('other_inspirations') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="d-sm-flex align-items-center justify-content-end mb-4">
                    <a class="btn btn-primary btn-user mb-3 continue-btn" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">Continue</a>
                </div>
            </div>

            <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                <div class="card mb-4">
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
                                value="{{ old('facebook') }}">
                        </div>
                        <div class="tab-text-label text-dark pb-3">
                            <label for="linkedin"> Linkedin</label>
                            <input 
                                type="text" 
                                class="form-control form-control-user @error('linkedin') is-invalid @enderror" 
                                id="linkedin"
                                name="linkedin" 
                                value="{{ old('linkedin') }}">
                        </div>
                        <div class="tab-text-label text-dark pb-3">
                            <label for="instagram"> Instagram</label>
                            <input 
                                type="text" 
                                class="form-control form-control-user @error('instagram') is-invalid @enderror" 
                                id="instagram"
                                name="instagram" 
                                value="{{ old('instagram') }}">
                        </div>
                        <div class="tab-text-label text-dark pb-3">
                            <label for="twitter"> Twitter</label>
                            <input 
                                type="text" 
                                class="form-control form-control-user @error('twitter') is-invalid @enderror" 
                                id="twitter"
                                name="twitter" 
                                value="{{ old('twitter') }}">
                        </div>
                        <div class="tab-text-label text-dark pb-3">
                            <label for="youtube"> Youtube</label>
                            <input 
                                type="text" 
                                class="form-control form-control-user @error('youtube') is-invalid @enderror" 
                                id="youtube"
                                name="youtube" 
                                value="{{ old('youtube') }}">
                        </div>
                        <div class="tab-text-label text-dark pb-3">
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
                <div class="d-sm-flex align-items-center justify-content-end mb-4">
                    <button type="submit" class="btn btn-primary btn-user mb-3">Save Brand Profile</button>
                </div>
            </div>
        </div>

    </form>

</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script>
    jQuery('.colorpicker').colorpicker();

    function addColor(color_type) {
        jQuery( "#"+ color_type +"-colors .color-pick:first-child" ).clone().find("input").val("").appendTo( "#"+ color_type +"-colors" );
        jQuery('#'+ color_type +'-colors').find('.'+ color_type +'-remove-btn').show();

        jQuery('.colorpicker').colorpicker();
    }

    jQuery(function($) {
        // Primary logo previews
        $('#primary-logo').on('change', function(e) {
            var files = e.target.files;
            // $('#primary-logos').html('');
            $.each( files, function( i, file ) {
                var form_data = new FormData();
                form_data.append("_token", $('.form-brand-profile').find("input[name=_token]").val());
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
                form_data.append("_token", $('.form-brand-profile').find("input[name=_token]").val());
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
                form_data.append("_token", $('.form-brand-profile').find("input[name=_token]").val());
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
                form_data.append("_token", $('.form-brand-profile').find("input[name=_token]").val());
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
                form_data.append("_token", $('.form-brand-profile').find("input[name=_token]").val());
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
                form_data.append("_token", $('.form-brand-profile').find("input[name=_token]").val());
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
                form_data.append("_token", $('.form-brand-profile').find("input[name=_token]").val());
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
                form_data.append("_token", $('.form-brand-profile').find("input[name=_token]").val());
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

    function removeSecondaryColor(e)
    {
        if(jQuery('#secondary-colors').find('.color-pick').length > 1) {
            e.parentNode.parentNode.removeChild(e.parentNode);

            if(jQuery('#secondary-colors').find('.color-pick').length == 1) {
               jQuery('#secondary-colors').find('.secondary-remove-btn').hide(); 
            }
        } else {
            jQuery('#secondary-colors').find('.secondary-remove-btn').hide();
        }
    }

    function removeTempFile(id)
    {
        var form_data = new FormData();
        form_data.append("_token", jQuery('.form-brand-profile').find("input[name=_token]").val());
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