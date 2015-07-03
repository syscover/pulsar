$(document).ready(function() {
    // library envents
    $('.attachment-action span').click(function(){
        $(this).closest('.attachment-item').toggleClass('cover');
    });

    $('button.open-ov').click(function(){
        $(this).closest('.attachment-item').toggleClass('fullcover');
    });

    $('button.close-ov').click(function(){
        $(this).closest('.attachment-item').removeClass('fullcover');
    });

    $('button.close-ov.full').click(function(){
        $(this).closest('.attachment-item').toggleClass('cover');
        $(this).closest('.attachment-item').removeClass('fullcover');
    });

    $('div.close-icon').click(function(){
        $(this).closest('.attachment-item').toggleClass('cover');
    });

    $('.sortable').sortable();
    $('.sortable').disableSelection();
});

