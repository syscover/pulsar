/*
 *	ElementTable v1.0 - 2015-06-1
 *	(c) 2015 Syscover S.L. - http://www.syscover.com/
 *	All rights reserved
 */

"use strict";

(function ($) {
    var ElementTable = {
        options: {
            id:					        '',
            lang: {
                editRecord:		        'Edit',
                deleteRecord:			'Remove'
            }
        },
        callback: null,

        init: function(options, callback)
        {
            // Extend options load
            this.options = $.extend({}, this.options, options||{});
            var $this = this;

            $('.mfp-cusstom-close').on('click', function(){
                $.magnificPopup.close();
            });

            $("#" + this.options.id + "Bt").magnificPopup({
                items: {
                    type: 'inline',
                    src: '#' + $this.options.id + 'Popup'
                },
                removalDelay: 300,
                mainClass: 'mfp-fade',
                callbacks: {
                    open: function() {
                        // set input to empty
                        $("#" + $this.options.id + "Form input").val('');

                        // show add and hide update button
                        $('.mfp-cusstom-update').hide();
                        $('.mfp-cusstom-add').show();
                    }
                }
            });

            $('.mfp-cusstom-add').on('click', function(){
                if ($.fn.validate){
                    if($("#" + $this.options.id + "Form").valid()){
                        $this.addElement();
                        $.magnificPopup.close();
                    }
                }
            });

            $('.mfp-cusstom-update').on('click', function(){
                if ($.fn.validate){
                    if($("#" + $this.options.id + "Form").valid()){
                        $this.updateElement();
                        $.magnificPopup.close();
                    }
                }
            });

            this.callback = callback;

            if(this.callback != null)
            {
                var response = {
                    success: true,
                    message: 'ElementTable init'
                };

                this.callback(response);
            }

            return this;
        },

        editElement: function($that)
        {
            $('.mfp-cusstom-update').show();
            $('.mfp-cusstom-add').hide();

            var index = $($that).closest("tr").index();
            $("[name=" + this.options.id + "Index]").val(index);
            var tBody = JSON.parse($("[name=" + this.options.id + "TBody]").val());
            var data = JSON.parse($("[name=" + this.options.id + "Data]").val());

            for(var i=0; i < tBody.length; i++)
            {
                if(tBody[i].properties.name != undefined)
                {
                    // set value on input from json data
                    $("[name=" + tBody[i].properties.name + "]").val(data[(index - 1)][tBody[i].properties.name]);
                }
            }
        },

        addElement: function()
        {
            var tBody = JSON.parse($("[name=" + this.options.id + "TBody]").val());
            var row = '<tr>';
            var dataRow = {};
            for(var i=0; i < tBody.length; i++)
            {
                if(tBody[i].properties.name != undefined)
                {
                    // check if td has a class
                    var $class = tBody[i].class != undefined? ' class="' + tBody[i].class + '"' : '';
                    // get value from input and create td
                    row += '<td' + $class + '>' + $("[name=" + tBody[i].properties.name + "]").val() + '</td>';
                    // get value to construct json object
                    dataRow[tBody[i].properties.name] =  $("[name=" + tBody[i].properties.name + "]").val()
                    // reset input value
                    $("[name=" + tBody[i].properties.name + "]").val('');
                }
            }

            // get data
            var data = JSON.parse($("[name=" + this.options.id + "Data]").val());
            data.push(dataRow);
            $("[name=" + this.options.id + "Data]").val(JSON.stringify(data));

            $("#" + this.options.id + "Popup .form-group").removeClass('has-success');

            row += this.getActions();
            row += '</tr>';
            $("#" + this.options.id + " tr:last").after(row);

            // add magnificPopup to edit button
            $(".btn-edit-" + this.options.id + "-popup").magnificPopup({
                items: {
                    type: 'inline',
                    src: "#" + this.options.id + "Popup"
                },

                removalDelay: 300,
                mainClass: 'mfp-fade'
            });
        },

        updateElement: function()
        {
            // get index value
            var index = $("[name=" + this.options.id + "Index]").val();
            var tBody = JSON.parse($("[name=" + this.options.id + "TBody]").val());
            var dataRow = {};

            for(var i=0; i < tBody.length; i++)
            {
                if(tBody[i].properties.name != undefined)
                {
                    // set value on td
                    $("#" + this.options.id + " tr:eq(" + index + ") td:eq(" + i + ")").html($("[name=" + tBody[i].properties.name + "]").val());

                    // get value to construct json object
                    dataRow[tBody[i].properties.name] =  $("[name=" + tBody[i].properties.name + "]").val();

                    // reset input value
                    $("[name=" + tBody[i].properties.name + "]").val('');
                }
            }

            // get data
            var data = JSON.parse($("[name=" + this.options.id + "Data]").val());
            data[(index-1)] = dataRow;
            $("[name=" + this.options.id + "Data]").val(JSON.stringify(data));

            $("#" + this.options.id + "Popup .form-group").removeClass('has-success');
        },

        deleteElement: function($this)
        {
            // get tr index
            var index = $($this).closest("tr").index() - 1;
            // delete tr
            $($this).closest("tr").remove();
            // get data
            var data = JSON.parse($("[name=" + this.options.id + "Data]").val());
            // delete tr from data
            data.splice(index, 1);
            // set data
            $("[name=" + this.options.id + "Data]").val(JSON.stringify(data));

        },

        getActions: function()
        {
            return  '<td class="align-center">' + '<ul class="table-controls">' +
                    '<li><a href="javascript:void(0);" onclick="$.elementTable.editElement(\''+ this.options.id +'\', this)" class="btn btn-xs bs-tooltip btn-edit-' + this.options.id + '-popup" data-original-title="' + this.options.lang.editRecord + '"><i class="icon-pencil"></i></a></li>' +
                    '<li><a href="javascript:void(0);" onclick="$.elementTable.deleteElement(\''+ this.options.id +'\', this)" class="btn btn-xs bs-tooltip" data-original-title="' + this.options.lang.deleteRecord + '"><i class="icon-trash"></i></a></li>' +
                    '</ul>' + '</td>';
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
    $.elementTable = function(options, callback) {
        if (!$.data(document, 'elementTable'))
        {
            if(options.id == null)
            {
                $.data(document, 'elementTable', Object.create(ElementTable).init(options, callback));
            }
            else
            {
                $.data(document, 'elementTable' + options.id, Object.create(ElementTable).init(options, callback));
            }
        }
    };

    /*
     * Edit element
     */
    $.elementTable.editElement = function(id, $this) {
        $.data(document, 'elementTable' + id).editElement($this)
    };

    /*
     * Delete element
     */
    $.elementTable.deleteElement = function(id, $this) {
        $.data(document, 'elementTable' + id).deleteElement($this)
    };

}( jQuery ));