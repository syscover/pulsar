<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        @section('title')
        <title>SYSCOVER</title>
        @show
        <!--=== CSS ===-->

        <!-- Bootstrap -->
        <link href="{{ asset('packages/syscover/pulsar/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

        <!-- Theme -->
        <link href="{{ asset('packages/syscover/pulsar/css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/css/plugins.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/css/responsive.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/css/custom/icons.css') }}" rel="stylesheet" type="text/css">

        <!-- Login -->
        <link href="{{ asset('packages/syscover/pulsar/css/login.css') }}" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/fontawesome/css/font-awesome.min.css') }}">

        <!--[if IE 8]>
        <link href="{{ asset('packages/syscover/pulsar/css/ie8.css') }}" rel="stylesheet" type="text/css" />
        <![endif]-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
        
        <!-- LIBS CSS PROPIAS -->
        <link href="{{ asset('packages/syscover/pulsar/vendor/pnotify/pnotify.custom.min.css') }}" type="text/css" rel="stylesheet">
        <link href="{{ asset('packages/syscover/pulsar/css/custom/style.css') }}" type="text/css" rel="stylesheet">
        <link href="{{ asset('packages/syscover/pulsar/vendor/cssloader/css/cssloader.css') }}" type="text/css" rel="stylesheet">

        <!--=== JavaScript ===-->

        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/libs/jquery-2.1.3.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/bootstrap/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/libs/lodash.compat.min.js') }}"></script>

        <!-- Loader -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/cssloader/js/jquery.cssloader.js') }}"></script>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="{{ asset('packages/syscover/pulsar/js/libs/html5shiv.js') }}"></script>
        <![endif]-->

        <!-- Beautiful Checkboxes -->
        <script src="{{ asset('packages/syscover/pulsar/plugins/uniform/jquery.uniform.min.js') }}"></script>

        <!-- Form Validation -->
        <script src="{{ asset('packages/syscover/pulsar/plugins/validation/jquery.validate.min.js') }}"></script>

        <!-- Slim Progress Bars -->
        <script src="{{ asset('packages/syscover/pulsar/plugins/nprogress/nprogress.js') }}"></script>
        
        <!-- Mensajes -->
        <script src="{{ asset('packages/syscover/pulsar/vendor/pnotify/pnotify.custom.min.js') }}"></script>

        <!-- JS personalizadas -->
        <script>
        $(document).ready(function() {
            "use strict";

            $.cssLoader({
                urlPlugin:  '/packages/syscover/pulsar/vendor',
                spinnerColor: '#4d7496',
                theme: 'material'
            });
            Login.init(); // Init login JavaScript
        });
        </script>

        <!-- custom head -->
        @yield('head')
    </head>

    <body class="login">
        <div id="pre-cssloader"></div>
        <div class="logo">
            @section('logo')
            <!-- <img src="assets/img/logo.png" alt="logo" /> -->
            <strong>PUL</strong>SAR
            @show
        </div>
	    <!-- /Logo -->

        <!-- Login Box -->
        <div class="box">
            <div class="content">
                <!--=== Page Content ===-->
                @yield('mainContent')
                <!-- /Page Content -->
            </div> <!-- /.content -->

            <!-- Forgot Password Form -->
            @yield('reminder')
            <!-- /Forgot Password Form -->
        </div> <!-- /Login Box -->
    </body>
</html>