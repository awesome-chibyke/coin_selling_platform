
@php $titleDescription = 'Add Bank Accounts' @endphp
@extends('layouts.logged_main')

@section('content')
<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-lg">
        <div class="nk-content-body">
            <div class="buysell wide-xs m-auto">
                <div class="buysell-nav text-center">
                    <ul class="nk-nav nav nav-tabs nav-tabs-s2">
                        <li class="nav-item">
                            <a class="nav-link" href="html/crypto/buy-sell.html">Deposit Coin</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#">Sell Coin</a>
                        </li> --}}
                    </ul>
                </div><!-- .buysell-nav -->
                <div class="buysell-title text-center">
                    <h2 class="title">What do you want to buy!</h2>
                </div><!-- .buysell-title -->
                <style>
                    .image_for_coins_carrier{
                        width:25%;
                        float:left;
                        cursor: pointer;
                    }
                    .image_for_coins_carrier:hover{
                        background-color:#ddd;
                    }
                    .image_for_coins{
                        width:40%;
                        margin-left:30%;
                        margin-right:30%;
                    }
                    .align_text_center{
                        text-align: center;
                    }
                </style>
                <div style="width:100%; float:left;">
                    @foreach ($coins_for_payment as $k => $eachCoinDetail)
                        <div class="image_for_coins_carrier">
                            @php $imageUrl = $coinMarketInstance->correctAString($eachCoinDetail->logo_url, 'image/', 'images/') @endphp
                            <img src="{{ $image_base_url.$imageUrl }}" data-coin-code="{{ $eachCoinDetail->name }}" class="image_for_coins" />
                            <p class="align_text_center text-sm"><small><em>{{ strtoupper($eachCoinDetail->name) }}</em></small></p>
                        </div>
                    @endforeach

                    {{-- <div class="image_for_coins_carrier">
                        <img src="https://nowpayments.io/images/coins/btc.svg" class="image_for_coins" />
                        <p class="align_text_center"><small><em>BTC</em></small></p>
                    </div>
                    <div class="image_for_coins_carrier">
                        <img src="https://nowpayments.io/images/coins/btc.svg" class="image_for_coins" />
                        <p class="align_text_center"><small><em>BTC</em></small></p>
                    </div>
                    <div class="image_for_coins_carrier">
                        <img src="https://nowpayments.io/images/coins/btc.svg" class="image_for_coins" />
                        <p class="align_text_center"><small><em>BTC</em></small></p>
                    </div>
                    <div class="image_for_coins_carrier">
                        <img src="https://nowpayments.io/images/coins/btc.svg" class="image_for_coins" />
                        <p class="align_text_center"><small><em>BTC</em></small></p>
                    </div>
                    <div class="image_for_coins_carrier">
                        <img src="https://nowpayments.io/images/coins/btc.svg" class="image_for_coins" />
                        <p class="align_text_center"><small><em>BTC</em></small></p>
                    </div>
                    <div class="image_for_coins_carrier">
                        <img src="https://nowpayments.io/images/coins/btc.svg" class="image_for_coins" />
                        <p class="align_text_center"><small><em>BTC</em></small></p>
                    </div>
                    <div class="image_for_coins_carrier">
                        <img src="https://nowpayments.io/images/coins/btc.svg" class="image_for_coins" />
                        <p class="align_text_center"><small><em>BTC</em></small></p>
                    </div> --}}
                </div>

                {{-- <div class="buysell-block" style="width:100%;">
                    <form action="#" class="buysell-form">
                        <div class="buysell-field form-group">
                            <div class="form-label-group">
                                <label class="form-label">Choose what you want to get</label>
                            </div>
                            <input type="hidden" value="btc" name="bs-currency" id="buysell-choose-currency">
                            <div class="dropdown buysell-cc-dropdown">
                                <a href="#" class="buysell-cc-choosen dropdown-indicator" data-toggle="dropdown">
                                    <div class="coin-item coin-btc">
                                        <div class="coin-icon">
                                            <em class="icon ni ni-sign-btc-alt"></em>
                                        </div>
                                        <div class="coin-info">
                                            <span class="coin-name">Bitcoin (BTC)</span>
                                            <span class="coin-text">Last Order: Nov 23, 2019</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-auto dropdown-menu-mxh">
                                    <ul class="buysell-cc-list">
                                        <li class="buysell-cc-item selected">
                                            <a href="#" class="buysell-cc-opt" data-currency="btc">
                                                <div class="coin-item coin-btc">
                                                    <div class="coin-icon">

                                                        <img src="https://nowpayments.io/images/coins/btc.svg" style="width:100%" />
                                                    </div>
                                                    <div class="coin-info">
                                                        <span class="coin-name">Bitcoin (BTC)</span>
                                                        <span class="coin-text">Last Order: Nov 23, 2019</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="buysell-cc-item">
                                            <a href="#" class="buysell-cc-opt" data-currency="eth">
                                                <div class="coin-item coin-eth">
                                                    <div class="coin-icon">

                                                        <img src="https://nowpayments.io/images/coins/btc.svg" style="width:100%" />
                                                    </div>
                                                    <div class="coin-info">
                                                        <span class="coin-name">Ethereum (ETH)</span>
                                                        <span class="coin-text">Not order yet!</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>

                                    </ul>
                                </div><!-- .dropdown-menu -->
                            </div><!-- .dropdown -->
                        </div><!-- .buysell-field -->
                        <div class="buysell-field form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="buysell-amount">Amount to Buy</label>
                            </div>
                            <div class="form-control-group">
                                <input type="text" class="form-control form-control-lg form-control-number" id="buysell-amount" name="bs-amount" placeholder="0.055960">
                                <div class="form-dropdown">
                                    <div class="text">BTC<span>/</span></div>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-indicator-caret" data-toggle="dropdown" data-offset="0,2">USD</a>
                                        <div class="dropdown-menu dropdown-menu-xxs dropdown-menu-right text-center">
                                            <ul class="link-list-plain">
                                                <li><a href="#">EUR</a></li>
                                                <li><a href="#">CAD</a></li>
                                                <li><a href="#">YEN</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-note-group">
                                <span class="buysell-min form-note-alt">Minimum: 10.00 USD</span>
                                <span class="buysell-rate form-note-alt">1 USD = 0.000016 BTC</span>
                            </div>
                        </div><!-- .buysell-field -->
                        <div class="buysell-field form-group">
                            <div class="form-label-group">
                                <label class="form-label">Payment Method</label>
                            </div>
                            <div class="form-pm-group">
                                <ul class="buysell-pm-list">
                                    <li class="buysell-pm-item">
                                        <input class="buysell-pm-control" type="radio" name="bs-method" id="pm-paypal" />
                                        <label class="buysell-pm-label" for="pm-paypal">
                                            <span class="pm-name">PayPal</span>
                                            <span class="pm-icon"><em class="icon ni ni-paypal-alt"></em></span>
                                        </label>
                                    </li>
                                    <li class="buysell-pm-item">
                                        <input class="buysell-pm-control" type="radio" name="bs-method" id="pm-bank" />
                                        <label class="buysell-pm-label" for="pm-bank">
                                            <span class="pm-name">Bank Transfer</span>
                                            <span class="pm-icon"><em class="icon ni ni-building-fill"></em></span>
                                        </label>
                                    </li>
                                    <li class="buysell-pm-item">
                                        <input class="buysell-pm-control" type="radio" name="bs-method" id="pm-card" />
                                        <label class="buysell-pm-label" for="pm-card">
                                            <span class="pm-name">Credit / Debit Card</span>
                                            <span class="pm-icon"><em class="icon ni ni-cc-alt-fill"></em></span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- .buysell-field -->
                        <div class="buysell-field form-action">
                            <a href="#" class="btn btn-lg btn-block btn-primary" data-toggle="modal" data-target="#buy-coin">Continue to Buy</a>
                        </div><!-- .buysell-field -->
                        <div class="form-note text-base text-center">Note: our transfer fee included, <a href="#">see our fees</a>.</div>
                    </form><!-- .buysell-form -->
                </div> --}}
                <!-- .buysell-block -->
            </div><!-- .buysell -->
        </div>
    </div>
</div>
<!-- content @e -->
@endsection
