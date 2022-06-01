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
        </div>
    </div>
    <div class="row justify-content-center mt-5 text-center">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4 mt-2">
                    <div class="shadow bg-white rounded card no-header">
                        <div class="card-body pt-5 pb-5 text-dark">
                            <h6 class="card-title">SILVER</h6>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur</p>
                            <h2>$449</h2>
                            <p>USD / MONTH</p>
                            @if(auth()->user()->payments->plan == 'basic')
                                <div class="bg-opacity-primary rounded d-block py-2 px-4 text-dark">Monthly plan, paid monthly</div>
                            @else
                                <div class="bg-primary rounded-pill d-inline-block py-2 px-4 text-white">Monthly</div>
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
                                    <span class="c-black">1 Brand profile</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited design requests & revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Social media ads, posters & banners</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Incl. stock library usage</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Cancel monthly</span>
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
                            <h6 class="card-title">BRONZE</h6>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur</p>
                            <h2>$1,145</h2>
                            <p>USD / MONTH</p>
                            @if(auth()->user()->payments->plan == 'premium')
                                <div class="bg-opacity-primary rounded d-block py-2 px-4 text-dark">Monthly plan, paid monthly</div>
                            @else
                                <div class="bg-primary rounded-pill d-inline-block py-2 px-4 text-white">Monthly</div>
                            @endif
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">2 Projects at a time</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">2 Brand profiles</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited design requests & revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Social media ads, posters & banners</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Incl. stock library usage</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Animated GIFs</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Cancel monthly</span>
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
                            <h6 class="card-title">GOLD</h6>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur</p>
                            <h2>$2,395</h2>
                            <p>USD / MONTH</p>
                            @if(auth()->user()->payments->plan == 'royal')
                                <div class="bg-opacity-primary rounded d-block py-2 px-4 text-dark">Monthly plan, paid monthly</div>
                            @else
                                <div class="bg-primary rounded-pill d-inline-block py-2 px-4 text-white">Monthly</div>
                            @endif
                            <ul class="pricing-details">
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">2 Active projects + backlog</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited brand profiles</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Unlimited design requests & revisions</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Social media ads, posters & banners</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Incl. stock library usage</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Animated GIFs</span>
                                </li>
                                <li>
                                    <div class="md-v-line"></div>
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>
                                    <span class="c-black">Cancel monthly</span>
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
            <p>Have a question? Get in touch with our support team! <a href="#" class="text-danger text-decoration-none" data-toggle="modal" data-target="#cancelPlanModal">Cancel Subscription</a></p>
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
        
    });
</script>
    
@endsection