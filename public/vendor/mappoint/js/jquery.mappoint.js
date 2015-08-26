/*
 *	mappoint v1.0 - 2015-09-1
 *	Loader build on css3
 *	(c) 2015 Syscover S.L. - http://www.syscover.com/
 *	All rights reserved
 */

"use strict";

(function ( $ ) {
    var MapPoint = {
        options: {
            urlPlugin:                  '.',
            locationMapWrapper:         'locationMapWrapper',
            inputSearchId:              'locationSearch',
            latitudeInput:              'latitude',
            longitudeInput:             'longitude',
            lat:                        40.434767,
            lng:                        -3.690826,
            zoom:                       5,
            searcher:                   true,
            style:                      null,
            icon: {
                dotColor:       '#000000',
                fillColor:      '#E81E25',
                borderColor:    '#000000',
                shadow:         true
            },
            customIcon: {
                src:            null,
                width:          null,
                height:         null,
                scaledWidth:    null,
                scaledHeight:   null,
                originX:        0,
                originY:        0,
                anchorX:        0,
                anchorY:        0,
                shape:          null
            },
            trans: {
                search:		    'Search'
            }
        },
        properties:{
            map:            null,
            markers:        [],
            inputSearch:    null,
            searchBox:      null,
            icon:           '<svg width="103px" height="88px" viewBox="0 0 103 88" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"><g id="icon" sketch:type="MSLayerGroup" transform="translate(3.000000, 3.000000)"><path d="M24.0820482,76.6819167 C22.3036265,68.0657172 19.1680148,60.8955193 15.3700941,54.250033 C12.5529981,49.3206691 9.28953058,44.7707691 6.26998471,39.9906825 C5.26200139,38.3949508 4.39210426,36.7091638 3.42352827,35.0530783 C1.48683503,31.7416769 -0.0834022243,27.9023041 0.0163776645,22.9220039 C0.113863763,18.0559821 1.53982159,14.1526333 3.59606719,10.9611699 C6.9779861,5.71201677 12.642823,1.40828619 20.243665,0.277274455 C26.4582776,-0.64745668 32.2849184,0.914860816 36.416861,3.29940302 C39.7933207,5.24802124 42.4080584,7.85093292 44.395582,10.9185646 C46.4700862,14.120351 47.8986131,17.9029469 48.0184407,22.8368385 C48.0797766,25.3646361 47.66038,27.7055316 47.0695454,29.6472677 C46.4716001,31.6126836 45.5099513,33.2555936 44.6543675,35.0104729 C42.9842127,38.436062 40.8903031,41.5747781 38.7889157,44.7153505 C32.5297576,54.0697865 26.6549472,63.6097663 24.0820482,76.6819167 L24.0820482,76.6819167 Z" id="marker_main" stroke="#000000" stroke-width="3" fill="#E81E25"></path><path d="M24.1021877,80.9731509 C30.1396279,75.500829 33.7963888,70.2888932 36.4491117,65.1041235 C38.416817,61.2582309 39.6852831,57.4448468 41.3556673,53.6010822 C41.9133336,52.3179407 42.6634949,51.0398703 43.3062609,49.7389804 C44.5916094,47.1378798 46.6394597,44.3903091 51.0606492,41.6753829 C55.3803156,39.0227121 59.9584921,37.4304215 64.4371182,36.4822823 C71.8031677,34.9228625 80.2311047,34.793507 87.4939796,37.1882817 C93.4323281,39.1463175 96.8835653,42.3257826 98.2187349,45.2866065 C99.3097994,47.7061023 99.2017618,50.1848653 98.1707947,52.6713706 C97.0947776,55.2666305 94.9793063,57.9264097 90.7799722,60.7030481 C88.6284884,62.1255059 86.2428545,63.2536199 84.063203,64.0928234 C81.857127,64.9422594 79.6315996,65.4688726 77.3961631,66.0994863 C73.0325019,67.330469 68.5687396,68.2344181 64.0971784,69.1364656 C50.7783753,71.8231392 37.6153207,74.7653091 24.1021877,80.9731509 L24.1021877,80.9731509 Z" id="marker_shadow" opacity="0.09" fill="#000000"></path><ellipse id="marker_dot" fill="#000000" cx="24.0180973" cy="22.8668569" rx="8.41055005" ry="8.30071157"></ellipse></g></svg>'
        },
        callback: null,

        init: function(options, callback)
        {
            // extend options.icon
            if(options.icon != undefined) options.icon = $.extend({}, this.options.icon, options.icon||{});

            // extend options.customIcon
            if(options.customIcon != undefined) options.customIcon = $.extend({}, this.options.customIcon, options.customIcon||{});

            this.options = $.extend({}, this.options, options||{});

            // activate input searcher
            if(this.options.searcher)
            {
                // create and get input text to search location
                $('body').append('<input id="' + this.options.inputSearchId + '" class="form-control input-search" name="" type="text" placeholder="' + this.options.trans.search + '">');

                // catch event keypress enter, if map is inside a form to disabled submit
                $("#" + this.options.inputSearchId).keypress(function(event){
                    if (event.which == 13) event.preventDefault();
                });

                this.properties.inputSearch = document.getElementById(this.options.inputSearchId);
            }

            // set icon application
            var icon, shape;
            if(this.options.customIcon.src == null)
            {
                var iconDom = $.parseHTML(this.properties.icon);

                $(iconDom).find('#marker_dot').attr('fill', this.options.icon.dotColor);
                $(iconDom).find('#marker_main').attr('fill', this.options.icon.fillColor);
                $(iconDom).find('#marker_main').attr('stroke', this.options.icon.borderColor);
                if(!this.options.icon.shadow) $(iconDom).find('#marker_shadow').attr('display', 'none');

                iconDom = $(iconDom).wrap('<p/>').parent().html();
                iconDom = btoa(iconDom);

                icon = {
                    url: 'data:image/svg+xml;base64,' + iconDom,
                    size: new google.maps.Size(103, 88),
                    origin: new google.maps.Point(0,0),
                    anchor: new google.maps.Point(14, 46),
                    scaledSize: new google.maps.Size(55, 47)
                };

                shape = {
                    coords: [0, 0, 32, 0, 32, 32, 0, 32],
                    type: 'poly'
                };
            }
            else
            {
                icon = {
                    url: this.options.customIcon.src,
                    origin: new google.maps.Point(this.options.customIcon.originX, this.options.customIcon.originY),
                    anchor: new google.maps.Point(this.options.customIcon.anchorX, this.options.customIcon.anchorY),
                    scaledSize: new google.maps.Size(this.options.customIcon.scaledWidth, this.options.customIcon.scaledHeight)
                };

                // only if the image is a sprite
                if(this.options.customIcon.width != null && this.options.customIcon.height != null)
                    icon.size = new google.maps.Size(this.options.customIcon.width, this.options.customIcon.height);

                shape = this.options.customIcon.shape;
            }

            // create map options
            var mapOptions = {
                center: { lat: this.options.lat, lng: this.options.lng},
                zoom: this.options.zoom,
                scrollwheel: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL,
                    position: google.maps.ControlPosition.RIGHT_BOTTOM
                },
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_BOTTOM
                }
            };

            // create Google map
            this.properties.map = new google.maps.Map(document.getElementById(this.options.locationMapWrapper), mapOptions);

            // Opción para dar etilo al mapa
            if(this.options.style != null) this.properties.map.setOptions({styles: this.options.style});

            // activate places on input search
            this.properties.searchBox = new google.maps.places.SearchBox(this.properties.inputSearch);

            // add input to google maps
            this.properties.map.controls[google.maps.ControlPosition.TOP_LEFT].push(this.properties.inputSearch);

            // Listen for the event fired when the user selects an item from the
            // pick list. Retrieve the matching places for that item.
            var that = this;
            google.maps.event.addListener(this.properties.searchBox, 'places_changed', function() {
                var places = that.properties.searchBox.getPlaces();

                if (places.length == 0) return;

                that.properties.markers.forEach(function(marker) {
                    marker.setMap(null);
                });
                that.properties.markers = [];

                // set map limits
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {

                    // Create a marker for each place.
                    var marker = new google.maps.Marker({
                        map:        that.properties.map,
                        icon:       icon,
                        shape:      shape,
                        draggable:  true,
                        clickable:  true,
                        title:      place.name,
                        position:   place.geometry.location
                    });

                    that.properties.markers.push(marker);

                    bounds.extend(place.geometry.location);

                    google.maps.event.addListener(marker, 'drag', function(){
                        this.setAnimation(null);
                        $('[name='+ that.options.latitudeInput +']').val(parseFloat(this.getPosition().lat())).removeClass('empty');
                        $('[name='+ that.options.longitudeInput +']').val(parseFloat(this.getPosition().lng())).removeClass('empty');
                    });

                    google.maps.event.addListener(marker, 'dragend', function(){
                        that.removeAllMarkers();
                        var visibleMarkers=[];

                        $.each(that.properties.markers, function(index, marker){
                            if (that.properties.map.getBounds().contains(that.properties.markers[index].getPosition())) {
                                visibleMarkers.push(that.properties.markers[index]);
                                that.properties.markers[index].setAnimation(null);
                            }
                        });

                        that.properties.markers = visibleMarkers;
                        that.setVisibleMarkers()
                        this.setAnimation(google.maps.Animation.BOUNCE);

                        $('[name='+ that.options.latitudeInput +']').val(parseFloat(this.getPosition().lat())).removeClass('empty');
                        $('[name='+ that.options.longitudeInput +']').val(parseFloat(this.getPosition().lng())).removeClass('empty');
                    });

                    google.maps.event.addListener(marker, 'click', function(){
                        that.properties.map.panTo(this.getPosition());
                        that.properties.map.setZoom(that.properties.map.getZoom() + 2);

                        that.removeAllMarkers()

                        var visibleMarkers=[];
                        $.each(that.properties.markers, function(index, marker){
                            if (that.properties.map.getBounds().contains(that.properties.markers[index].getPosition())) {
                                visibleMarkers.push(that.properties.markers[index]);
                                that.properties.markers[index].setAnimation(null);
                            }
                        });

                        that.properties.markers = visibleMarkers;
                        that.setVisibleMarkers();
                        this.setAnimation(google.maps.Animation.BOUNCE);

                        $('[name='+ that.options.latitudeInput +']').val(parseFloat(this.getPosition().lat())).removeClass('empty');
                        $('[name='+ that.options.longitudeInput +']').val(parseFloat(this.getPosition().lng())).removeClass('empty');
                    });

                    google.maps.event.addListener(marker, 'mousedown', function(){
                        this.setAnimation(null);
                    });

                });

                that.properties.map.fitBounds(bounds);

                // al haber solo un marcador metemos las coordenadas en los input
                if(that.properties.markers.length == 1)
                {
                    that.properties.markers[0].setAnimation(google.maps.Animation.BOUNCE);
                    $('[name='+ that.options.latitudeInput +']').val(parseFloat(that.properties.markers[0].getPosition().lat())).removeClass('empty');
                    $('[name='+ that.options.longitudeInput +']').val(parseFloat(that.properties.markers[0].getPosition().lng())).removeClass('empty');
                }
            });

            return this;
        },

        removeAllMarkers: function()
        {
            var that = this;
            $.each(this.properties.markers, function(index, marker){
                that.properties.markers[index].setMap(null);
            });
        },

        setVisibleMarkers: function()
        {
            var that = this;
            $.each(this.properties.markers, function(index, marker){
                that.properties.markers[index].setMap(that.properties.map);
            });
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
    $.mapPoint = function(options, callback) {
        if (!$.data(document, 'mapPoint'))
        {
            if(options.id == null)
            {
                $.data(document, 'mapPoint', Object.create(MapPoint).init(options, callback));
            }
            else
            {
                $.data(document, 'mapPoint' + options.id, Object.create(MapPoint).init(options, callback));
            }
        }
    };

    /*
     * Set new values
     */
    $.mapPoint.setOptions = function(options, callback) {

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