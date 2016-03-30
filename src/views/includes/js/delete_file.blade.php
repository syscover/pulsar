<!-- pulsar::includes.js.delete_file -->
<script>

    $(document).ready(function() {
        $('.link-delete-file').on('click', function(){

            $.deleteFile(this)
        })
    })

    $.deleteFile = function (that)
    {
        $.msgbox('{{ trans('pulsar::pulsar.message_delete_file') }}', {
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
                        headers:    {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: $(that).data('href'),
                        data:
                        {
                            id: $(that).data('id'),
                            file: $(that).data('file')
                        },
                        dataType: 'json',
                        success: function()
                        {
                            $(that).closest('.filelink').hide()
                            $(that).closest('.file-container').find('.fileinput').fadeIn()
                            $("[name=" + $(that).data('name') + "]").val('');
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
<!-- ./pulsar::includes.js.delete_file -->