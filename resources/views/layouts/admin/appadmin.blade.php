<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>@yield('title') | Haldinfoods</title>

  <meta name="description" content="" />

    <!-- Open Graph Meta -->
    <!-- <meta property="og:title" content="Codebase - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Codebase">
    <meta property="og:description" content="Codebase - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content=""> -->

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/favicon.ico')}}"/>
    <!-- END Icons -->

    <!-- Stylesheets -->

    <!-- Fonts and framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/adminnew/css/codebase.min.css') }}" >

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
    <link rel="stylesheet" id="css-theme" href="{{ asset('assets/adminnew/css/themes/pulse.min.css') }}">
    
    <!-- END Stylesheets -->
    @yield('css')
    @stack('additional-css')
    
  </head>
  <body>
  
    <div id="page-container" class="sidebar-o sidebar-dark sidebar-mini enable-page-overlay side-scroll page-header-fixed main-content-boxed">

      <!-- Side Overlay-->
  
      <!-- END Side Overlay -->

      <!-- Sidebar -->
      @include('layouts.admin.sidebaradmin')
      <!-- END Sidebar -->

      <!-- Header -->
      @include('layouts.admin.navbaradmin')
      <!-- END Header -->

      <!-- Main Container -->
      <main id="main-container">
        @yield('content')
      </main>
      <!-- END Main Container -->

      <!-- Footer -->
      <footer id="page-footer">
        <div class="content py-3">
          <div class="row fs-sm">
            <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
              Crafted with <i class="fa fa-heart text-danger"></i> by <a class="fw-semibold"  target="_blank">Christian Agus-Haldin</a>
            </div>
            <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
              <a class="fw-semibold"  target="_blank">Christian Agus Purwanto</a> &copy; <span data-toggle="year-copy"></span>
            </div>
          </div>
        </div>
      </footer>
      <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!--
        Codebase JS

        Core libraries and functionality
        webpack is putting everything together at assets/_js/main/app.js
    -->
    <script data-pagespeed-no-defer src="{{ asset('assets/adminnew/js/codebase.app.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script data-pagespeed-no-defer src="{{ asset('assets/adminnew/js/plugins/chart.js/chart.min.js') }}"></script>

    <!-- Page JS Code -->
    <script data-pagespeed-no-defer src="{{ asset('assets/adminnew/js/pages/db_pop.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/adminnew/js/lib/jquery.min.js') }}"></script>


    @yield('script')
    @stack('additional-js')

  </body>
</html>