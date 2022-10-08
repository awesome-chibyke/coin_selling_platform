<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ env('APP_NAME') }} | {{ @$titleDescription }}</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-social/bootstrap-social.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/bundles/summernote/summernote-bs4.css') }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets_old/logo/sell_data_logo_icon.png') }}" />

  <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <!--m own inserted css-->
  <link rel="stylesheet" href="{{ asset('assets/css/my_custom_css.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">

      @include('layouts.header_logged')

      @include('layouts.sidebar_logged')
      <!-- Main Content -->

      @yield('content')

      @include('layouts.footer_logged')

    </div>
  </div>
  @include('sweetalert::alert')

  <!-- General JS Scripts -->
  <script src="{{ asset('assets/js/app.min.js') }}"></script>
  <!-- JS Libraies -->
  <script src="{{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
  <!-- Page Specific JS File -->
  <script src="{{ asset('assets/js/page/index.js') }}"></script>
  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <!-- Custom JS File -->
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  <script src="{{ asset('assets/bundles/summernote/summernote-bs4.js') }}"></script>

  <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Page Specific JS File -->
  <script src="{{ asset('assets/js/page/datatables.js') }}"></script>

  {{-- validotor js --}}
  <script src="{{ asset('assets/js/validatorjs.js') }}"></script>

  <script src="{{ asset('assets/bundles/chartjs/chart.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/jquery.sparkline.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/jqvmap/dist/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
  <script src="{{ asset('assets/bundles/jqvmap/dist/maps/jquery.vmap.indonesia.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/momentjs.js') }}"></script>

  <!-- Page Specific JS File -->
  <script src="assets/js/page/widget-chart.js"></script>

@if (auth()->check())
{{-- display the validations --}}
  @include('js_files.important_js_modules.request_manager')
  @include('js_files.important_js_modules.validate_module')
  @include('js_files.important_js_modules.clipboard_js')
  @include('js_files.important_js_modules.update_button_status')
  @include('js_files.important_js_modules.pusher')
  @include('js_files.important_js_modules.live_chat')

{{-- pages for js --}}
@php $currentPageName = Request::segment(1); @endphp

  <!--create investment portfolio -->
@php $createPaymentPage = ['create-payment']; @endphp
@if(in_array($currentPageName, $createPaymentPage))
@include('js_files.deposit_coin_js')
@endif

@php $createCoinConversionRate = ['create-coin-converstion-rate']; @endphp
@if(in_array($currentPageName, $createCoinConversionRate))
@include('js_files.create_coin_conversion_rate_js')
@endif

@php $userManagerPage = ['list-of-users']; @endphp
@if(in_array($currentPageName, $userManagerPage))
    @include('js_files.user_level_manager_js')
@endif

@php $paymentHistoryPage = ['payment-history']; @endphp
@if(in_array($currentPageName, $paymentHistoryPage))
@include('js_files.payment_history_js')
@endif

@php $sendMailPage = ['send-mail']; @endphp
@if(in_array($currentPageName, $sendMailPage))
@include('js_files.send_mail_js')
@endif

@php $dashboardPage = ['home']; @endphp
@if(in_array($currentPageName, $dashboardPage))
@include('js_files.dashboard_js')
@endif

@php $bankPage = ['create-bank']; @endphp
@if(in_array($currentPageName, $bankPage))
@include('js_files.create_bank_js')
@endif


@endif

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>
