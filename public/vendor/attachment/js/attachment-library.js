$.setAttachmentActions = function() {

    // set button actions from li elements
    $('.attachment-action span').off('click').on('click', function(){
        $(this).closest('.attachment-item').toggleClass('cover');
    });

    $('button.open-ov').off('click').on('click', function(){
        $(this).closest('.attachment-item').toggleClass('cover');
    });

    $('button.close-ov').off('click').on('click', function(){
        $(this).closest('.attachment-item').toggleClass('cover');
    });

    $('div.close-icon').off('click').on('click', function(){
        $(this).closest('.attachment-item').toggleClass('cover');
    });

    $(".sortable").sortable();
    $(".sortable").disableSelection();

    // remove li elements
    $('.remove-img').off('click').on('click', function(){

        $(this).closest('li').fadeOut( "slow", function() {

            var fileName = $(this).find('.file-name').html()
            var dataFiles = JSON.parse($('[name=dataFiles]').val());

            for(var i = 0; i < dataFiles.length; i++)
            {
                if(dataFiles[i].name == fileName)
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
        });
    });
};