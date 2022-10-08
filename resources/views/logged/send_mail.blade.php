@php $titleDescription = 'Create Support Ticket' @endphp
@extends('layouts.logged_main')

@section('content')
<div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">

              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Send Mail</h4>
                  </div>
                  <div class="card-body">
                    <form action="{{route('send-mail')}}" method="POST" enctype="multipart/form-data" >
                    @csrf

                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                      <div class="col-sm-12 col-md-7 form-group">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" id="title" />
                        <div class="err_title text-danger"></div>
                      </div>
                    </div>

                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Send To:</label>
                      <div class="col-sm-12 col-md-7">
                        <select id="mail_readers" name="mail_readers" class="form-control selectric @error('mail_readers') is-invalid @enderror">
                            <option value="{{$bulkMailModelInstance->sendToAllUsers}}">{{strtoupper($bulkMailModelInstance->sendToAllUsers)}}</option>
                            <option value="{{$bulkMailModelInstance->sendToSelectedUsers}}">{{strtoupper($bulkMailModelInstance->sendToSelectedUsers)}}</option>
                        </select>
                        <div class="err_mail_readers text-danger"></div>
                      </div>
                    </div>

                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Mail body</label>
                      <div class="col-sm-12 col-md-7">
                        <textarea class="summernote mail_body form-control @error('mail_body') is-invalid @enderror" name="mail_body" id="mail_body">{{ old('mail_body') }}</textarea>
                        <div class="err_mail_body text-danger"></div>
                      </div>
                    </div>

                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Attachments</label>
                      <div class="col-sm-12 col-md-7">
                        <div>
                          <input type="file" multiple name="filename[]" id="filename" />
                          <div class="err_filename text-danger"></div>
                          @error('filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                      </div>
                    </div>
                    <div class="form-group row mb-4  user_table_holder" hidden id="user_table_holder">
                      <div class="col-sm-12 col-md-12">
                            <div class="table-responsive">

                        <table class="table table-striped" id="table-1">
                          <thead>
                            <tr>
                              <th class="text-center">S / N</th>
                              <th class="text-center">
                                <input onclick="checkAll()" type="checkbox" class="mainCheckBox" />
                              </th>
                              <th class="text-center">User Name</th>
                              <th class="text-center">User Email</th>
                              {{-- <th class="text-center">Gender</th> --}}
                              {{-- <th class="text-center">User Image</th> --}}
                              <th class="text-center">User Type</th>
                              <th class="text-center">Account Status</th>
                              <th class="text-center">Date Created</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (count($users) > 0)
                            @php $counter = 1;  @endphp
                                @foreach ($users as $each_user)
                                    <tr>
                                        <td class="text-center" scope="col">{{$counter}}</td>
                                        <td class="text-center sorting_1">
                                            <input type="checkbox" class="smallCheckBox" value="{{$each_user->unique_id}}">
                                        </td>
                                        <td class="text-center">{{ $each_user->name }}</td>
                                        <td class="text-center">{{ $each_user->email }}</td>
                                        <td class="text-center typeOfUserHolder">{{ strtoupper($each_user->type_of_user) }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-{{ $each_user->status == 'active'?'success':'danger' }}"> {{ $each_user->status == 'active'?'Active':$each_user->status }}</button>
                                        </td>
                                        <td class="text-center">{{ $each_user->created_at->diffForHumans() }}</td>
                                    </tr>

                                    @php $counter++;  @endphp
                                @endforeach
                            @else
                                <tr class="odd">
                                    <td colspan="9" class="dataTables_empty text-center" valign="top">No matching records found</td>
                                </tr>
                            @endif
                          </tbody>
                        </table>

                    </div>
                      </div>
                    </div>
                    </form>
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
        <button type="button" class="btn btn-success" id="send_mail" title="Click To Send Mail">Send Mail</button>
    </div>
      @endsection

