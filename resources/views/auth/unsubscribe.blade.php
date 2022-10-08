@php $pageDescription = 'login to your account' @endphp
@php $pageDescription = 'Create a new account' @endphp
@extends('layouts.auth_main')

@section('content')
<div class="nk-block nk-block-middle nk-auth-body wide-xs">
<div class="brand-logo pb-4 text-center">
    <a href="html/index.html" class="logo-link">
        <img class="logo-light logo-img logo-img-lg" src="{{ asset('assets/logo/sell_data_logo_main.png') }}" srcset="{{ asset('assets/logo/sell_data_logo_main.png') }} 2x" alt="logo" />
        <img class="logo-dark logo-img logo-img-lg" src="{{ asset('assets/logo/sell_data_logo_main.png') }}" srcset="{{ asset('assets/logo/sell_data_logo_main.png') }} 2x" alt="logo-dark">
    </a>
</div>
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Unsubscribe From Our Mailing List</h4>
            </div>
        </div>
        <form action="{{ route('store-unsubscribe', [$user_unique_id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <div class="col-sm-12">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
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
                <div class="text-left">Please State why you want to unsubscribe from our mailing list</div>

                @if(count($reasons) > 0)

                    @foreach ($reasons as $k => $reason)
                    <div class="border-2 p-2" for="unsubscribe_reason{{ $k }}">
                        <input type="radio" class="@error('unsubscribe_reason') is-invalid @enderror select_checker" name="unsubscribe_reason" id="unsubscribe_reason{{ $k }}" value="{{$reason}}" />
                        {{ $reason }}
                        @if ($k == count($reasons)-1)
                            @error('unsubscribe_reason')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        @endif

                    </div>
                    @endforeach
                @endif

            </div>

            <div class="form-group" id="unsubscribe_custom_holder" @error('unsubscribe_custom_reason') {{ '' }} @else {{ 'hidden' }} @enderror>
                <label class="form-label" for="unsubscribe_custom_reason">Custom Reason</label>
                <div class="form-control-wrap">
                    <textarea class="form-control form-control-lg @error('unsubscribe_custom_reason') is-invalid @enderror" name="unsubscribe_custom_reason" id="unsubscribe_custom_reason"></textarea>
                    @error('unsubscribe_custom_reason')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-lg btn-primary btn-block">Submit</button>
            </div>
        </form>
        <div class="form-note-s2 text-center pt-4"><a href="./"><strong>Home</strong></a>
        </div>

    </div>
</div>
</div>
@endsection
