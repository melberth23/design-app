@extends('layouts.app')

@section('title', 'Add Request')

@section('content')

<div class="container">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('request.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">{{ $designtype->name }}</span></h1>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')

    <form method="POST" action="{{route('request.store')}}" enctype="multipart/form-data" class="form-brand-request">
        @csrf

        <input type="hidden" name="design_type" value="{{ $designtype->id }}">

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                <h3 class="text-dark mb-0">Describe your request</h3>
            </div>
            <div class="card-body pt-0">
                <div class="tab-text-label text-dark pt-3">
                    <label for="title">Project name</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('title') is-invalid @enderror" 
                        id="title"
                        name="title" 
                        value="{{ old('title') }}">

                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="description">Project brief</label>
                    <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

                    @error('description')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="custom-control custom-switch pt-3">
                    <input type="checkbox" name="include_text" class="custom-control-input" id="include_text">
                    <label class="custom-control-label" for="include_text">Does your design need to include text?</label>
                </div>
                <div id="included-text-description" class="d-none tab-text-label text-dark pt-3">
                    <label for="included_text_description">Included text</label>
                    <textarea id="included_text_description" class="form-control form-control-user" name="included_text_description">{{ old('included_text_description') }}</textarea>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="dimensions">Design dimension</label>
                    <select id="dimensions" class="form-control form-control-user @error('dimensions') is-invalid @enderror" name="dimensions">
                        <option selected disabled>Select dimension</option>
                        <option value="landscape" {{ old('dimensions') == 'landscape' ? 'selected' : '' }}>Landscape</option>
                        <option value="square" {{ old('dimensions') == 'square' ? 'selected' : '' }}>Square</option>
                        <option value="portrait" {{ old('dimensions') == 'portrait' ? 'selected' : '' }}>Portrait</option>
                        <option value="custom" {{ old('dimensions') == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>

                    @error('dimensions')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div id="custom-dimension" class="d-none tab-text-label text-dark pt-3">
                    <label for="custom_dimension">Custom dimension</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="custom_dimension"
                        name="custom_dimension" 
                        value="{{ old('custom_dimension') }}">
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="dimensions">Dimension Additional Information</label>
                    <textarea id="dimensions_additional_info" class="form-control form-control-user @error('dimensions_additional_info') is-invalid @enderror" name="dimensions_additional_info">{{ old('dimensions_additional_info') }}</textarea>

                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="brand_id">Brand profile</label>
                    <select class="form-control form-control-user @error('brand_id') is-invalid @enderror" name="brand_id">
                        <option selected disabled>Select brand profile</option>
                        @foreach ($brands as $brand)
                            <option value="{{$brand->id}}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{$brand->name}}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <span class="text-dark font-weight-bold">Assets</span>
                </div>
                <div class="request-assets tab-text-label text-dark pt-3">
                    <label for="media">Upload any design assets or inspiration we should follow.</label>
                    <input type="file" id="asset-requests" name="media[]" class="form-control-file" multiple data-multiple-caption="{count} files selected" >
                    <div class="asset-uploader rounded bg-light py-5 text-center">
                        <div class="py-1 upload-icon"><i class="fa fa-cloud-upload" aria-hidden="true"></i></div>
                        <p id="asset-label" class="img-limit mb-0"></p>
                        <p class="img-description mb-0">Drag and drop or <a href="javascript:void(0);" onclick="getElementById('asset-requests').click();">click here</a> to attach</p>
                        <p class="img-limit">Acceptable file, PNG, JPEG, JPG, MP4 max file size 150mb.</p>
                    </div>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="reference_link">Example of reference links</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="reference_link"
                        name="reference_link" 
                        value="{{ old('reference_link') }}">
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <span class="text-dark font-weight-bold">File types</span>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="jpg" value="jpg">
                        <label class="custom-control-label" for="jpg">.jpg</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="png" value="png">
                        <label class="custom-control-label" for="png">.png</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="gif" value="gif">
                        <label class="custom-control-label" for="gif">.gif</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="pdf" value="pdf">
                        <label class="custom-control-label" for="pdf">.pdf</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="any" value="any">
                        <label class="custom-control-label" for="any">any</label>
                    </div>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <span class="text-dark font-weight-bold">Adobe</span>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="adobe_type[]" id="psd" value="psd">
                        <label class="custom-control-label" for="psd">psd</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="adobe_type[]" id="ai" value="ai">
                        <label class="custom-control-label" for="ai">ai</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="adobe_type[]" id="indd" value="indd">
                        <label class="custom-control-label" for="indd">indd</label>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light-custom py-4">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <button type="submit" class="btn btn-primary btn-user">Submit My Request</button>
                    <input type="submit" name="save_draft" value="Save As Draft" class="btn btn-default border border-dark">
                </div>
            </div>
        </div>

    </form>

</div>


@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        var $parent    = $('.asset-uploader'),
            $input    = $('#asset-requests'),
            $label    = $('#asset-label'),
            showFiles = function(files) {
              $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
            };

        $parent.on('drop', function(e) {
          droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
          showFiles( droppedFiles );
        });

        $input.on('change', function(e) {
          showFiles(e.target.files);
        });

        $('#include_text').on('change', function(e) {
            if( $('#include_text').is(':checked') ) {
                $('#included-text-description').removeClass('d-none');
            } else {
                $('#included-text-description').addClass('d-none');
            }
        });

        $('#dimensions').on('change', function(e) {
            if( $('#dimensions').val() == 'custom' ) {
                $('#custom-dimension').removeClass('d-none');
            } else {
                $('#custom-dimension').addClass('d-none');
            }
        });
    });
</script>

@endsection