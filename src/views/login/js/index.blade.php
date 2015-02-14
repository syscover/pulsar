<script type="text/javascript">
    $(document).ready(function() {
        @if($errors->has('loginErrors'))
        $.pnotify({
            type:   'error',
            title:  '{{ trans('pulsar::pulsar.error_login') }}',
            text:   '{{ trans('pulsar::pulsar.error_login_msg') }}',
            icon:   'picon icon16 iconic-icon-check-alt white',
            opacity: 0.95,
            history: false,
            sticker: false
        });
        @endif

        @if($errors->has('loginErrors') && $errors->first('loginErrors') == 1)
        $.pnotify({
            type:   'error',
            title:  '{{ trans('pulsar::pulsar.error_login') }}',
            text:   '{{ trans('pulsar::pulsar.error_login_msg_1') }}',
            icon:   'picon icon16 iconic-icon-check-alt white',
            opacity: 0.95,
            history: false,
            sticker: false
        });
        @endif

        @if($errors->has('loginErrors') && $errors->first('loginErrors') == 2)
        $.pnotify({
            type:   'error',
            title:  '{{ trans('pulsar::pulsar.error_login') }}',
            text:   '{{ trans('pulsar::pulsar.error_login_msg_2') }}',
            icon:   'picon icon16 iconic-icon-check-alt white',
            opacity: 0.95,
            history: false,
            sticker: false
        });
        @endif
    });          
</script>