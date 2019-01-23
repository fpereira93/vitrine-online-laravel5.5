<!DOCTYPE html>

<html lang="en">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-123175748-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag(){
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-123175748-1');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vitrine online | Ateliê Maria Modas">
    <meta name="author" content="Filipe de Morais Pereira">
    <meta name="base-url" content="{{ url('/') }}">

    <title>Ateliê - Maria Modas</title>

    <!-- default css -->
    <link href="{{ asset('showcase/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/responsive.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/toastr/toastr.min.css') }}">

    @yield('head')

</head><!--/head-->

<body id="body">

    <!-- Loading -->
    <div data-bind="allowBindings: false" id="custom-loading">
        <div
            id="loader-wrapper"
            data-bind="
                style:{
                    opacity: $data.display() ? '1' : '0',
                    visibility: $data.display() ? 'visible' : 'hidden'
                }">

            <div id="loader"></div>
            <div class="loader-section loader-section-left"></div>
            <div class="loader-section loader-section-right"></div>

            <ul data-bind="foreach: $data.arrayMessage">
                <li data-bind="text: $data.message, visible: $data.message"></li>
            </ul>
        </div>
    </div>

    <header id="header" class="green-background"><!--header-->
        @yield('header-inside')
    </header>

    @yield('header-below')

    <section>
        <div class="container" data-bind="allowBindings: false" id="container-view">
            <div class="row">
                @yield('container')
            </div>
        </div>
    </section>

    <footer id="footer"><!--Footer-->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="companyinfo">
                            <h2>Ateliê - <span>Maria Modas</span></h2>

                            <p>Criação de vestidos sobre medida e roupas da moda.</p>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="video-gallery text-center">
                            <a href="#">
                                <div class="iframe-img">
                                    <img src="" alt="" />
                                </div>
                                <div class="overlay-icon">
                                    <i class="fa fa-play-circle-o"></i>
                                </div>
                            </a>
                            <p>Circle of Hands</p>
                            <h2>24 DEC 2014</h2>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="contact">
                            <div class="col-sm-4 mt-5 mb-5">
                                <span><img src="/showcase/images/contact/Whatsapp_37229.png" /> (19) 99688-3395</span>
                            </div>
                            <div class="col-sm-4 mt-5 mb-5">
                                <span><img src="/showcase/images/contact/telephone.png" /> (19) 3565-3729</span>
                            </div>
                            <div class="col-sm-4 mt-5 mb-5">
                                <a target="_blank" href="https://www.facebook.com/Maria.Modas2010/">
                                    <span><img src="/showcase/images/contact/facebook.png" /> facebook</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <p class="pull-left">Copyright © 2018 Ateliê | <span>Maria Modas</span>. Todos os direitos Reservdos.</p>
                    <p class="pull-right">Desenvolvido por <span><a class="developer-info" target="_blank">Filipe Pereira | whatsapp (19) 9 8280-4952</a></span></p>
                </div>
            </div>
        </div>

    </footer><!--/Footer-->

    <!--default script js -->
    <script src="{{ asset('showcase/js/jquery.js') }}"></script>
    <script src="{{ asset('showcase/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/global-functions.js') }}"></script>

    <script src="{{ asset('js/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/knockout/knockout-min.js') }}"></script>
    <script src="{{ asset('js/libraries/hero.js') }}"></script>
    <script src="{{ asset('js/libraries/loading.js') }}"></script>
    <script src="{{ asset('js/libraries/routes.js') }}"></script>
    <script src="{{ asset('js/libraries/file.js') }}"></script>
    <script src="{{ asset('js/libraries/confirmation.js') }}"></script>

    <script src="{{ asset('showcase/js/base.js') }}"></script>

    @yield('script-footer')

</body>
</html>