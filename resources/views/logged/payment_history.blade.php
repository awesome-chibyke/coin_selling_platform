@php $titleDescription = 'Deposit Cryptocurrency'
@endphp
@extends('layouts.logged_main')

@section('content')

    <div class="main-content">
        <section class="section">
          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="row" style="width:100%;">
                        <h4 class="col-12 col-sm-8">
                            @if ($payment_modal_instance->coinSaleActionType === Request::segment(2))
                                Deposits
                            @endif
                            @if ($payment_modal_instance->transferSettlementType === Request::segment(2))
                                Withdrawals
                            @endif
                        </h4>
                        <div class="col-12 col-sm-4">

                            <label>Filter By Trasaction Status</label>

                            <select  onChange=" window.location.href = `${this.value}`" class="form-control selectric language" id="change__select">
                                @if ($payment_modal_instance->coinSaleActionType === Request::segment(2))
                                    <option {{ Request::segment(3) === $payment_modal_instance->paymentModalPendingStatus || Request::segment(3) === '' ? 'selected':'' }} value="{{ route('payment-history', [Request::segment(2) ,$payment_modal_instance->paymentModalPendingStatus, Request::segment(4), Request::segment(5)]) }}" >{{ strtoupper($payment_modal_instance->paymentModalPendingStatus) }}</option>

                                    <option {{ Request::segment(3) === $payment_modal_instance->paymentModalConfirmedStatus ? 'selected':'' }} value="{{ route('payment-history', [Request::segment(2), $payment_modal_instance->paymentModalConfirmedStatus, Request::segment(4), Request::segment(5)]) }}">{{ strtoupper($payment_modal_instance->paymentModalConfirmedStatus) }}</option>

                                    <option {{ Request::segment(3) === $payment_modal_instance->paymentModalExpiredStatus ? 'selected':'' }} value="{{ route('payment-history', [Request::segment(2), $payment_modal_instance->paymentModalExpiredStatus, Request::segment(4), Request::segment(5)]) }}">{{ strtoupper($payment_modal_instance->paymentModalExpiredStatus) }}</option>
                                @endif

                                @if ($payment_modal_instance->transferSettlementType === Request::segment(2))
                                    <option {{ Request::segment(4) === $payment_modal_instance->paymentModalProcessingTransfer ? 'selected':'' }} value="{{ route('payment-history', [Request::segment(2), $payment_modal_instance->paymentModalProcessingTransfer, Request::segment(4), Request::segment(5)]) }}">{{ strtoupper($payment_modal_instance->paymentModalProcessingTransfer) }}</option>

                                    <option {{ Request::segment(3) === $payment_modal_instance->paymentModalCompletedStatus ? 'selected':'' }} value="{{ route('payment-history', [Request::segment(2), $payment_modal_instance->paymentModalCompletedStatus, Request::segment(4), Request::segment(5)]) }}">{{ strtoupper($payment_modal_instance->paymentModalCompletedStatus) }}</option>
                                @endif

                                <option {{ Request::segment(3) === $payment_modal_instance->paymentModalFailedStatus ? 'selected':'' }} value="{{ route('payment-history', [Request::segment(2), $payment_modal_instance->paymentModalFailedStatus, Request::segment(4), Request::segment(5)]) }}">{{ strtoupper($payment_modal_instance->paymentModalFailedStatus) }}</option>
                            </select>
                        </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-striped" id="table-1">
                          <thead>
                            <tr>
                              <th class="text-center">S / N</th>
                              <th class="text-center">
                                <input onclick="checkAll()" type="checkbox" class="mainCheckBox" />
                              </th>
                              <th class="text-center">Transaction ID</th>
                              @if(auth()->user()->type !== auth()->user()->normalUserType) <th class="text-center">Full Name</th> @endif

                              <th class="text-center">Amount (USD)</th>
                              <th class="text-center">Cryptocurrency</th>
                              <th class="text-center">Cryptocurrency Value</th>
                              <th class="text-center">Address for Payment</th>
                              <th class="text-center">Description</th>
                              <th class="text-center">Transaction Option Used</th>
                              <th class="text-center">Transaction Status</th>
                              <th class="text-center">Amount Sent To User</th>
                              <th class="text-center">Rate</th>
                              <th class="text-center">Date Created</th>
                            </tr>
                          </thead>
                          {{-- user_unique_id,premium_plan_id,amount_in_usd,coin,coin_value,pay_address,description,hosted_url,action_type,payment_option,status,reference,coin_name,local_currency,rate,deposit_transaction_id,amount_transfered --}}
                          <tbody>
                            @if (count($payments) > 0)
                            @php $counter = 1;  @endphp
                                @foreach ($payments as $payment)
                                    <tr id="{{$payment->unique_id.'_holder'}}">


                                        <td class="text-center" scope="col">{{$counter}}</td>
                                        <td class="text-center sorting_1">
                                            <input type="checkbox" class="smallCheckBox" value="{{$payment->unique_id}}">
                                        </td>
                                        <td class="text-center" scope="col">{{$payment->unique_id}}</td>
                                        @if(auth()->user()->type !== auth()->user()->normalUserType)
                                            <td class="text-center">{{ $payment->user_object->name }}</td>
                                        @endif
                                        <td class="text-center">{{ $payment->amount_in_usd }}</td>
                                        <td class="text-center">{{ $payment->coin_name }} <br> @php echo $payment->status === $payment->paymentModalPendingStatus ? '<a href="/payment-invoice/'.$payment->unique_id.'">Details</a>' : '' @endphp </td>
                                        <td class="text-center">{{ $payment->coin_value }} ({{ $payment->coin }})</td>
                                        <td class="text-center">{{ $payment->pay_address }}</td>
                                        <td class="text-center">{{ $payment->description }} </td>
                                        <td class="text-center">{{ $payment->payment_option }} </td>
                                        <td class="text-center">
                                            @php $paymentStatusDetails = $payment->returnPaymentStatus() @endphp
                                            <button class="btn btn-{{$paymentStatusDetails->class}}">{{$paymentStatusDetails->value}}</button>
                                        </td>
                                        <td class="text-center">{{ $payment->local_currency }} {{ number_format($payment->amount_transfered, 2) }} </td>
                                        <td class="text-center">{{ number_format($payment->rate, 2) }}/$ </td>
                                        <td class="text-center">{{ $payment->created_at }}</td>
                                    </tr>

                                    @php $counter++;  @endphp
                                @endforeach
                            @else
                                <tr class="odd">
                                    <td colspan="{{ auth()->user()->type !== auth()->user()->normalUserType ? 13 : 12 }}" class="dataTables_empty text-center" valign="top">No matching records found</td>
                                </tr>
                            @endif
                          </tbody>
                        </table>

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

    <div style="position: fixed; bottom: 20px; right: 30px; z-index: 200">
        <button type="button" class="btn btn-danger" id="deletePayments" title="Select Payments(s) to be deleted by ticking the checkbox on each row and then click this button to delete">Delete Payments(s)</button>
    </div>

@endsection
