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
            tA1LabelPrefix:             '',
            tA1LabelSuffix:             '',
            tA2Label:                   'territorialArea2Label',                    // label Select territorial area 2
            tA2LabelPrefix:             '',
            tA2LabelSuffix:             '',
            tA3Label:                   'territorialArea3Label',                    // label Select territorial area 3
            tA3LabelPrefix:             '',
            tA3LabelSuffix:             '',

            countrySelect:              'country',                                  // name Select country
            prefixInput:                'prefix',                                   // name Select conutry
            tA1Select:                  'territorialArea1',                         // name Select territorial area 1
            tA2Select:                  'territorialArea2',                         // name Select territorial area 2
            tA3Select:                  'territorialArea3',                         // name Select territorial area 3

            nullValue:                  '',                                         // The best option is ''
            countryValue:               null,
            territorialArea1Value:      null,
            territorialArea2Value:      null,
            territorialArea3Value:      null,

            trans: {
                selectCountry:		    'Select a Country',
                selectA:		        'Select a '
            }
        },
        callback: null,

        init: function(options, callback)
        {
            this.options = $.extend({}, this.options, options||{});	                // Init options

            // hide wrappers
            $("#" + this.options.tA1Wrapper).hide();
            $("#" + this.options.tA2Wrapper).hide();
            $("#" + this.options.tA3Wrapper).hide();

            // set events on elements
            // when change country select
            $("[name='" + this.options.countrySelect + "']").change($.proxy(function() {
                // set country prefix
                if($("[name='" + this.options.countrySelect + "']").find('option:selected').data('prefix'))
                    $("[name='" + this.options.prefixInput + "']").val($("[name='" + this.options.countrySelect + "']").find('option:selected').data('prefix'));

                // when finish first fadeout we load name of label, like that we are sure that the efect fadeOut is run
                $("#" + this.options.tA1Wrapper).fadeOut(400, $.proxy(function() {

                    if($("[name='" + this.options.countrySelect + "']").val() != this.options.nullValue)
                    {
                        // check that territorial area label contain words
                        if($("[name='" + this.options.countrySelect + "']").find('option:selected').data('at1'))
                            $("#" + this.options.tA1Label).html(this.options.tA1LabelPrefix + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at1') + this.options.tA1LabelSuffix);
                        if($("[name='" + this.options.countrySelect + "']").find('option:selected').data('at2'))
                            $("#" + this.options.tA2Label).html(this.options.tA2LabelPrefix + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at2') + this.options.tA2LabelSuffix);
                        if($("[name='" + this.options.countrySelect + "']").find('option:selected').data('at3'))
                            $("#" + this.options.tA3Label).html(this.options.tA3LabelPrefix + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at3') + this.options.tA3LabelSuffix);

                        this.getTerritorialArea1();
                    }

                }, this));


                $("#" + this.options.tA2Wrapper).fadeOut(400);
                $("#" + this.options.tA3Wrapper).fadeOut(400);

            }, this));

            // when change territorial area 1 select
            $("[name='" + this.options.tA1Select + "']").change($.proxy(function() {
                if($("[name='" + this.options.tA1Select + "']").val() != this.options.nullValue)
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
                if($("[name='" + this.options.tA2Select + "']").val() != this.options.nullValue)
                {
                    this.getTerritorialArea3();
                }
                else
                {
                    $("#" + this.options.tA3Wrapper).fadeOut();
                }
            }, this));

            this.getCountries();

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
                var response = {
                    success: true,
                    message: 'GetAddress init'
                };

                this.callback(response);
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
                success: function(response) {
                    $("[name='" + this.options.countrySelect + "'] option").remove();
                    $("[name='" + this.options.countrySelect + "']").append(new Option(this.options.trans.selectCountry, this.options.nullValue));

                    var highlightCountry = false;

                    for(var i in this.options.highlightCountrys)
                    {
                        for(var j in response.data)
                        {
                            // check if this country is highlight
                            if(this.options.highlightCountrys[i] == response.data[j].id_002)
                            {
                                $("[name='" + this.options.countrySelect + "']")
                                    .append($('<option></option>').val(response.data[j].id_002).html(response.data[j].name_002).data('prefix', response.data[j].prefix_002).data('at1', response.data[j].territorial_area_1_002).data('at2', response.data[j].territorial_area_2_002).data('at3', response.data[j].territorial_area_3_002));
                                highlightCountry = true;
                            }
                        }
                    }

                    if(highlightCountry && this.options.useSeparatorHighlight)
                    {
                        $("[name='" + this.options.countrySelect + "']")
                            .append($('<option disabled></option>').html(this.options.textSeparatorHighlight));
                    }

                    for(var i in response.data)
                    {
                        // check if this country is highlight
                        if($.inArray(response.data[i].id_002, this.options.highlightCountrys) == -1)
                        {
                            $("[name='" + this.options.countrySelect + "']")
                                .append($('<option></option>').val(response.data[i].id_002).html(response.data[i].name_002).data('at1', response.data[i].territorial_area_1_002).data('at2', response.data[i].territorial_area_2_002).data('at3', response.data[i].territorial_area_3_002));
                        }
                    }

                    if(this.options.countryValue != null && this.options.countryValue != '')
                    {
                        $("[name='" + this.options.countrySelect + "']").val(this.options.countryValue).trigger("change");
                        this.options.countryValue = null;
                    }
                    else
                    {
                        $("[name='" + this.options.countrySelect + "']").val(this.options.nullValue).trigger("change");
                    }

                    if(this.callback != null)
                    {
                        var response = {
                            success: true,
                            message: 'Countries loaded'
                        };

                        this.callback(response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if(this.callback != null)
                    {
                        var response = {
                            success: false,
                            message: textStatus
                        };

                        this.callback(response);
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
                success: function(response) {

                    $("[name='" + this.options.tA1Select + "'] option").remove();

                    if(response.data.length > 0)
                    {
                        $("[name='" + this.options.tA1Select + "']").append(new Option(this.options.trans.selectA + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at1'), this.options.nullValue));

                        for(var i in response.data)
                        {
                            $("[name='" + this.options.tA1Select + "']").append(new Option(response.data[i].name_003, response.data[i].id_003));
                        }

                        // check if need set value from Territorial Area 1
                        if(this.options.territorialArea1Value != null && this.options.territorialArea1Value != '')
                        {
                            $("[name='" + this.options.tA1Select + "']").val(this.options.territorialArea1Value).trigger("change");
                            this.options.territorialArea1Value = null;
                        }
                        else
                        {
                            // reset value territorialArea 1
                            $("[name='" + this.options.tA1Select + "']").val(this.options.nullValue).trigger("change");
                        }

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
                        var response = {
                            success: true,
                            message: 'TerritorialArea1 loaded'
                        };

                        this.callback(response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if(this.callback != null)
                    {
                        var response = {
                            success: false,
                            message: textStatus
                        };

                        this.callback(response);
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
                success: function(response) {
                    $("[name='" + this.options.tA2Select + "'] option").remove();
                    if(response.data.length > 0)
                    {
                        $("[name='" + this.options.tA2Select + "']").append(new Option(this.options.trans.selectA + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at2'), this.options.nullValue));
                        for(var i in response.data)
                        {
                            $("[name='" + this.options.tA2Select + "']").append(new Option(response.data[i].name_004, response.data[i].id_004));
                        }

                        // check if need set value from Territorial Area 2
                        if(this.options.territorialArea2Value != null && this.options.territorialArea2Value != '')
                        {
                            $("[name='" + this.options.tA2Select + "']").val(this.options.territorialArea2Value).trigger("change");
                            this.options.territorialArea2Value = null;
                        }
                        else
                        {
                            // reset value territorialArea 2
                            $("[name='" + this.options.tA2Select + "']").val(this.options.nullValue).trigger("change");
                        }

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
                        var response = {
                            success: true,
                            message: 'TerritorialArea2 loaded'
                        };

                        this.callback(response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if(this.callback != null)
                    {
                        var response = {
                            success: false,
                            message: textStatus
                        };

                        this.callback(response);
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
                success: function(response) {
                    $("[name='" + this.options.tA3Select + "'] option").remove();
                    if(response.data.length > 0)
                    {
                        $("[name='" + this.options.tA3Select + "']").append(new Option(this.options.trans.selectA + $("[name='" + this.options.countrySelect + "']").find('option:selected').data('at3'), this.options.nullValue));
                        for(var i in response.data)
                        {
                            $("[name='" + this.options.tA3Select + "']").append(new Option(response.data[i].name_005, response.data[i].id_005));
                        }

                        // check if need set value from Territorial Area 3
                        if(this.options.territorialArea3Value != null && this.options.territorialArea3Value != '')
                        {
                            $("[name='" + this.options.tA3Select + "']").val(this.options.territorialArea3Value).trigger("change");
                            this.options.territorialArea3Value = null;
                        }
                        else
                        {
                            // reset value territorialArea 3
                            $("[name='" + this.options.tA3Select + "']").val(this.options.nullValue).trigger("change");
                        }

                        $("#" + this.options.tA3Wrapper).fadeIn();
                    }
                    else
                    {
                        $("#" + this.options.tA3Wrapper).fadeOut();
                        this.deleteTerritorialArea3();
                    }

                    if(this.callback != null)
                    {
                        var response = {
                            success: true,
                            message: 'TerritorialArea3 loaded'
                        };

                        this.callback(response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if(this.callback != null)
                    {
                        var response = {
                            success: false,
                            message: textStatus
                        };

                        this.callback(response);
                    }
                }
            });
        },

        deleteTerritorialArea1: function()
        {
            $("[name='" + this.options.tA1Select + "'] option").remove();
        },

        deleteTerritorialArea2: function()
        {
            $("[name='" + this.options.tA2Select + "'] option").remove();
        },

        deleteTerritorialArea3: function()
        {
            $("[name='" + this.options.tA3Select + "'] option").remove();
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
        if (!$.data(document, 'getAddress'))
        {
            if(options.id == null)
            {
                $.data(document, 'getAddress', Object.create(GetAddress).init(options, callback));
            }
            else
            {
                $.data(document, 'getAddress' + options.id, Object.create(GetAddress).init(options, callback));
            }
        }
    };

    /*
     * Set new values
     */
    $.getAddress.setOptions = function(options, callback) {

        // Get object
        var obj = $.data(document, 'getAddress' + options.id);

        // extend properties and execute country change to set new values
        obj.options =  $.extend({}, obj.options, options || {});
        if(obj.options.countryValue != null && obj.options.countryValue != '')
        {
            $("[name='" + obj.options.countrySelect + "']").val(obj.options.countryValue).trigger("change");
            obj.options.countryValue = null;
        }
    };

}( jQuery ));