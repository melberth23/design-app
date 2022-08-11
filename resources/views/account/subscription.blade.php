@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')

    {{-- Page Content --}}
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-8">
            <h1 class="text-dark font-weight-bold">Choose the plan that works for you</h1>
            @if(auth()->user()->payments->plan_status == 1)
            <div class="alert alert-danger" role="alert">
                Your current plan {{ ucfirst((new \App\Lib\SystemHelper)->getPlanName(auth()->user()->payments->plan, auth()->user()->payments->duration)) }} will be available until {{ date('j F, Y', strtotime(auth()->user()->payments->recurring_date)) }}.
            </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-3">
            <div id="plan-toggle" class="btn-group btn-group-toggle btn-light" data-toggle="buttons">
                <label id="monthly-plan" class="btn btn-light active">
                    <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off" checked> Monthly
                </label>
                <label id="yearly-plan" class="btn btn-light">
                    <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off"> Yearly
                </label>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5 text-center pricing-table">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4 mt-2">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5 text-dark">
                            <h6 class="card-title">Basic</h6>
                            <p class="card-text">The perfect starter plan for all your basic design needs.</p>
                            <h2 class="monthly-amount">S$399</h2>
                            <h2 class="yearly-amount hide-amount">S$359</h2>
                            <p>SGD / MONTH</p>
                            <div class="yearly-per-duration hide-per-duration pb-2">Save 10%</div>
                            <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white d-inline-block">Monthly</div>
                            <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white hide-label">Yearly</div>
                            <form method="POST" action="{{ route('profile.addplan') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="plan" value="basic" />
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    {{ __('Get Started') }}
                                </button>
                            </form>
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">1 Ongoing Request</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">1 Brand Profile</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Dedicated Designer</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited Design Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited Revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">48 Hours Turnaround</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="shadow bg-white rounded card">
                        <div class="card-header">MOST POPULAR</div>
                        <div class="card-body pt-5 pb-5 text-dark">
                            <h6 class="card-title">Premium</h6>
                            <p class="card-text">Get double the output and crush all your design needs.</p>
                            <h2 class="monthly-amount">S$599</h2>
                            <h2 class="yearly-amount hide-amount">S$539</h2>
                            <p>SGD / MONTH</p>
                            <div class="yearly-per-duration hide-per-duration pb-2">Save 10%</div>
                            <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white d-inline-block">Monthly</div>
                            <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white hide-label">Yearly</div>
                            <form method="POST" action="{{ route('profile.addplan') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="plan" value="premium" />
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Get Started') }}
                                </button>
                            </form>
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">2 Ongoing Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">2 Brand Profiles</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Dedicated Designer</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited Design Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited Revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">24 Hours Turnaround</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5 text-dark">
                            <h6 class="card-title">Enterprise</h6>
                            <p class="card-text">Level up your content with the ultimate creative plan.</p>
                            <h2 class="monthly-amount">S$1199</h2>
                            <h2 class="yearly-amount hide-amount">S$1079</h2>
                            <p>SGD / MONTH</p>
                            <div class="yearly-per-duration hide-per-duration pb-2">Save 10%</div>
                            <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white d-inline-block">Monthly</div>
                            <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white hide-label">Yearly</div>
                            <form method="POST" action="{{ route('profile.addplan') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="plan" value="royal" />
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    {{ __('Get Started') }}
                                </button>
                            </form>
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">2 Ongoing Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited Brand Profiles</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Dedicated Designer</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited Design Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited Revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">24 Hours Turnaround</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Dedicated Account Manager</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-8">
            <p>Have a question? Get in touch with our support team!</p>
        </div>
    </div>
</div>

<style type="text/css">
    .logout-link {
        text-decoration: none;
        color: #000;
    }
    .card {
        border: 0;
    }
    .card .card-header {
        height: 40px;
        background-color: #9672FB;
        color: #fff;
    }
    .card.no-header {
        margin-top: 40px;
    }
</style>
@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(function($) {
        $('#yearly-plan').on('click', function() {
            $('#monthly-plan').removeClass('active');
            $('.yearly-amount').removeClass('hide-amount');
            $('.monthly-amount').addClass('hide-amount');
            $('.yearly-per-duration').removeClass('hide-per-duration');
            $('.monthly-per-duration').addClass('hide-per-duration');
            $('.yearly-label').removeClass('hide-label');
            $('.yearly-label').addClass('d-inline-block');
            $('.monthly-label').removeClass('d-inline-block');
            $('.monthly-label').addClass('hide-label');
            $('.yearly-block-label').removeClass('hide-label');
            $('.yearly-block-label').addClass('d-block');
            $('.monthly-block-label').removeClass('d-block');
            $('.monthly-block-label').addClass('hide-label');
            $(this).addClass('active');
            $('.duration').val('yearly');
        });
        $('#monthly-plan').on('click', function() {
            $('#yearly-plan').removeClass('active');
            $('.monthly-amount').removeClass('hide-amount');
            $('.yearly-amount').addClass('hide-amount');
            $('.monthly-per-duration').removeClass('hide-per-duration');
            $('.yearly-per-duration').addClass('hide-per-duration');
            $('.monthly-label').removeClass('hide-label');
            $('.monthly-label').addClass('d-inline-block');
            $('.yearly-label').removeClass('d-inline-block');
            $('.yearly-label').addClass('hide-label');
            $('.monthly-block-label').removeClass('hide-label');
            $('.monthly-block-label').addClass('d-block');
            $('.yearly-block-label').removeClass('d-block');
            $('.yearly-block-label').addClass('hide-label');
            $(this).addClass('active');
            $('.duration').val('monthly');
        });
    });
</script>
    
@endsection
