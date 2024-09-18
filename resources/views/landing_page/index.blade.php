<!doctype html>
<html class="no-js" lang="zxx">

@php
    $favicon = settingByUnique('pict_favicon');
@endphp

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="manifest" href="site.webmanifest"> --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($favicon) }}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/slicknav.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/flaticon.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/animate.min.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/slick.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/nice-select.css">
    <link rel="stylesheet" href="{{ asset('theme/landing-page') }}/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine">

    <style>
        .header-pali {
            font-family: "Tangerine", serif;
            font-weight: bold;
            font-style: italic;
            font-size: 5.5cqw !important;
        }
    </style>
</head>

<body>
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="{{ asset($favicon) }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->
    <header>
        <!-- Header Start -->
        <div class="header-area">
            <div class="main-header  header-sticky">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <!-- Logo -->
                        <div class="col-xl-2 col-lg-2 col-md-1">
                            <div class="logo">

                                <a href="index.html"><img src="{{ asset(settingByUnique('pict_logo')) }}"
                                        height="50" alt=""></a>
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <div class="menu-main d-flex align-items-center justify-content-end">
                                <!-- Main-menu -->
                                <div class="main-menu f-right d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="index.html"></a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="header-right-btn f-right d-none d-xl-block ml-20">
                                    <a href="{{ url('login') }}" class="btn header-btn">LOGIN
                                        {{ env('APP_NAME') }}</a>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->
    </header>
    <main>

        <div class="about-me pt-5 pb-3" data-background="{{ asset('img') }}/section_bg04.jpg">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="about-me-img mb-30">
                            <img src="{{ asset(settingByUnique('pict_frontend')) }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <div class="about-me-caption">
                            <h6 class="mb-5">SELAMAT DATANG DI APLIKASI SIEMOP PEMBANGUNAN</h6>
                            <h2 class="header-pali">Pali Serasi Nia</h2>
                            <div class="pb-30">
                                {!! settingByUnique('desc_master_data') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <footer>
        <!-- Footer Start-->
        <div class="footer-area" style="background: #102128 !important">
            <div class="container">
                <div class="footer-top footer-padding">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="footer-top-cap text-center">
                                <img src="{{ asset(settingByUnique('pict_auth_logo')) }}" alt=""
                                    height="100">
                                <p>{{ settingByUnique('company_address') }} </p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="footer-bottom">
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-12 col-lg-12">
                            <div class="text-center">
                                <p style="color: #c5c5dd">
                                    Copyright &copy;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    Pali Serasi Nia
                                </p>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        <!-- Footer End-->
    </footer>
    <!-- Scroll Up -->
    <div id="back-top">
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <!-- JS here -->
    <!-- All JS Custom Plugins Link Here here -->
    <script src="{{ asset('theme/landing-page') }}/js/vendor/modernizr-3.5.0.min.js"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="{{ asset('theme/landing-page') }}/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/popper.min.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/bootstrap.min.js"></script>
    <!-- Jquery Mobile Menu -->
    <script src="{{ asset('theme/landing-page') }}/js/jquery.slicknav.min.js"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="{{ asset('theme/landing-page') }}/js/owl.carousel.min.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/slick.min.js"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="{{ asset('theme/landing-page') }}/js/wow.min.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/animated.headline.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/jquery.magnific-popup.js"></script>

    <!-- Nice-select, sticky -->
    <script src="{{ asset('theme/landing-page') }}/js/jquery.nice-select.min.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/jquery.sticky.js"></script>

    <!-- contact js -->
    <script src="{{ asset('theme/landing-page') }}/js/contact.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/jquery.form.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/jquery.validate.min.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/mail-script.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/jquery.ajaxchimp.min.js"></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="{{ asset('theme/landing-page') }}/js/plugins.js"></script>
    <script src="{{ asset('theme/landing-page') }}/js/main.js"></script>

</body>

</html>
