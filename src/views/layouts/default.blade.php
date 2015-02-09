<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        @section('title')
        <title>SYSCOVER</title>
        @show

        <!--=== CSS ===-->

        <!-- Bootstrap -->
        <link href="{{ asset('packages/pulsar/pulsar/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

        <!-- jQuery UI -->
        <!--<link href="{{ asset('packages/pulsar/pulsar/plugins/jquery-ui/jquery-ui-1.10.2.custom.css') }}" rel="stylesheet" type="text/css">-->
        <!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/pulsar/pulsar/plugins/jquery-ui/jquery.ui.1.10.2.ie.css') }}">
        <![endif]-->

        <!-- Theme -->
        <link href="{{ asset('packages/pulsar/pulsar/css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/pulsar/pulsar/css/plugins.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/pulsar/pulsar/css/responsive.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/pulsar/pulsar/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/pulsar/pulsar/css/MY_icons.css') }}" rel="stylesheet" type="text/css">
                
        <link rel="stylesheet" href="{{ asset('packages/pulsar/pulsar/css/fontawesome/font-awesome.min.css') }}">
        <!--[if IE 7]>
        <link rel="stylesheet" href="{{ asset('packages/pulsar/pulsar/css/fontawesome/font-awesome-ie7.min.css') }}">
        <![endif]-->
        
        <!--[if IE 8]>
		<link href="{{ asset('packages/pulsar/pulsar/css/ie8.css') }}" rel="stylesheet" type="text/css">
	    <![endif]-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
        
        <!-- LIBS CSS PROPIAS -->
        <link href="{{ asset('packages/pulsar/pulsar/plugins/jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css') }}" rel="stylesheet" type="text/css">
        <!--[if IE 6]>
        <link href="{{ asset('packages/pulsar/pulsar/plugins/misc/jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.ie6.css') }}" rel="stylesheet" type="text/css">
        <![endif]-->
        <link href="{{ asset('packages/pulsar/pulsar/plugins/jquery.msgbox/javascript/msgbox/jquery.msgbox.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('packages/pulsar/pulsar/plugins/pnotify/jquery.pnotify.default.css') }}" type="text/css" rel="stylesheet">
        <link href="{{ asset('packages/pulsar/pulsar/plugins/cssloader/css/cssloader.css') }}" rel="stylesheet" type="text/css">

               
        <!-- Librerías CSS y CSS inline  -->
        @yield('css')
        
        @if (isset($cssView) && $cssView != NULL)
            @include($cssView)
        @endif
        <!-- Css personalizadas -->
        <link href="{{ asset('packages/pulsar/pulsar/css/MY_style.css') }}" type="text/css" rel="stylesheet">
        
        <!--=== JavaScript ===-->
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/libs/jquery-1.10.2.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/bootstrap/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/libs/lodash.compat.min.js') }}"></script>

        <!-- Loader -->
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/cssloader/js/jquery.cssloader.min.js') }}"></script>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
                <script src="{{ asset('packages/pulsar/pulsar/js/libs/html5shiv.js') }}"></script>
        <![endif]-->

        <!-- Smartphone Touch Events -->
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/touchpunch/jquery.ui.touch-punch.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/event.swipe/jquery.event.move.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/event.swipe/jquery.event.swipe.js') }}"></script>

        <!-- General -->
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/libs/breakpoints.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/respond/respond.min.js') }}"></script> <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/cookie/jquery.cookie.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/slimscroll/jquery.slimscroll.horizontal.min.js') }}"></script>

        <!-- Google Maps  -->
        <?php
            $configPulsar = Session::get('configPulsar');
            if (isset($configPulsar['googleKeyMaps']) && $configPulsar['googleKeyMaps'] != ''):
        ?>
            <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo $configPulsar['googleKeyMaps']; ?>&sensor=false"></script> 
        <?php endif; ?>
        
        <!-- JS PROPIAS -->
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/jquery.lightbox/js/lightbox/jquery.lightbox.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/jquery.msgbox/javascript/msgbox/jquery.msgbox.min.js') }}"></script>
        
        <!-- App -->
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/app.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/plugins.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/plugins.form-components.js') }}"></script>        
        <script>
            $(document).ready(function()
            {
                $.cssLoader({
                    urlPlugin:  '/packages/pulsar/pulsar/plugins',
                    spinnerColor: '#2a4053'
                });

                App.init(); // Init layout and core plugins
                Plugins.init(); // Init all plugins
                FormComponents.init(); // Init all form-specific plugins
            });
        </script>

        <!-- Librerías JS y JS inline -->
        @yield('script')
        
        @if (isset($javascriptView) && $javascriptView != null)
            @include($javascriptView)
        @endif
        
        <!-- JS personalizadas -->
        <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/MY_script.js') }}"></script>
    </head>
    
    <body class="theme-dark">
    <div id="pre-cssloader"></div>
    <!-- Header -->
	<header class="header navbar navbar-fixed-top" role="banner">
		<!-- Top Navigation Bar -->
		<div class="container">

			<!-- Only visible on smartphones, menu toggle -->
			<ul class="nav navbar-nav">
				<li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
			</ul>

			<!-- Logo -->
			<a class="navbar-brand" href="{{ url(config('pulsar.appName') . '/pulsar/dashboard') }}">
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
					<a href="{{ url(config('pulsar.appName') . '/pulsar/dashboard') }}">
						<i class="icon-dashboard"></i> {{ trans('pulsar::pulsar.dashboard') }}
					</a>
				</li>
                <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i> Settings <i class="icon-caret-down small"></i></a>
					<ul class="dropdown-menu">
                        @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-google-services', 'access'))
                            <li><a href="{{ url(config('pulsar.appName') . '/pulsar/google/services') }}"><i class="icomoon-icon-google"></i>Google Services</a></li>
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
						<li><a href="{{ url(config('pulsar.appName') . '/pulsar/logout') }}"><i class="icon-key"></i> {{ trans('pulsar::pulsar.logout') }}</a></li>
					</ul>
				</li><!-- /user login dropdown -->
			</ul><!-- /Top Right Menu -->
		</div><!-- /top navigation bar -->
	</header> <!-- /.header -->

	<div id="container">
		<div id="sidebar" class="sidebar-fixed">
			<div id="sidebar-content">
				<!--=== Navigation ===-->
                @include('pulsar::common/block/block_navigation')
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
                            <a href="{{ url(config('pulsar.appName') . '/pulsar/dashboard') }}">{{ trans('pulsar::pulsar.dashboard') }}</a>
                        </li>
                        @yield('breadcrumbs')
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
    </body>
</html>