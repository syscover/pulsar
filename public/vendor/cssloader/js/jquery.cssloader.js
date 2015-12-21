/*
 *	CssLoader v1.4.1 - 2015-09-1
 *	Loader build on css3
 *	By Jose Carlos Rodriguez
 *	(c) 2015 Syscover S.L. - http://www.syscover.com/
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

            var that = this;

            $.ajax({
                async:      true,
                cache:      false,
                dataType:   'html',
                type:       'POST',
                url:        this.options.urlPlugin + '/cssloader/themes/' + this.options.theme + '/elements.html',
                success:  function(data)
                {
                    $('body').prepend(that.replaceWildcard(data));

                    // Get spinner object
                    that.properties.spinner = $('#loading-spinner');

                    if(that.options.isImage)
                    {
                        var img = that.properties.spinner.find('img');
                        $(img).attr('src', that.options.urlPlugin + '/cssloader/themes/' + that.options.theme + '/' + $(img).attr('src'));
                    }

                    var isIE10 = !!navigator.userAgent.match(/Trident.*rv[ :]*10\./);
                    var isIE11 = !!navigator.userAgent.match(/Trident.*rv[ :]*11\./);
                    if(isIE10 || isIE11)
                    {
                        var windowHeight = $(window).height() * 0.4;
                        that.properties.spinner.css('top', windowHeight + 'px');
                    }

                    that.properties.spinnerPosY = parseInt(that.properties.spinner.css('top'), 10); // Get initial position from top

                    that.callback = callback; // Callback instantiation

                    if(onLoadPage)
                    {
                        $('#loading-spinner').css('top', that.properties.spinnerPosY + $(window).scrollTop() + 'px');

                        $(window).scroll(function(){
                            that.updateSpinnerIconPosition();
                        });

                        $(window).on('load', function(){
                            that.loaded();
                        });

                        // after n seconds check if the loader is necessary.
                        // if there are few elements can produce the 'load' event before loading cssLoader pluging, then it never hide.
                        setTimeout(function() {
                            if (document.readyState === "complete" && that.properties.loaded == false) {
                                that.loaded();
                            }
                        }, 1000);
                    }
                    else
                    {
                        if(that.callback != null)
                        {
                            that.callback();
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
            // Fades out the loading animation
            this.properties.spinner.fadeOut(this.options.delay + 200);
            // Fades out the div that covers the website
            $('#pre-cssloader').delay(this.options.delay).fadeOut('slow');

            var that = this;
            setTimeout(function()
            {
                $('body').css({'overflow': 'visible'});
                //reset scroll event
                $(window).off('scroll', null, that.updateSpinnerIconPosition);
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
            var that = this;

            if(this.options.useLayer)
            {
                $('#pre-cssloader').delay(this.options.delay).fadeOut('slow'); // Fades out the div that covers the website
            }

            this.properties.spinner.fadeOut(this.options.delay + 200); // Fades out the loading animation

            setTimeout(function()
            {
                $('#loading-spinner').css('top', that.properties.spinnerPosY);
            }, this.options.delay + 200);

            this.callback = callback;
            if(this.callback != null)
            {
                this.callback();
            }
        },

        replaceWildcard: function(html){

            if(this.options.theme == 'fine')
            {
                var rgb = this.hexToRgb(this.options.spinnerColor);
                this.options.spinnerColor = rgb.r + ',' + rgb.g + ',' + rgb.b;
            }

            html = this.replaceAll(html, '$spinnerColor', this.options.spinnerColor);

            return html;
        },

        replaceAll: function(text, search, replace ){
            while (text.toString().indexOf(search) != -1)
            {
                text = text.toString().replace(search, replace);
            }
            return text;
        },

        hexToRgb: function(hex) {
            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }
    };

    /*
     * Make sure Object.create is available in the browser (for our prototypal inheritance)
     * Note this is not entirely equal to native Object.create, but compatible with our use-case
     */
    if(typeof Object.create !== 'function') {
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
        if(!$.data(document, 'cssLoader')) {
            $.data(document, 'cssLoader', Object.create(CssLoader).init(options, callback, true));
        }
    };
    //public methods
    $.cssLoader.show = function(options, callback) {
        if(!$.data(document, 'cssLoader')) {
            $.cssLoader = $.data(document, 'cssLoader', Object.create(CssLoader).init(options, callback, false));

        }
        $.data(document, 'cssLoader').show(options, callback);
    };

    $.cssLoader.hide = function(callback) {
        $.data(document, 'cssLoader').hide(callback);
    };

}( jQuery ));