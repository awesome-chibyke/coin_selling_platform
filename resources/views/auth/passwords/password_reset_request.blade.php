@php $pageDescription = 'login to your account' @endphp
@php $pageDescription = 'Create a new account' @endphp
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
                <h4 class="nk-block-title">Initiate Reset Password Request</h4>
                <div class="nk-block-des">
                    <p>Provide your email addess to continue</p>
                </div>
            </div>
        </div>
        <form action="{{ route('send-password-reset-token') }}" method="POST">
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
                <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" id="email" placeholder="Enter your email address or username">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <button class="btn btn-lg btn-primary btn-block">Send Mail</button>
            </div>
        </form>
        <div class="form-note-s2 text-center pt-4"><a href="{{ route('login') }}"><strong>login</strong></a>
        </div>

    </div>
</div>
</div>
@endsection
