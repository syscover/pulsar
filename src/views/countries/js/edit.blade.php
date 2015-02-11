<script type="text/javascript">
    function deleteLangElement(id, idioma, inicio){
        var url = "{{ url(config('pulsar.appName')) }}/pulsar/paises/destroy/lang/"+id+"/"+idioma+"/"+inicio;
        $.msgbox('<?php echo trans('pulsar::pulsar.aviso_borrado_registro');?>',
            {
                type:'confirm',
                buttons: [
                    {type: 'submit', value: 'Aceptar'},
                    {type: 'cancel', value: 'Cancelar'}
                ]
            },
            function(buttonPressed) {
                if(buttonPressed=='Aceptar'){
                    $(location).attr('href',url);
                }
            }
        );
    }
</script>