<?php
require_once('lib.php');
$title = 'Register | Convert your cryptocurrencies to Naira at an affordable rate';
$description = 'Sell and convert different cryptocurrencies to Naira. We offer the most competitive prices and speeds of transaction.';
$keyword = 'Crypto, Crypto exchange, Naira, bank account, Sell Bitcoin, Sell Crypto, Sell USDT, Sell ETH';
require_once('head.php');?>
    <body class="page-template page-template-page-templates page-template-template-full-width-page-without-header-title page-template-page-templatestemplate-full-width-page-without-header-title-php page page-id-15309 theme-toka woocommerce-js elementor-default elementor-kit-11 elementor-page elementor-page-15309 e--ua-firefox" data-elementor-device-mode="desktop">

<?php require_once('header.php');?>

    <main style="background: url(img/bg-rypto.png);" id="site-content" class="flex-grow-1 nav-black-desktop" role="main">
        <article class="post-15309 page type-page status-publish hentry" id="post-15309">
            <div class="post-inner">
                <header class="entry-header header-group">
                    <div class="entry-header-inner"></div>
                </header>

                <div class="entry-content clearfix m-2 elementor-top-section elementor-element elementor-element-6f96d79 elementor-section-height-min-height elementor-reverse-tablet elementor-reverse-mobile elementor-section-boxed elementor-section-height-default elementor-section-items-middle">
                    <div id="addtop"></div>
                    <div class="elementor-container elementor-column-gap-default">

                        <div class="elementor-column elementor-inner-column elementor-element elementor-element-c9e11d0">
                            <div class="elementor-widget-wrap elementor-element-populated">

                                <div class="elementor-element elementor-element-6cda226 elementor-widget elementor-widget-text-editor">
                                    <div class="elementor-widget-container" style="text-align: center !important;">
                                        <img src="asset/image-1382.png" alt="first offer" width="22">
                                    </div>
                                </div>

                                <div style="text-align: center !important;" class="elementor-element elementor-element-9b60ea7 elementor-widget elementor-widget-text-editor">
                                    <div class="elementor-widget-container" style="font-size: 30px;">
                                        Create Account
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="woocommerce"><div class="woocommerce-notices-wrapper"></div>
                        <form enctype="multipart/form-data" class="woocommerce-form woocommerce-form-login login" method="post">
                            <h5 class="mt-0 mb-3 h5-styled">Register</h5>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="email">
                                    Email address&nbsp;<span class="required">*</span>
                                </label>
                                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="email" autocomplete="email" value="">
                            </p>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="fullname">
                                    Full Name&nbsp;<span class="required">*</span>
                                </label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="fullname" id="fullname" autocomplete="fullname" value="">
                            </p>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="username">
                                    Phone Number&nbsp;<span class="required">*</span>
                                </label>
                                <input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="phone" id="phone" autocomplete="phone" value="">
                            </p>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="password">
                                    Password&nbsp;<span class="required">*</span>
                                </label>
                                <span class="password-input">
                                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="myInput" autocomplete="current-password">
                                </span>
                                <span id="eye" onclick="myFunction()" class="fa fa-eye-slash" style="margin-top: 35px; z-index: 1000; margin-right: 10px; position: absolute; right: 10%;"></span>
                            </p>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="cpassword">
                                    Comfirm Password&nbsp;<span class="required">*</span>
                                </label>
                                <span class="password-input">
                                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="cpassword" id="myInput2" autocomplete="current-password">
                                </span>
                                <span id="eye2" onclick="myFunction2()" class="fa fa-eye-slash" style="margin-top: 35px; z-index: 1000; margin-right: 10px; position: absolute; right: 10%;"></span>
                            </p>

                            <div class="form-row woo-bottom-f-row">
                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever"> <span>I accept <a href="terms">Terms and Conditions</a></span>
                                </label>
                                <div class="login-btn">
                                    <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="register" value="Create Account">Create Account</button>
                                </div>
                            </div>
                            <p class="woocommerce-LostPassword lost_password">
                                <a class="text-primary" href="login">Already have an account? <u>Log in</u></a>
                            </p>
                        </form>
                    </div>
                </div>



            </div>
            <div class="section-inner clearfix"></div>
        </article>
    </main>

<?php require_once('footer.php');?>