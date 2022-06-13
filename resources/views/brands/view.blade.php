@extends('layouts.app')

@section('title', 'View Brand')

@section('content')

<div class="container">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{route('brand.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">{{ $brand->name }}</span></h1>
        <div class="actions d-sm-flex align-items-center justify-content-between">
            <div class="dropdown m-1">
              <button class="btn btn-outline-light text-dark border dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                More Actions
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <!-- <a class="dropdown-item" href="#"><img src="{{ asset('images/downloiad.svg') }}" class="action-icons"> Download Brand Profile</a> -->
                <a class="dropdown-item" href="#"><img src="{{ asset('images/create.svg') }}" class="action-icons"> Create Brand Request</a>
                <a class="dropdown-item" href="{{ route('brand.edit', ['section' => 'all', 'brand' => $brand->id]) }}"><img src="{{ asset('images/edit.svg') }}" class="action-icons"> Edit Brand Profile</a>
                @if ($brand->status == 0)
                    <a class="dropdown-item" href="{{ route('brand.status', ['brand_id' => $brand->id, 'status' => 1]) }}"><img src="{{ asset('images/brand-icon.svg') }}" class="action-icons"> Activate Brand Profile</a>
                @elseif ($brand->status == 1)
                    <a class="dropdown-item" href="{{ route('brand.status', ['brand_id' => $brand->id, 'status' => 2]) }}"><img src="{{ asset('images/trash.svg') }}" class="action-icons"> Archive Brand Profile</a>
                @elseif ($brand->status == 2)
                    <a class="dropdown-item" href="{{ route('brand.status', ['brand_id' => $brand->id, 'status' => 0]) }}"><img src="{{ asset('images/brand-icon.svg') }}" class="action-icons"> Restore Brand Profile</a>
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
    </div>

    <div class="card mb-4 single-view">
        <div class="card-header bg-primary py-4">
            <div class="top-main-logo">
                @if ($logos->count() > 0)
                    <img src="{{ url('storage/logos') }}/{{ auth()->user()->id }}/{{ $logos[0]->filename }}" class="main-logo" >
                @else
                    <h2>{{ substr($brand->name, 0, 1) }}.</h2>
                @endif
            </div>
        </div>
        <div class="card-body pt-5">
            <p class="title-brand text-dark mb-1">{{ $brand->name }}</p>
            <div class="extra-information">
                <ul class="d-flex justify-content-start">
                    <li>{{ $brand->industry }}</li>
                    <li>{{ $brand->website }}</li>
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
                            <div class="col-md-9">{{ $brand->website }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'information', 'brand' => $brand->id]) }}">Edit</a>
                </div>
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
                        <div class="d-flex logos">
                            @if ($logos->count() > 0)
                                @foreach ($logos as $logo)
                                    <div id="media-{{ $logo->id }}" class="mx-1 logo media-container">
                                        <img src="{{ url('storage/logos') }}/{{ $brand->user_id }}/{{ $logo->filename }}" class="logo-img" />
                                        <div class="overlay">
                                            <a href="{{ route('download', ['asset' => $logo->id]) }}" class="icon">
                                              <i class="fas fa-download"></i>
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
                            @if ($secondary_logos->count() > 0)
                                @foreach ($secondary_logos as $secondary_logo)
                                    <div id="media-{{ $secondary_logo->id }}" class="mx-1 logo media-container">
                                        <img src="{{ url('storage/logos') }}/{{  $brand->user_id }}/{{ $secondary_logo->filename }}" class="logo-img" />
                                        <div class="overlay">
                                            <a href="{{ route('download', ['asset' => $secondary_logo->id]) }}" class="icon">
                                              <i class="fas fa-download"></i>
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
                    <a href="{{ route('brand.edit', ['section' => 'logo', 'brand' => $brand->id]) }}">Edit</a>
                </div>
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
                                    <div class="mx-1 color" style="background-color: {{ $color->filename }};border-radius: 50px; width: 40px; height: 40px;"></div>
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
                                    <div class="mx-1 color" style="background-color: {{ $secondary_color->filename }};border-radius: 50px; width: 40px; height: 40px;"></div>
                                @endforeach
                            @else
                                <p><em>-No secondary colors available</em></p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'colors', 'brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Fonts</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4 border-bottom">
                        <h5 class="card-label text-dark">Primary Fonts</h5>
                        <div class="d-flex fonts">
                            @if ($fonts->count() > 0)
                                @foreach ($fonts as $font)
                                    <div id="media-{{ $font->id }}">
                                        <div class="mx-1 font media-container media-documents">
                                            <img src="{{ asset('images/font-img-') }}{{ $font->file_type }}.png" class="font-img">
                                            <div class="overlay">
                                                <a href="{{ route('download', ['asset' => $font->id]) }}" class="icon">
                                                  <i class="fas fa-download"></i>
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
                            @if ($secondary_fonts->count() > 0)
                                @foreach ($secondary_fonts as $secondary_font)
                                    <div id="media-{{ $secondary_font->id }}">
                                        <div class="mx-1 font media-container media-documents">
                                            <img src="{{ asset('images/font-img-') }}{{ $secondary_font->file_type }}.png" class="font-img">
                                            <div class="overlay">
                                                <a href="{{ route('download', ['asset' => $secondary_font->id]) }}" class="icon">
                                                  <i class="fas fa-download"></i>
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
                    <a href="{{ route('brand.edit', ['section' => 'fonts', 'brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Images</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4">
                        <h5 class="card-label text-dark">Images</h5>
                        <div class="d-flex pictures">
                            @if ($images->count() > 0)
                                @foreach ($images as $image)
                                    <div id="media-{{ $image->id }}" class="mx-1 picture media-container">
                                        <img src="{{ url('storage/pictures') }}/{{ $brand->user_id }}/{{ $image->filename }}" class="picture-img">
                                        <div class="overlay">
                                            <a href="{{ route('download', ['asset' => $image->id]) }}" class="icon">
                                              <i class="fas fa-download"></i>
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
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'images', 'brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="guidelines" role="tabpanel" aria-labelledby="guidelines-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Brand Guidelines</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex guidelines">
                        @if ($guidelines->count() > 0)
                            @foreach ($guidelines as $guideline)
                                <div id="media-{{ $guideline->id }}">
                                    <div class="mx-1 guideline media-container media-documents">
                                        <img src="{{ asset('images/guidelines-img-pdf.png') }}" class="guideline-img">
                                        <div class="overlay">
                                            <a href="{{ route('download', ['asset' => $guideline->id]) }}" class="icon">
                                              <i class="fas fa-download"></i>
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
                    <a href="{{ route('brand.edit', ['section' => 'guidelines', 'brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="templates" role="tabpanel" aria-labelledby="templates-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Templates</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex templates">
                        @if ($templates->count() > 0)
                            @foreach ($templates as $template)
                                <div id="media-{{ $template->id }}">
                                    <div class="mx-1 template media-container media-documents">
                                        <img src="{{ asset('images/template-img-') }}{{ $template->file_type }}.png" class="template-img">
                                        <div class="overlay">
                                            <a href="{{ route('download', ['asset' => $template->id]) }}" class="icon">
                                              <i class="fas fa-download"></i>
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
                    <a href="{{ route('brand.edit', ['section' => 'templates', 'brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="inspirations" role="tabpanel" aria-labelledby="inspirations-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom py-4">
                    <h3 class="text-dark mb-0">Brand Inspiration</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex inspirations">
                        @if ($inspirations->count() > 0)
                            @foreach ($inspirations as $inspiration)
                                <div class="mx-1 inspiration media-container">
                                    <img src="{{ url('storage/inspirations') }}/{{ $brand->user_id }}/{{ $inspiration->filename }}" class="inspiration-img">
                                    <div class="overlay">
                                        <a href="{{ route('download', ['asset' => $inspiration->id]) }}" class="icon">
                                          <i class="fas fa-download"></i>
                                        </a>
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
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'inspirations', 'brand' => $brand->id]) }}">Edit</a>
                </div>
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
                            <div class="col-md-9">{{ $brand->facebook }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Linkedin</div>
                            <div class="col-md-9">{{ $brand->linkedin }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Instagram</div>
                            <div class="col-md-9">{{ $brand->instagram }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Twitter</div>
                            <div class="col-md-9">{{ $brand->twitter }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3 single-label">Youtube</div>
                            <div class="col-md-9">{{ $brand->youtube }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3">
                        <div class="row">
                            <div class="col-md-3 single-label">Tiktok</div>
                            <div class="col-md-9">{{ $brand->tiktok }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['section' => 'social', 'brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection