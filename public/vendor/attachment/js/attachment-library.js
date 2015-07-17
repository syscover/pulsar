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

    // remove li elements
    $('.remove-img').off('click').on('click', function() {

        $(this).closest('li').fadeOut( "slow", function() {

            var fileName = $(this).find('.file-name').html();
            var dataFiles = JSON.parse($('[name=dataFiles]').val());

            for(var i = 0; i < dataFiles.length; i++)
            {
                if(dataFiles[i].fileName == fileName)
                {
                    dataFiles.splice(i, 1);
                }
            }
            $('[name=dataFiles]').val(JSON.stringify(dataFiles));

            $(this).remove();

            if($('.sortable li').length == 0)
            {
                $('#library-placeholder').show().css('opacity', 1).css('z-index', 'auto');
            }
            else
            {
                $.shortingElements();
            }
        });
    });
};

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

$.shortingElements = function() {
    var dataFiles   = JSON.parse($('[name=dataFiles]').val());
    $('.sortable').find('li').each(function(index) {
        for(var i = 0; i < dataFiles.length; i++)
        {
            if($(this).find('.file-name').html() == dataFiles[i].fileName)
            {
                dataFiles[i].sorting = index;
            }
        }
    });
    $('[name=dataFiles]').val(JSON.stringify(dataFiles));
};

$.setFamilyAttachment = function(fileName, family) {
    var dataFiles   = JSON.parse($('[name=dataFiles]').val());
    for(var i = 0; i < dataFiles.length; i++)
    {
        if(dataFiles[i].fileName == fileName)
        {
            dataFiles[i].family = family;
        }
    }
    $('[name=dataFiles]').val(JSON.stringify(dataFiles));
};