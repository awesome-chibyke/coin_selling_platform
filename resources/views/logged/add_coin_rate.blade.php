@php $titleDescription = 'Deposit Cryptocurrency' @endphp
@extends('layouts.logged_main')

@section('content')
<div class="main-content">
        <section class="section">
          <div class="section-body">

            <div class="row">

            <div class="col-12 col-sm-12 col-lg-1"></div>
              <div class="col-12 col-sm-12 col-lg-10">
                <div class="card mt-4">
                  <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <h5 class="mb-0">Crypto Currencies available for exchange</h5>
                            <p><small class="text-success">Click preferred crypto to see address details</small></p>
                        </div>

                        @foreach ($combined_coin_rate_and_coin_for_payments_array as $k => $eachCoinDetail)
                        <div class="col-sm-3 col-6 mb-md-0 mb-4 text-center triggerCoinRateUpdateProcedure" style="margin-top: 10px;" data-coin-name="{{ $eachCoinDetail->name }}" data-coin-code="{{ $eachCoinDetail->code }}" data-local-rate="{{ $eachCoinDetail->local_currency_rate ?? '' }}">
                            <div class="image_for_coins_carrier">
                                @php $imageUrl = $coinMarketInstance->correctAString($eachCoinDetail->logo_url, 'image/', 'images/') @endphp
                                @php $imageUrl2 = asset('assets/img/trc20.png') @endphp
                                <div class="img-shadow flag-icon flag-icon-au">
                                    {{-- {{ $image_base_url.$imageUrl }} --}}
                                    <img src="{{ $eachCoinDetail->code === 'USDTTRC20' ? $imageUrl2 : $image_base_url.$imageUrl}}" class="image_for_coins" />
                                </div>
                                <div class="mt-3 font-weight-bold text-small"><em>{{ strtoupper($eachCoinDetail->name) }}</em></div>
                                <div class="text-small text-muted">{{ $eachCoinDetail->code }}</div>
                                <div class="text-small text-muted " id="local_currency_rate{{$eachCoinDetail->code}}">@php echo $eachCoinDetail->local_currency_rate !== null ? '<strong>Rate: </strong> '.$eachCoinDetail->local_currency.' '.$eachCoinDetail->local_currency_rate.'/$' : '' @endphp</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div>
        </section>

        <div class="settingSidebar">
          <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
          </a>
          <div class="settingSidebar-body ps-container ps-theme-default">
            <div class=" fade show active">
              <div class="setting-panel-header">Setting Panel
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Select Layout</h6>
                <div class="selectgroup layout-color w-50">
                  <label class="selectgroup-item">
                    <input type="radio" name="value" value="1" class="selectgroup-input-radio select-layout" checked>
                    <span class="selectgroup-button">Light</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="value" value="2" class="selectgroup-input-radio select-layout">
                    <span class="selectgroup-button">Dark</span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Sidebar Color</h6>
                <div class="selectgroup selectgroup-pills sidebar-color">
                  <label class="selectgroup-item">
                    <input type="radio" name="icon-input" value="1" class="selectgroup-input select-sidebar">
                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                      data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="icon-input" value="2" class="selectgroup-input select-sidebar" checked>
                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                      data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Color Theme</h6>
                <div class="theme-setting-options">
                  <ul class="choose-theme list-unstyled mb-0">
                    <li title="white" class="active">
                      <div class="white"></div>
                    </li>
                    <li title="cyan">
                      <div class="cyan"></div>
                    </li>
                    <li title="black">
                      <div class="black"></div>
                    </li>
                    <li title="purple">
                      <div class="purple"></div>
                    </li>
                    <li title="orange">
                      <div class="orange"></div>
                    </li>
                    <li title="green">
                      <div class="green"></div>
                    </li>
                    <li title="red">
                      <div class="red"></div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                  <label class="m-b-0">
                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                      id="mini_sidebar_setting">
                    <span class="custom-switch-indicator"></span>
                    <span class="control-label p-l-10">Mini Sidebar</span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                  <label class="m-b-0">
                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                      id="sticky_header_setting">
                    <span class="custom-switch-indicator"></span>
                    <span class="control-label p-l-10">Sticky Header</span>
                  </label>
                </div>
              </div>
              <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                  <i class="fas fa-undo"></i> Restore Default
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endsection
