<!DOCTYPE html>
<html>
    <head>
        <title> {{ env('APP_NAME') }}  </title>

        <!-- metas -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url('/') }}">

        <!-- links -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/skins/skin-green.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/icheck/green.css') }}">
        <link rel="stylesheet" href="{{ asset('css/toastr/toastr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/fullcalendar/fullcalendar.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cropper.min.css') }}">

        <!-- external links -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-green sidebar-mini">
        <div class="wrapper" id="wrapper">

            <!-- Loading -->
            <div id="loader-wrapper" data-bind="style:{opacity: loading.display() ? '1' : '0', visibility: loading.display() ? 'visible' : 'hidden'}">
                <div id="loader"></div>
                <div class="loader-section loader-section-left"></div>
                <div class="loader-section loader-section-right"></div>

                <ul data-bind="foreach: loading.arrayMessage">
                    <li data-bind="text: $data.message" class="tada"></li>
                </ul>
            </div>

            <app-header params='{ onClickLogout : $data.onClickLogout, userData: $data.userData }'></app-header>

            <!-- ko if: $data.loadPage()  -->
            <app-sidebar params='menus: $data.loadPage().menus(), userData: $data.userData'></app-sidebar>
            <!-- /ko -->

            <div class="content-wrapper">
                <div class="content">
                    <div id="template">
                        <!-- content here -->
                    </div>
                </div>
            </div>
            <app-footer></app-footer>
        </div>
    </body>

    <div class="confirmation" style="display:none">
        <div class="header" id="confirmHeader">Cabecalho</div>
        <div class="body" id="confirmBody">Corpo</div>
        <div class="footer" id="confirmFooter"></div>
    </div>

    <!-- scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/moment/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment/locale/pt-br.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/icheck.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/knockout/knockout-min.js') }}"></script>
    <script src="{{ asset('js/knockout/knockout.custom.bindings.js') }}"></script>
    
    <!-- script component -->
    <script src="{{ asset('js/knockout/layout/header.js') }}"></script>
    <script src="{{ asset('js/knockout/layout/footer.js') }}"></script>
    <script src="{{ asset('js/knockout/layout/sidebar.js') }}"></script>
    <script src="{{ asset('js/knockout/layout/custom-components.js') }}"></script>
    <script src="{{ asset('js/knockout/layout/knockout.autocomplete-min.js') }}"></script>

    <script src="{{ asset('js/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/libraries/load-page.js') }}"></script>
    <script src="{{ asset('js/libraries/hero.js') }}"></script>
    <script src="{{ asset('js/libraries/loading.js') }}"></script>
    <script src="{{ asset('js/libraries/routes.js') }}"></script>
    <script src="{{ asset('js/libraries/file.js') }}"></script>
    <script src="{{ asset('js/libraries/confirmation.js') }}"></script>
    <script src="{{ asset('js/libraries/permission.js') }}"></script>
    <script src="{{ asset('js/base.js') }}"></script>

    <script src="{{ asset('js/cropper.min.js') }}"></script>
</html>
