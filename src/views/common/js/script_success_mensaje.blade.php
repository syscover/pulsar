    @if(\Illuminate\Support\Facades\Session::get('msg') != null && \Illuminate\Support\Facades\Session::get('msg')==1)
        $.pnotify({
            type:   'success',
            title:  '{{ trans('pulsar::pulsar.action_successful') }}',
            text:   '{!! \Illuminate\Support\Facades\Session::get('txtMsg') !!}',
            icon:   'picon icon16 iconic-icon-check-alt white',
            opacity: 0.95,
            history: false,
            sticker: false
        }); 
    @endif