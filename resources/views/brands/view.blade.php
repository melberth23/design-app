@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="container-fluid">

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
                <a class="dropdown-item" href="#"><img src="{{ asset('images/downloiad.svg') }}" class="action-icons"> Download Brand Profile</a>
                <a class="dropdown-item" href="#"><img src="{{ asset('images/create.svg') }}" class="action-icons"> Create Brand Request</a>
                <a class="dropdown-item" href="#"><img src="{{ asset('images/edit.svg') }}" class="action-icons"> Edit Brand Profile</a>
                <a class="dropdown-item" href="#"><img src="{{ asset('images/trash.svg') }}" class="action-icons"> Archive Brand Profile</a>
              </div>
            </div>
            <div class="btn-group" role="group" aria-label="Actions">
              <button type="button" class="btn btn-outline-light text-dark border"><i class="fas fa-angle-left fa-sm"></i></button>
              <button type="button" class="btn btn-outline-light text-dark border"><i class="fas fa-angle-right fa-sm"></i></button>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary py-5"></div>
        <div class="card-body">
            <p class="text-dark">{{ $brand->name }}</p>
        </div>
        <div class="card-footer py-0 bg-light-custom">
            <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active text-dark" id="information-tab" data-toggle="tab" href="#information" role="tab" aria-controls="information" aria-selected="true">Brand Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="assets-tab" data-toggle="tab" href="#assets" role="tab" aria-controls="assets" aria-selected="false">Assets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="guidelines-tab" data-toggle="tab" href="#guidelines" role="tab" aria-controls="guidelines" aria-selected="false">Brand Guidelines</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="templates-tab" data-toggle="tab" href="#templates" role="tab" aria-controls="templates" aria-selected="false">Templates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="inspirations-tab" data-toggle="tab" href="#inspirations" role="tab" aria-controls="inspirations" aria-selected="false">Brand Inspiration</a>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">Social Profile</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="brand-tab-contents">
        <div class="tab-pane fade show active" id="information" role="tabpanel" aria-labelledby="information-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom">
                    <h3 class="text-dark">Brand Information</h3>
                </div>
                <div class="card-body py-0">
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Brand name</div>
                            <div class="col-md-9">{{ $brand->name }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Brand description</div>
                            <div class="col-md-9">{{ $brand->description }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Industry</div>
                            <div class="col-md-9">{{ $brand->industry }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Target audience</div>
                            <div class="col-md-9">{{ $brand->target_audience }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Services Provider</div>
                            <div class="col-md-9">{{ $brand->services_provider }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3">
                        <div class="row">
                            <div class="col-md-3">Website</div>
                            <div class="col-md-9">{{ $brand->website }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="assets-tab">
            <div class="card mb-4">
                <div class="card-header bg-light-custom">
                    <h3 class="text-dark">Logo</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4 border-bottom">
                        <h5 class="card-label text-dark">Primary Logo</h5>
                        <div class="d-flex logos">
                            @if ($logos->count() > 0)
                                @foreach ($logos as $logo)
                                    <div class="mx-1 logo"><img src="{{ url('storage/logos') }}/{{ auth()->user()->id }}/{{ $logo->filename }}" class="logo-img" /></div>
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
                                    <div class="mx-1 logo"><img src="{{ url('storage/logos') }}/{{ auth()->user()->id }}/{{ $secondary_logo->filename }}" class="logo-img" /></div>
                                @endforeach
                            @else
                                <p><em>-No secondary logo available</em></p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light-custom">
                    <h3 class="text-dark">Colors</h3>
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
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light-custom">
                    <h3 class="text-dark">Fonts</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4 border-bottom">
                        <h5 class="card-label text-dark">Primary Fonts</h5>
                        <div class="d-flex fonts">
                            @if ($fonts->count() > 0)
                                @foreach ($fonts as $font)
                                    <div class="mx-1 font">
                                        <img src="{{ asset('images/font-img-') }}{{ $font->file_type }}.png" class="font-img">
                                        <label>{{ $font->filename }}</label>
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
                                    <div class="mx-1 font">
                                        <img src="{{ asset('images/font-img-') }}{{ $font->file_type }}.png" class="font-img">
                                        <label>{{ $font->filename }}</label>
                                    </div>
                                @endforeach
                            @else
                                <p><em>-No secondary fonts available</em></p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light-custom">
                    <h3 class="text-dark">Images</h3>
                </div>
                <div class="card-body py-0">
                    <div class="py-4">
                        <h5 class="card-label text-dark">Images</h5>
                        <div class="d-flex pictures">
                            @if ($images->count() > 0)
                                @foreach ($images as $image)
                                    <div class="mx-1 picture">
                                        <img src="{{ url('storage/pictures') }}/{{ auth()->user()->id }}/{{ $image->filename }}" class="picture-img">
                                    </div>
                                @endforeach
                            @else
                                <p><em>-No images available</em></p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="guidelines" role="tabpanel" aria-labelledby="guidelines-tab">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="text-dark">Brand Guidelines</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex guidelines">
                        @if ($guidelines->count() > 0)
                            @foreach ($guidelines as $guideline)
                                <div class="mx-1 guideline">
                                    <img src="{{ url('storage/guidelines') }}/{{ auth()->user()->id }}/{{ $guideline->filename }}" class="guideline-img">
                                </div>
                            @endforeach
                        @else
                            <p><em>-No guidelines available</em></p>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="templates" role="tabpanel" aria-labelledby="templates-tab">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="text-dark">Templates</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex templates">
                        @if ($templates->count() > 0)
                            @foreach ($templates as $template)
                                <div class="mx-1 template">
                                    <img src="{{ asset('images/font-img-') }}{{ $template->file_type }}.png" class="template-img">
                                    <label>{{ $template->filename }}</label>
                                </div>
                            @endforeach
                        @else
                            <p><em>-No templates available</em></p>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="inspirations" role="tabpanel" aria-labelledby="inspirations-tab">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="text-dark">Brand Inspiration</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex inspirations">
                        @if ($inspirations->count() > 0)
                            @foreach ($inspirations as $inspiration)
                                <div class="mx-1 inspiration">
                                    <img src="{{ url('storage/inspirations') }}/{{ auth()->user()->id }}/{{ $inspiration->filename }}" class="inspiration-img">
                                </div>
                            @endforeach
                        @else
                            <p><em>-No inspirations available</em></p>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="text-dark">Social Profile</h3>
                </div>
                <div class="card-body">
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Facebook</div>
                            <div class="col-md-9">{{ $brand->facebook }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Linkedin</div>
                            <div class="col-md-9">{{ $brand->linkedin }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Instagram</div>
                            <div class="col-md-9">{{ $brand->instagram }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Twitter</div>
                            <div class="col-md-9">{{ $brand->twitter }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3 border-bottom">
                        <div class="row">
                            <div class="col-md-3">Youtube</div>
                            <div class="col-md-9">{{ $brand->youtube }}</div>
                        </div>
                    </div>
                    <div class="tab-text-label text-dark py-3">
                        <div class="row">
                            <div class="col-md-3">Tiktok</div>
                            <div class="col-md-9">{{ $brand->tiktok }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-custom">
                    <a href="{{ route('brand.edit', ['brand' => $brand->id]) }}">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection