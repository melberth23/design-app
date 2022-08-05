@extends('layouts.app')

@section('title', 'View Brand')

@section('content')

<div class="container">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{ $backurl }}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">{{ $brand->name }}</span></h1>
        @if(!auth()->user()->hasRole('Designer'))
        <div class="actions d-sm-flex align-items-center justify-content-between">
            <div class="dropdown m-1">
              <button class="btn btn-outline-light text-dark border dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                More Actions
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <!-- <a class="dropdown-item" href="#"><img src="{{ asset('images/downloiad.svg') }}" class="action-icons"> Download Brand Profile</a> -->
                <!-- <a class="dropdown-item" href="#"><img src="{{ asset('images/create.svg') }}" class="action-icons"> Create Brand Request</a> -->
                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'all', 'brand' => $brand->id]) }}"><img src="{{ asset('images/edit.svg') }}" class="action-icons"> Edit Brand Profile</a>
                @if ($brand->status == 0)
                    <a class="dropdown-item" href="{{ route('brand.status', ['brand_id' => $brand->id, 'status' => 1]) }}"><img src="{{ asset('images/brand-icon.svg') }}" class="action-icons"> Activate Brand Profile</a>
                @elseif ($brand->status == 1)
                    <a class="dropdown-item" href="{{ route('brand.status', ['brand_id' => $brand->id, 'status' => 2]) }}"><img src="{{ asset('images/trash.svg') }}" class="action-icons"> Archive Brand Profile</a>
                @elseif ($brand->status == 2)
                    <a class="dropdown-item" href="{{ route('brand.status', ['brand_id' => $brand->id, 'status' => 0]) }}"><img src="{{ asset('images/brand-icon.svg') }}" class="action-icons"> Restore Brand Profile</a>
                    <a class="dropdown-item delete-action" href="#" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash" aria-hidden="true"></i> Delete Brand Profile</a>
                @endif
              </div>
            </div>
            <div class="btn-group" role="group" aria-label="Actions">
                @if ($previous)
                    <a href="{{ route('brand.view', ['brand' => $previous]) }}" class="btn btn-outline-light text-dark border"><i class="fas fa-angle-left fa-sm"></i></a>
                @else
                    <a href="javascript:void(0);" class="btn btn-outline-light text-dark border disabled"><i class="fas fa-angle-left fa-sm"></i></a>
                @endif
                @if ($next)
                    <a href="{{ route('brand.view', ['brand' => $next]) }}" class="btn btn-outline-light text-dark border"><i class="fas fa-angle-right fa-sm"></i></a>
                @else
                    <a href="javascript:void(0);" class="btn btn-outline-light text-dark border disabled"><i class="fas fa-angle-right fa-sm"></i></a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="card mb-4 single-view">
        <div class="card-header bg-primary py-4">
            <div class="top-main-logo">
                @if ($logos->count() > 0)
                    <img src="{{ Storage::disk('s3')->url($logos[0]->filename) }}" class="main-logo" >
                @else
                    <h2>{{ substr($brand->name, 0, 1) }}</h2>
                @endif
            </div>
        </div>
        <div class="card-body pt-5">
            <p class="title-brand text-dark mb-1">{{ $brand->name }}</p>
            <div class="extra-information">
                <ul class="d-flex justify-content-start">
                    <li>{{ $brand->industry }}</li>
                    <li>
                        <?php 
                            $website = $brand->website;
                            if (strpos($website, "http") === false){
                                $website = 'http://'. $brand->website;
                            }
                        ?>
                        <a href="{{ $website }}" target="_blank">{{ $brand->website }}</a>
                    </li>
                    <li>
                        @if ($brand->status == 0)
                            <span class="badge badge-warning p-2">DRAFT</span>
                        @elseif ($brand->status == 1)
                            <span class="badge badge-success p-2">ACTIVE</span>
                        @else
                            <span class="badge badge-danger p-2">ARCHIVED</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-footer py-0 bg-light-custom">
            <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active py-3 text-dark" id="information-tab" data-toggle="tab" href="#information" role="tab" aria-controls="information" aria-selected="true">Brand Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 text-dark" id="assets-tab" data-toggle="tab" href="#assets" role="tab" aria-controls="assets" aria-selected="false">Assets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 text-dark" id="guidelines-tab" data-toggle="tab" href="#guidelines" role="tab" aria-controls="guidelines" aria-selected="false">Brand Guidelines</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 text-dark" id="templates-tab" data-toggle="tab" href="#templates" role="tab" aria-controls="templates" aria-selected="false">Templates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 text-dark" id="inspirations-tab" data-toggle="tab" href="#inspirations" role="tab" aria-controls="inspirations" aria-selected="false">Brand Inspiration</a>
                <li class="nav-item">
                    <a class="nav-link py-3 text-dark" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">Social Profile</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="brand-tab-contents">
        <div class="tab-pane fade show active" id="information" role="tabpanel" aria-labelledby="information-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Brand Information</h3>
                </div>
                <div class="card-body py-0">
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Brand name</div>
                            <div class="col-md-9">{{ $brand->name }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Brand description</div>
                            <div class="col-md-9">{{ $brand->description }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Industry</div>
                            <div class="col-md-9">{{ $brand->industry }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Target audience</div>
                            <div class="col-md-9">{{ $brand->target_audience }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Services Provider</div>
                            <div class="col-md-9">{{ $brand->services_provider }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3">
                        <div class="row">
                            <div class="col-md-3 single-label">Website</div>
                            <div class="col-md-9"><a href="{{ $website }}" target="_blank">{{ $brand->website }}</a></div>
                        </div>
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'information', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="assets-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Logo</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4 border-bottom">
                        <h5 class="card-label text-dark">Primary Logo</h5>
                        <div class="d-flex flex-wrap logos">
                            @if ($logos->count() > 0)
                                @foreach ($logos as $logo)
                                    <div id="media-{{ $logo->id }}">
                                        <div class="mx-1 logo media-container">
                                            <img src="{{ Storage::disk('s3')->url($logo->filename) }}" class="logo-img" />
                                            <div class="overlay">
                                                <div class="full-height d-flex align-items-center justify-content-center">
                                                    <a href="{{ route('download', ['asset' => $logo->id]) }}" class="action-icon">
                                                      <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="deleteAsset({{ $logo->id }});" class="action-icon">
                                                      <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block d-sm-none">
                                            <div class="full-height d-flex align-items-center justify-content-start">
                                                <a href="{{ route('download', ['asset' => $logo->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteAsset({{ $logo->id }});" class="action-icon">
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
                    </div>
                    <div class="py-4">
                        <h5 class="card-label text-dark">Secondary Logo</h5>
                        <div class="d-flex flex-wrap logos">
                            @if ($secondary_logos->count() > 0)
                                @foreach ($secondary_logos as $secondary_logo)
                                    <div id="media-{{ $secondary_logo->id }}">
                                        <div class="mx-1 logo media-container">
                                            <img src="{{ Storage::disk('s3')->url($secondary_logo->filename) }}" class="logo-img" />
                                            <div class="overlay">
                                                <div class="full-height d-flex align-items-center justify-content-center">
                                                    <a href="{{ route('download', ['asset' => $secondary_logo->id]) }}" class="action-icon">
                                                      <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="deleteAsset({{ $secondary_logo->id }});" class="action-icon">
                                                      <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block d-sm-none">
                                            <div class="full-height d-flex align-items-center justify-content-start">
                                                <a href="{{ route('download', ['asset' => $secondary_logo->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteAsset({{ $secondary_logo->id }});" class="action-icon">
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
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'logo', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Colors</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4 border-bottom">
                        <h5 class="card-label text-dark">Primary Colors</h5>
                        <div class="d-flex colors">
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
                    </div>
                    <div class="py-4">
                        <h5 class="card-label text-dark">Secondary Colors</h5>
                        <div class="d-flex colors">
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
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'colors', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Fonts</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4 border-bottom">
                        <h5 class="card-label text-dark">Primary Fonts</h5>
                        <div class="d-flex flex-wrap fonts">
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
                                                    <a href="javascript:void(0);" onclick="deleteAsset({{ $font->id }});" class="action-icon">
                                                      <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="mt-1">{{ basename($font->filename) }}</label>
                                        <div class="d-block d-sm-none">
                                            <div class="full-height d-flex align-items-center justify-content-start">
                                                <a href="{{ route('download', ['asset' => $font->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteAsset({{ $font->id }});" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p><em>-No primary fonts available</em></p>
                            @endif
                        </div>
                    </div>
                    <div class="py-4">
                        <h5 class="card-label text-dark">Secondary Fonts</h5>
                        <div class="d-flex flex-wrap fonts">
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
                                                    <a href="javascript:void(0);" onclick="deleteAsset({{ $secondary_font->id }});" class="action-icon">
                                                      <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="mt-1">{{ basename($font->filename) }}</label>
                                        <div class="d-block d-sm-none">
                                            <div class="full-height d-flex align-items-center justify-content-start">
                                                <a href="{{ route('download', ['asset' => $secondary_font->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteAsset({{ $secondary_font->id }});" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p><em>-No secondary fonts available</em></p>
                            @endif
                        </div>
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'fonts', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Images</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4">
                        <h5 class="card-label text-dark">Images</h5>
                        <div class="d-flex flex-wrap pictures">
                            @if ($images->count() > 0)
                                @foreach ($images as $image)
                                    <div id="media-{{ $image->id }}">
                                        <div class="mx-1 picture media-container">
                                            <img src="{{ Storage::disk('s3')->url($image->filename) }}" class="picture-img">
                                            <div class="overlay">
                                                <div class="full-height d-flex align-items-center justify-content-center">
                                                    <a href="{{ route('download', ['asset' => $image->id]) }}" class="action-icon">
                                                      <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="deleteAsset({{ $image->id }});" class="action-icon">
                                                      <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block d-sm-none">
                                            <div class="full-height d-flex align-items-center justify-content-start">
                                                <a href="{{ route('download', ['asset' => $image->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteAsset({{ $image->id }});" class="action-icon">
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
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'images', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="guidelines" role="tabpanel" aria-labelledby="guidelines-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Brand Guidelines</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap guidelines">
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
                                                <a href="javascript:void(0);" onclick="deleteAsset({{ $guideline->id }});" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="mt-1">{{ basename($guideline->filename) }}</label>
                                    <div class="d-block d-sm-none">
                                        <div class="full-height d-flex align-items-center justify-content-start">
                                            <a href="{{ route('download', ['asset' => $guideline->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                            <a href="javascript:void(0);" onclick="deleteAsset({{ $guideline->id }});" class="action-icon">
                                              <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No guidelines available</em></p>
                        @endif
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'guidelines', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="templates" role="tabpanel" aria-labelledby="templates-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Templates</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap templates">
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
                                                <a href="javascript:void(0);" onclick="deleteAsset({{ $template->id }});" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="mt-1">{{ basename($template->filename) }}</label>
                                    <div class="d-block d-sm-none">
                                        <div class="full-height d-flex align-items-center justify-content-start">
                                            <a href="{{ route('download', ['asset' => $template->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                            <a href="javascript:void(0);" onclick="deleteAsset({{ $template->id }});" class="action-icon">
                                              <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No templates available</em></p>
                        @endif
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'templates', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="inspirations" role="tabpanel" aria-labelledby="inspirations-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Brand Inspiration</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap inspirations">
                        @if ($inspirations->count() > 0)
                            @foreach ($inspirations as $inspiration)
                                <div id="media-{{ $inspiration->id }}">
                                    <div class="mx-1 inspiration media-container">
                                        <img src="{{ Storage::disk('s3')->url($inspiration->filename) }}" class="inspiration-img">
                                        <div class="overlay">
                                            <div class="full-height d-flex align-items-center justify-content-center">
                                                <a href="{{ route('download', ['asset' => $inspiration->id]) }}" class="action-icon">
                                                  <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteAsset({{ $inspiration->id }});" class="action-icon">
                                                  <img src="{{ asset('images/delete-media.svg') }}" class="delete-img rounded-circle p-2 m-1 bg-white text-dark">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-block d-sm-none">
                                        <div class="full-height d-flex align-items-center justify-content-start">
                                            <a href="{{ route('download', ['asset' => $inspiration->id]) }}" class="action-icon">
                                              <img src="{{ asset('images/download-media.svg') }}" class="download-img rounded-circle p-2 m-1 bg-white text-dark">
                                            </a>
                                            <a href="javascript:void(0);" onclick="deleteAsset({{ $inspiration->id }});" class="action-icon">
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
                    <div class="mt-3 pt-3 border-top">
                        <p>{{ $brand->other_inspirations }}</p>
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'inspirations', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Social Profile</h3>
                </div>
                <div class="card-body">
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Facebook</div>
                            <?php 
                                $facebook = $brand->facebook;
                                if (strpos($facebook, "http") === false){
                                    $facebook = 'https://'. $brand->facebook;
                                }
                            ?>
                            <div class="col-md-9"><a href="{{ $facebook }}" target="_blank">{{ $brand->facebook }}</a></div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Linkedin</div>
                            <?php 
                                $linkedin = $brand->linkedin;
                                if (strpos($linkedin, "http") === false){
                                    $linkedin = 'https://'. $brand->linkedin;
                                }
                            ?>
                            <div class="col-md-9"><a href="{{ $linkedin }}" target="_blank">{{ $brand->linkedin }}</a></div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Instagram</div>
                            <?php 
                                $instagram = $brand->instagram;
                                if (strpos($instagram, "http") === false){
                                    $instagram = 'https://'. $brand->instagram;
                                }
                            ?>
                            <div class="col-md-9"><a href="{{ $instagram }}" target="_blank">{{ $brand->instagram }}</a></div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Twitter</div>
                            <?php 
                                $twitter = $brand->twitter;
                                if (strpos($twitter, "http") === false){
                                    $twitter = 'https://'. $brand->twitter;
                                }
                            ?>
                            <div class="col-md-9"><a href="{{ $twitter }}" target="_blank">{{ $brand->twitter }}</a></div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Youtube</div>
                            <?php 
                                $youtube = $brand->youtube;
                                if (strpos($youtube, "http") === false){
                                    $youtube = 'https://'. $brand->youtube;
                                }
                            ?>
                            <div class="col-md-9"><a href="{{ $youtube }}" target="_blank">{{ $brand->youtube }}</a></div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3">
                        <div class="row">
                            <div class="col-md-3 single-label">Tiktok</div>
                            <?php 
                                $tiktok = $brand->tiktok;
                                if (strpos($tiktok, "http") === false){
                                    $tiktok = 'https://'. $brand->tiktok;
                                }
                            ?>
                            <div class="col-md-9"><a href="{{ $tiktok }}" target="_blank">{{ $brand->tiktok }}</a></div>
                        </div>
                    </div>
                </div>
                @if(!auth()->user()->hasRole('Designer'))
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'social', 'brand' => $brand->id]) }}">Edit</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .delete-action i {
        font-size: 18px;
        width: 20px;
        margin: 8px 0px 8px 5px;
    }
</style>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="deleteModalExample">Delete?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-dark">Select "Delete" will permanently delete this brand profile.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('brand-delete-form').submit();">
                    Delete
                </a>
                <form id="brand-delete-form" method="POST" action="{{ route('brand.destroy', ['brand' => $brand->id]) }}">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
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
</script>
@endsection