<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Haldinfoods Template">
    <meta name="keywords" content="Haldinfoods">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | Haldinfoods</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/favicon.ico')}}"/>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" type="text/css">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}" type="text/css"> --}}

    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
    integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>

    <link rel="stylesheet" href="{{ asset('assets/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-mobile.css') }}" type="text/css">
    @yield('css')
    <style>
        /* .sticky {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: var(--NN0,#FFFFFF);
            z-index: 999;
            border-bottom: 1px solid var(--NN50,#F0F3F7);
            height: 121px;
        } */
        .sticky + .categories {
            padding-top: 102px;
        }
    </style>
</head>

<body>
    @include('layouts.sales.header')
    @yield('content')


    <!-- Js Plugins -->
    <script data-pagespeed-no-defer src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/js/jquery.slicknav.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/js/mixitup.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/js/main.js') }}"></script>
    @yield('script')
    <script>
        window.onscroll = function() {
            myFunction()
        };
        var header = document.getElementById("myHeader");
        var sticky = header.offsetTop;
        function myFunction() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
        }
    </script>


</body>

</html>
