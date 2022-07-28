@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="container">

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- Page Heading -->
        <div class="mb-4">
            <h1 class="text-dark page-heading">Manage billing methods</h1>
        </div>

        {{-- Page Content --}}
        <div class="row">
            <div id="brand-tab-contents" class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-light-custom py-4">
                        <h3 class="text-dark mb-0">Payment details</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="tab-text-label text-dark py-3 border-bottom">
                            <div class="row">
                                <div class="col-md-3 single-label">Payment Method</div>
                                <div class="col-md-9">
                                    <span>Change your card</span>
                                    <span class="float-right text-primary">
                                        <a href="{{ route('profile.changecard') }}" target="_blank"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-text-label text-dark py-3">
                            <div class="row">
                                <div class="col-md-3 single-label">Billing Address</div>
                                <div class="col-md-9">
                                    <span>
                                        <span class="d-block">{{ ($data['billing_fname'] != '')?$data['billing_fname']:auth()->user()->first_name }} {{ ($data['billing_lname'] != '')?$data['billing_lname']:auth()->user()->last_name }}</span>
                                        @if(!empty($data['billing_address1']))
                                            <span>{{ $data['billing_address1'] }} {{ $data['billing_address2'] }}, {{ $data['billing_city'] }} {{ $data['billing_state'] }}, {{ $data['billing_zipcode'] }} {{ $data['billing_country'] }}</span>
                                        @else
                                            <span>{{ auth()->user()->address_1 }} {{ auth()->user()->address_2 }}, {{ auth()->user()->city }} {{ auth()->user()->state }}, {{ auth()->user()->zip }} {{ auth()->user()->country }}</span>
                                        @endif
                                    </span>
                                    <span class="float-right text-primary">
                                        <a href="#" data-toggle="modal" data-target="#billingModal"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Password -->
    <form method="POST" action="{{ route('profile.update') }}" id="form-update-password-account">
        @csrf
        <input type="hidden" name="action" value="billing" >
        <div class="modal fade" id="billingModal" tabindex="-1" role="dialog" aria-labelledby="billingModalExample"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold" id="billingModalExample">Update billing address</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="msg"></div>
                        <div class="form-group">
                            <div class="text-dark">
                                <label for="billing_fname">First name</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="billing_fname" 
                                    name="billing_fname" 
                                    value="{{ (old('billing_fname')) ? old('billing_fname') : ($data['billing_fname'] != ''?$data['billing_fname']:auth()->user()->first_name) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark">
                                <label for="billing_lname">Last name</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="billing_lname" 
                                    name="billing_lname" 
                                    value="{{ (old('billing_lname')) ? old('billing_lname') : ($data['billing_lname'] != ''?$data['billing_lname']:auth()->user()->last_name) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark">
                                <label for="billing_address1">Address line 1</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="billing_address1" 
                                    name="billing_address1" 
                                    value="{{ (old('billing_address1')) ? old('billing_address1') : ($data['billing_address1'] != ''?$data['billing_address1']:auth()->user()->address_1) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark">
                                <label for="billing_address2">Address line 2</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="billing_address2" 
                                    name="billing_address2" 
                                    value="{{ (old('billing_address2')) ? old('billing_address2') : ($data['billing_address2'] != ''?$data['billing_address2']:auth()->user()->address_2) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark">
                                <label for="billing_city">City</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="billing_city" 
                                    name="billing_city" 
                                    value="{{ (old('billing_city')) ? old('billing_city') : ($data['billing_city'] != ''?$data['billing_city']:auth()->user()->city) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark">
                                <label for="billing_state">State/Region/Province</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="billing_state" 
                                    name="billing_state" 
                                    value="{{ (old('billing_state')) ? old('billing_state') : ($data['billing_state'] != ''?$data['billing_state']:auth()->user()->state) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark">
                                <label for="billing_zipcode">Zip</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-user" 
                                    id="billing_zipcode" 
                                    name="billing_zipcode" 
                                    value="{{ (old('billing_zipcode')) ? old('billing_zipcode') : ($data['billing_zipcode'] != ''?$data['billing_zipcode']:auth()->user()->zip) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-dark">
                                <label for="billing_country">Country</label>
                                <select class="form-control" id="billing_country" name="billing_country">
                                    @php
                                        $billing_country = (old('billing_country')) ? old('billing_country') : ($data['billing_country'] != ''?$data['billing_country']:auth()->user()->country);
                                    @endphp
                                    @foreach($data['countries'] as $country)
                                        <option value="{{ $country->name }}" {{ $billing_country == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        $('#form-update-password-account').on('submit', function(e) {
            e.preventDefault();

            var dis = $(this);

            dis.find(".msg").html('');

            $.ajax({
               type:'POST',
               url:'{{ route('profile.update') }}',
               data: $(this).serialize(),
               success:function(data) {
                if(data.error == 1) {
                    let errorMessages = '';
                    $.each(data.msg, function (i, message) {
                        errorMessages += '<span>'+ message +'</span><br>';
                    });

                    dis.find(".msg").html('<p class="alert alert-danger">'+ errorMessages +'</p>');
                } else {
                    window.location.reload(true);
                }
               }
            });

            return false;
        });
    });
</script>
    
@endsection
