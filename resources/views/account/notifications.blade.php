@extends('layouts.app')

@section('title', 'Notification Settings')

@section('content')
    <div class="container">

        {{-- Alert Messages --}}
        @include('common.alert')

        {{-- Page Content --}}
        <div class="row">
            <div id="brand-tab-contents" class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-light-custom py-4">
                        <h3 class="text-dark mb-0">Notifications</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="tab-text-label text-dark py-3">
                            <div class="row">
                                <div class="col-md-12 font-weight-bold">Email notifications</div>
                            </div>
                            <div class="row">
                                <div class="col-md-9 single-label">Receive email updates on mentions and comments via email.</div>
                                <div class="col-md-3">
                                    <span class="float-right text-primary">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="verify_email" checked disabled>
                                            <label class="custom-control-label" for="verify_email"></label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        
    });
</script>
    
@endsection
