@php $pageDescription = 'Activate your account' @endphp
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
                <h4 class="nk-block-title">Account Activation</h4>
                <div class="nk-block-des">
                    <p>Please provide token to activate account</p>
                </div>
            </div>
        </div>
        <form action="{{ route('activate_account') }}" method="POST">
        @csrf
            <div class="form-group">
                <div class="col-sm-12">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (!session('status'))
                        <div class="alert alert-success" role="alert">
                            Registration was successful, An account activation token was sent to your email. Please provide token to activate account.
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
                <label class="form-label" for="email">Token</label>
                <input type="text" class="form-control form-control-lg @error('code') is-invalid @enderror" name="code" id="code" placeholder="Provide Token" />
                @error('code')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <input type="hidden" value="{{ $user_id }}" name="user_unique_id" />
            <div class="form-group">
                <small><a href="{{ route('resend_activation_code', [$user_id]) }}">Resend Activation Code</a></small>
                <button class="btn btn-lg btn-primary btn-block">Activate Account</button>
            </div>
        </form>
        <div class="form-note-s2 text-center pt-4"><a href="{{ route('login') }}"><strong>login</strong></a>
        </div>

    </div>
</div>
</div>
@endsection
