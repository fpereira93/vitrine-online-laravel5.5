    <!DOCTYPE html>
    <html>
    <head>
        <title> {{ env('APP_NAME') }} </title>

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
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- external links -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-box-body">
                <div class="login-logo">
                    <a href="javascript:;">{{ env('APP_NAME') }}</a>
                </div>
                <h4 data-bind="visible:isWaitingRequest">AGUARDE...</h4>

                <!-- ko if: page() == Pages.Login -->
                <p class="login-box-msg">Autenticação necessária</p>
                <form action="#" method="post">
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" data-bind="css: checkIsInvalid('email') ? 'has-error': '', value:email, valueUpdate: 'afterkeydown'" placeholder="E-mail">
                        <span class="form-control-feedback" data-bind="css: checkIsInvalid('email') ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok'"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" data-bind="css: checkIsInvalid('password')? 'has-error': '', value: password, valueUpdate: 'afterkeydown'" placeholder="Senha">
                        <span class="form-control-feedback" data-bind="css: checkIsInvalid('password') ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok'"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-success btn-block btn-flat" data-bind="css: isValid() ? 'ripple' : 'disabled', click:enter">Entrar</button>
                        </div>  
                        <div class="col-xs-12 col-md-6">
                            <a href="javascript:;" data-bind="click:forgotPassword">Esqueci minha senha</a><br>
                        </div>
                    </div>
                </form>
                <!-- /ko -->

                <!-- ko if: page() == Pages.ForgotPassword -->
                <form action="#" method="post">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <a href="javascript:;" class="white-link" data-bind="click:showLogin">Voltar</a><br>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" data-bind="css: checkIsInvalid('email') ? 'has-error': '', value:email, valueUpdate: 'afterkeydown'" placeholder="E-mail">
                        <span class="form-control-feedback" data-bind="css: checkIsInvalid('email') ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok'"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-success btn-block btn-flat" data-bind="css: checkIsInvalid('email') ? 'disabled' : 'ripple'">Enviar senha por email</button>
                        </div>  
                    </div>
                </form>
                <!-- /ko -->
            </div>
        </div>
    </body>

    <!-- scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/moment/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment/locale/pt-br.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/icheck.min.js') }}"></script>
    <script src="{{ asset('js/knockout/knockout-min.js') }}"></script>
    <script src="{{ asset('js/knockout/knockout.custom.bindings.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>

    <!-- script component -->
    <script src="{{ asset('js/knockout/layout/header.js') }}"></script>
    <script src="{{ asset('js/knockout/layout/footer.js') }}"></script>
    <script src="{{ asset('js/knockout/layout/sidebar.js') }}"></script>
    <script src="{{ asset('js/knockout/layout/custom-components.js') }}"></script>

    <script src="{{ asset('js/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/libraries/hero.js') }}"></script>
    <script src="{{ asset('js/libraries/routes.js') }}"></script>

</html>
