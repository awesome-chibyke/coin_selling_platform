@php $titleDescription = 'User Profile' @endphp
@extends('layouts.logged_main')

@section('content')
<div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row mt-sm-4">

              {{-- <div class="col-12 col-md-12 col-lg-4">
                <div class="card author-box">
                  <div class="card-body">
                    <div class="author-box-center">
                      <img alt="image" src="assets/img/users/user-1.png" class="rounded-circle author-box-picture">
                      <div class="clearfix"></div>
                      <div class="author-box-name">
                        <a href="#">Sarah Smith</a>
                      </div>
                      <div class="author-box-job">Web Developer</div>
                    </div>
                    <div class="text-center">
                      <div class="author-box-description">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur voluptatum alias molestias
                          minus quod dignissimos.
                        </p>
                      </div>
                      <div class="mb-2 mt-3">
                        <div class="text-small font-weight-bold">Follow Hasan On</div>
                      </div>
                      <a href="#" class="btn btn-social-icon mr-1 btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                      </a>
                      <a href="#" class="btn btn-social-icon mr-1 btn-twitter">
                        <i class="fab fa-twitter"></i>
                      </a>
                      <a href="#" class="btn btn-social-icon mr-1 btn-github">
                        <i class="fab fa-github"></i>
                      </a>
                      <a href="#" class="btn btn-social-icon mr-1 btn-instagram">
                        <i class="fab fa-instagram"></i>
                      </a>
                      <div class="w-100 d-sm-none"></div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h4>Personal Details</h4>
                  </div>
                  <div class="card-body">
                    <div class="py-4">
                      <p class="clearfix">
                        <span class="float-left">
                          Birthday
                        </span>
                        <span class="float-right text-muted">
                          30-05-1998
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Phone
                        </span>
                        <span class="float-right text-muted">
                          (0123)123456789
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Mail
                        </span>
                        <span class="float-right text-muted">
                          test@example.com
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Facebook
                        </span>
                        <span class="float-right text-muted">
                          <a href="#">John Deo</a>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Twitter
                        </span>
                        <span class="float-right text-muted">
                          <a href="#">@johndeo</a>
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h4>Skills</h4>
                  </div>
                  <div class="card-body">
                    <ul class="list-unstyled user-progress list-unstyled-border list-unstyled-noborder">
                      <li class="media">
                        <div class="media-body">
                          <div class="media-title">Java</div>
                        </div>
                        <div class="media-progressbar p-t-10">
                          <div class="progress" data-height="6">
                            <div class="progress-bar bg-primary" data-width="70%"></div>
                          </div>
                        </div>
                      </li>
                      <li class="media">
                        <div class="media-body">
                          <div class="media-title">Web Design</div>
                        </div>
                        <div class="media-progressbar p-t-10">
                          <div class="progress" data-height="6">
                            <div class="progress-bar bg-warning" data-width="80%"></div>
                          </div>
                        </div>
                      </li>
                      <li class="media">
                        <div class="media-body">
                          <div class="media-title">Photoshop</div>
                        </div>
                        <div class="media-progressbar p-t-10">
                          <div class="progress" data-height="6">
                            <div class="progress-bar bg-green" data-width="48%"></div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div> --}}
                <div class="col-12 col-md-12 col-lg-1"></div>
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="padding-20">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                          aria-selected="true">Add Bank Account</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab2" data-toggle="tab" href="#settings" role="tab"
                          aria-selected="false">Bank Accounts Details</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
                        <form action="{{ route('save-bank', [$user_object->unique_id]) }}" method="POST" >
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-2"></div>
                            <div class="col-12 col-md-12 col-lg-8">
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        @if (session('success'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        @if (session('error'))
                                            <div class="alert alert-warning" role="alert">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- account_number bank_code --}}
                                    <div class="form-group col-md-12 col-12">
                                        <label for="account_number">Account Number</label>
                                        <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}" class="form-control @error('account_number') is-invalid @enderror" />
                                        @error('account_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="err_account_number text-danger"></div>
                                    </div>

                                    <div class="form-group col-md-12 col-12">
                                        <label for="bank_code">Select Bank</label>
                                        <select name="bank_code" id="bank_code" class="form-control @error('bank_code') is-invalid @enderror">
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
                                        <div class="err_bank_code text-danger"></div>
                                    </div>
                                    <div class="form-group col-md-12 col-12">
                                        <button type="button" id="verify_account_button" class="btn btn-info btn-block">Verify Account Details</button>
                                    </div>

                                    <div class="form-group col-md-12 col-12" id="beneficiary_name_holder" hidden>
                                        <label for="beneficiary_name">Account Name</label>

                                        <input type="text" id="beneficiary_name" name="beneficiary_name" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('beneficiary_name') }}">
                                        @error('beneficiary_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="form-group col-md-12 col-12" id="main_submit_button_holder" hidden>
                                        <button type="submit" id="main_submit_button" class="btn btn-primary btn-block">Save Changes</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        </form>


                      </div>
                      <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="profile-tab2">

                          <div class="card-header">
                            {{-- <h4>Edit Profile</h4> --}}
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="table-responsive col-md-12 col-12">
                                <table class="table table-striped" id="table-2">
                                    <thead>
                                    <tr>
                                        <th class="text-center pt-3">
                                        <div class="custom-checkbox custom-checkbox-table custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                                            class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>

                                        </th>
                                        <th>Bank Name</th>
                                        <th>Beneficiary Name</th>
                                        <th>Account Number</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Options</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($user_banks) > 0)
                                        @foreach($user_banks as $eachBankDetails)
                                        <tr>
                                            <td class="text-center pt-2">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                                id="checkbox-1">
                                                <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                                            </div>
                                            </td>
                                            <td class="align-middle">{{ $eachBankDetails->bank_name }} <br> <small>{{ $eachBankDetails->bank_code }}</small></td>
                                            <td class="align-middle">{{ $eachBankDetails->beneficiary_name }}</td>
                                            <td class="align-middle">{{ $eachBankDetails->account_number }}</td>

                                            <td>
                                                @php $statusDetails = $eachBankDetails->getStatus($eachBankDetails->status) @endphp
                                                <div class="badge badge-{{ $statusDetails->class }} badge-shadow">{{ $statusDetails->keyword }}</div>
                                            </td>
                                            <td>{{ $eachBankDetails->created_at }}</td>
                                            <td>
                                                <div class="dropdown d-inline mr-2">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Options
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @php $UserBankDetailsModel = new App\Models\UserBankDetails(); @endphp
                                                        @if($eachBankDetails->status === $UserBankDetailsModel->notActiveStatus)
                                                        <a class="dropdown-item" href="{{ route('activate-bank', [$eachBankDetails->unique_id]) }}">Activate</a>
                                                        @endif
                                                        <a class="dropdown-item" href="{{ route('delete-bank', [$eachBankDetails->unique_id]) }}">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr colspan="7">
                                            <td class="text-center pt-2">
                                                <p class="alert alert-warning">No Data Available</p>
                                            </td>
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
