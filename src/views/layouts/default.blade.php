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
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/slimscroll/jquery.slimscroll.horizontal.min.js') }}"></script>

        <!-- App -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/app.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/plugins.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/plugins.form-components.js') }}"></script>

        <!-- JS OWN -->
        <!-- Loader -->
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/cssloader/js/jquery.cssloader.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/jquery.msgbox/javascript/msgbox/jquery.msgbox.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/custom/app.js') }}"></script>
        <!-- /JS OWN -->

        <script>
            $(document).ready(function()
            {
                $.cssLoader({
                    urlPlugin:  '/packages/syscover/pulsar/vendor',
                    spinnerColor: '#2a4053'
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

        @if(isset($modal) && $modal)
            <div class="container-modal">
                <!--=== Page Header ===-->
                <div class="page-header-margin"></div>
                <!-- /Page Header -->
                <!--=== Page Content ===-->
                @yield('mainContent')
                <!-- /Page Content -->
            </div><!-- /.container -->
        @else
            <!-- Header -->
            <header class="header navbar navbar-fixed-top" role="banner">
                <!-- Top Navigation Bar -->
                <div class="container">

                    <!-- Only visible on smartphones, menu toggle -->
                    <ul class="nav navbar-nav">
                        <li class="nav-toggle"><a href="javascript:void(0);"><i class="icon-reorder"></i></a></li>
                    </ul>

                    <!-- Logo -->
                    <a class="navbar-brand" href="{{ route('dashboard') }}">
                        <!-- <img src="assets/img/logo.png" alt="logo"> -->
                        <strong>PUL</strong>SAR
                    </a>
                    <!-- /logo -->

                    <!-- Sidebar Toggler -->
                    <a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation">
                        <i class="icon-reorder"></i>
                    </a>
                    <!-- /Sidebar Toggler -->

                    <!-- Top Left Menu -->
                    <ul class="nav navbar-nav navbar-left hidden-xs hidden-sm">
                        <li>
                            <a href="{{ route('dashboard') }}">
                                <i class="icon-dashboard"></i> {{ trans('pulsar::pulsar.dashboard') }}
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i> Settings <i class="icon-caret-down small"></i></a>
                            <ul class="dropdown-menu">
                                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-google-services', 'access'))
                                    <li><a href="{{ route('googleServices') }}"><i class="icomoon-icon-google"></i>Google Services</a></li>
                                    <!-- <li class="divider"></li> -->
                                @endif
                            </ul>
                        </li>
                    </ul>
                    <!-- /Top Left Menu -->

                    <!-- Top Right Menu -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- User Login Dropdown -->
                        <li class="dropdown user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-male"></i>
                                <span class="username">{{ Auth::user()->user_010 }}</span>
                                <i class="icon-caret-down small"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('logout') }}"><i class="icon-off"></i> {{ trans('pulsar::pulsar.logout') }}</a></li>
                            </ul>
                        </li><!-- /user login dropdown -->
                    </ul><!-- /Top Right Menu -->
                </div><!-- /top navigation bar -->
            </header> <!-- /.header -->

            <div id="container">
                <div id="sidebar" class="sidebar-fixed">
                    <div id="sidebar-content">
                        <!--=== Navigation ===-->
                        @include('pulsar::includes.nav.main')
                        <!-- /Navigation -->
                    </div>
                    <div id="divider" class="resizeable"></div>
                </div><!-- /Sidebar -->
                <div id="content">
                    <div class="container">
                        <!-- Breadcrumbs line -->
                        <div class="crumbs">
                            <ul id="breadcrumbs" class="breadcrumb">
                                <li>
                                    <i class="icon-home"></i>
                                    <a href="{{ route('dashboard') }}">{{ trans('pulsar::pulsar.dashboard') }}</a>
                                </li>
                                @if(View::exists($package . '::' . $folder . '.breadcrumbs'))
                                    @include($package . '::' . $folder . '.breadcrumbs')
                                @endif
                            </ul>
                            @yield('crumbsButtons')
                        </div>
                        <!-- /Breadcrumbs line --

                        <!--=== Page Header ===-->
                        <div class="page-header-margin"></div>
                        <!-- /Page Header -->

                        <!--=== Page Content ===-->
                        @yield('mainContent')
                        <!-- /Page Content -->

                    </div><!-- /.container -->
                </div><!-- /.content -->
            </div><!-- /.container -->
        @endif
        @yield('endBody')
    </body>
</html>