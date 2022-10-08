@php $pageDescription = 'login to your account' @endphp
@extends('layouts.auth_main')

@section('content')
<div class="nk-block nk-block-middle nk-auth-body wide-xs">
<div class="brand-logo pb-4 text-center">
    <a href="./" class="logo-link">
        <img class="logo-light logo-img logo-img-lg" src="{{ asset('assets/logo/sell_data_logo_main.png') }}" srcset="{{ asset('assets/logo/sell_data_logo_main.png') }} 2x" alt="logo" />
        <img class="logo-dark logo-img logo-img-lg" src="{{ asset('assets/logo/sell_data_logo_main.png') }}" srcset="{{ asset('assets/logo/sell_data_logo_main.png') }} 2x" alt="logo-dark">
    </a>
</div>
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Sign-In</h4>
                <div class="nk-block-des">
                    <p>Access the {{ env('APP_NAME') }} using your email and passcode</p>
                </div>
            </div>
        </div>
        <form action="{{ route('login') }}" method="POST">
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
                <label class="form-label" for="email">Email Address</label>
                <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" id="email" placeholder="Enter your email address or username" />
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="form-control-wrap">
                    <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your passcode" />
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    {{-- 2022-07-21 14:08:12 --}}
                @enderror
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-control-xs custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="checkbox">Remember Me </label>
                </div>
            </div>
            <div class="form-group">
                <small><a href="{{ route('initiate-password-reset') }}">Forgot Password</a></small>
                <button class="btn btn-lg btn-primary btn-block">Login</button>
            </div>
        </form>
        <div class="form-note-s2 text-center pt-4"> New to our platform? <a href="{{ route('register') }}"><strong>Register</strong></a>
        </div>

    </div>
</div>
</div>
@endsection
