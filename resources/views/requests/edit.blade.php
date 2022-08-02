@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="container">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('request.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">Edit {{ $requests->title }}</span></h1>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')

    <form method="POST" action="{{route('request.update', ['requests' => $requests->id])}}" enctype="multipart/form-data" class="form-brand-request">
        @csrf
        @method('PUT')

        <input type="hidden" id="tempfile_code" name="tempfile_code" value="<?php echo time(); ?>">

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between bg-light-custom">
                <h3 class="text-dark mb-0">Describe your request</h3>
            </div>
            <div class="card-body pt-0">
                <div class="tab-text-label text-dark pt-3">
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
                <div class="tab-text-label text-dark pt-3">
                    <label for="title">Project name</label>
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
                <div class="tab-text-label text-dark pt-3">
                    <label for="description">Project brief</label>
                    <textarea id="description" class="form-control form-control-user @error('description') is-invalid @enderror" name="description">{{ old('description') ?  old('description') : $requests->description }}</textarea>

                    @error('description')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="custom-control custom-switch pt-3">
                    <input type="checkbox" name="include_text" class="custom-control-input" id="include_text" {{ ($requests->include_text == 'on') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="include_text">Does your design need to include text?</label>
                </div>
                <div id="included-text-description" class="{{ ($requests->include_text == 'on') ? '' : 'd-none' }} tab-text-label text-dark pt-3">
                    <label for="included_text_description">Included text</label>
                    <textarea id="included_text_description" class="form-control form-control-user" name="included_text_description">{{ old('included_text_description') ?  old('included_text_description') : $requests->included_text_description }}</textarea>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="dimensions">Design dimension</label>
                    <select id="dimensions" class="form-control form-control-user @error('dimensions') is-invalid @enderror" name="dimensions">
                        <option selected disabled>Select dimension</option>
                        @foreach($dimensions as $dimension)
                            <option value="{{ $dimension->label }}" {{old('dimensions') ? ((old('dimensions') == $dimension->label) ? 'selected' : '') : (($requests->dimensions == $dimension->label) ? 'selected' : '')}}>{{ $dimension->label }}</option>
                        @endforeach  
                        <option value="custom" {{old('dimensions') ? ((old('dimensions') == 'custom') ? 'selected' : '') : (($requests->dimensions == 'custom') ? 'selected' : '')}}>Custom</option>
                    </select>

                    @error('dimensions')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div id="custom-dimension" class="{{ ($requests->dimensions == 'custom') ? '' : 'd-none' }} tab-text-label text-dark pt-3">
                    <label for="custom_dimension">Custom dimension</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="custom_dimension"
                        name="custom_dimension" 
                        value="{{ old('custom_dimension') ?  old('custom_dimension') : $requests->custom_dimension }}">
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="dimensions">Dimension Additional Information</label>
                    <textarea id="dimensions_additional_info" class="form-control form-control-user @error('dimensions_additional_info') is-invalid @enderror" name="dimensions_additional_info">{{ old('dimensions_additional_info') ?  old('dimensions_additional_info') : $requests->dimensions_additional_info }}</textarea>

                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="brand_id">Brand profile</label>
                    <select class="form-control form-control-user @error('brand_id') is-invalid @enderror" name="brand_id">
                        <option selected disabled>Select brand profile</option>
                        @foreach ($brands as $brand)
                            <option value="{{$brand->id}}" {{old('brand_id') ? ((old('brand_id') == $brand->id) ? 'selected' : '') : (($brand->id == $requests->brand_id) ? 'selected' : '')}}>{{$brand->name}}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <span class="text-dark font-weight-bold">Assets</span>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <p class="img-description">Upload any design assets or inspiration we should follow.</p>
                    <div class="d-flex flex-wrap pictures">
                        @if ($medias->count() > 0)
                            @foreach ($medias as $media)
                                <div id="media-{{ $media->id }}">
                                    <div class="mx-1 media media-container">
                                        <img src="{{ Storage::disk('s3')->url($media->filename) }}" class="picture-img">
                                        <div class="overlay">
                                            <div class="full-height d-flex align-items-center justify-content-center">
                                                <a href="{{ route('request.download', ['asset' => $media->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="{{ route('request.delete', ['asset' => $media->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No images available</em></p>
                        @endif
                    </div>
                </div>
                <div id="pictures-preview" class="d-flex flex-wrap pictures">
                    <!-- Preview Images -->
                </div>
                <div class="request-assets tab-text-label text-dark pt-3">
                    <input type="file" id="asset-requests" name="media[]" class="form-control-file" multiple data-multiple-caption="{count} files selected" accept=".png,.jpeg,.jpg">
                    <div class="asset-uploader rounded bg-light py-5 text-center">
                        <div class="py-1 upload-icon"><i class="fa fa-cloud-upload" aria-hidden="true"></i></div>
                        <p id="asset-label" class="img-limit mb-0"></p>
                        <p class="img-description mb-0">Drag and drop or <a href="javascript:void(0);" onclick="getElementById('asset-requests').click();">click here</a> to attach</p>
                        <p class="img-limit">Acceptable file, PNG, JPEG, JPG max file size 150mb.</p>
                    </div>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <label for="reference_link">Example of reference links</label>
                    <input 
                        type="text" 
                        class="form-control @error('reference_link') is-invalid @enderror" 
                        id="reference_link"
                        name="reference_link" 
                        value="{{ old('reference_link') ?  old('reference_link') : $requests->reference_link }}">

                    @error('reference_link')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <span class="text-dark font-weight-bold">File types</span>
                </div>
                <?php 
                    $file_types = explode(',', $requests->format);
                ?>
                <div class="tab-text-label text-dark pt-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="jpg" value="jpg" {{ (in_array('jpg', $file_types)) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="jpg">.jpg</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="png" value="png" {{ (in_array('png', $file_types)) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="png">.png</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="pdf" value="pdf" {{ (in_array('pdf', $file_types)) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="pdf">.pdf</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="file_type[]" id="any" value="any" {{ (in_array('any', $file_types)) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="any">any</label>
                    </div>
                </div>
                <div class="tab-text-label text-dark pt-3">
                    <span class="text-dark font-weight-bold">Adobe</span>
                </div>
                <?php 
                    $adobe_types = explode(',', $requests->adobe_format);
                ?>
                <div class="tab-text-label text-dark pt-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="adobe_type[]" id="psd" value="psd" {{ (in_array('psd', $adobe_types)) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="psd">psd</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="adobe_type[]" id="ai" value="ai" {{ (in_array('ai', $adobe_types)) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="ai">ai</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="adobe_type[]" id="indd" value="indd" {{ (in_array('indd', $adobe_types)) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="indd">indd</label>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light-custom py-4">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <button type="submit" class="btn btn-primary btn-user">Update Request</button>
                </div>
            </div>
        </div>

    </form>

</div>


@endsection

@section('scripts')
<script>
    jQuery(function($) {
        $("html").on("dragover", function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

        var $parent    = $('.asset-uploader'),
            $input    = $('#asset-requests'),
            $label    = $('#asset-label'),
            showFiles = function(files) {
              $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
            };

        $parent.on('drop', function(e) {
          droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
          showFiles( droppedFiles );
          showPreview( droppedFiles );
        });

        $input.on('change', function(e) {
          showFiles(e.target.files);
          showPreview(e.target.files);
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

        $('#designType').on('change', function() {
            jQuery.ajax({
                type:'POST',
                url:"{{ route('request.dimensions') }}",
                data: {
                    design_type:$(this).val(),
                    _token: jQuery('meta[name="csrf-token"]').attr('content')
                },
                success:function(data) {
                    $('#dimensions').html(data.dimensions);
                }
            });
        });
    });
    
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

    function showPreview(files)
    {
        $.each( files, function( i, file ) {
            var form_data = new FormData();
            form_data.append("_token", $('.form-brand-request').find("input[name=_token]").val());
            form_data.append("request", file);
            form_data.append("tempfile_code", $('#tempfile_code').val());
            form_data.append("module", "media");

            $.ajax({
                url:'{{ route('tempfiles') }}',
                method:'POST',
                data:form_data,
                contentType:false,
                cache:false,
                processData:false,
                success: function(data) {
                    $('#pictures-preview').append('<div id="media-preview-'+ data.file.picture_id +'" class="mx-1 picture media-container"><a href="javascript:void(0)" class="preview-remove" onclick="removeTempFile('+ data.file.picture_id +');"><i class="fas fa-times"></i></a><img src="'+ data.file.path +'" class="picture-img" /></div>');
                }
            });
        });
    }

    function removeTempFile(id)
    {
        var form_data = new FormData();
        form_data.append("_token", jQuery('.form-brand-request').find("input[name=_token]").val());
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