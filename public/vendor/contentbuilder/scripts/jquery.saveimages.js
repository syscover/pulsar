"use strict";

(function ($) {

    var SaveImages = {
        options: {
            handler:    null,
            customval:  0,
            csfr:       null,
            onComplete: null
        },
        properties: {
            element: null
        },
        init: function(options, element){
            this.options = $.extend({}, this.options, options||{});
            this.properties.element = element;

            return this;
        },
        save: function()
        {
            var that = this;

            //Get quality info (from content builder plugin)
            var hiquality = true;
            try
            {
                hiquality = this.properties.element.data('contentbuilder').settings.hiquality;
            }
            catch (e) {};

            var count = 0;

            //Check all images
            this.properties.element.find('img').not('#divCb img').each(function () {

                //Find base64 images
                if (jQuery(this).attr('src').indexOf('base64') != -1)
                {
                    count++;
                    //Read image (base64 string)
                    var image = jQuery(this).attr('src');
                    image = image.replace(/^data:image\/(png|jpeg);base64,/, "");

                    //Prepare form to submit image
                    if (jQuery('#form-' + count).length == 0) {
                        var s = '<form id="form-' + count + '" target="frame-' + count + '" method="post" enctype="multipart/form-data">' +
                            '<input name="_token" type="hidden" value="' + that.options.csfr + '" />' +
                            '<input id="hidimg-' + count + '" name="hidimg-' + count + '" type="hidden" />' +
                            '<input id="hidname-' + count + '" name="hidname-' + count + '" type="hidden" />' +
                            '<input id="hidtype-' + count + '" name="hidtype-' + count + '" type="hidden" />' +
                            '<input id="hidcustomval-' + count + '" name="hidcustomval-' + count + '" type="hidden" />' +
                            '<iframe id="frame-' + count + '" name="frame-' + count + '" style="width:1px;height:1px;border:none;visibility:hidden;position:absolute"></iframe>' +
                            '</form>';
                        jQuery('body').append(s);
                    }

                    //Give ID to image
                    jQuery(this).attr('id', 'img-' + count);

                    //Set hidden field with image (base64 string) to be submitted
                    jQuery('#hidimg-' + count).val(image);

                    //Set hidden field with custom value to be submitted
                    jQuery('#hidcustomval-' + count).val(that.options.customval);

                    //Set hidden field with file name to be submitted
                    var filename = '';
                    if (jQuery(this).data('filename') != undefined)
                    {
                        filename = jQuery(this).data('filename'); //get filename data from the imagemebed plugin
                    }
                    var filename_without_ext = filename.substr(0, filename.lastIndexOf('.')) || filename;
                    filename_without_ext = filename_without_ext.toLowerCase().replace(/ /g, '-');
                    jQuery('#hidname-' + count).val(filename_without_ext);

                    //Set hidden field with file extension to be submitted
                    if (hiquality) {
                        //If high quality is set true, set image as png
                        jQuery('#hidtype-' + count).val('png'); //high quality
                    }
                    else
                    {
                        //If high quality is set false, depend on image extension
                        var extension = filename.substr((filename.lastIndexOf('.') + 1));
                        extension = extension.toLowerCase();
                        if (extension == 'jpg' || extension == 'jpeg')
                        {
                            jQuery('#hidtype-' + count).val('jpg');
                        }
                        else
                        {
                            jQuery('#hidtype-' + count).val('png');
                        }
                    }

                    //Submit form
                    jQuery('#form-' + count).attr('action', that.options.handler + '?count=' + count);
                    jQuery('#form-' + count).submit();

                    //Note: the submitted image will be saved on the server
                    //by saveimage.php (if using PHP) or saveimage.ashx (if using .NET)
                    //and the image src will be changed with the new saved image.
                }
            });

            //Check per 2 sec if all images have been changed with the new saved images.
            var int = setInterval(function () {
                var finished = true;
                that.properties.element.find('img').not('#divCb img').each(function () {
                    if (jQuery(this).attr('src').indexOf('base64') != -1) { //if there is still base64 image, means not yet finished.
                        finished = false;
                    }
                });

                if (finished)
                {
                    //that.properties.element.data('saveimages').options.onComplete();
                    that.options.onComplete();
                    window.clearInterval(int);

                    //remove unused forms (previously used for submitting images)
                    for (var i = 1; i <= count; i++)
                    {
                        jQuery('#form-' + i).remove();
                    }
                }
            }, 2000);
        }
    }


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

    $.fn.saveImages = function(options) {

        if (!this.data('saveImages'))
        {
            // return element
            return this.data('saveImages', Object.create(SaveImages).init(options, this));
        }
        else
        {
            // return value of data-saveImages
            return this.data('saveImages');
        }
    };

}( jQuery ));