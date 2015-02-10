    function deleteElements(){
        if($('input[name^="element"]').is(':checked')){ 
            $.msgbox('{{ trans('pulsar::pulsar.message_delete_records') }}',
            {
                type:'confirm',
                buttons: [
                    {type: 'submit', value: '{{ trans('pulsar::pulsar.accept') }}'},
                    {type: 'cancel', value: '{{ trans('pulsar::pulsar.cancel') }}'}
                ]
            },
            function(buttonPressed) {
                if(buttonPressed == '{{ trans('pulsar::pulsar.accept') }}'){
                    $('#formView').submit();
                }
            });
        }
        else
        {
            $.msgbox('{{ trans('pulsar::pulsar.message_record_no_select') }}',
            {
                type:'info',
                buttons: [
                    {type: 'cancel', value: 'Aceptar'}
                ]
            });
        }
    }
