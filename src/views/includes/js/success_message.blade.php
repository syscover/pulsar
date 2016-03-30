@if(session('msg') != null && session('msg')==1)
    <!-- pulsar::includes.js.success_message -->
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
    <!-- ./pulsar::includes.js.success_message -->
@endif