@if(isset($id))
    <!-- pulsar::includes.js.delete_translation_record -->
    <script>
        $(document).ready(function() {
            $('.delete-lang-record').on('click', function() {

                var that = this

                $.msgbox('{!! trans('pulsar::pulsar.message_delete_translation_record', ['id' => $id]) !!}',
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
                            $(location).attr('href', $(that).data('delete-url'));
                        }
                    }
                )
            })
        })
    </script>
    <!-- /.pulsar::includes.js.delete_translation_record -->
@endif