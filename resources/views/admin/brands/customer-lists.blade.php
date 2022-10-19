@extends('layouts.app')

@section('title', 'Brand List')

@section('content')
    <div class="container-fluid">

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($users->count() > 0)

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 page-heading">{{ $labelstatus }}</h1>
        </div>

        <div class="card">
            <div class="card-body py-0 px-1">
                <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                    <li class="nav-item {{ (request()->is('admin/brands/list/all')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('admin/brands/list/all')) ? 'active' : '' }}" id="allbrand-tab" href="{{ route('adminbrand.list', ['status' => 'all']) }}">All {{ $brands->count() }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/brands/list/1')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('admin/brands/list/1')) ? 'active' : '' }}" id="active-tab" href="{{ route('adminbrand.list', ['status' => 1]) }}">Active {{ $active->count() }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/brands/list/0')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('admin/brands/list/0')) ? 'active' : '' }}" id="draft-tab" href="{{ route('adminbrand.list', ['status' => 0]) }}">Draft {{ $draft->count() }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/brands/list/2')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('admin/brands/list/2')) ? 'active' : '' }}" id="archived-tab" href="{{ route('adminbrand.list', ['status' => 2]) }}">Archived {{ $archived->count() }}</a>
                    </li>
                </ul>
            </div>

            <div class="card-footer bg-light-custom p-0">
                <div class="table-responsive">
                    <table class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="30%" class="border-left-0 border-right-0">CUSTOMER NAME</th>
                                <th width="12%" class="border-left-0 border-right-0">ACTIVE</th>
                                <th width="12%" class="border-left-0 border-right-0">DRAFT</th>
                                <th width="12%" class="border-left-0 border-right-0">ARCHIVED</th>
                                <th width="10%" class="border-left-0 border-right-0">PLAN</th>
                                <th width="12%" class="border-left-0 border-right-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('adminbrand.'. $status, ['customer_id' => $user->uid]) }}">{{ $user->first_name }} {{ $user->last_name }}</a></td>
                                    <td class="border-left-0 border-right-0 font-weight-bold">{{ (new \App\Lib\SystemHelper)->getBrandsCount($user->uid, 1) }}</td>
                                    <td class="border-left-0 border-right-0 font-weight-bold">{{ (new \App\Lib\SystemHelper)->getBrandsCount($user->uid, 0) }}</td>
                                    <td class="border-left-0 border-right-0 font-weight-bold">{{ (new \App\Lib\SystemHelper)->getBrandsCount($user->uid, 2) }}</td>
                                    <td class="border-left-0 border-right-0 font-weight-bold">{{ ucfirst((new \App\Lib\SystemHelper)->getUserPlanName($user->uid)) }}</td>
                                    <td class="d-flex justify-content-end border-left-0 border-right-0">
                                        <a href="{{ route('adminbrand.'. $status, ['customer_id' => $user->uid]) }}"
                                            class="text-dark mx-2" data-toggle="tooltip" data-placement="top" title="View Brand">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $users->links() }}
            </div>
        </div>

        @else

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="min-height-600 d-flex align-items-center justify-content-center">
                        <div class="no-record py-4 text-center">
                            <img src="{{ asset('images/requests-empty.svg') }}">
                            <div class="pt-4">
                                <h2>No requests.</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif  
    </div>

@endsection

@section('scripts')

@endsection
