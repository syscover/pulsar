<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <!-- custom css -->
        @yield('css')

        <!-- custom script -->
        @yield('script')
    </head>
    <body>
	    <div id="container">
            @yield('mainContent')
	    </div>
    </body>
</html>