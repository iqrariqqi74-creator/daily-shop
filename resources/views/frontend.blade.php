<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>E-C My-Shop</title>

    <meta
        name="description"
        content="E-C My-Shop - Online Shopping"
    >

    <meta
        name="keywords"
        content="ecommerce, shopping, products, online store"
    >

    {{-- =========================
        Frontend CSS
    ========================== --}}

    <link
        href="{{ asset('frontend/css/font-awesome.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('frontend/css/bootstrap.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('frontend/css/jquery.smartmenus.bootstrap.css') }}"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('frontend/css/jquery.simpleLens.css') }}"
    >

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('frontend/css/slick.css') }}"
    >

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('frontend/css/nouislider.css') }}"
    >

    <link
        id="switcher"
        href="{{ asset('frontend/css/theme-color/default-theme.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('frontend/css/sequence-theme.modern-slide-in.css') }}"
        rel="stylesheet"
        media="all"
    >

    <link
        href="{{ asset('frontend/css/style.css') }}"
        rel="stylesheet"
    >

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css?family=Lato"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css?family=Raleway"
        rel="stylesheet"
    >

    {{-- Vue/Vite --}}
    @vite([
        'resources/css/frontend/app.css',
        'resources/js/frontend/app.js'
    ])
</head>

<body class="productPage">

    {{-- Vue App --}}
    <div id="app"></div>


    {{-- =========================
        Loader
    ========================== --}}

    <div id="wpf-loader-two">
        <div class="wpf-loader-two-inner">
            <span>Loading</span>
        </div>
    </div>


    {{-- =========================
        Scroll To Top
    ========================== --}}

    <a
        class="scrollToTop"
        href="#"
    >
        <i class="fa fa-chevron-up"></i>
    </a>


    {{-- =========================
        Preloader
    ========================== --}}

    <div id="preloader"></div>


    {{-- =========================
        Frontend JS
    ========================== --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script src="{{ asset('frontend/js/bootstrap.js') }}"></script>

    <script
        type="text/javascript"
        src="{{ asset('frontend/js/jquery.smartmenus.js') }}"
    ></script>

    <script
        type="text/javascript"
        src="{{ asset('frontend/js/jquery.smartmenus.bootstrap.js') }}"
    ></script>

    <script
        src="{{ asset('frontend/js/sequence.js') }}"
    ></script>

    <script
        src="{{ asset('frontend/js/sequence-theme.modern-slide-in.js') }}"
    ></script>

    <script
        type="text/javascript"
        src="{{ asset('frontend/js/jquery.simpleGallery.js') }}"
    ></script>

    <script
        type="text/javascript"
        src="{{ asset('frontend/js/jquery.simpleLens.js') }}"
    ></script>

    <script
        type="text/javascript"
        src="{{ asset('frontend/js/slick.js') }}"
    ></script>

    <script
        type="text/javascript"
        src="{{ asset('frontend/js/nouislider.js') }}"
    ></script>

    <script
        src="{{ asset('frontend/js/custom.js') }}"
    ></script>

</body>

</html>
