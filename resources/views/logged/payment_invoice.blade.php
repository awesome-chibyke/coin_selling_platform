@php $titleDescription = 'Payment Invoice' @endphp
@extends('layouts.logged_main')

@section('content')
<div class="@if(auth()->check()) main-content @else my-main-content @endif" >
        <section class="section">
          <div class="section-body">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice-title">
                                <h2>{{ $payment_details->coin }}</h2>
                                <div class="invoice-number">Transaction ID: {{ $payment_details->unique_id }}</div>
                                </div>
                                <div class="row">
                                <div class="col-md-6">
                                    {{-- <address>
                                    <strong>Billed To:</strong><br>
                                    {{ $payment_details->user_object->name }}<br>
                                    {{ $payment_details->user_object->email }},<br>
                                    {{ $payment_details->user_object->phone }}
                                    </address> --}}
                                </div>
                                <div class="col-md-6 text-md-right">
                                    {{-- <address>
                                    <strong>Shipped To:</strong><br>
                                    Keith Johnson<br>
                                    197 N 2000th E<br>
                                    Rexburg, ID,<br>
                                    Springfield Center, USA
                                    </address> --}}
                                </div>
                                </div>
                                <div class="row">
                                {{-- <div class="col-md-6">
                                    <address>
                                    <strong>Payment Currency:</strong><br>
                                    COIN: {{ $payment_details->coin_name }}<br>
                                    CODE: {{ $payment_details->coin }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                    <strong>Order Date:</strong><br>
                                    {{ $payment_details->status }}<br><br>
                                    </address>
                                </div> --}}
                                </div>
                            </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-1"></div>
                                <div class="form-group col-md-10 text-center mb-0">
                                    <h5 class="mb-0"><strong>Amount: </strong>$ {{ number_format($payment_details->amount_in_usd) }}</h5>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3"></div>
                                <div class="col-md-6 text-center">
                                    <img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl={{ $payment_details->pay_address }}" />
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="p-2" style="border:2px solid black; border-radius:10px;">

                                        <div class="row mt-2">
                                            <div class="col-md-1"></div>
                                            <div class="form-group col-md-10 text-center mb-1 position-relative">
                                                <label class="text-left" for="wallet_address">Amount To Send ({{ $payment_details->coin }})</label>
                                                <input type="text" id="wallet_address" name="wallet_address" value="{{ $payment_details->coin_value }}" disabled class="form-control" />
                                                <span data-target="wallet_address" class="fa fa-clipboard position-absolute copier_placement buttonThatsTriggersCopy text-info" title="Click to copy amount"></span>
                                                <small class="text-danger">Very Important: Please click the copy icon (<i class="fa fa-clipboard text-info"></i>) on the right hand side of the field above to copy amount which you will send.</small><br>
                                                <small class="text-danger">NOTE: Not sending the exact amount above ({{ $payment_details->coin_value }} {{ $payment_details->coin }}) will affect the processing of your transaction</small>
                                            </div>
                                            <div class="col-md-1"></div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-1"></div>
                                            <div class="form-group col-md-10 text-center mb-1 position-relative">
                                                <label class="text-left" for="pay_address">Wallet Address</label>
                                                <input type="text" id="pay_address" name="pay_address" value="{{ $payment_details->pay_address }}" disabled class="form-control" />
                                                <span data-target="pay_address" class="fa fa-clipboard position-absolute copier_placement buttonThatsTriggersCopy text-info" title="Click to copy wallet address"></span>
                                                <small class="text-danger">Please click the copy icon (<i class="fa fa-clipboard text-info"></i>) on the right hand side of the field above to copy wallet address</small>
                                            </div>
                                            <div class="col-md-1"></div>
                                        </div>
                                        <div class="row mt-2 mb-2">
                                            <div class="col-md-1"></div>
                                            <div class="form-group col-md-10 text-center mb-0">
                                                <label class="text-left" for="amount_in_usd">You will recieve</label>
                                                <input type="text" id="amount_in_usd" name="amount_in_usd" value="NGN {{ number_format($payment_details->rate * $payment_details->amount_in_usd, 2) }}" disabled class="form-control" />
                                            </div>
                                            <div class="col-md-1"></div>
                                        </div>

                                        <div class="row mt-2 mb-2">
                                            <div class="col-md-1"></div>
                                            <div class="form-group col-md-10 text-center mb-0">
                                                @php $paymentStatusDetails = $payment_details->returnPaymentStatus() @endphp
                                                <button class="btn btn-{{$paymentStatusDetails->class}}">{{$paymentStatusDetails->value}}</button>
                                            </div>
                                            <div class="col-md-1"></div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                            @if(auth()->check() && auth()->user()->unique_id === $payment_details->user_unique_id)
                            <div class="row mt-4">
                            <div class="col-md-12">

                                <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="section-title">Important Note</div>
                                        <ul>
                                            <li>Send only Tether {{ $payment_details->coin }} network to this deposit address.</li>
                                            <li>Sending coins other than {{ $payment_details->coin }} network to this address may result in the loss of your deposit.</li>
                                            <li>Coins will be received after one network confirmation.</li>
                                        </ul>
                                </div>

                                </div>
                            </div>
                            </div>
                            @endif
                        </div>
                        {{-- <div class="text-md-right">
                            <div class="float-lg-left mb-lg-0 mb-3">
                            <button class="btn btn-primary btn-icon icon-left"><i class="fas fa-credit-card"></i> Process
                                Payment</button>
                            <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i> Cancel</button>
                            </div>
                            <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
                        </div> --}}
                        </div>
                </div>
                <div class="col-sm-2"></div>
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
