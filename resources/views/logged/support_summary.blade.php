@php $titleDescription = 'User Profile' @endphp
@extends('layouts.logged_main')

@section('content')

<div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="card">
                  <div class="body">
                    <div id="mail-nav">
                      <a href="{{ route('create-support') }}" class="btn btn-danger waves-effect btn-compose m-b-15">Create Ticket</a>
                      <ul class="" id="mail-folders">
                        <li class="active">
                          <a href="mail-inbox.html" title="Inbox">Inbox ({{ $support_array->count() }})
                          </a>
                        </li>
                      </ul>

                    </div>
                  </div>
                </div>
              </div>


              <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <div class="card">
                  <div class="boxs mail_listing">
                    <div class="inbox-center table-responsive">
                      <table class="table table-hover">
                        <thead>
                            <tr>
                                <th colspan="{{auth()->user()->type_of_user === auth()->user()->normalUserType ? 5 : 6 }}" class="text-left">
                                    <h5 class="text-center">List of Support Messages</h5>
                                </th>
                          </tr>
                            <tr>
                                <th class="hidden-xs" colspan="{{auth()->user()->type_of_user === auth()->user()->normalUserType ? 3 : 4 }}"></th>
                                <th class="hidden-xs" colspan="2">
                                    <div class="pull-right">
                                        @php echo $pagination_details @endphp
                                    </div>
                                </th>
                          </tr>

                          <tr>
                            <th class="text-left">S/No</th>
                            <th class="tbl-checkbox">
                              <label class="form-check-label">
                                <input type="checkbox">
                                <span class="form-check-sign"></span>
                              </label>
                            </th>
                            <th class="text-left">Status</th>
                            @if(auth()->user()->type_of_user !== auth()->user()->normalUserType) <th> Name</th> @endif


                            <th class="text-left">Title</th>
                            <th class="text-left">Date Created</th>
                          </tr>
                        </thead>
                        <tbody>
                        @if($support_array->count() > 0)
                        @php $sno = 1 @endphp
                        @foreach($support_array as $k => $eachSupport)

                          <tr class="unread">
                            <td>{{ $sno }}</td>
                            <td class="tbl-checkbox">
                              <label class="form-check-label">
                                <input type="checkbox">
                                <span class="form-check-sign"></span>
                              </label>
                            </td>
                            <td class="hidden-xs">
                                @php $openCloseStatus = $eachSupport->getMessageStatus() @endphp
                              <span class="btn btn-{{ $openCloseStatus->class }}">{{ $eachSupport->status }}</span>
                            </td>

                            @if(auth()->user()->type_of_user !== auth()->user()->normalUserType)
                                <td class="hidden-xs">{{ $eachSupport->support_sender->name }}</td>
                            @endif

                            <td class="max-texts">
                              <a href="{{ route('create-support', [$eachSupport->unique_id]) }}">
                                {{ $eachSupport->topic }} <span class="badge badge-info">{{ $eachSupport->getReadStatus() }}</span></a>
                            </td>
                            <td class="text-right"> {{ Carbon\Carbon::parse($eachSupport->created_at)->diffForHumans() }} </td>
                          </tr>
                          @php $sno++ @endphp
                        @endforeach
                        @else
                            <tr class="text-center">
                                <td  colspan="{{auth()->user()->type_of_user === auth()->user()->normalUserType ? 5 : 6 }}"class="tbl-checkbox text-center">
                                <p class="alert alert-warning text-center">No Data Found</p>
                                </td>
                            </tr>
                        @endif

                        </tbody>
                      </table>
                    </div>
                    {{-- <div class="row">
                      <div class="col-sm-7 ">
                        <p class="p-15">Showing 1 - 15 of 200</p>
                      </div>
                    </div> --}}
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
