@extends('layouts.frontnonav')

@section('title', 'Register')

@section('content')
<div class="container-fluid pb-5">
    <div class="row pt-3">
        <div class="col-md-6 text-start">
            <img src="{{ asset('images/logo-dark.svg') }}">
        </div>
        <div class="col-md-6 text-end">
            <a class="logout-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-lock-fill"></i> Log out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-6">
            <h1>Choose the plan that works for you</h1>
        </div>
    </div>

    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-3">
            <div id="plan-toggle" class="btn-group btn-group-toggle btn-light" data-toggle="buttons">
                <label id="monthly-plan" class="btn btn-light active">
                    <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off"> Monthly
                </label>
                <label id="yearly-plan" class="btn btn-light">
                    <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off" checked> Yearly
                </label>
            </div>
        </div>
    </div>

    @if (session('error'))
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-6">
            <span class="text-danger"> {{ session('error') }}</span>
        </div>
    </div>
    @endif

    <?php 
        $monthlybasic = 399;
        $yearlybasic = 359;
        $monthlypremium = 599;
        $yearlypremium = 539;
        $monthlyenterprise = 1199;
        $yearlyenterprise = 1079;
        $withdiscount = '';
        if(!empty($codediscout)) {
            $withdiscount = $codediscout->code;
            $codeamount = $codediscout->discount_amount;
            if($codediscout->discount_amount == 'percent') {
                $monthlybasic = 399;
                $monthlybasic = $monthlybasic - ( $monthlybasic * ($codeamount / 100) );
                $yearlybasic = 359;
                $yearlybasic = $yearlybasic - ( $yearlybasic * ($codeamount / 100) );
                $monthlypremium = 599;
                $monthlypremium = $monthlypremium - ( $monthlypremium * ($codeamount / 100) );
                $yearlypremium = 539;
                $yearlypremium = $yearlypremium - ( $yearlypremium * ($codeamount / 100) );
                $monthlyenterprise = 1199;
                $monthlyenterprise = $monthlyenterprise - ( $monthlyenterprise * ($codeamount / 100) );
                $yearlyenterprise = 1079;
                $yearlyenterprise = $yearlyenterprise - ( $yearlyenterprise * ($codeamount / 100) );
            } else {
                $monthlybasic = 399 - $codeamount;
                $yearlybasic = 359 - $codeamount;
                $monthlypremium = 599 - $codeamount;
                $yearlypremium = 539 - $codeamount;
                $monthlyenterprise = 1199 - $codeamount;
                $yearlyenterprise = 1079 - $codeamount;
            }
        }

    ?>

    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-8">
            <div class="row">
                <div class="plan-item-col plan-item-free col-md-3">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5">
                            <h6 class="card-title">Free</h6>
                            <p class="card-text">For you to test us out at zero cost and risk.</p>
                            <h2 class="monthly-amount">S$0</h2>
                            <h2 class="yearly-amount hide-amount">S$0</h2>
                            <p>SGD / MONTH</p>
                            <div class="yearly-per-duration hide-per-duration pb-2">Free</div>
                            <form method="POST" action="{{ route('user.addplan') }}">
                                @csrf
                                <input type="hidden" name="plan" value="free" />
                                <input type="hidden" name="duration" class="duration" value="monthly" />
                                <button type="submit" class="btn btn-outline-primary btn-lg btn-block">
                                    {{ __('Get Started') }}
                                </button>
                            </form>
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">1 Design Request</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">1 Brand Profile</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">3 Revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">48 Hours Turnaround</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="plan-item-col col-md-3">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5">
                            <h6 class="card-title">Basic</h6>
                            <p class="card-text">The perfect starter plan for all your basic design needs.</p>
                            <h2 class="monthly-amount">S${{ $monthlybasic }}</h2>
                            <h2 class="yearly-amount hide-amount">S${{ $yearlybasic }}</h2>
                            <p>SGD / MONTH</p>
                            <div class="yearly-per-duration hide-per-duration pb-2">Save 10%</div>
                            <form method="POST" action="{{ route('user.addplan') }}">
                                @csrf
                                <input type="hidden" name="withdiscount" value="{{ $withdiscount }}" />
                                <input type="hidden" name="plan" value="basic" />
                                <input type="hidden" name="duration" class="duration" value="monthly" />
                                <button type="submit" class="btn btn-outline-primary btn-lg btn-block">
                                    {{ __('Get Started') }}
                                </button>
                            </form>
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">1 Ongoing Request</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">1 Brand Profile</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Dedicated Designer</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited Design Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited Revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">48 Hours Turnaround</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="plan-item-col col-md-3">
                    <div class="shadow bg-white rounded card">
                        <div class="card-header">MOST POPULAR</div>
                        <div class="card-body pt-5 pb-5">
                            <h6 class="card-title">Premium</h6>
                            <p class="card-text">Get double the output and crush all your design needs.</p>
                            <h2 class="monthly-amount">S${{ $monthlypremium }}</h2>
                            <h2 class="yearly-amount hide-amount">S${{ $yearlypremium }}</h2>
                            <p>SGD / MONTH</p>
                            <div class="yearly-per-duration hide-per-duration pb-2">Save 10%</div>
                            <form method="POST" action="{{ route('user.addplan') }}">
                                @csrf
                                <input type="hidden" name="withdiscount" value="{{ $withdiscount }}" />
                                <input type="hidden" name="plan" value="premium" />
                                <input type="hidden" name="duration" class="duration" value="monthly" />
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ __('Get Started') }}
                                </button>
                            </form>
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">2 Ongoing Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">2 Brand Profiles</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Dedicated Designer</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited Design Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited Revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">24 Hours Turnaround</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="plan-item-col col-md-3">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5">
                            <h6 class="card-title">Enterprise</h6>
                            <p class="card-text">Level up your content with the ultimate creative plan.</p>
                            <h2 class="monthly-amount">S${{ $monthlyenterprise }}</h2>
                            <h2 class="yearly-amount hide-amount">S${{ $yearlyenterprise }}</h2>
                            <p>SGD / MONTH</p>
                            <div class="yearly-per-duration hide-per-duration pb-2">Save 10%</div>
                            <form method="POST" action="{{ route('user.addplan') }}">
                                @csrf
                                <input type="hidden" name="withdiscount" value="{{ $withdiscount }}" />
                                <input type="hidden" name="plan" value="royal" />
                                <input type="hidden" name="duration" class="duration" value="monthly" />
                                <button type="submit" class="btn btn-outline-primary btn-lg btn-block">
                                    {{ __('Get Started') }}
                                </button>
                            </form>
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">2 Ongoing Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited Brand Profiles</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Dedicated Designer</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited Design Requests</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited Revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">24 Hours Turnaround</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Source Files (AI, PSD, INDD)</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Dedicated Account Manager</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
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
            $('.plan-item-col').removeClass('col-md-3');
            $('.plan-item-col').addClass('col-md-4');
            $('.plan-item-free').hide();
            $(this).addClass('active');
            $('.duration').val('yearly');
        });
        $('#monthly-plan').on('click', function() {
            $('#yearly-plan').removeClass('active');
            $('.monthly-amount').removeClass('hide-amount');
            $('.yearly-amount').addClass('hide-amount');
            $('.monthly-per-duration').removeClass('hide-per-duration');
            $('.yearly-per-duration').addClass('hide-per-duration');
            $('.plan-item-col').removeClass('col-md-4');
            $('.plan-item-col').addClass('col-md-3');
            $('.plan-item-free').show();
            $(this).addClass('active');
            $('.duration').val('monthly');
        });
    });
</script>
    
@endsection
