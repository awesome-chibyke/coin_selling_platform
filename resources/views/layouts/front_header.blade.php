<body
    class="page-template page-template-page-templates page-template-template-full-width-page-without-header-title page-template-page-templatestemplate-full-width-page-without-header-title-php page page-id-15309 theme-toka woocommerce-js elementor-default elementor-kit-11 elementor-page elementor-page-15309 e--ua-firefox"
    data-elementor-device-mode="desktop">

<div style="width: 100%; padding-top: 5px; background-color: #000;">

    <marquee direction="center" onmouseover="this.stop()" onmouseout="this.start()">
        <ul style="display: inline-flex; padding: 0px; margin: 0px; list-style: none; color: #fff;">
            @if (count($conversion_rate) > 0)
                @foreach ($conversion_rate as $rate)
                    <li style="margin-right: 30px;">{{ $rate->coin_name }} ({{ $rate->unique_id }}) @ {{ $rate->rate_in_local_currency ?? null }}/$</li>
                @endforeach
            @endif
        </ul>
    </marquee>
</div>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;"><defs><filter id="wp-duotone-dark-grayscale"><feColorMatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 "></feColorMatrix><feComponentTransfer color-interpolation-filters="sRGB"><feFuncR type="table" tableValues="0 0.49803921568627"></feFuncR><feFuncG type="table" tableValues="0 0.49803921568627"></feFuncG><feFuncB type="table" tableValues="0 0.49803921568627"></feFuncB><feFuncA type="table" tableValues="1 1"></feFuncA></feComponentTransfer><feComposite in2="SourceGraphic" operator="in"></feComposite></filter></defs></svg>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;"><defs><filter id="wp-duotone-grayscale"><feColorMatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 "></feColorMatrix><feComponentTransfer color-interpolation-filters="sRGB"><feFuncR type="table" tableValues="0 1"></feFuncR><feFuncG type="table" tableValues="0 1"></feFuncG><feFuncB type="table" tableValues="0 1"></feFuncB><feFuncA type="table" tableValues="1 1"></feFuncA></feComponentTransfer><feComposite in2="SourceGraphic" operator="in"></feComposite></filter></defs></svg>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;"><defs><filter id="wp-duotone-purple-yellow"><feColorMatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 "></feColorMatrix><feComponentTransfer color-interpolation-filters="sRGB"><feFuncR type="table" tableValues="0.54901960784314 0.98823529411765"></feFuncR><feFuncG type="table" tableValues="0 1"></feFuncG><feFuncB type="table" tableValues="0.71764705882353 0.25490196078431"></feFuncB><feFuncA type="table" tableValues="1 1"></feFuncA></feComponentTransfer><feComposite in2="SourceGraphic" operator="in"></feComposite></filter></defs></svg>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;"><defs><filter id="wp-duotone-blue-red"><feColorMatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 "></feColorMatrix><feComponentTransfer color-interpolation-filters="sRGB"><feFuncR type="table" tableValues="0 1"></feFuncR><feFuncG type="table" tableValues="0 0.27843137254902"></feFuncG><feFuncB type="table" tableValues="0.5921568627451 0.27843137254902"></feFuncB><feFuncA type="table" tableValues="1 1"></feFuncA></feComponentTransfer><feComposite in2="SourceGraphic" operator="in"></feComposite></filter></defs></svg>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;"><defs><filter id="wp-duotone-midnight"><feColorMatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 "></feColorMatrix><feComponentTransfer color-interpolation-filters="sRGB"><feFuncR type="table" tableValues="0 0"></feFuncR><feFuncG type="table" tableValues="0 0.64705882352941"></feFuncG><feFuncB type="table" tableValues="0 1"></feFuncB><feFuncA type="table" tableValues="1 1"></feFuncA></feComponentTransfer><feComposite in2="SourceGraphic" operator="in"></feComposite></filter></defs></svg>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;"><defs><filter id="wp-duotone-magenta-yellow"><feColorMatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 "></feColorMatrix><feComponentTransfer color-interpolation-filters="sRGB"><feFuncR type="table" tableValues="0.78039215686275 1"></feFuncR><feFuncG type="table" tableValues="0 0.94901960784314"></feFuncG><feFuncB type="table" tableValues="0.35294117647059 0.47058823529412"></feFuncB><feFuncA type="table" tableValues="1 1"></feFuncA></feComponentTransfer><feComposite in2="SourceGraphic" operator="in"></feComposite></filter></defs></svg>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;"><defs><filter id="wp-duotone-purple-green"><feColorMatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 "></feColorMatrix><feComponentTransfer color-interpolation-filters="sRGB"><feFuncR type="table" tableValues="0.65098039215686 0.40392156862745"></feFuncR><feFuncG type="table" tableValues="0 1"></feFuncG><feFuncB type="table" tableValues="0.44705882352941 0.4"></feFuncB><feFuncA type="table" tableValues="1 1"></feFuncA></feComponentTransfer><feComposite in2="SourceGraphic" operator="in"></feComposite></filter></defs></svg>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;"><defs><filter id="wp-duotone-blue-orange"><feColorMatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 "></feColorMatrix><feComponentTransfer color-interpolation-filters="sRGB"><feFuncR type="table" tableValues="0.098039215686275 1"></feFuncR><feFuncG type="table" tableValues="0 0.66274509803922"></feFuncG><feFuncB type="table" tableValues="0.84705882352941 0.41960784313725"></feFuncB><feFuncA type="table" tableValues="1 1"></feFuncA></feComponentTransfer><feComposite in2="SourceGraphic" operator="in"></feComposite></filter></defs></svg>

<nav style="padding-top: 30px;" id="pr-nav" class="primary-menu navbar navbar-expand-lg navbar-dark nav-black-desktop">

    <div class="container primary-menu-inner ">

            <div class="top-wrap">
            <a class="custom-logo-link" href="./"><h5 class="m-0"><img width="150" src="{{ asset('front/asset/img/dataseller-logo-main.png') }}"></h5></a>            <button id="mobile-toggle" class="navbar-toggler animate-button collapsed" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span id="m-tgl-icon" class="animated-icon1"><span></span><span></span></span>
            </button>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navbarColor01">
            <ul id="primary-menu" class="navbar-nav pl-3" itemscope="">
                <li class="menu-item menu-item-type-custom menu-item-object-custom nav-item">
                    <a href="./#about" class="nav-link"><span>About Us</span></a>
                </li>

                <li class="menu-item menu-item-type-custom menu-item-object-custom nav-item">
                    <a href="./#how-it-works" class="nav-link"><span>How It Works</span></a>
                </li>

                <li class="menu-item menu-item-type-custom menu-item-object-custom nav-item">
                    <a href="./#exchangeTable" class=" nav-link"><span>Price Chart</span></a>
                </li>

                <li class="menu-item menu-item-type-custom menu-item-object-custom nav-item">
                    <a href="./#exchangeTable" class=" nav-link"><span>Rate Calculator</span></a>
                </li>
                <li class="menu-item menu-item-type-custom menu-item-object-custom nav-item">
                    <a href="./#faq" class="nav-link"><span>FAQs</span></a>
                </li>

                <li id="menu-item-1868" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown menu-item-1868 nav-item"><a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link" id="menu-item-dropdown-1868"><span itemprop="name">Accounts</span></a>
                    <ul class="dropdown-menu" aria-labelledby="menu-item-dropdown-1868">
                        @guest
                        <li id="menu-item-1867" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1867 nav-item"><a itemprop="url" href="login" class="dropdown-item"><span itemprop="name">Login</span></a></li>
                        <li id="menu-item-1878" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1878 nav-item"><a itemprop="url" href="register" class="dropdown-item"><span itemprop="name">Register</span></a></li>
                        @else
                        <li id="menu-item-1878" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1878 nav-item"><a itemprop="url" href="{{ route('home') }}" class="dropdown-item"><span itemprop="name">Dashboard</span></a></li>

                        <li id="menu-item-1879" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1879 nav-item"><a itemprop="url" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><span itemprop="name">Logout</span></a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                        @endguest
                    </ul>

                </li>
            </ul>

                <div class="header-icons">
                <div id="magic-search" class="magic-search">
                    <form role="search" method="get" class="search-form" action="https://toka.peerduck.com/">
                        <div class="inner-form">
                            <div class="row justify-content-end">
                                <div class="input-field first justify-content-end" id="first">
                                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="612.01px" height="612.01px" viewBox="0 0 612.01 612.01" xml:space="preserve"><g><path d="M606.209,578.714L448.198,423.228C489.576,378.272,515,318.817,515,253.393C514.98,113.439,399.704,0,257.493,0
				C115.282,0,0.006,113.439,0.006,253.393s115.276,253.393,257.487,253.393c61.445,0,117.801-21.253,162.068-56.586
				l158.624,156.099c7.729,7.614,20.277,7.614,28.006,0C613.938,598.686,613.938,586.328,606.209,578.714z M257.493,467.8
				c-120.326,0-217.869-95.993-217.869-214.407S137.167,38.986,257.493,38.986c120.327,0,217.869,95.993,217.869,214.407
				S377.82,467.8,257.493,467.8z"></path></g>
                    </svg>
                                    <input autocomplete="off" type="search" class="input" id="inputFocus" placeholder="Search" name="s" required="">
                                    <input type="submit" value="Search" class="search-submit">
                                    <input type="hidden" name="post_type" value="product">                    <div class="clear" id="clear">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#6e6e73" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="header-cart-icon">
                    <a class="cart-contents menu-item" href="https://toka.peerduck.com/cart/" title="View your shopping cart">
                        <svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.79942 11.6369H5.80024C5.80093 11.6369 5.80161 11.6367 5.8023 11.6367H15.3633C15.5987 11.6367 15.8056 11.4806 15.8703 11.2543L17.9797 3.87144C18.0251 3.71228 17.9933 3.54117 17.8937 3.40906C17.794 3.27695 17.6381 3.19922 17.4727 3.19922H4.58323L4.20626 1.50279C4.15257 1.26151 3.93861 1.08984 3.69141 1.08984H0.527351C0.236076 1.08984 7.62939e-06 1.32591 7.62939e-06 1.61719C7.62939e-06 1.90846 0.236076 2.14453 0.527351 2.14453H3.26844C3.33518 2.44514 5.0724 10.2627 5.17237 10.7125C4.61193 10.9561 4.21876 11.5149 4.21876 12.1641C4.21876 13.0364 4.92847 13.7461 5.80079 13.7461H15.3633C15.6546 13.7461 15.8906 13.51 15.8906 13.2188C15.8906 12.9275 15.6546 12.6914 15.3633 12.6914H5.80079C5.51006 12.6914 5.27345 12.4548 5.27345 12.1641C5.27345 11.8737 5.50924 11.6375 5.79942 11.6369ZM16.7735 4.25391L14.9654 10.582H6.22376L4.81751 4.25391H16.7735Z"></path>
                            <path d="M5.27342 15.3281C5.27342 16.2004 5.98314 16.9102 6.85545 16.9102C7.72777 16.9102 8.43749 16.2004 8.43749 15.3281C8.43749 14.4558 7.72777 13.7461 6.85545 13.7461C5.98314 13.7461 5.27342 14.4558 5.27342 15.3281ZM6.85545 14.8008C7.14618 14.8008 7.3828 15.0374 7.3828 15.3281C7.3828 15.6189 7.14618 15.8555 6.85545 15.8555C6.56473 15.8555 6.32811 15.6189 6.32811 15.3281C6.32811 15.0374 6.56473 14.8008 6.85545 14.8008Z"></path>
                            <path d="M12.7266 15.3281C12.7266 16.2004 13.4363 16.9102 14.3086 16.9102C15.1809 16.9102 15.8906 16.2004 15.8906 15.3281C15.8906 14.4558 15.1809 13.7461 14.3086 13.7461C13.4363 13.7461 12.7266 14.4558 12.7266 15.3281ZM14.3086 14.8008C14.5993 14.8008 14.8359 15.0374 14.8359 15.3281C14.8359 15.6189 14.5993 15.8555 14.3086 15.8555C14.0179 15.8555 13.7812 15.6189 13.7812 15.3281C13.7812 15.0374 14.0179 14.8008 14.3086 14.8008Z"></path>
                        </svg>
                    </a>

                </div>
            </div>
            <div class="header-cta">
                <a href="login" target="_blank">
                    <div class="d-inline-block elementor-button-link elementor-button elementor-size-md">Fiat Rate {{ $fiat_rate->rate_in_local_currency ?? null }}/$</div>
                </a>
            </div>
            </div>

    </div>
</nav>
