    function deleteElements(){
        if($('input[name^="element"]').is(':checked')){ 
            $.msgbox('{{ trans('pulsar::pulsar.aviso_borrado_registros') }}',
            {
                type:'confirm',
                buttons: [
                    {type: 'submit', value: 'Aceptar'},
                    {type: 'cancel', value: 'Cancelar'}
                ]
            },
            function(buttonPressed) {
                if(buttonPressed=='Aceptar'){
                    $('#formView').submit();
                }
            });
        }
        else
        {
            $.msgbox('{{ trans('pulsar::pulsar.aviso_registro_no_select') }}',
            {
                type:'info',
                buttons: [
                    {type: 'cancel', value: 'Aceptar'}
                ]
            });
        }
    }
