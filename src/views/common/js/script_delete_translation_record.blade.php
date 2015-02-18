<!-- pulsar::common.js.script_delete_lang_record -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.delete-lang-record').bind('click', function() {

            var url = "{{ route('deleteTranslation' . $routeSuffix, [$id, $lang->id_001, $offset]) }}";

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
                        $(location).attr('href', url);
                    }
                }
            );
        });
    });
</script>
<!-- /pulsar::common.js.script_delete_lang_record -->