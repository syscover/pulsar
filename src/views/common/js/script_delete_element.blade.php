        $.msgbox('{{ trans('pulsar::pulsar.aviso_borrado_registro') }}',
            {
                type:'confirm',
                buttons: [
                    {type: 'submit', value: 'Aceptar'},
                    {type: 'cancel', value: 'Cancelar'}
                ]
            },
            function(buttonPressed) {
                if(buttonPressed=='Aceptar')
                {
                    $(location).attr('href',url);
                }
            }
        );