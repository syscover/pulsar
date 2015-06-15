/*
 *	ElementTable v1.0 - 2015-06-1
 *	(c) 2015 Syscover S.L. - http://www.syscover.com/
 *	All rights reserved
 */

"use strict";

(function ($) {
    var Forms = {
        options: {
            debug: false,
            appName: 'pulsar',
            ajax: false,
            id: null,
            cleanFields: true,
            fields: {
                subject: 'subject',
                name: 'name',
                surname: 'surname',
                company: 'company',
                email: 'email',
                date: 'date',
                data: []
            },
            redirectOk: null
        },
        callback: null,
        item: null,

        init: function(options, callback, item)
        {
            // extend options.fields
            if(options.fields != undefined)
            {
                options.fields = $.extend({}, this.options.fields, options.fields || {});
            }

            // Extend options load
            this.options = $.extend({}, this.options, options||{});
            this.item = item;
            this.callback = callback;

            var $this = this;

            if(this.options.id == null)
            {
                if(this.options.debug) console.error('Need implement id paramenter');
                throw {
                    success: false,
                    message: 'Need implement id paramenter'
                }
            }

            $.ajax({
                url:						'/' + this.options.appName + '/forms/forms/init/form/' + this.options.id,
                type:						'GET',
                dataType:					'json',
                success: function(response)
                {
                    if(response.success)
                    {
                        $($this.item).attr('action', response.action);
                        $($this.item).prepend('<input type="hidden" name="_redirectOk">');
                        $($this.item).prepend('<input type="hidden" name="_fields">');
                        $($this.item).prepend('<input type="hidden" name="_tokenForm" value="' + response.token + '">');
                        $($this.item).prepend('<input type="hidden" name="_token" value="' + response.csfr + '">');

                        var data = [];
                        $($this.options.fields.data).each(function(){
                            if($('[name=' + this + ']').prop("type") != undefined)
                            {
                                var obj = {type: $('[name=' + this + ']').prop("type"), name: this};
                                if($('[name=' + this + ']').data('length') != undefined)
                                {
                                    obj.length = $('[name=' + this + ']').data('length');
                                }
                                data.push(obj);
                            }
                        });

                        $this.options.fields.data = data;

                        $("[name=_fields]", $this.item).val(JSON.stringify($this.options.fields));
                        $("[name=_redirectOk]", $this.item).val($this.options.redirectOk);
                    }
                    else
                    {
                        if(this.options.debug) console.error(response.message);
                        throw response;
                    }
                }
            });

            if(this.options.ajax)
            {
                $(this.item).on('submit', function(){
                    event.preventDefault();
                    $.ajax({
                        url:						$(this).attr('action'),
                        data:                       $(this).serializeArray(),
                        type:						$(this).attr('method'),
                        dataType:					'json',
                        success: function(response)
                        {
                            if($.isFunction($this.callback))
                            {
                                if($this.options.cleanFields)
                                {
                                    $('[name=' + $this.options.fields.subject + ']').val('');
                                    $('[name=' + $this.options.fields.name + ']').val('');
                                    $('[name=' + $this.options.fields.surname + ']').val('');
                                    $('[name=' + $this.options.fields.company + ']').val('');
                                    $('[name=' + $this.options.fields.email + ']').val('');
                                    $('[name=' + $this.options.fields.date + ']').val('');
                                    $('[name=' + $this.options.fields.cp + ']').val('');
                                    $('[name=' + $this.options.fields.locality + ']').val('');
                                    $('[name=' + $this.options.fields.address + ']').val('');
                                    $('[name=' + $this.options.fields.message + ']').val('');

                                    $($this.options.fields.data).each(function(){
                                        $('[name=' + this.name + ']').val('');
                                    });
                                }
                                $this.callback(response);
                            }
                        }
                    });
                });
            }
            return this;
        }
    };

    /*
     * Make sure Object.create is available in the browser (for our prototypal inheritance)
     * Note this is not entirely equal to native Object.create, but compatible with our use-case
     */
    if (typeof Object.create !== 'function')
    {
        Object.create = function (o) {
            function F() {}
            F.prototype = o;
            return new F();
        };
    }

    /*
     * Start the plugin
     */
    $.fn.forms = function(options, callback) {
        this.each(function() {
            if (!$.data(this, 'forms'))
            {
                try
                {
                    $.data(this, 'forms', Object.create(Forms).init(options, callback, this));
                }
                catch (err)
                {
                    if($.isFunction(callback))
                    {
                        callback(err);
                    }
                }
            }
        });
    };
}( jQuery ));