/*
 *	getAddress v1.0 - 2015-02-2
 *	Loader build on css3
 *	By Jose Carlos Rodriguez
 *	(c) 2014 Syscover S.L. - http://www.syscover.com/
 *	All rights reserved
 */

"use strict";

(function ( $ ) {
    var GetAddress = {
        options: {
            type:                       null,                                       // Set if the plugin is used on laravel or not, set value to "laravel" to config for pulsar
            urlPlugin:                  '.',
            appName:                    'pulsar',
            token:                      null,
            lang:                       'es',

            highlightCountrys:          ['ES'],                                     // Countrys that you want highlight

            useSeparatorHighlight:      false,
            textSeparatorHighlight:     '*********',

            tA1Wrapper:					'territorialArea1Wrapper',                  // ID Wrapper territorial area 1
            tA2Wrapper:					'territorialArea2Wrapper',	                // ID Wrapper territorial area 2
            tA3Wrapper:					'territorialArea3Wrapper',		            // ID Wrapper territorial area 3

            tA1Label:                   'territorialArea1Label',                    // label Select territorial area 1
            tA2Label:                   'territorialArea2Label',                    // label Select territorial area 2
            tA3Label:                   'territorialArea3Label',                    // label Select territorial area 3

            countrySelect:              'country',                                  // name Select conutry
            tA1Select:                  'territorialArea1',                         // name Select territorial area 1
            tA2Select:                  'territorialArea2',                         // name Select territorial area 2
            tA3Select:                  'territorialArea3',                         // name Select territorial area 3

            nullValue:                  'null',
            countryValue:               null,
            territorialArea1Value:      null,
            territorialArea2Value:      null,
            territorialArea3Value:      null,


            trans: {
                selectCountry:		    'Select a country',
                selectA:		        'Select a '
            }
        },
        /*
         properties: {
         spinner:        null,
         spinnerPosY:    null,
         loaded:         false
         },
         */
        callback: null,

        init: function(options, callback)
        {
            this.options = $.extend({}, this.options, options||{});	                // Init options

            // hide wrappers
            $("#" + this.options.tA1Wrapper).hide();
            $("#" + this.options.tA2Wrapper).hide();
            $("#" + this.options.tA3Wrapper).hide();

            this.getCountries();

            if(jQuery().select2) {
                $("[name='" + this.options.countrySelect + "']").select2();
            }


            // set events on elements
            // when change country select
            $("[name='" + this.options.countrySelect + "']").change($.proxy(function() {
                $("#" + this.options.tA1Wrapper).fadeOut();
                $("#" + this.options.tA2Wrapper).fadeOut();
                // when finish last fadeout we load name of label
                $("#" + this.options.tA3Wrapper).fadeOut(400, $.proxy(function() {

                    if($("[name='" + this.options.countrySelect + "']").val() != this.options.nullValue)
                    {
                        $("#" + this.options.tA1Label).html($("[name='" + this.options.countrySelect + "']").find('option:selected').data('at1'));
                        //$("[name='nameAreaTerritorial1']").val(data.area_territorial_1_002);
                        $("#" + this.options.tA2Label).html($("[name='" + this.options.countrySelect + "']").find('option:selected').data('at2'));
                        //$("[name='nameAreaTerritorial2']").val(data.area_territorial_2_002);
                        $("#" + this.options.tA3Label).html($("[name='" + this.options.countrySelect + "']").find('option:selected').data('at3'));
                        //$("[name='nameAreaTerritorial3']").val(data.area_territorial_3_002);

                        this.getTerritorialArea1();
                    }
                }, this));
            }, this));

            // when change territorial area 1 select
            $("[name='" + this.options.tA1Select + "']").change($.proxy(function() {
                if($("[name='" + this.options.tA1Select + "']").val() != 'null')
                {
                    this.getTerritorialArea2();
                }
                else
                {
                    $("#" + this.options.tA2Wrapper).fadeOut();
                    $("#" + this.options.tA3Wrapper).fadeOut();
                }
            }, this));

            // when change territorial area 2 select
            $("[name='" + this.options.tA2Select + "']").change($.proxy(function() {
                if($("[name='" + this.options.tA2Select + "']").val() != 'null')
                {
                    this.getTerritorialArea3();
                }
                else
                {
                    $("#" + this.options.tA3Wrapper).fadeOut();
                }
            }, this));

            // check if must to show any area territorial select
            if($("[name='" + this.options.countrySelect + "']").val() != 'null' && $("[name='" + this.options.tA1Select + "'] option").length > 1)
            {
                $("#" + this.options.tA1Wrapper).show();
            }

            if($("[name='" + this.options.tA1Select + "']").attr('value') != 'null' && $("[name='" + this.options.tA2Select + "'] option").length > 1)
            {
                $("#" + this.options.tA2Wrapper).show();
            }

            if($("[name='" + this.options.tA2Select + "']").attr('value') != 'null' && $("[name='" + this.options.tA3Select + "'] option").length > 1)
            {
                $("#" + this.options.tA3Wrapper).show();
            }

            this.callback = callback;

            if(this.callback != null)
            {
                var data = {
                    success: true,
                    action: 'init',
                    message: 'GetAddres init'
                };

                this.callback(data);
            }

            return this;
        },

        getCountries: function()
        {
            $.ajax({
                type: "POST",
                url: this.options.type == 'laravel'? '/' + this.options.appName + '/pulsar/countries/json/countries/' + this.options.lang : this.options.urlPlugin + '/getaddress/php/Controllers/Server.php',
                data: this.options.type == 'laravel'? {_token: this.options.token} : {lang : this.options.lang, action: 'getCountries'},
                dataType: 'json',
                context: this,
                success: function(data) {
                    $("[name='" + this.options.countrySelect + "'] option").remove();
                    $("[name='" + this.options.countrySelect + "']").append(new Option(this.options.trans.selectCountry, this.options.nullValue));

                    var highlightCountry = false;

                    for(var i in this.options.highlightCountrys)
                    {
                        for(var j in data)
                        {
                            // check if this country is highlight
                            if(this.options.highlightCountrys[i] == data[j].id_002)
                            {
                                $("[name='" + this.options.countrySelect + "']")
                                    .append($('<option></option>').val(data[j].id_002).html(data[j].name_002).data('at1', data[j].territorial_area_1_002).data('at2', data[j].territorial_area_2_002).data('at3', data[j].territorial_area_3_002));
                                highlightCountry = true;
                            }
                        }
                    }

                    if(highlightCountry && this.options.useSeparatorHighlight)
                    {
                        $("[name='" + this.options.countrySelect + "']")
                            .append($('<option disabled></option>').html(this.options.textSeparatorHighlight));
                    }

                    for(var i in data)
                    {
                        // check if this country is highlight
                        if($.inArray(data[i].id_002, this.options.highlightCountrys) == -1)
                        {
                            $("[name='" + this.options.countrySelect + "']")
                                .append($('<option></option>').val(data[i].id_002).html(data[i].name_002).data('at1', data[i].territorial_area_1_002).data('at2', data[i].territorial_area_2_002).data('at3', data[i].territorial_area_3_002));
                        }
                    }
                }
            });
        },

        getTerritorialArea1: function()
        {
            $.ajax({
                type: "POST",
                url: this.options.type == 'laravel'? '/' + this.options.appName + '/pulsar/territorialareas1/json/from/country/' + $("[name='" + this.options.countrySelect + "']").val() : this.options.urlPlugin + '/getaddress/php/Controllers/Server.php',
                data: this.options.type == 'laravel'? {_token: this.options.token} : {country : $("[name='" + this.options.countrySelect + "']").val(), action: 'getTerritorialArea1'},
                dataType: 'json',
                context: this,
                success: function(data) {

                    $("[name='" + this.options.tA1Select + "'] option").remove();
                    if(data.length > 0)
                    {
                        $("[name='" + this.options.tA1Select + "']").append(new Option(this.options.trans.selectA + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at1'), this.options.nullValue));
                        for(var i in data)
                        {
                            $("[name='" + this.options.tA1Select + "']").append(new Option(data[i].name_003, data[i].id_003));
                        }
                        //$("[name='" + this.options.tA1Select + "']").val("SS").trigger("change");
                        $("#" + this.options.tA1Wrapper).fadeIn();
                    }
                    else
                    {
                        $("#" + this.options.tA1Wrapper).fadeOut();
                        this.deleteTerritorialArea1();
                        $("#" + this.options.tA2Wrapper).fadeOut();
                        this.deleteTerritorialArea2();
                        $("#" + this.options.tA3Wrapper).fadeOut();
                        this.deleteTerritorialArea3();
                    }

                    if(this.callback != null)
                    {
                        var data = {
                            success: true,
                            action: 'tA1Loaded',
                            message: 'TerritorialArea1 loaded'
                        };

                        this.callback(data);
                    }
                }
            });
        },

        getTerritorialArea2: function()
        {
            $.ajax({
                type: "POST",
                url: this.options.type == 'laravel'? '/' + this.options.appName + '/pulsar/territorialareas2/json/from/territorialarea1/' + $("[name='" + this.options.countrySelect + "']").val() + '/' + $("[name='" + this.options.tA1Select + "']").val() : this.options.urlPlugin + '/getaddress/php/Controllers/Server.php',
                data: this.options.type == 'laravel'? {_token: this.options.token} : {territorialArea1 : $("[name='" + this.options.tA1Select + "']").val(), action: 'getTerritorialArea2'},
                dataType: 'json',
                context: this,
                success: function(data) {
                    $("[name='" + this.options.tA2Select + "'] option").remove();
                    if(data.length > 0)
                    {
                        $("[name='" + this.options.tA2Select + "']").append(new Option(this.options.trans.selectA + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at2'), this.options.nullValue));
                        for(var i in data)
                        {
                            $("[name='" + this.options.tA2Select + "']").append(new Option(data[i].name_004, data[i].id_004));
                        }
                        //$("[name='" + this.options.tA2Select + "']").select2("val", "null");
                        $("#" + this.options.tA2Wrapper).fadeIn();
                    }
                    else
                    {
                        $("#" + this.options.tA2Wrapper).fadeOut();
                        this.deleteTerritorialArea2();
                        $("#" + this.options.tA3Wrapper).fadeOut();
                        this.deleteTerritorialArea3();
                    }

                    if(this.callback != null)
                    {
                        var data = {
                            success: true,
                            action: 'tA2Loaded',
                            message: 'TerritorialArea2 loaded'
                        };

                        this.callback(data);
                    }
                }
            });
        },

        getTerritorialArea3: function()
        {
            $.ajax({
                type: "POST",
                url: this.options.type == 'laravel'? '/' + this.options.appName + '/pulsar/territorialareas3/json/from/territorialarea2/' + $("[name='" + this.options.countrySelect + "']").val() + '/' + $("[name='" + this.options.tA2Select + "']").val() : this.options.urlPlugin + '/getaddress/php/Controllers/Server.php',
                data: this.options.type == 'laravel'? {_token: this.options.token} : {territorialArea2 : $("[name='" + this.options.tA2Select + "']").val(), action: 'getTerritorialArea3'},
                dataType: 'json',
                context: this,
                success: function(data) {
                    $("[name='" + this.options.tA3Select + "'] option").remove();
                    if(data.length > 0)
                    {
                        $("[name='" + this.options.tA3Select + "']").append(new Option(this.options.trans.selectA + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at3'), this.options.nullValue));
                        for(var i in data)
                        {
                            $("[name='" + this.options.tA3Select + "']").append(new Option(data[i].name_005, data[i].id_005));
                        }
                        //$("[name='" + this.options.tA3Select + "']").select2("val", "null");
                        $("#" + this.options.tA3Wrapper).fadeIn();
                    }
                    else
                    {
                        $("#" + this.options.tA3Wrapper).fadeOut();
                        this.deleteTerritorialArea3();
                    }

                    if(this.callback != null)
                    {
                        var data = {
                            success: true,
                            action: 'tA3Loaded',
                            message: 'TerritorialArea3 loaded'
                        };

                        this.callback(data);
                    }
                }
            });
        },

        deleteTerritorialArea1: function()
        {
            $("[name='" + this.options.tA1Select + "'] option").remove();
            $("[name='" + this.options.tA1Select + "']").append(new Option('Elija un/a aT1', 'null'));
        },

        deleteTerritorialArea2: function()
        {
            $("[name='" + this.options.tA2Select + "'] option").remove();
            $("[name='" + this.options.tA2Select + "']").append(new Option('Elija un/a aT2', 'null'));
        },

        deleteTerritorialArea3: function()
        {
            $("[name='" + this.options.tA3Select + "'] option").remove();
            $("[name='" + this.options.tA3Select + "']").append(new Option('Elija un/a aT3', 'null'));
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
    $.getAddress = function(options, callback) {
        if (!$.data(document, 'getAddress')) {

            if(options.id == null)
            {
                return $.data(document, 'getAddress', Object.create(GetAddress).init(options, callback));
            }
            else
            {
                return $.data(document, 'getAddress' + options.id, Object.create(GetAddress).init(options, callback));
            }
        }
    };

}( jQuery ));