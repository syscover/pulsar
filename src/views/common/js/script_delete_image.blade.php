    function deleteImage(url, fieldDb, inputUpload, inputShow) {
        $.msgbox('{{ trans('pulsar::pulsar.message_delete_image') }}',
            {
                type:'confirm',
                buttons: [
                    {type: 'submit', value: '{{ trans('pulsar::pulsar.accept') }}'},
                    {type: 'cancel', value: '{{ trans('pulsar::pulsar.cancel') }}'}
                ]
            },
            function(buttonPressed) {
                if(buttonPressed == '{{ trans('pulsar::pulsar.accept') }}')
                {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: 'json',
                        success: function()
                        {
                            if(inputUpload && inputShow)
                            {
                                $(inputShow).hide();
                                $(inputUpload).fadeIn();
                            }
                            else
                            {
                                $('#inputShow').hide();
                                $('#inputUpload').fadeIn();
                            }
                            $(fieldDb).val('');
                        }
                    });
                }
            }
        );
    }