@extends('layouts.app')

@section('title', 'View Brand')

@section('content')

<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="brand-title h3 mb-0 text-gray-800 d-flex align-items-center justify-content-between"><a href="{{ route('subscribers.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-outline-light text-dark border"><i class="fas fa-arrow-left fa-sm"></i></a> <span class="mx-2">{{ $subscriber->first_name }} {{ $subscriber->last_name }}</span> 

            @if ($subscriber->payments->status == 'cancelled')
                <span class="badge badge-warning">Cancelled</span>
            @elseif ($subscriber->payments->status == 'active')
                <span class="badge badge-success">Active</span>
            @elseif ($subscriber->payments->status == 'expired')
                <span class="badge badge-danger">Expired</span>
            @endif
        </h1>
        <div class="actions d-flex align-items-center justify-content-end">
            <button class="btn btn-outline-light text-dark border m-1" type="button" >
                <i class="fa fa-file-text" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Notes</span>
            </button>
            <button class="btn btn-outline-light text-dark border m-1" type="button" >
                <i class="fa fa-list-ul" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Activities</span>
            </button>
            <button class="btn btn-primary m-1" type="button" >
                <i class="fa fa-plus" aria-hidden="true"></i> <span class="d-none d-md-inline-block">New</span>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        @if(!empty($subscriber->profile_img))
                            <img class="rounded-circle" width="80px" src="{{ Storage::disk('s3')->url($subscriber->profile_img) }}">
                        @else
                            <img class="rounded-circle" width="80px" src="{{ asset('admin/img/undraw_profile.svg') }}">
                        @endif
                    </div>
                </div>
            </div>
            <div class="card my-4">
                <div class="card-header bg-light-custom">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-dark mb-0 font-weight-bold">Requests</span>
                        <select>
                            <option value="">All time</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header bg-light-custom">
                    <h3 class="text-dark mb-0">Brand Information</h3>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script type="text/javascript">
    
</script>
@endsection