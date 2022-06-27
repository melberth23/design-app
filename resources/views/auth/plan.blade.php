@extends('layouts.frontnonav')

@section('title', 'Register')

@section('content')
<div class="container-fluid">
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
                <label id="yearly-plan" class="btn btn-light">
                    <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off" checked> Yearly
                </label>
                <label id="monthly-plan" class="btn btn-light active">
                    <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off"> Monthly
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
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5">
                            <h6 class="card-title">SILVER</h6>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur</p>
                            <h2 class="monthly-amount">$449</h2>
                            <h2 class="yearly-amount hide-amount">$1,759</h2>
                            <p>USD / <span class="monthly-per-duration">MONTH</span><span class="yearly-per-duration hide-per-duration">YEAR</span></p>
                            <form method="POST" action="{{ route('user.addplan') }}">
                                @csrf
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
                                    <span class="c-black">1 Project at a time</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">1 Brand profile</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited design requests & revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Social media ads, posters & banners</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Incl. stock library usage</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Cancel monthly</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="shadow bg-white rounded card">
                        <div class="card-header">MOST POPULAR</div>
                        <div class="card-body pt-5 pb-5">
                            <h6 class="card-title">BRONZE</h6>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur</p>
                            <h2 class="monthly-amount">$1,145</h2>
                            <h2 class="yearly-amount hide-amount">$2,545</h2>
                            <p>USD / <span class="monthly-per-duration">MONTH</span><span class="yearly-per-duration hide-per-duration">YEAR</span></p>
                            <form method="POST" action="{{ route('user.addplan') }}">
                                @csrf
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
                                    <span class="c-black">2 Projects at a time</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">2 Brand profiles</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited design requests & revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Social media ads, posters & banners</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Incl. stock library usage</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Animated GIFs</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Cancel monthly</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5">
                            <h6 class="card-title">GOLD</h6>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur</p>
                            <h2 class="monthly-amount">$2,395</h2>
                            <h2 class="yearly-amount hide-amount">$3,795</h2>
                            <p>USD / <span class="monthly-per-duration">MONTH</span><span class="yearly-per-duration hide-per-duration">YEAR</span></p>
                            <form method="POST" action="{{ route('user.addplan') }}">
                                @csrf
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
                                    <span class="c-black">2 Active projects + backlog</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited brand profiles</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Unlimited design requests & revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Social media ads, posters & banners</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Incl. stock library usage</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Animated GIFs</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="bi bi-check-circle-fill mr-4 pr-3"></i>
                                    <span class="c-black">Cancel monthly</span>
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
            $(this).addClass('active');
            $('.duration').val('yearly');
        });
        $('#monthly-plan').on('click', function() {
            $('#yearly-plan').removeClass('active');
            $('.monthly-amount').removeClass('hide-amount');
            $('.yearly-amount').addClass('hide-amount');
            $('.monthly-per-duration').removeClass('hide-per-duration');
            $('.yearly-per-duration').addClass('hide-per-duration');
            $(this).addClass('active');
            $('.duration').val('monthly');
        });
    });
</script>
    
@endsection
