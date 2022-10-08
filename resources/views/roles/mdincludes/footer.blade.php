{{--@php $app_settings = new \App\Models\AppSettings(); @endphp
@php $settings = $app_settings->getSingleModel(); @endphp--}}

<div class="footer-copyright-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copy-right">
                    <p>Copyright ©  @php $d=date('Y'); print $d; @endphp - {{env('APP_NAME')}} All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>
