<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        @section('title')
        <title>SYSCOVER</title>
        @show

        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/jquery-ui/jquery-ui.min.css') }}">

        <!-- Theme -->
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/plugins.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/responsive.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/icons.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/custom/icons.css') }}">

        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/fontawesome/css/font-awesome.min.css') }}">

        <!-- Custom fonts -->
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/font-syscover/styles.css') }}">

        <!--[if IE 8]>
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/ie8.css') }}">
        <![endif]-->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700">

        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/cssloader/css/cssloader.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/plugins/jquery.msgbox/javascript/msgbox/jquery.msgbox.css') }}">

        <!-- custom css -->
        @yield('css')
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/custom/style.css') }}">

        <!--=== JavaScript ===-->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/libs/jquery-2.1.3.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery-ui/jquery-ui.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/bootstrap/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/libs/lodash.compat.min.js') }}"></script>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="{{ asset('packages/syscover/pulsar/js/libs/html5shiv.js') }}"></script>
        <![endif]-->

        <!-- Smartphone Touch Events -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/touchpunch/jquery.ui.touch-punch.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/event.swipe/jquery.event.move.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/event.swipe/jquery.event.swipe.js') }}"></script>

        <!-- General -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/libs/breakpoints.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/respond/respond.min.js') }}"></script> <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/cookie/jquery.cookie.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/slimscroll/jquery.slimscroll.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/slimscroll/jquery.slimscroll.horizontal.js') }}"></script>

        <!-- App -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/app.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/plugins.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/plugins.form-components.js') }}"></script>

        <!-- JS OWN -->
        <!-- Loader -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/cssloader/js/jquery.cssloader.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/jquery.msgbox/javascript/msgbox/jquery.msgbox.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/custom/app.js') }}"></script>
        <!-- /JS OWN -->

        <script>
            $(document).ready(function()
            {
                $.cssLoader({
                    urlPlugin:  '/packages/syscover/pulsar/vendor',
                    spinnerColor: '#4d7496',
                    theme: 'material'
                });

                App.init(); // Init layout and core plugins
                Plugins.init(); // Init all plugins
                FormComponents.init(); // Init all form-specific plugins
                PulsarApp.init() // Init custom plugins
            });
        </script>

        <!-- custom script -->
        @yield('script')
    </head>
    
    <body class="theme-dark">
        <div id="pre-cssloader"></div>

        <div id="container">
            <!--=== Page Header ===-->
            <div class="page-header-margin"></div>
            <!-- /Page Header -->
            <!--=== Page Content ===-->
            @yield('mainContent')
            <!-- /Page Content -->
        </div><!-- /.container -->
    </body>
</html>