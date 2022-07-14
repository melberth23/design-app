@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')

    {{-- Page Content --}}
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-8">
            <h1 class="text-dark font-weight-bold">Upgrade to higher plan to unleash more features</h1>
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
                <label id="yearly-plan" class="btn btn-light">
                    <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off" {{ ($durationlabel == 'yearly')?'checked':'' }}> Yearly
                </label>
                <label id="monthly-plan" class="btn btn-light active">
                    <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off" {{ ($durationlabel == 'monthly')?'checked':'' }}> Monthly
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
                            <p class="card-text">Ideal for entrepreneurs and small companies who only require a few designs. Our top-notch designers will help them fulfill their design needs while staying on-brand.</p>
                            <h2 class="monthly-amount {{ ($durationlabel == 'yearly')?'hide-amount':'' }}">$399</h2>
                            <h2 class="yearly-amount {{ ($durationlabel == 'monthly')?'hide-amount':'' }}">$4310</h2>
                            <p>USD / <span class="monthly-per-duration {{ ($durationlabel == 'yearly')?'hide-per-duration':'' }}">MONTH</span><span class="yearly-per-duration {{ ($durationlabel == 'monthly')?'hide-per-duration':'' }}">YEAR</span></p>
                            @if(auth()->user()->payments->plan == 'basic')
                                <div class="monthly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'yearly')?'hide-label':'d-block' }}">Monthly plan, paid monthly</div>
                                <div class="yearly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'monthly')?'hide-label':'d-block' }}">Yearly plan, paid yearly</div>
                            @else
                                <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'yearly')?'hide-label':'d-inline-block' }}">Monthly</div>
                                <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'monthly')?'hide-label':'d-inline-block' }}">Yearly</div>
                            @endif
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">1 Project at a time</span>
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
                            @if(auth()->user()->payments->plan == 'basic')
                                <button type="button" class="btn btn-outline-success btn-block text-success mt-3" disabled>
                                    {{ __('Active') }}
                                </button>
                            @elseif((new \App\Lib\SystemHelper)->planPositionChecker('basic', auth()->user()->payments->plan))
                            <form method="POST" action="{{ route('profile.upgradeplan') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="plan" value="basic" />
                                <input type="hidden" name="duration" class="duration" value="{{ $durationlabel }}" />
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Upgrade') }}
                                </button>
                            </form>
                            @else
                                <button type="button" class="btn btn-outline-secondary btn-block mt-3" disabled>
                                    {{ __('Included') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="shadow bg-white rounded card">
                        <div class="card-header">MOST POPULAR</div>
                        <div class="card-body pt-5 pb-5 text-dark">
                            <h6 class="card-title">Premium</h6>
                            <p class="card-text">Ideal for marketing teams looking to increase their creative output while also refreshing their brand.</p>
                            <h2 class="monthly-amount {{ ($durationlabel == 'yearly')?'hide-amount':'' }}">$599</h2>
                            <h2 class="yearly-amount {{ ($durationlabel == 'monthly')?'hide-amount':'' }}">$6470</h2>
                            <p>USD / <span class="monthly-per-duration {{ ($durationlabel == 'yearly')?'hide-per-duration':'' }}">MONTH</span><span class="yearly-per-duration {{ ($durationlabel == 'monthly')?'hide-per-duration':'' }}">YEAR</span></p>
                            @if(auth()->user()->payments->plan == 'premium')
                                <div class="monthly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'yearly')?'hide-label':'d-block' }}">Monthly plan, paid monthly</div>
                                <div class="yearly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'monthly')?'hide-label':'d-block' }}">Yearly plan, paid yearly</div>
                            @else
                                <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'yearly')?'hide-label':'d-inline-block' }}">Monthly</div>
                                <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'monthly')?'hide-label':'d-inline-block' }}">Yearly</div>
                            @endif
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
                            @if(auth()->user()->payments->plan == 'premium')
                                <button type="button" class="btn btn-outline-success btn-block text-success mt-3" disabled>
                                    {{ __('Active') }}
                                </button>
                            @elseif((new \App\Lib\SystemHelper)->planPositionChecker('premium', auth()->user()->payments->plan))
                            <form method="POST" action="{{ route('profile.upgradeplan') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="plan" value="premium" />
                                <input type="hidden" name="duration" class="duration" value="{{ $durationlabel }}" />
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Upgrade') }}
                                </button>
                            </form>
                            @else
                                <button type="button" class="btn btn-outline-secondary btn-block mt-3" disabled>
                                    {{ __('Included') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5 text-dark">
                            <h6 class="card-title">Enterprise</h6>
                            <p class="card-text">Ideal for businesses who want full-service creative and want to increase their productivity by a factor of two to help them grow their business.</p>
                            <h2 class="monthly-amount {{ ($durationlabel == 'yearly')?'hide-amount':'' }}">$2395</h2>
                            <h2 class="yearly-amount {{ ($durationlabel == 'monthly')?'hide-amount':'' }}">$8795</h2>
                            <p>USD / <span class="monthly-per-duration {{ ($durationlabel == 'yearly')?'hide-per-duration':'' }}">MONTH</span><span class="yearly-per-duration {{ ($durationlabel == 'monthly')?'hide-per-duration':'' }}">YEAR</span></p>
                            @if(auth()->user()->payments->plan == 'royal')
                                <div class="monthly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'yearly')?'hide-label':'d-block' }}">Monthly plan, paid monthly</div>
                                <div class="yearly-block-label bg-opacity-primary rounded py-2 px-4 text-dark {{ ($durationlabel == 'monthly')?'hide-label':'d-block' }}">Yearly plan, paid yearly</div>
                            @else
                                <div class="monthly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'yearly')?'hide-label':'d-inline-block' }}">Monthly</div>
                                <div class="yearly-label bg-primary rounded-pill py-2 px-4 text-white {{ ($durationlabel == 'monthly')?'hide-label':'d-inline-block' }}">Yearly</div>
                            @endif
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
                            @if(auth()->user()->payments->plan == 'royal')
                                <button type="button" class="btn btn-outline-success btn-block text-success mt-3" disabled>
                                    {{ __('Active') }}
                                </button>
                            @elseif((new \App\Lib\SystemHelper)->planPositionChecker('royal', auth()->user()->payments->plan))
                            <form method="POST" action="{{ route('profile.upgradeplan') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="plan" value="royal" />
                                <input type="hidden" name="duration" class="duration" value="{{ $durationlabel }}" />
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Upgrade') }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-8">
            <p>
            Have a question? Get in touch with our support team!
            @if(auth()->user()->payments->plan_status == 0)
            <a href="#" class="text-danger text-decoration-none" data-toggle="modal" data-target="#cancelPlanModal">Cancel Subscription</a>
            @else
            <a href="{{ route('profile.subscription') }}" class="text-danger text-decoration-none">Continue Subscription</a>
            @endif
            </p>
        </div>
    </div>
</div>

<!-- Cancel Plan -->
<form method="POST" action="{{ route('profile.cancel') }}" id="form-cancel-plan-account">
    @csrf

    <div class="modal fade" id="cancelPlanModal" tabindex="-1" role="dialog" aria-labelledby="cancelPlanModalExample"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold" id="cancelPlanModalExample">Cancel subscription</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-dark">Are you sure you want to cancel your <strong>{{ (new \App\Lib\SystemHelper)->getPlanInformation(auth()->user()->payments->plan)['label'] }}</strong> subscription which cost ${{ number_format((new \App\Lib\SystemHelper)->getPlanInformation(auth()->user()->payments->plan)['amount'], 2) }} a month?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" >Yes, Proceed</button>
                </div>
            </div>
        </div>
    </div>
</form>

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
