
$(document).ready(function() {

    $('.fr-toolbar .fa-expand').parent('a').on('click', function(){
        if($(this).hasClass('active'))
        {
            $(this).removeClass('active');
            parent.$('.iframe-contentbuilder').removeClass('fr-fullscreen');
            parent.$('body').removeClass('lock-scroll');
        }
        else
        {
            $(this).addClass('active');
            parent.$('.iframe-contentbuilder').addClass('fr-fullscreen');
            parent.$('body').addClass('lock-scroll');
        }
    });

});