
@if(Session::get('msg') != null && Session::get('msg')==1)
    <!-- pulsar::common.js.script_success_message -->
    <script type="text/javascript">
        $(document).ready(function() {
            $.pnotify({
                type:   'success',
                title:  '{{ trans('pulsar::pulsar.action_successful') }}',
                text:   '{!! Session::get('txtMsg') !!}',
                icon:   'picon icon16 iconic-icon-check-alt white',
                opacity: 0.95,
                history: false,
                sticker: false
            });
        });
    </script>
    <!-- /pulsar::common.js.script_success_message -->
@endif