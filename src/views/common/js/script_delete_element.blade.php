        $.msgbox('{!! trans('pulsar::pulsar.message_delete_record') !!}',
            {
                type:'confirm',
                buttons: [
                    {type: 'submit', value: '{{ trans('pulsar::pulsar.accept') }}'},
                    {type: 'cancel', value: '{{ trans('pulsar::pulsar.cancel') }}'}
                ]
            },
            function(buttonPressed) {
                if(buttonPressed=='{{ trans('pulsar::pulsar.accept') }}')
                {
                    $(location).attr('href',url);
                }
            }
        );