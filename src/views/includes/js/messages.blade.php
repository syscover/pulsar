@if(session('msg') != null && session('msg') == 1)
    <!-- pulsar::includes.js.messages -->
    <script>
        $(document).ready(function() {
            new PNotify({
                type:   'success',
                title:  '{{ trans('pulsar::pulsar.action_successful') }}',
                text:   '{!! session('txtMsg') !!}',
                opacity: .9,
                styling: 'fontawesome'
            });
        });
    </script>
    <!-- /.pulsar::includes.js.messages -->
@endif

@if(session('msg') != null && session('msg') == 2)
    <!-- pulsar::includes.js.messages -->
    <script>
        $(document).ready(function() {
            new PNotify({
                type:   'error',
                title:  '{{ trans('pulsar::pulsar.action_error') }}',
                text:   '{!! session('txtMsg') !!}',
                opacity: .9,
                styling: 'fontawesome'
            });
        });
    </script>
    <!-- /.pulsar::includes.js.messages -->
@endif