@php $titleDescription = 'Create Support Ticket' @endphp
@extends('layouts.logged_main')

@section('content')
<div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">

                @if ($support_object !== null)
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                        <div class="boxs mail_listing">
                            <div class="inbox-body no-pad">
                            <section class="mail-list">
                                <div class="mail-sender">
                                <div class="mail-heading">
                                    <h4 class="vew-mail-header">
                                    <b>{{ $support_object !== null ? $support_object->topic : '' }}</b>
                                    <a {{ $support_object !== null && $support_object->status ===  $support_object->supportMessageClosedStatus ? 'hidden' : ''}} class="btn btn-primary btn-sm" href="javascript:;" onClick="if(confirm('Do you really want to close this ticket?') === true ){ window.location.href = '{{ route("close-ticket", [$support_object]) }}' }" >Close Ticket</a>
                                    </h4>
                                </div>
                                <hr>
                                <div class="media">
                                    <a href="#" class="table-img m-r-15">
                                    <img alt="image" src="{{ asset('assets/avatar/avatar.png') }}" class="rounded-circle" width="35"
                                        data-toggle="tooltip" title="Profile Picture" />
                                    </a>
                                    <div class="media-body">
                                    <span class="date pull-right">{{ $support_object !== null ? $support_object->created_at : '' }}</span>
                                    <h5 class="text-primary" style="color:#6777ef !important;">{{ $support_object !== null ? $support_object->support_sender->name : '' }}</h5>
                                    <small class="text-muted">From: {{ $support_object !== null ? $support_object->support_sender->email : '' }}
                                    {{ auth()->user()->unique_id !== $support_object->support_sender->unique_id ? $support_object->support_sender->type_of_user !== auth()->user()->normalUserType && auth()->user()->type_of_user === auth()->user()->normalUserType ? '(Admin)' : '(User)' : ''}}</small>
                                    </div>
                                </div>
                                </div>

                                @php $allSupportMessage = $support_object->support_message_array; @endphp
                                @if(count($allSupportMessage) > 0)
                                @foreach ($allSupportMessage as $p => $eachSupportMessage)
                                    @if ($p > 0)
                                    <hr>
                                        <div class="media">
                                        <a href="#" class="table-img m-r-15">
                                            <img alt="image" src="{{ asset('assets/avatar/avatar.png') }}" class="rounded-circle" width="35"
                                            data-toggle="tooltip" title="Sachin Pandit">
                                        </a>
                                        <div class="media-body">
                                            <span class="date pull-right">{{ $eachSupportMessage->created_at}}</span>
                                            <h5 class="text-primary" style="color:#6777ef !important;">{{ $eachSupportMessage->sender->name }}</h5>
                                            <small class="text-muted">From: {{ $eachSupportMessage->sender->email }} {{ auth()->user()->unique_id !== $eachSupportMessage->sender->unique_id ? $eachSupportMessage->sender->type_of_user !== auth()->user()->normalUserType && auth()->user()->type_of_user === auth()->user()->normalUserType ? '(Admin)' : '(User)' : ''}}</small>
                                        </div>
                                        </div>
                                    @endif


                                    <div class="view-mail p-t-20">
                                        @php echo $eachSupportMessage->message @endphp
                                    </div>

                                    <div class="attachment-mail">
                                    @php $supportFiles = $eachSupportMessage->support_files_array @endphp

                                    @if (count($supportFiles) > 0)
                                    <p>
                                        <span>
                                        <i class="fa fa-paperclip"></i> {{ count($supportFiles) }} attachment(s)</span>
                                    </p>
                                    <div class="row">
                                        @foreach ($supportFiles as $eachSupportFile)
                                        @php $imageName = asset('storage/'.$image_folder.$eachSupportFile->filename) @endphp
                                            <div class="col-md-2">
                                            <a href="{{ $imageName }}">
                                                <img class="img-thumbnail img-responsive" alt="attachment"
                                                src="{{ $imageName }}">
                                            </a>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                    </div>

                                @endforeach
                                @endif
                            </section>
                            </div>
                        </div>
                        </div>
                    </div>
                @endif

              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>{{ $support_object === null ? 'Create' : 'Reply Ticket' }}</h4>
                  </div>
                  <div class="card-body">
                    <form action="{{ $support_object === null ? '/store-support' : '/store-continious-support' }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    @if($support_object === null)
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title/Topic</label>
                      <div class="col-sm-12 col-md-7 form-group">
                        <input type="text" class="form-control @error('topic') is-invalid @enderror" name="topic" value="{{ old('topic') }}" id="topic">
                        @error('topic')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    @endif

                    {{-- the reciver_id, sender_id and the support_unique_id --}}
                    <input type="hidden" name="reciever_id" value="{{ auth()->user()->type_of_user !==  auth()->user()->normalUserType && $support_object !== null ? $support_object->user_id : '' }}" />
                    <input type="hidden" type="hidden" name="sender_id" value="{{ auth()->user()->unique_id }}" />
                    <input type="hidden" type="hidden" name="support_unique_id" value="{{ $support_object !== null ? $support_object->unique_id : '' }}" />

                    @if($support_object === null)
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Category</label>
                      <div class="col-sm-12 col-md-7">
                        <select name="category_unique_id" class="form-control selectric @error('category_unique_id') is-invalid @enderror">
                        <option value="">Select Ticket Category</option>
                        @if (count($support_ticket_category) > 0)
                            @foreach ($support_ticket_category as $eachSupportTicket)
                                <option {{ old('topic') === $eachSupportTicket->unique_id ? 'selected' : '' }} value="{{ $eachSupportTicket->unique_id }}">{{ $eachSupportTicket->category }}</option>
                            @endforeach
                        @endif
                        </select>
                        @error('category_unique_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    @endif

                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                      <div class="col-sm-12 col-md-7">
                        <textarea {{ $support_object !== null && $support_object->status ===  $support_object->supportMessageClosedStatus ? 'disabled' : ''}} class=" message form-control @error('message') is-invalid @enderror" name="message" id="message">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>

                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Upload Files</label>
                      <div class="col-sm-12 col-md-7">
                        <div>
                          <label for="image-upload" id="image-label"><small class="text-warning">Please hold down `Ctrl` to select more than one file</small></label>
                          <input type="file" multiple name="filename[]" id="image-upload" />
                          @error('filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                      <div class="col-sm-12 col-md-7">
                        <button type="submit" {{ $support_object !== null && $support_object->status ===  $support_object->supportMessageClosedStatus ? 'disabled' : ''}} class="btn btn-primary btn-block">{{ $support_object !== null && $support_object->status ===  $support_object->supportMessageClosedStatus ? 'Ticket Closed' : 'Submit'}} </button>
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
      @endsection
