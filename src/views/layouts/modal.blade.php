<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        @section('title')
        <title>SYSCOVER</title>
        @show

        <!--=== CSS ===-->

        <!-- Bootstrap -->
        <link href="{{ asset('packages/syscover/pulsar/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

        <!-- jQuery UI -->
        <!--<link href="{{ asset('packages/syscover/pulsar/plugins/jquery-ui/jquery-ui-1.10.2.custom.css') }}" rel="stylesheet" type="text/css">-->
        <!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/syscover/pulsar/plugins/jquery-ui/jquery.ui.1.10.2.ie.css') }}">
        <![endif]-->

        <!-- Theme -->
        <link href="{{ asset('packages/syscover/pulsar/css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/css/plugins.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/css/responsive.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/css/custom/icons.css') }}" rel="stylesheet" type="text/css">
                
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/fontawesome/font-awesome.min.css') }}">
        <!--[if IE 7]>
        <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/css/fontawesome/font-awesome-ie7.min.css') }}">
        <![endif]-->
        
        <!--[if IE 8]>
		<link href="{{ asset('packages/syscover/pulsar/css/ie8.css') }}" rel="stylesheet" type="text/css">
	    <![endif]-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
        
        <!-- LIBS CSS PROPIAS -->
        <link href="{{ asset('packages/syscover/pulsar/plugins/jquery.msgbox/javascript/msgbox/jquery.msgbox.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/syscover/pulsar/plugins/pnotify/jquery.pnotify.default.css') }}" type="text/css" rel="stylesheet">
        <link href="{{ asset('packages/syscover/pulsar/plugins/cssloader/css/cssloader.css') }}" type="text/css" rel="stylesheet">
        <!-- /LIBS CSS PROPIAS -->

        <!-- Librerías CSS y CSS inline  -->
        @yield('css')

        <!-- Css personalizadas -->
        <link href="{{ asset('packages/syscover/pulsar/css/custom/style.css') }}" type="text/css" rel="stylesheet">
        
        <!--=== JavaScript ===-->

        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/libs/jquery-2.1.3.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js') }}"></script>

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
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/slimscroll/jquery.slimscroll.horizontal.min.js') }}"></script>

        <!-- App -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/app.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/plugins.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/plugins.form-components.js') }}"></script>

        <!-- JS OWN -->
        <!-- Loader -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/cssloader/js/jquery.cssloader.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/jquery.msgbox/javascript/msgbox/jquery.msgbox.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/custom/app.js') }}"></script>
        <!-- /JS OWN -->

        <script>
            $(document).ready(function()
            {
                $.cssLoader({
                    urlPlugin:  '/packages/syscover/pulsar/plugins',
                    spinnerColor: '#2a4053'
                });

                App.init(); // Init layout and core plugins
                Plugins.init(); // Init all plugins
                FormComponents.init(); // Init all form-specific plugins
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