<script type="text/javascript">

    $(document).ready(function() {

        @if(isset($attachments) && count($attachments) > 0)
            // if we have attachment, hide placehosder and
            $('#library-placeholder').hide();
            $.setAttachmentActions();
            $.setEventSaveAttachmentProperties();
        @endif

        $.dragDropEffects();

        // we create a getFile object, on layer attachments
        // all new attachments will go to the library folder and database
        $('#attachment-library-content').getFile(
            {
                urlPlugin:          '/packages/syscover/pulsar/vendor',
                folder:             '{{ config($routesConfigFile . '.libraryFolder') }}',
                tmpFolder:          '{{ config($routesConfigFile . '.libraryFolder') }}',
                multiple:           true,
                activateTmpDelete:  false
            },
            function(dataUploaded)
            {
                if(dataUploaded.success && Array.isArray(dataUploaded.files))
                {
                    $.storeLibrary(dataUploaded.files);
                }
            }
        );
    });

    // store files in library database
    $.storeLibrary = function(files) {
        $.ajax({
            url: '{{ route('storeAttachmentLibrary') }}',
            data:       {
                files:              files,
                resource:           '{{ $resource }}',
                routesConfigFile:   '{{ $routesConfigFile }}'
            },
            headers:  {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type:		'POST',
            dataType:	'json',
            success: function(libraryDataStored)
            {
                // Variable $action defined in edit.blade.php with a include
                @if($action == 'edit')
                    // Guardamos el adjunto en la base de datos, ya que es un artículo
                    // que estamos editando y ya está registrado en la base de datos
                    $.storeAttachment(libraryDataStored.files);
                @else
                    if($('.sortable li').length == 0 && libraryDataStored.files.length > 0) $('#library-placeholder').hide();

                    libraryDataStored.files.forEach(function(file, index, array){
                        $('.sortable').loadTemplate('#file', {
                            image:              file.type.id == 1? '{{ config($routesConfigFile . '.tmpFolder') }}/' + file.tmpFileName : '{{ config($routesConfigFile . '.iconsFolder') }}/' + file.type.icon,
                            fileName:           file.fileName,
                            tmpFileName:        file.tmpFileName,
                            isImage:            file.type.id == 1? 'is-image' : 'no-image'
                        }, { prepend:true });
                    });

                    // set input hidden with attachment data
                    var attachments = JSON.parse($('[name=attachments]').val());
                    $('[name=attachments]').val(JSON.stringify(attachments.concat(libraryDataStored.files)));

                    $.shortingElements();
                    $.setAttachmentActions();
                    $.setEventSaveAttachmentProperties();
                @endif
            }
        });
    };

    // function used when is a edit action
    $.storeAttachment = function(files) {
        $.ajax({
            url: '{{ route('storeAttachment', ['object' => isset($objectId)? $objectId : null , 'lang' => $lang->id_001]) }}',
            data:       {
                attachments:        files,
                lang:               $('[name=lang]').val(),
                object:             $('[name=id]').val(),
                resource:           '{{ $resource }}',
                routesConfigFile:   '{{ $routesConfigFile }}'
            },
            headers:  {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type:		'POST',
            dataType:	'json',
            success: function(attachmentDataStored)
            {
                if(attachmentDataStored.success)
                {
                    var newAttachments = [];
                    if($('.sortable li').length == 0 && attachmentDataStored.attachments.length > 0) $('#library-placeholder').hide();

                    attachmentDataStored.attachments.forEach(function(attachment, index, array){

                        // parse data atributtes to json
                        var attachmentData = JSON.parse(attachment.data_016);

                        newAttachments.push({
                            id:                 attachment.id_016,
                            type:               {id: attachment.type_016, name: attachment.type_text_016},
                            mime:               attachment.mime_016,
                            family:             null,
                            folder:             '{{ config($routesConfigFile . '.attachmentFolder') }}/' + attachment.object_016 + '/' + attachment.lang_016,
                            fileName:           attachment.file_name_016,
                            library:            attachment.library_016,
                            libraryFileName:    attachment.library_file_name_016,
                            name:               null,
                            url:                null
                        });

                        $('.sortable').loadTemplate('#file', {
                            id:                 attachment.id_016,
                            image:              attachment.type_016 == 1? '{{ config($routesConfigFile . '.attachmentFolder') }}/' + attachment.object_016 + '/' + attachment.lang_016 + '/' + attachment.file_name_016 : '{{ config($routesConfigFile . '.iconsFolder') }}/' + attachmentData.icon,
                            fileName:           attachment.file_name_016,
                            isImage:            attachment.type_016 == 1? 'is-image' : 'no-image'
                        }, { prepend:true });

                        // put id across loadTemple to replace id by data-id, to detect if is a stored database element
                        $('#' + attachment.id_016).data('id', attachment.id_016).removeAttr('id');

                    });

                    // set input hidden with attachment data
                    var attachments = JSON.parse($('[name=attachments]').val());
                    $('[name=attachments]').val(JSON.stringify(attachments.concat(newAttachments)));

                    $.shortingElements();
                    $.setAttachmentActions();
                    $.setEventSaveAttachmentProperties();
                }
            }
        });
    };

    // set save event attachment element
    $.setEventSaveAttachmentProperties = function() {
        $('.attachment-family, .image-name').off('focus').on('focus', function () {
            // get previous value from select
            $(this).data('previous', $(this).val());
        }).off('change').on('change', function(){
            $(this).addClass('changed');
        });

        // Bottom to save properties for attachment
        $('.save-attachment').off('click').on('click', function() {
            // comprobamos que hay una familia elegida y que ha cambiado algún valor del attachemnt
            if($(this).closest('li').find('select').val() != '' && $(this).closest('li').find('.attachment-family').hasClass('changed'))
            {
                var url = '{{ route('apiShowAttachmentFamily', ['id' => 'id', 'api' => 1]) }}';
                var that = this;

                // get select attachment family
                $.ajax({
                    url:    url.replace('id', $(this).closest('li').find('select').val()),
                    headers:  {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type:		'POST',
                    dataType:	'json',
                    success: function(data)
                    {
                        // check if element is a image to do a crop and the family attachment has width and height defined
                        if($(that).closest('li').find('img').hasClass('is-image') && data.width_015 != null && data.height_015 != null)
                        {
                            @if($action == 'create')
                                var action = 'create';
                            @else
                                var action = 'edit';
                            @endif

                            // check if folder is tmp or attachment folder, depend if is a create or edit action
                            //if($(that).closest('li').data('id') == undefined ||  action == 'create')
                            if(action == 'create')
                            {
                                var folder      = '{{ config($routesConfigFile . '.tmpFolder') }}';
                                var fileName    = $(that).closest('li').find('[name=tmpFileName]').val();
                            }
                            else
                            {
                                var folder      = '{{ config($routesConfigFile . '.attachmentFolder') }}/{{ isset($objectId)? $objectId : null }}/{{ $lang->id_001 }}';
                                var fileName    = null;
                            }

                            // throw get file plugin to crop and create or overwrite image
                            $.getFile(
                                {
                                    urlPlugin:  '/packages/syscover/pulsar/vendor',
                                    srcFolder:  '{{ config($routesConfigFile . '.libraryFolder') }}',
                                    srcFile:    $(that).closest('li').find('.file-name').html(),
                                    folder:     folder,
                                    filename:   fileName,
                                    crop: {
                                        active:     true,
                                        width:      data.width_015,
                                        height:     data.height_015,
                                        overwrite:  true
                                    }
                                },
                                function(response)
                                {
                                    $(that).closest('li').find('img').attr('src', response.folder + '/' + response.name + '?' + Math.floor((Math.random() * 1000) + 1));
                                    $(that).closest('li').find('.family-name').html(data.name_015);
                                    $(that).closest('li').find('.attachment-family').removeClass('changed');
                                    $(that).closest('.attachment-item').toggleClass('cover');
                                    $(that).closest('li').find('.attachment-family').data('previous', $(that).closest('li').find('.attachment-family').val());
                                    $.setFamilyAttachment($(that).closest('li').find('.file-name').html(), data.id_015);
                                    $.setNameAttachment(that);
                                    if($(that).closest('li').data('id') != undefined) $.updateAttachment(that);
                                }
                            );
                        }
                        else
                        {
                            // set family without getFile
                            $(that).closest('li').find('.family-name').html(data.name_015);
                            $(that).closest('li').find('.attachment-family').removeClass('changed');
                            $(that).closest('.attachment-item').toggleClass('cover');
                            $(that).closest('li').find('.attachment-family').data('previous', $(that).closest('li').find('.attachment-family').val());
                            $.setFamilyAttachment($(that).closest('li').find('.file-name').html(), data.id_015);
                            $.setNameAttachment(that);
                            if($(that).closest('li').data('id') != undefined) $.updateAttachment(that);
                        }
                    }
                });
            }
            else
            {
                if($(this).closest('li').find('.attachment-family').hasClass('changed'))
                {
                    $(this).closest('li').find('.family-name').html('');
                    $(this).closest('li').find('.attachment-family').removeClass('changed');
                    $(this).closest('li').find('.attachment-family').data('previous', $(this).closest('li').find('.attachment-family').val());
                    $.setFamilyAttachment($(this).closest('li').find('.file-name').html(), '');
                }
                $(this).closest('.attachment-item').toggleClass('cover');
                $.setNameAttachment(this);
                if($(this).closest('li').data('id') != undefined) $.updateAttachment(this);
            }
        });
    };

    // Update elements only on database
    $.updateAttachment = function(element) {
        if($(element).closest('li').data('id') != undefined)
        {
            var attachments         = JSON.parse($('[name=attachments]').val());
            var attachmentToUpdate  = null;

            attachments.forEach(function(attachment, index, array){
                if(attachment.id == $(element).closest('li').data('id'))
                {
                    attachmentToUpdate = attachment;
                }
            });
            var url = '{{ route('updateAttachment', ['object'=> isset($objectId)? $objectId : null , 'lang'=> $lang->id_001, 'id' => 'id']) }}';

            // update attachment across ajax
            $.ajax({
                url:    url.replace('id', $(element).closest('li').data('id')),
                headers:  {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    _method: 'PUT',
                    attachment: attachmentToUpdate,
                    action: '{{ $action }}',
                    routesConfigFile: '{{ $routesConfigFile }}'
            },
                type:		'POST',
                dataType:	'json',
                success: function(data){}
            });
        }
    };

    // set events on attachment elements
    $.setAttachmentActions = function() {
        // set button actions from li elements
        $('.attachment-action span').off('click').on('click', function() {
            $(this).closest('.attachment-item').toggleClass('cover');
        });

        $('button.open-ov').off('click').on('click', function() {
            $(this).closest('.attachment-item').toggleClass('cover');
        });

        $('button.close-ov').off('click').on('click', function() {
            $(this).closest('.attachment-item').toggleClass('cover');
        });

        $('div.close-icon').off('click').on('click', function() {
            $(this).closest('.attachment-item').toggleClass('cover');
            $(this).closest('li').find('.attachment-family').removeClass('changed').val($(this).closest('li').find('.attachment-family').data('previous'));
            $(this).closest('li').find('.image-name').removeClass('changed').val($(this).closest('li').find('.image-name').data('previous'));
        });

        // sorting elements
        $(".sortable").sortable({
            stop: function(event, ui){
                $.shortingElements();
            }
        });

        // remove attachment element
        $('.remove-img').off('click').on('click', function() {

            $(this).closest('li').fadeOut( "slow", function() {

                var that = this;

                // check that attachment have id and is stored in database
                if($(this).data('id') != undefined)
                {
                    // delete file from attachment folder
                    var url = '{{ route('deleteAttachment', ['lang'=> $lang->id_001, 'id' => 'id']) }}';
                    $.ajax({
                        url:    url.replace('id', $(this).data('id')),
                        headers:  {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            _method: 'DELETE',
                            routesConfigFile: '{{ $routesConfigFile }}'
                        },
                        type:		'POST',
                        dataType:	'json',
                        success: function(data)
                        {
                            if(data.success)
                            {
                                $.removeAttachment(that);
                            }
                        }
                    });
                }
                else
                {
                    // delete file from tmp folder
                    $.ajax({
                        url:    '{{ route('deleteTmpAttachment') }}',
                        headers:  {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            _method:            'DELETE',
                            fileName:           $(this).find('.file-name').html(),
                            routesConfigFile:   '{{ $routesConfigFile }}'
                        },
                        type:		'POST',
                        dataType:	'json',
                        success: function(data)
                        {
                            if(data.success)
                            {
                                $.removeAttachment(that);
                            }
                        }
                    });
                }
            });
        });
    };

    // sorting elements and register on database new sorting
    $.shortingElements = function() {
        var attachments   = JSON.parse($('[name=attachments]').val());
        var hasId         = false;

        $('.sortable li').each(function(i) {
            var that = this;
            attachments.forEach(function(attachment, j, attachments){
                if($(that).find('.file-name').html() == attachment.fileName)
                {
                    attachment.sorting = i;
                }
                if(attachment.id != undefined)
                {
                    hasId = true;
                }
            });
        });

        if(hasId)
        {
            // update attachment across ajax
            $.ajax({
                url:    '{{ route('updatesAttachment', ['object' => isset($objectId)? $objectId : null ,'lang' => $lang->id_001]) }}',
                headers:  {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    _method: 'PUT',
                    attachments:        attachments,
                    action:             '{{ $action }}',
                    routesConfigFile:   '{{ $routesConfigFile }}'
                },
                type:		'POST',
                dataType:	'json',
                success: function(data)
                {
                    if(data.success)
                    {
                        $('[name=attachments]').val(JSON.stringify(attachments));
                    }
                }
            });
        }
        else
        {
            $('[name=attachments]').val(JSON.stringify(attachments));
        }
    };
</script>

<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.loadTemplate/jquery.loadTemplate-1.5.0.min.js') }}"></script>
<script type="text/html" id="file">
    <li data-id="id">
        @include('pulsar::includes.html.form_hidden', ['name' => 'tmpFileName', 'data' => ['value' => 'tmpFileName']])
        <div class="attachment-item">
            <div class="attachment-img">
                <img data-src="image" data-class="isImage" />
            </div>
            <div class="attachment-over">
                <div class="col-md-10 col-sm-10 col-xs-10 uncovered">
                    <h4 class="attachment-title family-name"></h4>
                    <p class="attachment-sub file-name" data-content="fileName"></p>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2 uncovered">
                    <h4 class="attachment-action"><span class="glyphicon glyphicon-pencil"></span></h4>
                </div>
                <div>
                    <div class="close-icon covered"><span class="glyphicon glyphicon-remove"></span></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 covered">
                        <div class="form-group">
                            <input type="text" class="form-control image-name" placeholder="{{ trans('pulsar::pulsar.image_name') }}" data-previous="">
                        </div>
                        <div class="form-group">
                            <select class="form-control attachment-family" name="attachmentFamily" data-previous="">
                                <option value="" selected>{{ trans('pulsar::pulsar.select_family') }}</option>
                                @foreach($attachmentFamilies as $attachmentFamily)
                                    <option value="{{ $attachmentFamily->id_015 }}">{{ $attachmentFamily->name_015 }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 covered">
                        <div class="form-group">
                            <button type="button" class="close-ov form-control save-attachment">{{ trans('pulsar::pulsar.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="remove-img">
            <span class="glyphicon glyphicon-remove"></span>
        </div>
    </li>
</script>