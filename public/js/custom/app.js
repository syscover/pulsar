/* 
 * Scripts propios para ejecutar en toda la platafroma
 */

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

var PulsarApp = function() {

    "use strict";

    /**************************
     * Validation             *
     **************************/
    var initValidation = function() {
        if ($.validator) {
            // Set default options
            $.extend( $.validator.defaults, {
                errorClass: "has-error",
                validClass: "has-success",
                highlight: function(element, errorClass, validClass) {

                    if (element.type === 'radio') {
                        this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                    } else {
                        $(element).addClass(errorClass).removeClass(validClass);
                    }

                    // @see http://support.stammtec.de/discussion/412/form-vertical-validation
                    if ($(element).closest("form").hasClass('form-vertical')) {
                        var class_selector = "*[class^=col-]";
                    } else {
                        var class_selector = ".form-group";
                    }
                    $(element).closest(class_selector).addClass(errorClass).removeClass(validClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                    if (element.type === 'radio') {
                        this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                    } else {
                        $(element).removeClass(errorClass).addClass(validClass);
                    }

                    // @see http://support.stammtec.de/discussion/412/form-vertical-validation
                    if ($(element).closest("form").hasClass('form-vertical')) {
                        var class_selector = "*[class^=col-]";
                    } else {
                        var class_selector = ".form-group";
                    }

                    $(element).closest(class_selector).removeClass(errorClass).addClass(validClass);

                    // Fix for not removing label in BS3
                    $(element).closest(class_selector).find('label[generated="true"]').html('');
                },
                errorPlacement: function(error, element) {
                    if (element.data("error-placement")) {
                        error.insertAfter("#" + element.data("error-placement"));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            var _base_resetForm = $.validator.prototype.resetForm;
            $.extend( $.validator.prototype, {
                resetForm: function() {
                    var resetForm_this = this;
                    _base_resetForm.call( this );

                    $(this.currentForm).find('.form-group').each(function () {
                        $(this).removeClass(resetForm_this.settings.errorClass + ' ' + resetForm_this.settings.validClass);
                    });

                    // Removing states from select2-boxes
                    $(this.currentForm).find('.select2-container').removeClass(resetForm_this.settings.errorClass + ' ' + resetForm_this.settings.validClass);

                    $(this.currentForm).find('label[generated="true"]').html('');
                },
                showLabel: function(element, message) {
                    var label = this.errorsFor( element );
                    if ( label.length ) {
                        // refresh error/success class
                        label.removeClass( this.settings.validClass ).addClass( this.settings.errorClass );

                        // check if we have a generated label, replace the message then
                        if ( label.attr("generated") ) {
                            label.html(message);
                        }
                    } else {
                        // create label
                        label = $("<" + this.settings.errorElement + "/>")
                            .attr({"for":  this.idOrName(element), generated: true})
                            .addClass(this.settings.errorClass)
                            .addClass('help-block')
                            .html(message || "");
                        if ( this.settings.wrapper ) {
                            // make sure the element is visible, even in IE
                            // actually showing the wrapped element is handled elsewhere
                            label = label.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
                        }
                        if ( !this.labelContainer.append(label).length ) {
                            if ( this.settings.errorPlacement ) {
                                this.settings.errorPlacement(label, $(element) );
                            } else {
                                label.insertAfter(element);
                            }
                        }
                    }
                    if ( !message && this.settings.success ) {
                        label.text("");
                        if ( typeof this.settings.success === "string" ) {
                            label.addClass( this.settings.success );
                        } else {
                            this.settings.success( label, element );
                        }
                    }
                    this.toShow = this.toShow.add(label);
                }
            });
        }

        // Run validate on forms
        if ($.fn.validate) {
            $("form").validate();
        }
    }

    /**************************
     * Uniform                *
     **************************/
    var initUniform = function() {
        if ($.fn.uniform) {
            $(".uniform").uniform();
        }
    }

    /**************************
     * Colorpicker            *
     **************************/
    var initColorpicker = function() {
        if ($.fn.colorpicker) {
            $('.color-picker').colorpicker();
        }
    }

    /**************************
     * Select2                *
     **************************/
    var initSelect2 = function() {
        if ($.fn.select2) {
            $('.select2').each(function() {
                var self = $(this);
                $(self).select2(self.data());
            });
        }
    }

    /**************************
     * Inputlimiter           *
     **************************/
    var initInputlimiter = function() {
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
    }

    /**************************
     * FileInput              *
     **************************/
    var initFileInput = function() {
        if ($.fn.fileInput) {
            $('[data-style="fileinput"]').each(function () {
                var $input = $(this);
                $input.fileInput($input.data());
            });
        }
    }



    return {
        // main function to initiate all plugins
        init: function () {
            initValidation(); // Validation
            initUniform(); // Uniform
            initColorpicker(); // Colorpicker
            initSelect2(); // Select2
            initInputlimiter(); // Inputlimiter
            initFileInput(); // FileInput
        }
    };

}();