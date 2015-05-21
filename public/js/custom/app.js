/* 
 * Scripts propios para ejecutar en toda la platafroma
 */

$(document).ready(function() {
    if ($.fn.validate) {
        $("form").validate();
    }
    if ($.fn.uniform) {
        $(".uniform").uniform();
    }
    if ($.fn.lightbox) {
        $('.lightbox').lightbox();
    }
    if ($.fn.inputlimiter) {
        $(".limited").each(function(index, value) {
            var limitText = $.fn.inputlimiter.defaults.limitText;
            var data_limit = $(this).data('limit');
            limitText = limitText.replace(/\%n/g, data_limit);
            limitText = limitText.replace(/\%s/g, (data_limit <= 1 ? '' : 's'));

            $(this).parent().find('#'+$(this).attr('box-id')).html(limitText);
            $(this).inputlimiter({
                limit: data_limit,
                boxId: $(this).attr('box-id')
            });
        });
    }
    if ($.fn.select2) {
        $('.select2').each(function() {
            var self = $(this);
            $(self).select2(self.data());
        });
    }
});

//Insert text in the textarea#areaId where the caret is
function insertAtCaret(obj, string) {
    obj = document.getElementById(obj);
    obj.focus();

    if (typeof (document.selection) != 'undefined') {
        var range = document.selection.createRange();
        if (range.parentElement() != obj) return;
        range.text = string;
        range.select();
    }
    else if (typeof (obj.selectionStart) != 'undefined') {
        var start = obj.selectionStart;
        obj.value = obj.value.substr(0, start) + string + obj.value.substr(obj.selectionEnd, obj.value.length);
        start += string.length;
        obj.setSelectionRange(start, start);
    }
    else {
        obj.value += string;
    }

    obj.focus();
}