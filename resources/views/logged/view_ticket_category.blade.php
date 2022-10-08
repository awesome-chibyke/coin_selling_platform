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
                        <h4 class="col-12 col-sm-8">List Of Ticket Categories</h4>
                        <div class="col-12 col-sm-4">


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
                              <th class="text-center">Category Name</th>
                              <th class="text-center">Date Created</th>
                              <th class="text-center">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (count($support_ticket_category) > 0)
                            @php $counter = 1;  @endphp
                                @foreach ($support_ticket_category as $each_category)
                                    <tr>
                                        <td class="text-center" scope="col">{{$counter}}</td>
                                        <td class="text-center sorting_1">
                                            <input type="checkbox" class="smallCheckBox" value="{{$each_category->unique_id}}">
                                        </td>
                                        <td class="text-center">{{ $each_category->category }}</td>
                                        <td class="text-center">{{ $each_category->created_at }}</td>
                                        <td class="text-center action_holder" data-url="{{ route('manage-user-level', [$each_category->unique_id]) }}">
                                            <div class="btn-group mb-2" >
                                                <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Options
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('edit-ticket-category', [$each_category->unique_id]) }}">Edit</a>
                                                    <a class="dropdown-item" href="{{ route('delete-ticket-category', [$each_category->unique_id]) }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    @php $counter++;  @endphp
                                @endforeach
                            @else
                                <tr class="odd">
                                    <td colspan="5" class="dataTables_empty text-center" valign="top">No matching records found</td>
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
        <button type="button" class="btn btn-warning" id="deactivateAccount" title="Select User(s) to be deactivated by ticking the checkbox on each row and then click this button to delete">Deactivate User(s) Account</button>
        <button type="button" class="btn btn-success" id="activateAccount" title="Select User(s) to be activated by ticking the checkbox on each row and then click this button to delete">Activate User(s) Account</button>
        <button type="button" class="btn btn-danger" id="deleteUser" title="Select User(s) to be deleted by ticking the checkbox on each row and then click this button to delete">Delete User(s)</button>
    </div>

@endsection
