<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redirect Modal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script type="text/javascript" src="{{ URL::asset('packages/pulsar/pulsar/js/libs/jquery-1.10.2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            parent.window.location.replace(parent.$('[name="urlTarget"]').val());
        });
    </script>
</head>
<body>
</body>
</html>