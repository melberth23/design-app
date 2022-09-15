@extends('layouts.app')

@section('title', 'Subscribers List')

@section('content')
    <div class="container-fluid">

        {{-- Alert Messages --}}
        @include('common.alert')

        @if ($subscribers->count() > 0)

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 page-heading">Subscribers</h1>
            <div class="actions d-flex align-items-center justify-content-between">
                <!-- Filter -->
                <button class="btn btn-outline-light text-dark border" type="button" onclick="openNav('filter-actions');">
                    <i class="fa fa-sliders" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Filter</span>
                </button>
                <!-- Sort -->
                <div class="dropdown m-1">
                  <button class="btn btn-outline-light text-dark border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-sort" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Sort</span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('subscribers.index.sort', ['type' => 'date', 'sort' => 'asc']) }}">Date added (Ascending)</a>
                    <a class="dropdown-item" href="{{ route('subscribers.index.sort', ['type' => 'date', 'sort' => 'desc']) }}">Date added (Descending)</a>
                    <a class="dropdown-item" href="{{ route('subscribers.index.sort', ['type' => 'name', 'sort' => 'asc']) }}">Name (A-Z)</a>
                    <a class="dropdown-item" href="{{ route('subscribers.index.sort', ['type' => 'name', 'sort' => 'desc']) }}">Name (Z-A)</a>
                  </div>
                </div>
                <!-- Activities -->
                <button class="btn btn-outline-light text-dark border" type="button" onclick="openNav('activity-actions');">
                    <i class="fa fa-list-ul" aria-hidden="true"></i> <span class="d-none d-md-inline-block">Activities</span>
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body py-0 px-1">
                <ul class="nav nav-tabs" id="brand-tabs" role="tablist">
                    <li class="nav-item {{ (request()->is('admin/subscribers')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('admin/subscribers')) ? 'active' : '' }}" id="all-tab" href="{{ route('subscribers.index') }}">All {{ $users }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/subscribers/basic')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('admin/subscribers/basic')) ? 'active' : '' }}" id="basic-tab" href="{{ route('subscribers.basic') }}">Basic {{ $basic }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/subscribers/premium')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('admin/subscribers/premium')) ? 'active' : '' }}" id="premium-tab" href="{{ route('subscribers.premium') }}">Premium {{ $premium }}</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/subscribers/enterprise')) ? 'border-bottom' : '' }}">
                        <a class="nav-link py-3 {{ (request()->is('admin/subscribers/enterprise')) ? 'active' : '' }}" id="enterprise-tab" href="{{ route('subscribers.enterprise') }}">Enterprise {{ $royal }}</a>
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-light-custom p-0">
                <div class="table-responsive">
                    <table class="table table-bordered bg-white border-0 table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="8%" class="border-left-0 border-right-0">CUSTOMER ID</th>
                                <th width="20%" class="border-left-0 border-right-0">CUSTOMER NAME</th>
                                <th width="15%" class="border-left-0 border-right-0">CONTACT NUMBER</th>
                                <th width="15%" class="border-left-0 border-right-0">EMAIL ADDRESS</th>
                                <th width="22%" class="border-left-0 border-right-0">LOCATION</th>
                                <th width="10%" class="border-left-0 border-right-0">PLAN</th>
                                <th width="10%" class="border-left-0 border-right-0">PLAN STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscribers as $user)
                                <tr>
                                    <td class="border-left-0 border-right-0 text-primary">DS{{ $user->uid }}</td>
                                    <td class="border-left-0 border-right-0 font-weight-bold"><a class="text-dark" href="{{ route('subscribers.view', ['subscriber' => $user->uid]) }}">{{ $user->full_name }}</a></td>
                                    <td class="border-left-0 border-right-0">{{ $user->mobile_number }}</td>
                                    <td class="border-left-0 border-right-0">{{ $user->email }}</td>
                                    <td class="border-left-0 border-right-0">{{ $user->address_1 }} {{ $user->address_2 }}, {{ $user->city }} {{ $user->state }}, {{ $user->zip }} {{ $user->country }}</td>
                                    <td class="border-left-0 border-right-0">
                                        @if ($user->plan == 'basic')
                                            <span class="text-info font-weight-normal">Basic</span>
                                        @elseif ($user->plan == 'premium')
                                            <span class="text-primary font-weight-normal">Premium</span>
                                        @elseif ($user->plan == 'royal')
                                            <span class="text-warning font-weight-normal">Enterprise</span>
                                        @endif
                                    </td>
                                    <td class="border-left-0 border-right-0">
                                        @if ($user->ustatus == 'cancelled')
                                            <span class="badge badge-warning">Cancelled</span>
                                        @elseif ($user->ustatus == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif ($user->ustatus == 'expired')
                                            <span class="badge badge-danger">Expired</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $subscribers->links() }}
            </div>
        </div>

        @else
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="min-height-600 d-flex align-items-center justify-content-center">
                        <div class="no-record py-4 text-center">
                            <img src="{{ asset('images/requests-empty.svg') }}">
                            <div class="pt-4">
                                <h2>No subscribers.</h2>
                            </div>
                            <div class="pt-4">
                                <h5>Add new subscriber.</h5>
                            </div>
                            <div class="pt-4">
                                <a href="{{ route('users.create') }}" class="btn btn-primary">Add New Subscriber</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        @endif  
    </div>

@endsection