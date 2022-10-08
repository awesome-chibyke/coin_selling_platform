@php $titleDescription = 'Settings' @endphp
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
                        <h4 class="col-12 col-sm-8">List Of User</h4>
                        <div class="col-12 col-sm-4">

                            @php $userModelInstance = new App\Models\User(); @endphp
                            <select  onChange=" window.location.href = '/list-of-users/'+this.value" class="form-control selectric language" id="change_user_select">
                                <option {{ Request::segment(2) === $userModelInstance->normalUserType || Request::segment(2) === '' ? 'selected':'' }} value="{{ $userModelInstance->normalUserType }}">{{ strtoupper($userModelInstance->normalUserType) }}</option>
                                <option {{ Request::segment(2) === $userModelInstance->adminUserType ? 'selected':'' }} value="{{ $userModelInstance->adminUserType }}">{{ strtoupper($userModelInstance->adminUserType) }}</option>
                                <option {{ Request::segment(2) === $userModelInstance->midAdminUserType ? 'selected':'' }} value="{{ $userModelInstance->midAdminUserType }}">{{ strtoupper($userModelInstance->midAdminUserType) }}</option>
                                <option {{ Request::segment(2) === $userModelInstance->superAdminUserType ? 'selected':'' }} value="{{ $userModelInstance->superAdminUserType }}">{{ strtoupper($userModelInstance->superAdminUserType) }}</option>
                            </select>
                        </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="padding-20">
                                    @if (session('success'))
                                        <div class="alert alert-success text-center text-white">
                                        <p>{{session('success')}}</p>
                                        </div>
                                    @endif
                                    @if (session('error'))
                                    <div class="alert alert-danger text-center text-white">
                                        <p>{{session('error')}}</p>
                                    </div>
                                    @endif
                                    <form method="post" action="{{route('update-settings')}}" class="needs-validation">
                                        @csrf
                                        <div class="card-header">
                                            <h4>System Setting</h4>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-12 col-12">
                                                    <label>Site Name</label>
                                                    <input type="text" name="site_name" class="form-control" value="{{$appSetting->site_name}}">
                                                    <div class="invalid-feedback err_site_name"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Site Email 1</label>
                                                    <input type="email" name="email1" class="form-control" value="{{$appSetting->email1}}">
                                                    <div class="invalid-feedback err_email1"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Site Email 2</label>
                                                    <input type="email" name="email2" class="form-control" value="{{$appSetting->email2}}">
                                                    <div class="invalid-feedback err_email2"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Site Url</label>
                                                    <input type="text" name="site_url" class="form-control" value="{{$appSetting->site_url}}">
                                                    <div class="invalid-feedback err_site_url"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Site Logo Url</label>
                                                    <input type="text" name="logo_url" class="form-control" value="{{$appSetting->logo_url}}">
                                                    <div class="invalid-feedback err_logo_url"></div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Address 1</label>
                                                    <textarea name="address1" class="form-control">{{$appSetting->address1}}</textarea>
                                                    <div class="invalid-feedback error_displayer err_address1"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Address 2</label>
                                                    <textarea name="address2" class="form-control">{{$appSetting->address2}}</textarea>
                                                    <div class="invalid-feedback error_displayer err_address2"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6 col-12">
                                                    <label>office Hours</label>
                                                    <textarea name="office_hour" class="form-control">{{$appSetting->office_hour}}</textarea>
                                                    <div class="invalid-feedback error_displayer err_office_hour"></div>
                                                </div>
                                                {{-- <div class="form-group col-md-6 col-12">office_hour
                                                    <label>Paypal Url</label>
                                                    <textarea name="paypal_url" class="form-control">{{$appSetting->paypal_url}}</textarea>
                                                    <div class="invalid-feedback error_displayer err_paypal_url"></div>
                                                </div> --}}
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Facebook Url</label>
                                                    <input type="text" name="facebook" class="form-control" value="{{$appSetting->facebook}}">
                                                    <div class="invalid-feedback err_facebook"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Instagram Url</label>
                                                    <input type="text" name="instagram" class="form-control" value="{{$appSetting->instagram}}">
                                                    <div class="invalid-feedback err_instagram"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Twitter Url</label>
                                                    <input type="text" name="twitter" class="form-control" value="{{$appSetting->twitter}}">
                                                    <div class="invalid-feedback err_twitter"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Linkedin Url</label>
                                                    <input type="text" name="linkedin" class="form-control" value="{{$appSetting->linkedin}}">
                                                    <div class="invalid-feedback err_linkedin"></div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Referal Percentage</label>
                                                    <input type="text" name="referal_percentage" class="form-control" value="{{$appSetting->referal_percentage}}">
                                                    <div class="invalid-feedback err_referal_percentage"></div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Least Referal Amount</label>
                                                    <input type="text" name="least_referal_amount" class="form-control" value="{{$appSetting->least_referal_amount}}">
                                                    <div class="invalid-feedback err_least_referal_amount"></div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save Changes</button>
                                        </div>
                                    </form>
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

    {{-- <div style="position: fixed; bottom: 20px; right: 30px; z-index: 200">
        <button type="button" class="btn btn-warning" id="deactivateAccount" title="Select User(s) to be deactivated by ticking the checkbox on each row and then click this button to delete">Deactivate User(s) Account</button>
        <button type="button" class="btn btn-success" id="activateAccount" title="Select User(s) to be activated by ticking the checkbox on each row and then click this button to delete">Activate User(s) Account</button>
        <button type="button" class="btn btn-danger" id="deleteUser" title="Select User(s) to be deleted by ticking the checkbox on each row and then click this button to delete">Delete User(s)</button>
    </div> --}}

@endsection
