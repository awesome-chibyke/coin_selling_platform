    @php $titleDescription = 'Edit User Profile' @endphp
@extends('layouts.logged_main')

@section('content')
          <!-- content @s -->
          <div class="nk-content nk-content-fluid">
            <div class="container-xl wide-lg">
              <div class="nk-content-body">
                <div class="nk-block-head">
                  <div class="nk-block-head-content">
                    <div class="nk-block-head-sub">
                      <span>Account Setting</span>
                    </div>
                    <h2 class="nk-block-title fw-normal">Update Profile</h2>

                  </div>
                </div>
                <!-- .nk-block-head -->
                <ul class="nk-nav nav nav-tabs">
                  {{--<li class="nav-item">
                    <a class="nav-link" href="{{ route('profile', [$user_object->unique_id]) }}"
                      >Personal</a
                    >
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('edit-profile', [$user_object->unique_id]) }}"
                      >Update Profile</a
                    >
                  </li>
                   <li class="nav-item">
                    <a
                      class="nav-link"
                      href="html/crypto/profile-notification.html"
                      >Notifications</a
                    >
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      href="html/crypto/profile-connected.html"
                      >Connect Social</a
                    >
                  </li> --}}
                </ul>
                <!-- .nk-menu -->
                <!-- NK-Block @s -->
                <div class="nk-block">
                    <form action="{{ route('update-profile', [$user_object->unique_id]) }}" method="POST">
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

                  <div class="nk-block-head">
                    <div class="nk-block-head-content">
                      <h5 class="nk-block-title">Personal Information</h5>
                      <div class="nk-block-des">
                        <p>
                          Basic info, like your name and phone Number, that you use
                          on Nio Platform.
                        </p>
                      </div>
                    </div>
                  </div>
                  <!-- .nk-block-head -->
                  <div class="nk-data data-list">
                    <div class="data-head">
                      <h6 class="overline-title">Basics</h6>
                    </div>
                    <div
                      class="data-item"
                      data-toggle="modal"
                      data-target="#profile-edit"
                    >
                      <div class="data-col">
                        <span class="data-label">Full Name</span>
                        <span class="data-value">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ $user_object->name }}" name="name" id="name" placeholder="Enter your fullname" />
                                @error('name')
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
                        <span class="data-label">Email</span>
                        <span class="data-value">{{ $user_object->email }}</span>
                      </div>
                      <div class="data-col data-col-end">
                        <span class="data-more disable"
                          ><em class="icon ni ni-lock-alt"></em
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
                        <span class="data-label">Phone Number</span>
                        <span class="data-value text-soft">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror" value="{{ $user_object->phone }}" name="phone" id="phone" placeholder="Enter new phone number" />
                                @error('phone')
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
                        <button type="submit" class="btn btn-block btn-lg btn-primary">Update Profile</button>
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
