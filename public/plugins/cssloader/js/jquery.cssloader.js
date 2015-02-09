/*
 *	cssLoader v1.0 - 2014-09-19
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
            theme:					    'bubbles',		                                // URL relative where is Get File plugin folder
            spinnerColor:               '#333',
            delay:						250,										// Path to the target folder (from the document root)
            useLayer:					true,
            layerColor:					'#ffffff'
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
                async:      false,
                cache:      false,
                dataType:   'html',
                context:	this,
                type:       'POST',
                url:        this.options.urlPlugin + '/cssloader/themes/' + this.options.theme + '/elements.php',
                data:       this.options,
                success:  function(data)
                {
                    $('body').prepend(data);

                    this.properties.spinner     = $('#loading-spinner');
                    this.properties.spinnerPosY = parseInt(this.properties.spinner.css('top'), 10);         // Get initial position from top
                },
                error:function(objXMLHttpRequest)
                {
                    //error
                }
            });

            this.callback = callback;												// Callback instantiation


            if(onLoadPage != false)
            {
                $(window).on('load', $.proxy(this.loaded, this));

                // after n seconds check if the loader is necessary.
                // if there are few elements can produce the load event before loading windows loader, then it never hide.
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

            return this;
        },

        loaded: function()
        {
            var that = this;

            $('#loading-spinner').css('top', this.properties.spinnerPosY + $(window).scrollTop() + 'px');

            this.properties.spinner.fadeOut(this.options.delay + 200); // Fades out the loading animation
            $('#pre-cssloader').delay(this.options.delay).fadeOut('slow'); // Fades out the div that covers the website

            $(window).scroll(function(event)
            {
                $('#loading-spinner').css('top', that.properties.spinnerPosY + $(window).scrollTop() + 'px');
            });

            setTimeout(function()
            {
                $('body').css({'overflow': 'visible'});
                $(window).off('scroll'); //reset scroll event
            }, this.options.delay);

            this.properties.loaded = true;

            if(this.callback != null)
            {
                this.callback();
            }
        },

        show: function(options, callback)
        {
            var that = this;

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
        if (!$.data(document, 'cssLoader')) {
            return $.data(document, 'cssLoader', Object.create(CssLoader).init(options, callback));
        }
    };
    //public methods
    $.cssLoader.show = function(options, callback) {
        if (!$.data(document, 'cssLoader')) {
            $.cssLoader = $.data(document, 'cssLoader', Object.create(CssLoader).init(options, callback, false));
        }
        else
        {
            $.data(document, 'cssLoader').show(options, callback);
        }
    };

    $.cssLoader.hide = function(callback) {
        $.data(document, 'cssLoader').hide(callback);
    };

}( jQuery ));