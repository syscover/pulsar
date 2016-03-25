<script>
    $(document).ready(function() {
        // hide every elements
        $('#headerCustomFields').hide()
        $('#wrapperCustomFields').hide()

        // on change family show fields and custom fields
        $("[name=customFieldGroup]").on('change', function() {
            if($("[name=customFieldGroup]").val())
            {
                // get html doing a request to controller to render the views
                @if($action == 'edit' || isset($id))
                    var request = {
                            customFieldGroup: $("[name=customFieldGroup]").val(),
                            lang: '{{ $lang->id_001 }}',
                            object: '{{ $id }}',
                            resource: '{{ $resource }}',
                            action: '{{ $action }}'
                        }
                @else
                    var request = {
                            customFieldGroup: $("[name=customFieldGroup]").val(),
                            lang: '{{ $lang->id_001 }}'
                        }
                @endif

                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    url: '{{ route('apiGetCustomFields') }}',
                    data: request,
                    success: function (data) {
                        // set html custom fields section
                        $('#wrapperCustomFields').html(data.html)

                        if ($.fn.select2)
                            $('.select2').each(function() {
                                var self = $(this)
                                $(self).select2(self.data())
                            })

                        if($.fn.froalaEditor)
                            $('.wysiwyg').froalaEditor({
                                language: '{{ config('app.locale') }}',
                                toolbarInline: false,
                                toolbarSticky: true,
                                tabSpaces: true,
                                shortcutsEnabled: ['show', 'bold', 'italic', 'underline', 'strikeThrough', 'indent', 'outdent', 'undo', 'redo', 'insertImage', 'createLink'],
                                toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'insertHR', 'insertLink', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
                                heightMin: 130,
                                enter: $.FroalaEditor.ENTER_BR,
                                key: '{{ config('pulsar.froalaEditorKey') }}'
                            })

                        if (data.html != '')
                        {
                            $(".uniform").uniform()
                            $('#headerCustomFields').fadeIn()
                            $('#wrapperCustomFields').fadeIn()
                        }
                    }
                })
            }
            else
            {
                $('#headerCustomFields').fadeOut()
                $('#wrapperCustomFields').fadeOut()
                $('#wrapperCustomFields').html('')
            }
        })

        // if we have customFieldGroup value, throw event to show or hide elements
        if($("[name=customFieldGroup]").val())
            $("[name=customFieldGroup]").trigger('change')

    })
</script>