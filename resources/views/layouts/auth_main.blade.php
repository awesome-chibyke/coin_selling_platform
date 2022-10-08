<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('assets_old/logo/sell_data_logo_icon.png') }}" />
    <!-- Page Title  -->
    <title>{{ env('APP_NAME') }} | {{ $pageDescription }}</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets_old/css/dashlite.css?ver=2.2.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets_old/css/theme.css?ver=2.2.0') }}">
</head>
<body class="nk-body bg-white npc-general pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">

                    @yield('content')

                    <!--footer area -->
                    @include('layouts.footer_auth')
                    <!--footer area -->

                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
</body>
    <!-- JavaScript -->
    <script src="{{ asset('assets_old/js/bundle.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets_old/js/scripts.js?ver=2.2.0') }}"></script>

{{-- pages for js --}}
@php $currentPageName = Request::segment(1); @endphp

@php $registerPage = ['register']; @endphp
@if(in_array($currentPageName, $registerPage))
    @include('js_files.register_js')
@endif

@php $unsubscribePage = ['unsubscribe']; @endphp
@if(in_array($currentPageName, $unsubscribePage))
    @include('js_files.select_checker_js')
@endif

</html>
