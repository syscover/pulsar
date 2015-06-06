/*
 *	CssLoader v1.3 - 2015-05-12
 *	Loader build on css3
 *	By Jose Carlos Rodriguez
 *	(c) 2014 Syscover S.L. - http://www.syscover.com/
 *	All rights reserved
 */

"use strict";

(function ( $ ) {
    var CssLoader = {
        options: {
            urlPlugin:                  '.',
            theme:					    'bubbles',		                            // URL relative where is Get File plugin folder
            spinnerColor:               '#333',
            delay:						250,										// Path to the target folder (from the document root)
            useLayer:					true,
            layerColor:					'#ffffff',
            isImage:                    false
        },

        properties: {
            spinner:        null,
            spinnerPosY:    null,
            loaded:         false
        },

        callback: null,

        init: function(options, callback, onLoadPage)
        {
            this.options = $.extend({}, this.options, options||{});	// Options load

            $.ajax({
                async:      true,
                cache:      false,
                dataType:   'html',
                context:	this,
                type:       'POST',
                url:        this.options.urlPlugin + '/cssloader/themes/' + this.options.theme + '/elements.php',
                data:       this.options,
                success:  function(data)
                {
                    $('body').prepend(data);

                    this.properties.spinner = $('#loading-spinner'); // Get spinner object

                    if(this.options.isImage)
                    {
                        var img = this.properties.spinner.find('img');
                        $(img).attr('src', this.options.urlPlugin + '/cssloader/themes/' + this.options.theme + '/' + $(img).attr('src'));
                    }

                    var isIE10 = !!navigator.userAgent.match(/Trident.*rv[ :]*10\./);
                    var isIE11 = !!navigator.userAgent.match(/Trident.*rv[ :]*11\./);
                    if(isIE10 || isIE11)
                    {
                        var windowHeight = $(window).height() * 0.4;
                        this.properties.spinner.css('top', windowHeight + 'px');
                    }

                    this.properties.spinnerPosY = parseInt(this.properties.spinner.css('top'), 10); // Get initial position from top

                    this.callback = callback; // Callback instantiation

                    if(onLoadPage)
                    {
                        $('#loading-spinner').css('top', this.properties.spinnerPosY + $(window).scrollTop() + 'px');

                        $(window).scroll($.proxy(this.updateSpinnerIconPosition, this));

                        $(window).on('load', $.proxy(this.loaded, this));

                        // after n seconds check if the loader is necessary.
                        // if there are few elements can produce the 'load' event before loading cssLoader pluging, then it never hide.
                        var that = this;
                        setTimeout(function() {
                            if (document.readyState === "complete" && that.properties.loaded == false) {
                                that.loaded();
                            }
                        }, 1000);
                    }
                    else
                    {
                        if(this.callback != null)
                        {
                            this.callback();
                        }
                    }
                },
                error:function(e)
                {
                    //error
                }
            });

            return this;
        },

        loaded: function()
        {
            this.properties.spinner.fadeOut(this.options.delay + 200); // Fades out the loading animation
            $('#pre-cssloader').delay(this.options.delay).fadeOut('slow'); // Fades out the div that covers the website

            var that = this;
            setTimeout(function()
            {
                $('body').css({'overflow': 'visible'});
                $(window).off('scroll', null, that.updateSpinnerIconPosition); //reset scroll event
                //$(window).unbind('scroll', that.updateSpinnerIconPosition); //alternative way to reset scroll event
            }, this.options.delay);

            this.properties.loaded = true;

            if(this.callback != null)
            {
                this.callback();
            }
        },

        updateSpinnerIconPosition: function(event)
        {
            $('#loading-spinner').css('top', this.properties.spinnerPosY + $(window).scrollTop() + 'px');
        },

        show: function(options, callback)
        {
            this.options = $.extend({}, this.options, options||{});	// Options load

            if(this.options.useLayer)
            {
                if($('#pre-cssloader').length == 0)
                {
                    $('body').prepend('<div id="pre-cssloader" style="display: none"></div>');
                }
                $('#pre-cssloader').css('background-color', this.options.layerColor);
                $('#pre-cssloader').fadeIn();
            }

            $('#loading-spinner').css('top', this.properties.spinnerPosY + $(window).scrollTop() + 'px');
            $('#loading-spinner').show();

            var that = this;
            $(window).scroll(function(event)
            {
                $('#loading-spinner').css('top', that.properties.spinnerPosY + $(window).scrollTop() + 'px');
            });

            this.callback = callback;
            if(this.callback != null)
            {
                this.callback();
            }
        },

        hide: function(callback)
        {
            if(this.options.useLayer)
            {
                $('#pre-cssloader').delay(this.options.delay).fadeOut('slow'); // Fades out the div that covers the website
            }

            this.properties.spinner.fadeOut(this.options.delay + 200); // Fades out the loading animation

            var that = this;
            setTimeout(function()
            {
                $('#loading-spinner').css('top', that.properties.spinnerPosY);
            }, this.options.delay + 200);

            this.callback = callback;
            if(this.callback != null)
            {
                this.callback();
            }
        }
    };

    /*
     * Make sure Object.create is available in the browser (for our prototypal inheritance)
     * Note this is not entirely equal to native Object.create, but compatible with our use-case
     */
    if (typeof Object.create !== 'function') {
        Object.create = function (o) {
            function F() {}
            F.prototype = o;
            return new F();
        };
    }

    /*
     * Start the plugin
     */
    $.cssLoader = function(options, callback) {
        if (!$.data(document, 'cssLoader'))
        {
            $.data(document, 'cssLoader', Object.create(CssLoader).init(options, callback, true));
        }
    };
    //public methods
    $.cssLoader.show = function(options, callback) {
        if (!$.data(document, 'cssLoader')) {
            $.cssLoader = $.data(document, 'cssLoader', Object.create(CssLoader).init(options, callback, false));

        }
        $.data(document, 'cssLoader').show(options, callback);
    };

    $.cssLoader.hide = function(callback) {
        $.data(document, 'cssLoader').hide(callback);
    };

}( jQuery ));