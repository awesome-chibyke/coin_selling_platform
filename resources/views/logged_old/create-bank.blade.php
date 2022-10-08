@php $titleDescription = 'Add Bank Accounts' @endphp
@extends('layouts.logged_main')

@section('content')
          <!-- content @s -->
          <div class="nk-content nk-content-fluid">
            <div class="container-xl wide-lg">
              <div class="nk-content-body">
                <div class="nk-block-head">
                  <div class="nk-block-head-content">
                    <div class="nk-block-head-sub">
                      <span>Bank Details</span>
                    </div>
                    <h2 class="nk-block-title fw-normal">Add New Bank Details</h2>

                  </div>
                </div>
                <!-- .nk-block-head -->
                <ul class="nk-nav nav nav-tabs">

                </ul>
                <!-- .nk-menu -->
                <!-- NK-Block @s -->
                <div class="nk-block">
                    <form action="{{ route('save-bank', [$user_object->unique_id]) }}" method="POST">
                        @csrf
                    @if (session('error'))
                    <div class="alert alert-warning">
                        <div class="alert-cta flex-wrap flex-md-nowrap">
                        <div class="alert-text">
                            <p>
                            {{ session('error') }}
                            </p>
                        </div>
                        </div>
                    </div>
                  @endif

                  @if (session('status'))
                    <div class="alert alert-success">
                        <div class="alert-cta flex-wrap flex-md-nowrap">
                        <div class="alert-text">
                            <p>
                            {{ session('status') }}
                            </p>
                        </div>
                        </div>
                    </div>
                  @endif


                  <!-- .nk-block-head -->
                  <div class="nk-data data-list" style="margin-top: 0rem;">
                    <div class="data-head">
                      <h6 class="overline-title">Add Bank Details Below</h6>
                    </div>
                    <div
                      class="data-item"
                      data-toggle="modal"
                      data-target="#profile-edit"
                    >
                      <div class="data-col">
                        <span class="data-label">Account Name</span>
                        <span class="data-value">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg @error('beneficiary_name') is-invalid @enderror" value="{{ old('beneficiary_name') }}" name="beneficiary_name" id="beneficiary_name" placeholder="Enter your fullname" />
                                @error('beneficiary_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </span>

                      </div>

                      <div class="data-col data-col-end">
                        <span class="data-more"
                          ><em class="icon ni ni-forward-ios"></em
                        ></span>
                      </div>
                    </div>
                    <!-- .data-item -->

                    <!-- .data-item -->
                    <div class="data-item">
                      <div class="data-col">
                        <span class="data-label">Account Number</span>
                        <span class="data-value">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" name="account_number" id="account_number" placeholder="Enter Account Number" />
                                @error('account_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </span>
                      </div>
                      <div class="data-col data-col-end">
                        <span class="data-more disable"
                          ><em class="icon ni ni-forward-ios"></em
                        ></span>
                      </div>
                    </div>
                    <!-- .data-item -->
                    <div
                      class="data-item"
                      data-toggle="modal"
                      data-target="#profile-edit"
                    >
                      <div class="data-col">
                        <span class="data-label">Select Bank</span>
                        <span class="data-value text-soft">
                            <div class="form-group">
                                <select name="bank_code" id="bank_code" class="form-control form-control-lg @error('bank_code') is-invalid @enderror">
                                    <option value="">Select Bank</option>
                                    @if(count($bank_array) > 0)
                                        @foreach($bank_array as $eachBank)
                                            <option value="{{ $eachBank->code }}">{{ $eachBank->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('bank_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </span>
                      </div>
                      <div class="data-col data-col-end">
                        <span class="data-more"
                          ><em class="icon ni ni-forward-ios"></em
                        ></span>
                      </div>
                    </div>
                    <!-- .data-item -->

                    <!-- .data-item -->
                    <div class="data-item">
                      <div class="data-col">
                        <button type="submit" class="btn btn-block btn-lg btn-primary">Add Bank</button>
                      </div>
                    </div>

                    <!-- .data-item -->
                  </div>
                  <!-- .nk-data -->

                  <!-- .nk-data -->
                    </form>
                </div>
                <!-- NK-Block @e -->
                <!-- //  Content End -->
              </div>
            </div>
          </div>
          <!-- content @e -->
          @endsection
