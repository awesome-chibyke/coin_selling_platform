@php $titleDescription = 'Dashboard' @endphp
@extends('layouts.logged_main')

@section('content')
<div class="main-content">

        <section class="section">

          <div class="row ">
            @if(auth()->user()->normalUserType !== auth()->user()->type_of_user)
            <div class="col-12 col-md-12 col-lg-3"></div>
            @endif
            <div class="col-12 col-md-12 @if(auth()->user()->normalUserType === auth()->user()->type_of_user) col-lg-7 @else col-lg-6 @endif">
                <div class="card author-box">
                  <div class="card-body">

                    <div class="author-box-center">
                      <img alt="image" src="{{ asset('assets/img/avatar-images.png') }}" class="rounded-circle author-box-picture">
                      <div class="clearfix"></div>
                      <div class="author-box-name">
                        <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
                      </div>
                      <div class="author-box-job">{{ auth()->user()->email }}</div>
                    </div>
                    @if(auth()->user()->normalUserType === auth()->user()->type_of_user)
                    <div class="text-center">
                      <div class="author-box-description">
                        <div class="form-group position-relative">
                            <label class="ref_link">My Referal Link</label>
                          <input type="text" class="form-control" id="ref_link" readonly value="{{ URL::to('/').'/register?ref='.auth()->user()->username }}" />
                          <div class="position-absolute buttonThatsTriggersCopy" data-target="ref_link" style="top:43px; right:10px; cursor:pointer;"><i class="fa fa-copy"></i></div>
                        </div>
                      </div>
                    </div>
                    @endif
                  </div>

                </div>
              </div>
              @if(auth()->user()->normalUserType !== auth()->user()->type_of_user)
                <div class="col-12 col-md-12 col-lg-3"></div>
                @endif

                @if(auth()->user()->normalUserType === auth()->user()->type_of_user)
              <div class="col-12 col-md-12 col-lg-5">
                <div class="card">
                  <div class="card-header">
                    <h4>Active Bank Details</h4>
                  </div>
                  <div class="card-body">
                    <div class="py-4" style="padding-top:0px !important;">
                        @if ($user_bank_detail !== null)
                            <p class="clearfix">
                                <span class="float-left">
                                Account Name
                                </span>
                                <span class="float-right text-muted">
                                {{ $user_bank_detail->beneficiary_name }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                Account Number
                                </span>
                                <span class="float-right text-muted">
                                {{ $user_bank_detail->account_number }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                Bank Name
                                </span>
                                <span class="float-right text-muted">
                                {{ $user_bank_detail->bank_name }}
                                </span>
                            </p>
                            <p class="clearfix">
                                {{-- <span class="float-left">
                                Twitter
                                </span> --}}
                                <span class="text-muted text-center" style="width:100%;">
                                    <a class="btn btn-outline-primary btn-block" href="{{ route('create-bank', [auth()->user()->unique_id]) }}">View All Banks</a>
                                </span>
                            </p>
                        @else
                            <p class="clearfix">
                                {{-- <span class="float-left">
                                Facebook
                                </span> --}}
                                <span class="text-muted" style="width:100%;">
                                <a class="btn btn-outline-primary btn-block" href="{{ route('create-bank', [auth()->user()->unique_id]) }}">Add Bank Account</a>
                                </span>
                            </p>
                        @endif
                    </div>
                  </div>
                </div>
              </div>
              @endif

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Total Deposits</h5>
                          <h2 class="mb-3 font-18">{{ $all_transactions_count }}</h2>
                          {{-- <p class="mb-0"><span class="col-green">10%</span> Increase</p> --}}
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/1.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Comfirmed Deposits</h5>
                          <h2 class="mb-3 font-18">{{ $all_completed_deposits_count }}</h2>
                          {{-- <p class="mb-0"><span class="col-orange">09%</span> Decrease</p> --}}
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/2.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">All Withdrawals</h5>
                          <h2 class="mb-3 font-18">{{auth()->user()->type_of_user === auth()->user()->normalUserType ? $all_transfers_count : $all_transfers_count - 3 }}</h2>
                          {{-- <p class="mb-0"><span class="col-green">18%</span>
                            Increase</p> --}}
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/3.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Un-Read Support Messages</h5>
                          <h2 class="mb-3 font-18">{{ $all_unread_support_message_count }}</h2>
                          {{-- <p class="mb-0"><span class="col-green">42%</span> Increase</p> --}}
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/5.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row ">

              <div class="col-12 col-md-12 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Deposits Summary</h4>
                  </div>
                  <div class="card-body">
                    <div class="py-4" style="padding-top:0px !important;">
                            <p class="clearfix">
                                <span class="float-left">
                                Total Deposit Amount
                                </span>
                                <span class="float-right text-muted">
                                $ {{ $all_transactions_sum }}
                                </span>
                            </p>
                            @php $paymentModalModelInstance = new App\Models\PaymentModal() @endphp
                            <p class="clearfix">
                                {{-- <span class="float-left">
                                Twitter
                                </span> --}}
                                <span class="text-muted text-center" style="width:100%;">
                                    <a class="btn btn-outline-primary btn-block" href="{{ route('payment-history', [$paymentModalModelInstance->coinSaleActionType]) }}">View Deposits</a>
                                </span>
                            </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-12 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Withdrawals Summary</h4>
                  </div>
                  <div class="card-body">
                    <div class="py-4" style="padding-top:0px !important;">
                        <p class="clearfix">
                            <span class="float-left">
                                Total Withdrawals Amount
                            </span>
                            <span class="float-right text-muted">
                            {{ auth()->user()->nairaSymbol }} {{ $all_completed_deposits__sum }}
                            </span>
                        </p>
                        <p class="clearfix">
                            {{-- <span class="float-left">
                            Twitter
                            </span> --}}
                            <span class="text-muted text-center" style="width:100%;">
                                <a class="btn btn-outline-primary btn-block" href="{{ route('payment-history', [$paymentModalModelInstance->transferSettlementType]) }}">View Withdrawals</a>
                            </span>
                        </p>
                    </div>
                  </div>
                </div>
              </div>


              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Bar CHart for confirmed Deposits and Completed Withdrawals</h4>
                  </div>
                  <div class="card-body">

                    <div class="row">
                        <div class="col-sm-12" style="overflow-x:auto;"><div id="echart_bar_line" class="chartsh chart-shadow"></div></div>
                    </div>
                    <div class="row" style="margin-top:40px;">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label" for="main_select">Filter By: </label>
                                <select class="form-control selectric" id="main_select">
                                    <option value="">Select</option>
                                    <option value="{{ $filter_by_day }}">Filter By {{ ucfirst($filter_by_day) }}</option>
                                    <option value="{{ $filter_by_month }}">Filter By {{ ucfirst($filter_by_month) }}</option>
                                    <option value="{{ $filter_by_year }}">Filter By {{ ucfirst($filter_by_year) }}</option>
                                </select>
                                <div class="err_main_select"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label" for="day_">Date</label>
                                <select class="form-control selectric" id="day_">
                                    <option value="">Select Date</option>
                                    @for ($i = 1; $i < 32; $i++)
                                        <option value="{{ $i < 10 ? '0'.$i : $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <div class="err_day_"></div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label" for="month_">Month</label>
                                <select class="form-control selectric" id="month_">
                                    @php $monthNo = 1; @endphp
                                    <option value="">Select Month</option>
                                    @foreach($months_array as $k => $eachMonth)
                                        <option value="{{ $monthNo < 10 ? '0'.$monthNo : $monthNo }}">{{ $eachMonth }}</option>
                                        @php $monthNo++ @endphp
                                    @endforeach
                                </select>
                                <div class="err_month_"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            @php $currentYear = Carbon\Carbon::now()->format('Y'); @endphp
                            <div class="form-group">
                                <label class="form-label" for="year_1">Year</label>
                                <select class="form-control selectric" id="year_1">
                                    @php $theYear = 2000; @endphp
                                    <option value="">Select Year</option>
                                    @for($i = $theYear; $i < $currentYear+1;  $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @php $monthNo++ @endphp
                                    @endfor
                                </select>
                                <div class="err_year_1"></div>
                            </div>
                        </div>

                        <div class="col-sm-6" id="year_2_holder" hidden>
                            @php $currentYear = Carbon\Carbon::now()->format('Y'); @endphp
                            <div class="form-group">
                                <label class="form-label" for="year_2">Year Two</label>
                                <select class="form-control selectric" id="year_2">
                                    <option value="">Select Year</option>
                                    @php $theYear = 2000; @endphp
                                    @for($i = $theYear; $i < $currentYear+1;  $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @php $monthNo++ @endphp
                                    @endfor
                                </select>
                                <div class="err_year_2"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="year_2" style="color:white;">Year Two</label>
                            <button class="btn btn-primary btn-outline-primary btn-block" id="filter_by_values">Proceed</button>
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
