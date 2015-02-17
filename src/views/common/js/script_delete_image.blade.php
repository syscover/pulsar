<!-- pulsar::common.js.script_delete_image -->
<script type="text/javascript">
    function deleteImage(url, inputHidden, inputFile, inputImage)
    {
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
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        url: url,
                        dataType: 'text',
                        success: function()
                        {
                            if(typeof inputFile != 'undefined' && typeof inputImage != 'undefined')
                            {
                                $(inputImage).hide();
                                $(inputFile).fadeIn();
                            }
                            else
                            {
                                $('#inputImage').hide();
                                $('#inputFile').fadeIn();
                            }

                            if(typeof inputHidden != 'undefined')
                            {
                                $(inputHidden).val('');
                            }
                            else
                            {
                                $("[name='image']").val('');
                            }
                        },
                        error: function(e)
                        {
                            alert('error');
                            console.log(e);
                        }
                    });
                }
            }
        );
    }
</script>
<!-- pulsar::common.js.script_delete_image -->