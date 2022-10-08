@php $pageDescription = 'Create a new account' @endphp
@extends('layouts.auth_main')

@section('content')
<div class="nk-block nk-block-middle nk-auth-body wide-xs">
                        <div class="brand-logo pb-4 text-center">
                            <a href="./" class="logo-link">
                                <img class="logo-light logo-img logo-img-lg" src="{{ asset('assets/logo/sell_data_logo_main.png') }}" srcset="{{ asset('assets/logo/sell_data_logo_main.png') }} 2x" alt="{{ env('APP_NAME') }}" />
                                <img class="logo-dark logo-img logo-img-lg" src="{{ asset('assets/logo/sell_data_logo_main.png') }}" srcset="{{ asset('assets/logo/sell_data_logo_main.png') }} 2x" alt="{{ env('APP_NAME') }}">
                            </a>
                        </div>
                        <div class="card card-bordered">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">Register </h4>
                                        <div class="nk-block-des">
                                            <p>Create New Account</p>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('register') }}" id="register_form" method="POST" >
                                    @csrf
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            @if (session('status'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('status') }}
                                                </div>
                                            @endif
                                            @if (session('error'))
                                                <div class="alert alert-warning" role="alert">
                                                    {{ session('error') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"  name="name" id="name" placeholder="Enter your name" value="{{ old('name') }}" />
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" name="email" placeholder="Enter your email address or username">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="phone">Phone Number</label>
                                        <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Enter Phone Number" value="{{ old('phone') }}" />
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="referrer_username">Referal User ID <span class="text-danger">(Optional)</span> </label>
                                        <input type="text" class="form-control form-control-lg @error('referrer_username') is-invalid @enderror" name="referrer_username" id="referrer_username" value="{{ $referrer_username }}" />
                                        @error('referrer_username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>



                                    <div class="form-group">
                                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password"  id="password" placeholder="Enter your passcode" />
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-control-xs custom-checkbox">
                                            <input type="checkbox" class="custom-control-input @error('check_thick') is-invalid @enderror" value="confirmed" name="check_thick" id="checkbox">
                                            <label class="custom-control-label" for="checkbox">I agree to Dashlite <a href="#">Privacy Policy</a> &amp; <a href="#"> Terms.</a></label>

                                            @error('check_thick')
                                                <div class="invalid-feedback" role="alert" style="display:block; width:100%;">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit"  class="btn btn-lg btn-primary btn-block">Register</button>
                                    </div>
                                </form>
                                <div class="form-note-s2 text-center pt-4"> Already have an account? <a href="{{ route('login') }}"><strong>Sign in instead</strong></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endsection
