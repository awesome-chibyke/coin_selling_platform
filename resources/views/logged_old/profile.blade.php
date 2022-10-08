@php $titleDescription = 'User Profile' @endphp
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
                    <h2 class="nk-block-title fw-normal">My Profile</h2>

                  </div>
                </div>
                <!-- .nk-block-head -->
                <ul class="nk-nav nav nav-tabs">

                </ul>
                <!-- .nk-menu -->
                <!-- NK-Block @s -->
                <div class="nk-block">
                  {{-- <div class="alert alert-warning">
                    <div class="alert-cta flex-wrap flex-md-nowrap">
                      <div class="alert-text">
                        <p>
                          Upgrade your account to unlock full feature and
                          increase your limit of transaction amount.
                        </p>
                      </div>
                      <ul class="alert-actions gx-3 mt-3 mb-1 my-md-0">
                        <li class="order-md-last">
                          <a href="#" class="btn btn-sm btn-warning">Upgrade</a>
                        </li>
                        <li>
                          <a href="#" class="link link-primary">Learn More</a>
                        </li>
                      </ul>
                    </div>
                  </div> --}}
                  <!-- .alert -->
                  <div class="nk-block-head">
                    <div class="nk-block-head-content">
                      <h5 class="nk-block-title">Personal Information</h5>
                      <div class="nk-block-des">
                        <p>
                          Basic info, like your name and address, that you use
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
                        <span class="data-value">{{ $user_object->name }}</span>
                      </div>
                      <div class="data-col data-col-end">
                        <span class="data-more"
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
                        <span class="data-label">Referal ID</span>
                        <span class="data-value">{{ $user_object->username }}</span>
                      </div>
                      <div class="data-col data-col-end">
                        <span class="data-more"
                          ><em class="icon ni ni-forward-ios"></em
                        ></span>
                      </div>
                    </div>
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
                        <span class="data-value text-soft">{{ $user_object->phone }}</span>
                      </div>
                      <div class="data-col data-col-end">
                        <span class="data-more"
                          ><em class="icon ni ni-forward-ios"></em
                        ></span>
                      </div>
                    </div>
                    <!-- .data-item -->
                    {{-- <div
                      class="data-item"
                      data-toggle="modal"
                      data-target="#profile-edit"
                    >
                      <div class="data-col">
                        <span class="data-label">Date of Birth</span>
                        <span class="data-value">29 Feb, 1986</span>
                      </div>
                      <div class="data-col data-col-end">
                        <span class="data-more"
                          ><em class="icon ni ni-forward-ios"></em
                        ></span>
                      </div>
                    </div> --}}
                    <!-- .data-item -->
                    {{-- <div
                      class="data-item"
                      data-toggle="modal"
                      data-target="#profile-edit"
                      data-tab-target="#address"
                    >
                      <div class="data-col">
                        <span class="data-label">Address</span>
                        <span class="data-value"
                          >2337 Kildeer Drive,<br />Kentucky, Canada</span
                        >
                      </div>
                      <div class="data-col data-col-end">
                        <span class="data-more"
                          ><em class="icon ni ni-forward-ios"></em
                        ></span>
                      </div>
                    </div> --}}
                    <!-- .data-item -->
                  </div>
                  <!-- .nk-data -->
                  {{-- <div class="nk-data data-list">
                    <div class="data-head">
                      <h6 class="overline-title">Preferences</h6>
                    </div>
                    <div class="data-item">
                      <div class="data-col">
                        <span class="data-label">Language</span>
                        <span class="data-value">English (United State)</span>
                      </div>
                      <div class="data-col data-col-end">
                        <a
                          href="#"
                          data-toggle="modal"
                          data-target="#profile-language"
                          class="link link-primary"
                          >Change Language</a
                        >
                      </div>
                    </div>
                    <!-- .data-item -->
                    <div class="data-item">
                      <div class="data-col">
                        <span class="data-label">Date Format</span>
                        <span class="data-value">M d, YYYY</span>
                      </div>
                      <div class="data-col data-col-end">
                        <a
                          href="#"
                          data-toggle="modal"
                          data-target="#profile-language"
                          class="link link-primary"
                          >Change</a
                        >
                      </div>
                    </div>
                    <!-- .data-item -->
                    <div class="data-item">
                      <div class="data-col">
                        <span class="data-label">Timezone</span>
                        <span class="data-value">Bangladesh (GMT +6)</span>
                      </div>
                      <div class="data-col data-col-end">
                        <a
                          href="#"
                          data-toggle="modal"
                          data-target="#profile-language"
                          class="link link-primary"
                          >Change</a
                        >
                      </div>
                    </div>
                    <!-- .data-item -->
                  </div> --}}
                  <!-- .nk-data -->
                </div>
                <!-- NK-Block @e -->
                <!-- //  Content End -->
              </div>
            </div>
          </div>
          <!-- content @e -->
          @endsection
