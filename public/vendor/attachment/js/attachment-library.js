$.dragDropEffects = function() {
    $(document).on('dragover', function(e){
        e.preventDefault();
        var al = $('#attachment-library').get(0);
        if($.contains(al, e.target)|| e.target.id=='attachment-library' || e.target.id == 'attachment-library-mask' || e.target.id == 'attachment-library-content')
        {
            $('#attachment-library-mask').css('z-index', 9999999999).css('opacity', 1);
            $('#library-placeholder').css('opacity', 0).css('z-index', -1);
        }
        else
        {
            $('#attachment-library-mask').css('opacity', 0).css('z-index', -1);
            $('#library-placeholder').css('opacity', 1).css('z-index', 'auto');
        }
    });

    $(document).on('dragenter', function(e)
    {
        e.preventDefault();
        var al = $('#attachment-library').get(0);
        if($.contains(al, e.target) || e.target.id=='attachment-library-mask' || e.target.id == 'attachment-library-content' || e.target.id == 'attachment-library')
        {
            $('#attachment-library-mask').css('z-index', 9999999999).css('opacity', 1);
            $('#library-placeholder').css('opacity', 0).css('z-index', -1);
        }
    });

    $(document).on('dragleave', function(e)
    {
        e.preventDefault();
        if(e.target.id=='attachment-library-mask' || e.target.id=='attachment-library-content')
        {
            $('#attachment-library-mask').css('opacity', 0).css('z-index', -1);
            $('#library-placeholder').css('opacity', 1).css('z-index', 'auto');
        }
    });

    $('#attachment-library-content, #attachment-library-mask').on('drop', function(e){
        e.preventDefault();
        $('#attachment-library-mask').css('opacity', 0).css('z-index', -1);
    });
};

$.removeAttachment = function(element) {
    var fileName = $(element).find('.file-name').html();
    var attachments = JSON.parse($('[name=attachments]').val());

    for(var i = 0; i < attachments.length; i++)
    {
        if(attachments[i].fileName == fileName)
        {
            attachments.splice(i, 1);
        }
    }
    $('[name=attachments]').val(JSON.stringify(attachments));

    $(element).remove();

    if($('.sortable li').length == 0)
    {
        $('#library-placeholder').show().css('opacity', 1).css('z-index', 'auto');
    }
    else
    {
        $.shortingElements();
    }
};

$.setNameAttachment = function(element) {
    // set name of image
    var fileName    = $(element).closest('li').find('.file-name').html();
    var attachments = JSON.parse($('[name=attachments]').val());

    for(var i = 0; i < attachments.length; i++)
    {
        if(attachments[i].fileName == fileName)
        {
            attachments[i].imageName = $(element).closest('li').find('.image-name').val();
        }
    }
    // set previous value to image name
    $(element).closest('li').find('.image-name').data('previous', $(element).closest('li').find('.image-name').val());

    $('[name=attachments]').val(JSON.stringify(attachments));
};

$.setFamilyAttachment = function(fileName, family) {
    var attachments   = JSON.parse($('[name=attachments]').val());
    for(var i = 0; i < attachments.length; i++)
    {
        if(attachments[i].fileName == fileName)
        {
            attachments[i].family = family;
        }
    }
    $('[name=attachments]').val(JSON.stringify(attachments));
};