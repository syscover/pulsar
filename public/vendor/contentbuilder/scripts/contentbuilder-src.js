var cb_list='';
var cb_edit = true;

var oScripts=document.getElementsByTagName("script"); 
var sScriptPath;
for(var i=0;i<oScripts.length;i++) {
    var sSrc=oScripts[i].src.toLowerCase();
    if(sSrc.indexOf("contentbuilder-src.js")!=-1) sScriptPath=oScripts[i].src.replace(/contentbuilder-src.js/,"");
    if(sSrc.indexOf("contentbuilder.js")!=-1) sScriptPath=oScripts[i].src.replace(/contentbuilder.js/,"");
}

var sScriptPathArray = sScriptPath.split("?");
sScriptPath = sScriptPathArray[0];

if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
    var sc = document.createElement('script');
    sc.src = sScriptPath + 'megapix-image.js';
    document.getElementsByTagName('head')[0].appendChild(sc);
}

(function (jQuery) {

    var $activeRow;

    jQuery.contentbuilder = function (element, options) {

        var defaults = {
            zoom: '1',
            selectable: "h1,h2,h3,h4,h5,h6,p,ul,ol,small,.edit",
            editMode: 'default',
            onRender: function () { },
            onDrop: function () { },
            snippetFile: 'assets/default/snippets.html',
            snippetPathReplace: ['',''],
            hiquality: false,
            snippetTool: 'right',
            snippetOpen: false,
            snippetCategories: [
                [0,"Default"],
                [-1,"All"],
                [1,"Title"],
                [2,"Title, Subtitle"],
                [3,"Info, Title"],
                [4,"Info, Title, Subtitle"],
                [5,"Heading, Paragraph"],
                [6,"Paragraph"],
                [7,"Paragraph, Images + Caption"],
                [8,"Heading, Paragraph, Images + Caption"],
                [9,"Images + Caption"],
                [10,"Images + Long Caption"],
                [11,"Images"],
                [12,"Single Image"],
                [13,"Call to Action"],
                [14,"List"],
                [15,"Quotes"],
                [16,"Profile"],
                [17,"Map"],
                [20,"Video"],
                [18,"Social"],
                [19,"Separator"]
                ],
            imageselect: '',
            imageEmbed: true,
            sourceEditor: true,
            fileselect: '',
            enableZoom: true,
            colors: ["#ffffc5","#e9d4a7","#ffd5d5","#ffd4df","#c5efff","#b4fdff","#c6f5c6","#fcd1fe","#ececec",                            
                "#f7e97a","#d09f5e","#ff8d8d","#ff80aa","#63d3ff","#7eeaed","#94dd95","#ef97f3","#d4d4d4",                         
                "#fed229","#cc7f18","#ff0e0e","#fa4273","#00b8ff","#0edce2","#35d037","#d24fd7","#888888",                         
                "#ff9c26","#955705","#c31313","#f51f58","#1b83df","#0bbfc5","#1aa71b","#ae19b4","#333333"],
            snippetList: '#divSnippetList',
            toolbar: 'top',
            toolbarDisplay: 'auto',
            axis: '',
            hideDragPreview: false
        };

        this.settings = {};

        var $element = jQuery(element),
                    element = element;

        this.init = function () {

            this.settings = jQuery.extend({}, defaults, options);

            //$element.css({ 'margin-top': '80px', 'margin-bottom': '80px' });

            /**** Zoom ****/
            if (!this.settings.enableZoom) {
                localStorage.removeItem("zoom");
            }

            if (localStorage.getItem("zoom") != null) {
                this.settings.zoom = localStorage.zoom;
            } else {
                localStorage.zoom = this.settings.zoom;
            }

            $element.css('zoom', this.settings.zoom);
            $element.css('-moz-transform', 'scale(' + this.settings.zoom + ')');
            $element.addClass('connectSortable');
            
            //IE fix
            this.settings.zoom = this.settings.zoom + ''; //Fix undefined
            if (this.settings.zoom.indexOf('%') != -1) {
                this.settings.zoom = this.settings.zoom.replace('%', '') / 100;
                localStorage.zoom = this.settings.zoom;
            }
            if (this.settings.zoom == 'NaN') {
                this.settings.zoom = 1;
                localStorage.zoom = 1;
            }
            /**** Zoom ****/

            /**** Get list of editable area ****/
            if(cb_list==''){
                cb_list = '#'+$element.attr('id');
            }
            else {
                cb_list = cb_list + ',#'+$element.attr('id');
            }
      
            /**** Enlarge droppable area ****/
            $element.css({ 'min-height': '50px' });

            /**** Localize All ****/
            if (jQuery('#divCb').length == 0) {
                jQuery('body').append('<div id="divCb"></div>');
            }

            /**** Load snippets library ****/
            if (jQuery('#divSnippets').length == 0) {

                jQuery('#divCb').append('<div id="divSnippets" style="display:none"></div>');

                //Snippets Filter
                var html_catselect = '';
                for(var i=0;i<this.settings.snippetCategories.length;i++){
                    html_catselect += '<option value="' + this.settings.snippetCategories[i][0] + '">' + this.settings.snippetCategories[i][1] + '</option>';
                }
                html_catselect = '<select id="selSnips" style="display:none;width:83%;margin:5px;padding:5px;margin:3px 0 13px 5px;font-size:12px;letter-spacing:1px;height:28px;line-height:1;color:#454545;border-radius:0px;border:none;background:#fff;box-shadow: 0 0 5px rgba(0, 0, 0,0.2);cursor:pointer;">' +
                    html_catselect +
                    '</select>';

                var s = '<div id="divTool">' + html_catselect;
                s += '<div id="divSnippetList"></div>';                                
                s += '';
                s += '<br><div id="divRange"><input type="range" id="inpZoom" min="80" max="100" value="100"></div>';
                s += '';
                s += '<a id="lnkToolOpen" href="#"><i class="cb-icon-left-open-big" style="font-size: 15px;"></i></a></div>';
                jQuery('#divCb').append(s);

                jQuery('#inpZoom').val(this.settings.zoom * 100);

                jQuery('#divCb input[type="range"]').rangeslider({
                    onSlide: function (position, value) { },
                    polyfill: false
                });

                var val = jQuery('#inpZoom').val() / 100;
                this.zoom(val);

                jQuery('#inpZoom').on('change', function () {                
                    //if zoom slider not used, resizing window makes this event triggered and the zoom changed to 0.8
                    if($element.data('contentbuilder').settings.enableZoom==true){//don't run this if slider is not used                        
                        var val = jQuery('#inpZoom').val() / 100;
                        $element.data('contentbuilder').zoom(val);
                    }
                });

                //Enable/disable Zoom
                if (!this.settings.enableZoom && this.settings.snippetList=='#divSnippetList') {
                    jQuery('#divRange').css('display', 'none');
                    jQuery('#divSnippetList').css('height', '100%');
                }

                jQuery.get(this.settings.snippetFile, function (data) {
                    var htmlData = '';
                    var htmlThumbs = '';
                    var i = 1;
                    var bUseSnippetsFilter = false;
                    jQuery('<div/>').html(data).children('div').each(function () {

                        var block = jQuery(this).html();
                        //Enclode each block. Source: http://stackoverflow.com/questions/1219860/html-encoding-in-javascript-jquery
                        var blockEncoded = jQuery('<div/>').text(block).html();
                        htmlData += '<div id="snip' + i + '">' + blockEncoded + '</div>'; //Encoded html prevents loading many images

                        if(jQuery(this).data("cat")!=null) bUseSnippetsFilter=true;

                        var thumb = jQuery(this).data("thumb");
                        if($element.data('contentbuilder').settings.snippetPathReplace[0]!='') {
                            thumb = thumb.replace($element.data('contentbuilder').settings.snippetPathReplace[0],$element.data('contentbuilder').settings.snippetPathReplace[1]);
                        }

                        if(bUseSnippetsFilter){
                            htmlThumbs += '<div style="display:none" title="Snippet ' + i + '" data-snip="' + i + '" data-cat="' + jQuery(this).data("cat") + '"><img src="' + thumb + '" /></div>';
                        } else {
                            htmlThumbs += '<div title="Snippet ' + i + '" data-snip="' + i + '" data-cat="' + jQuery(this).data("cat") + '"><img src="' + thumb + '" /></div>';
                        }                        

                        i++;
                    });
                    
                    if($element.data('contentbuilder').settings.snippetPathReplace[0]!='') {
					    var regex = new RegExp($element.data('contentbuilder').settings.snippetPathReplace[0], 'g');
                        htmlData = htmlData.replace(regex,$element.data('contentbuilder').settings.snippetPathReplace[1]);
                    }

                    jQuery('#divSnippets').html(htmlData);

                    //jQuery('#divSnippetList').html(htmlThumbs);
                    jQuery($element.data('contentbuilder').settings.snippetList).html(htmlThumbs);
                    
                    //Snippets Filter
                   
                    if(bUseSnippetsFilter){
                        var cats = [];

                        //jQuery($element.data('contentbuilder').settings.snippetList + ' > div').css('display','none');
                        var defaultExists = false;
                        jQuery($element.data('contentbuilder').settings.snippetList + ' > div').each(function () {
                            for(var j=0;j<jQuery(this).attr('data-cat').split(',').length;j++){
                                var catid = jQuery(this).attr('data-cat').split(',')[j];
                                if(catid == 0){
                                    jQuery(this).fadeIn(400);
                                    defaultExists = true;
                                }
                                if(jQuery.inArray(catid, cats)==-1){
                                    cats.push(catid);
                                }
                            }                                    
                        });

                        //Remove empty categories
                        jQuery('#selSnips option').each(function(){                           
                            var catid = jQuery(this).attr('value');        
                            if(jQuery.inArray(catid, cats)==-1){
                                if(catid!=0 && catid!=-1){
                                    jQuery("#selSnips option[value='" + catid + "']").remove();
                                }
                            }
                        });

                        if(!defaultExists){//old version: default not exists, show all (backward compatibility)
                            jQuery($element.data('contentbuilder').settings.snippetList + ' > div').css('display','block');
                            jQuery("#selSnips option[value='0']").remove();
                        }

                        jQuery('#selSnips').css('display', 'block');
                        jQuery('#divSnippetList').css('height', '86%');                        
                      
                        jQuery("#selSnips").on("change", function (e) {
                            var optionSelected = jQuery("option:selected", this);
                            var valueSelected = this.value;
                            if(valueSelected=='-1'){
                                //jQuery($element.data('contentbuilder').settings.snippetList + ' > div').css('display','block');
                                jQuery($element.data('contentbuilder').settings.snippetList + ' > div').fadeIn(200);
                            } else {
                                //jQuery($element.data('contentbuilder').settings.snippetList + ' > div').css('display','none');                            
                                //jQuery("[data-cat=" +valueSelected+ "]").css('display','block');
                                jQuery($element.data('contentbuilder').settings.snippetList + ' > div').fadeOut(200, function () {
                                    //jQuery("[data-cat=" +valueSelected+ "]").fadeIn(400);
                                    for(var j=0;j<jQuery(this).attr('data-cat').split(',').length;j++){
                                        if(valueSelected == jQuery(this).attr('data-cat').split(',')[j]){
                                            jQuery(this).fadeIn(400);
                                        }
                                    }
                                    
                                });
                            }
                        });
                    }

                    /* Draggable */
                    var bJUIStable = false;
                    if(jQuery.ui.version=='1.11.0'){
                        bJUIStable = true; 
                    }

                    if(bJUIStable){

                        jQuery($element.data('contentbuilder').settings.snippetList + ' > div').draggable({
                            cursor: 'move',
                            helper: function () {
                                return jQuery("<div class='dynamic'></div>")[0];
                            },
                            connectToSortable: cb_list, /*"#" + $element.attr('id'),*/
                            stop: function (event, ui) {

                                /* fix bug */
                                $element.children("div").each(function () {
                                    if (jQuery(this).children("img").length == 1) {
                                        jQuery(this).remove();
                                    }
                                });

                            }
                        });

                    } else {
                        
                        jQuery($element.data('contentbuilder').settings.snippetList + ' > div').draggable({ //jQuery('#divSnippetList > div').draggable({
                            cursor: 'move',
                            //helper: function () { /* Custom helper not returning draggable item using the latest jQuery UI */
                            //    return jQuery("<div class='dynamic'></div>")[0];
                            //},
                            helper: "clone", /* So we use cloned draggable item as the helper */
                            drag: function (event, ui) {

                                /* Needed by latest jQuery UI: styling the helper */
                                jQuery(ui.helper).css("overflow","hidden");
                                jQuery(ui.helper).css("padding-top","60px"); //make helper content empty by adding a top padding the same as height
                                jQuery(ui.helper).css("box-sizing","border-box");
                                jQuery(ui.helper).css("width","150px");
                                jQuery(ui.helper).css("height","60px");
                                jQuery(ui.helper).css("border","rgba(225,225,225,0.9) 5px solid");

                                /* Needed by latest jQuery UI: adjust helper position */
                                var zoom = localStorage.zoom;
                                if (zoom == 'normal') zoom = 1;
                                if (zoom == undefined) zoom = 1;

                                //IE fix
                                zoom = zoom + ''; //Fix undefined
                                if (zoom.indexOf('%') != -1) {
                                    zoom = zoom.replace('%', '') / 100;
                                }
                                if (zoom == 'NaN') {
                                    zoom = 1;
                                }

                                zoom = zoom*1;

                                var scrolltop = jQuery(window).scrollTop();
                                var offsettop = jQuery(event.target).offset().top;
                                var offsetleft = jQuery(event.target).offset().left;

                                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                                var is_ie = detectIE();
                                var browserok = true;
                                if (is_firefox||is_ie) browserok = false;
                                if(browserok){
                                    //Chrome 37, Opera 24
                                    var adjy = 0;
                                    var adjx = 60;
                                
                                    var newzoom = (5*zoom -1)/4; //if zoom = 0.8, make it 0.75 (to fix wrong jquery ui handler position)

                                    jQuery(ui.helper).css("margin-top", (event.clientY + adjy - ((event.clientY + adjy) * newzoom)) + (scrolltop + 0 - ((scrolltop + 0) * newzoom)));
                                    jQuery(ui.helper).css("margin-left", event.clientX + adjx - ((event.clientX + adjx) * zoom));

                                } else {
                                    if(is_ie){
                                        //IE 11 (No Adjustment required)

                                    }  
                                    if(is_firefox) {
                                        //Firefox (hidden => not needed)                                    

                                    }
                                }   

                            },
                            connectToSortable: cb_list, /*"#" + $element.attr('id'),*/
                            stop: function (event, ui) {

                                /* fix bug */
                                $element.children("div").each(function () {
                                    if (jQuery(this).children("img").length == 1) {
                                        jQuery(this).remove();
                                    }
                                });

                            }
                        });

                    } 
                    /* /Draggable */

                });

            }
            
            /**** Apply builder elements ****/            
            $element.children("*").wrap("<div class='ui-draggable'></div>"); //$element.children("*").not('link').wrap("<div class='ui-draggable'></div>");
            $element.children("*").append('<div class="row-tool">' +
                '<div class="row-handle"><i class="cb-icon-move"></i></div>' +
                '<div class="row-html"><i class="cb-icon-code"></i></div>' +
                '<div class="row-copy"><i class="cb-icon-plus"></i></div>' +
                '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' +
                '</div>');
            
            if (jQuery('#temp-contentbuilder').length == 0) {
                jQuery('#divCb').append('<div id="temp-contentbuilder" style="display: none"></div>');
            }

            /* Snippet Tool */
            var $window = jQuery(window);
            var windowsize = $window.width();
            var toolwidth = 260;
            if (windowsize < 600) {
                toolwidth = 150;
            }

            if (this.settings.snippetTool == 'right') {
                // Sliding from Right
                jQuery('#divTool').css('width', toolwidth + 'px');
                jQuery('#divTool').css('right', '-' + toolwidth + 'px');

                jQuery("#lnkToolOpen").unbind('click');
                jQuery("#lnkToolOpen").click(function (e) {
                    $element.data('contentbuilder').clearControls();

                    if (parseInt(jQuery('#divTool').css('right')) == 0) {
                        jQuery('#divTool').animate({
                            right: '-=' + toolwidth + 'px'
                        }, 200);
                        jQuery("#lnkToolOpen i").attr('class','cb-icon-left-open-big');
                    } else {
                        jQuery('#divTool').animate({
                            right: '+=' + toolwidth + 'px'
                        }, 200);
                        jQuery("#lnkToolOpen i").attr('class','cb-icon-right-open-big');
                    }

                    e.preventDefault();
                });

                //Adjust the row tool
                jQuery('.row-tool').css('right', 'auto');
                if (windowsize < 600) {
                    jQuery('.row-tool').css('left', '-30px'); //for small screen
                } else {
                    jQuery('.row-tool').css('left', '-37px');
                }

                if(this.settings.snippetOpen){
                    if(jQuery('#divTool').attr('data-snip-open') != 1){
                        jQuery('#divTool').attr('data-snip-open',1);
                        jQuery('#divTool').animate({
                            right: '+=' + toolwidth + 'px'
                        }, 900);
                        jQuery("#lnkToolOpen i").attr('class','cb-icon-right-open-big');
                    }
                }

            } else {

                // Sliding from Left
                jQuery('#divTool').css('width', toolwidth + 'px');
                jQuery('#divTool').css('left', '-' + toolwidth + 'px');

                jQuery('#lnkToolOpen').addClass('leftside');

                jQuery("#lnkToolOpen").unbind('click');
                jQuery("#lnkToolOpen").click(function (e) {
                    $element.data('contentbuilder').clearControls();

                    if (parseInt(jQuery('#divTool').css('left')) == 0) {
                        jQuery('#divTool').animate({
                            left: '-=' + (toolwidth + 0) + 'px'
                        }, 200);
                        jQuery("#lnkToolOpen i").attr('class','cb-icon-right-open-big');
                    } else {
                        jQuery('#divTool').animate({
                            left: '+=' + (toolwidth + 0) + 'px'
                        }, 200);
                        jQuery("#lnkToolOpen i").attr('class','cb-icon-left-open-big');
                    }

                    e.preventDefault();
                });

                //Adjust the row tool
                jQuery('.row-tool').css('left', 'auto');
                if (windowsize < 600) {
                    jQuery('.row-tool').css('right', '-30px'); //for small screen
                } else {
                    jQuery('.row-tool').css('right', '-37px');
                }

                if(this.settings.snippetOpen){
                    if(jQuery('#divTool').attr('data-snip-open') != 1){
                        jQuery('#divTool').attr('data-snip-open',1);
                        jQuery('#divTool').animate({
                            left: '+=' + toolwidth + 'px'
                        }, 900);
                        jQuery("#lnkToolOpen i").attr('class','cb-icon-left-open-big');
                    }
                }

            }


            /**** Apply builder behaviors ****/
            this.applyBehavior();

            /**** Trigger Render event ****/
            this.settings.onRender();

            /**** DRAG & DROP behavior ****/          
            $element.sortable({
                sort: function(event, ui) {  
                    var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                    var is_ie = detectIE();
                    if(is_firefox) { 
                        //ui.helper.css({'top' : ui.position.top + jQuery(window).scrollTop() - 100 + 'px'});
                        ui.helper.css({'display' : 'none'});
                    }
                    if($element.data('contentbuilder').settings.hideDragPreview){
                        ui.helper.css({'display' : 'none'});
                    }
                },
                items: '.ui-draggable', 
                connectWith: '.connectSortable', 'distance': 5,
                axis: 'y', tolerance: 'pointer',
                handle: '.row-handle',
                delay: 200,
                cursor: 'move',
                placeholder: 'block-placeholder',
                start: function (e,ui){
                    jQuery(ui.placeholder).slideUp(80); 
                    cb_edit = false;
                }, 
                change: function (e,ui){
                    jQuery(ui.placeholder).hide().slideDown(80);
                },
                deactivate: function (event, ui) {
                    cb_edit = true;

                    var bDrop = false;
                    if (ui.item.find('.row-tool').length==0) {
                        bDrop = true;
                    }

                    if (ui.item.parent().attr('id') == $element.attr('id')) {
                    
                        ui.item.replaceWith(ui.item.html());
                        /*
                        if(ui.item.children(0).attr('src').indexOf('thumbnails/')==-1){
                            ui.item.replaceWith(ui.item.html());
                        } else {
                             var snip = jQuery(ui.item).data('snip');
                            var snipHtml = jQuery('#snip' + snip).text();
                            ui.item.replaceWith(snipHtml);
                        }*/


                        $element.children("*").each(function () {

                            if (!jQuery(this).hasClass('ui-draggable')) {
                                jQuery(this).wrap("<div class='ui-draggable'></div>");
                            }
                        });

                        $element.children('.ui-draggable').each(function () {
                            if (jQuery(this).find('.row-tool').length == 0) {
                                jQuery(this).append('<div class="row-tool">' +
                                '<div class="row-handle"><i class="cb-icon-move"></i></div>' +
                                '<div class="row-html"><i class="cb-icon-code"></i></div>' +
                                '<div class="row-copy"><i class="cb-icon-plus"></i></div>' +
                                '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' +
                                '</div>');
                            }
                        });

                        $element.children('.ui-draggable').each(function () {                        
                            if (jQuery(this).children('*').length == 1) {//empty (only <div class="row-tool">)
                                jQuery(this).remove();
                            }
                            //For some reason, the thumbnail is dropped, not the content (when dragging mouse released not on the drop area)
                            if (jQuery(this).children('*').length == 2) {//only 1 element
                                if(jQuery(this).children(0).prop("tagName").toLowerCase()=='img' &&
                                    jQuery(this).children(0).attr('src').indexOf('thumbnails/') != -1 ) {//check if the element is image thumbnail
                                    jQuery(this).remove();//remove it.
                                }
                            }
                        });

                        /*
                        //dropped on root
                        if (ui.item.find('.row-tool').length == 0) {
                        ui.item.append('<div class="row-tool">' +
                        '<div class="row-handle"><i class="cb-icon-move"></i></div>' +
                        '<div class="row-html"><i class="cb-icon-code"></i></div>' +
                        '<div class="row-copy"><i class="cb-icon-plus"></i></div>' +
                        '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' +
                        '</div>');
                        }*/

                    }

                    //Apply builder behaviors
                    $element.data('contentbuilder').applyBehavior();

                    //Trigger Render event
                    $element.data('contentbuilder').settings.onRender();

                    //Trigger Drop event
                    if(bDrop) $element.data('contentbuilder').settings.onDrop(event, ui); 

                }
            });

            if(cb_list.indexOf(',')!=-1){
                jQuery(cb_list).sortable('option','axis',false);
            }
            if(this.settings.axis!=''){
                jQuery(cb_list).sortable('option','axis',this.settings.axis);
            }

            /* http://stackoverflow.com/questions/6285758/cannot-drop-a-draggable-where-two-droppables-touch-each-other */
            jQuery.ui.isOverAxis2 = function( x, reference, size ) {
                return ( x >= reference ) && ( x < ( reference + size ) );
            };
            jQuery.ui.isOver = function( y, x, top, left, height, width ) {
                return jQuery.ui.isOverAxis2( y, top, height ) && jQuery.ui.isOverAxis( x, left, width );
            };

            $element.droppable({
                drop: function (event, ui) {
                    if (jQuery(ui.draggable).data('snip')) {
                        var snip = jQuery(ui.draggable).data('snip');
                        var snipHtml = jQuery('#snip' + snip).text();
                        jQuery(ui.draggable).data('snip', null); //clear
                        return ui.draggable.html(snipHtml);
                        event.preventDefault();
                    }
                },
                tolerance: 'pointer',
                greedy: true
            });


            jQuery(document).bind('mousedown', function (event) {

                //console.log(jQuery(event.target).prop("tagName").toLowerCase());

                //Remove Overlay on embedded object to enable the object.
                if (jQuery(event.target).attr("class") == 'ovl') {
                    jQuery(event.target).css('z-index', '-1');
                }

                if (jQuery(event.target).parents('.ui-draggable').length > 0 && jQuery(event.target).parents(cb_list).length > 0) {

                    var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

                    /****** Row Controls ******/
                    jQuery(".ui-draggable").removeClass('code');
                    if( jQuery(event.target).parents("[data-mode='code']").length > 0 ) { //Mode: code
                        jQuery(event.target).parents(".ui-draggable").addClass('code');
                    }

                    if( jQuery(event.target).parents("[data-mode='readonly']").length > 0 ) { //Mode: readonly
                        jQuery(event.target).parents(".ui-draggable").addClass('code');
                    }

                    jQuery(".ui-draggable").removeClass('ui-dragbox-outlined');
                    jQuery(event.target).parents(".ui-draggable").addClass('ui-dragbox-outlined');
                    if(is_firefox) jQuery(event.target).parents(".ui-draggable").addClass('firefox');

                    jQuery('.row-tool').stop(true, true).fadeOut(0);
                    if( jQuery(event.target).parents(".ui-draggable").find("[data-html-edit='off']").length > 0 || !$element.data('contentbuilder').settings.sourceEditor){
                        jQuery(event.target).parents(".ui-draggable").find('.row-tool .row-html').css({ display: 'none' });
                    }
                    jQuery(event.target).parents(".ui-draggable").find('.row-tool').stop(true, true).css({ display: 'none' }).fadeIn(300);
                    /****************************/
                    
                    return;
                }

                if( jQuery(event.target).parent().attr('id') == 'rte-toolbar' ||
                    jQuery(event.target).parent().parent().attr('id') == 'rte-toolbar') {
                    return;
                }

                if (jQuery(event.target).is('[contenteditable]') ||
                    jQuery(event.target).css('position') == 'absolute' ||
                    jQuery(event.target).css('position') == 'fixed'
                    ) {
                    return;
                }

                jQuery(event.target).parents().each(function (e) {

                    if (jQuery(this).is('[contenteditable]') ||
                        jQuery(this).css('position') == 'absolute' ||
                        jQuery(this).css('position') == 'fixed'
                        ) {
                        return;
                    }

                });
   
                $element.data('contentbuilder').clearControls();

            });

        };

        /**** Read HTML ****/
        this.html = function () {

            var selectable = this.settings.selectable;
            jQuery('#temp-contentbuilder').html($element.html());
            jQuery('#temp-contentbuilder').find('.row-tool').remove();
            jQuery('#temp-contentbuilder').find('.ovl').remove();
            jQuery('#temp-contentbuilder').find('[contenteditable]').removeAttr('contenteditable');
            jQuery('*[class=""]').removeAttr('class');
            jQuery('#temp-contentbuilder').find('.ui-draggable').replaceWith(function () { return jQuery(this).html() });
            /*jQuery('#temp-contentbuilder').find('p').each(function () {
                if (jQuery.trim(jQuery(this).text()) == '') jQuery(this).remove();
            });*/
            jQuery("#temp-contentbuilder").find("[data-mode='code']").each(function () {//Mode: code
                if(jQuery(this).attr("data-html")!=undefined){
                    jQuery(this).html( decodeURIComponent(jQuery(this).attr("data-html")) );
                }
            });            
            var html = jQuery('#temp-contentbuilder').html().trim();
            html = html.replace(/<font/g,'<span').replace(/<\/font/g,'</span');
            return html;

        };

        this.zoom = function (n) {
            this.settings.zoom = n;

            jQuery(cb_list).css('zoom', n);
            jQuery(cb_list).css('-moz-transform', 'scale(' + n + ')');

            localStorage.zoom = n;

            this.clearControls();
        };

        this.clearControls = function () {
            jQuery('.row-tool').stop(true, true).fadeOut(0);

            jQuery(".ui-draggable").removeClass('code');
            jQuery(".ui-draggable").removeClass('ui-dragbox-outlined');

            var selectable = this.settings.selectable;
            $element.find(selectable).blur();
        };

        this.viewHtml = function () {
            /**** Custom Modal ****/
            jQuery('#md-html').css('width', '45%');
            jQuery('#md-html').simplemodal();
            jQuery('#md-html').data('simplemodal').show();

            jQuery('#txtHtml').val(this.html());

            jQuery('#btnHtmlOk').unbind('click');
            jQuery('#btnHtmlOk').bind('click', function (e) {

                $element.html(jQuery('#txtHtml').val());

                jQuery('#md-html').data('simplemodal').hide();

                //Re-Init
                $element.children("*").wrap("<div class='ui-draggable'></div>");
                $element.children("*").append('<div class="row-tool">' +
                    '<div class="row-handle"><i class="cb-icon-move"></i></div>' +
                    '<div class="row-html"><i class="cb-icon-code"></i></div>' +
                    '<div class="row-copy"><i class="cb-icon-plus"></i></div>' +
                    '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' +
                    '</div>');

                //Apply builder behaviors
                $element.data('contentbuilder').applyBehavior();

                //Trigger Render event
                $element.data('contentbuilder').settings.onRender();

            });
            /**** /Custom Modal ****/
        };

        this.loadHTML = function (html) {
            $element.html(html);

            //Re-Init
            $element.children("*").wrap("<div class='ui-draggable'></div>");
            $element.children("*").append('<div class="row-tool">' +
                '<div class="row-handle"><i class="cb-icon-move"></i></div>' +
                '<div class="row-html"><i class="cb-icon-code"></i></div>' +
                '<div class="row-copy"><i class="cb-icon-plus"></i></div>' +
                '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' +
                '</div>');

            //Apply builder behaviors
            $element.data('contentbuilder').applyBehavior();

            //Trigger Render event
            $element.data('contentbuilder').settings.onRender();
        };

        this.applyBehavior = function () {

            //Make hyperlinks not clickable
            $element.find('a').click(function () { return false });

            //Mode: code
            $element.find("[data-mode='code']").each(function () {
                if(jQuery(this).attr("data-html")!=undefined){
                    jQuery(this).html( decodeURIComponent(jQuery(this).attr("data-html")) );
                }
            });

            //Get settings
            var selectable = this.settings.selectable;
            var hq = this.settings.hiquality;
            var imageEmbed = this.settings.imageEmbed;
            var colors = this.settings.colors;
            var editMode = this.settings.editMode;
            var toolbar = this.settings.toolbar;
            var toolbarDisplay = this.settings.toolbarDisplay;

            //Custom Image Select
            var imageselect = this.settings.imageselect;
            var fileselect = this.settings.fileselect;

            //Apply ContentEditor plugin
            $element.contenteditor({ fileselect: fileselect, editable: selectable, colors: colors, editMode: editMode, toolbar: toolbar, toolbarDisplay: toolbarDisplay });
            $element.data('contenteditor').render();

            //Apply ImageEmbed plugin
            $element.find('img').each(function () {
                
                if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code

                if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

                jQuery(this).imageembed({ hiquality: hq, imageselect: imageselect, fileselect: fileselect, imageEmbed: imageEmbed });
                //to prevent icon dissapear if hovered above absolute positioned image caption
                if (jQuery(this).parents('figure').length != 0) {
                    if (jQuery(this).parents('figure').find('figcaption').css('position') == 'absolute') {
                        jQuery(this).parents('figure').imageembed({ hiquality: hq, imageselect: imageselect, fileselect: fileselect, imageEmbed: imageEmbed });
                    }
                }

            });

            //Add "Hover on Embed" event
            $element.find(".embed-responsive").each(function () {

                if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code

                if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

                if (jQuery(this).find('.ovl').length == 0) {
                    jQuery(this).append('<div class="ovl" style="position:absolute;background:#fff;opacity:0.2;cursor:pointer;top:0;left:0px;width:100%;height:100%;z-index:-1"></div>');
                }
            });
            $element.find(".embed-responsive").hover(function () {

                if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code

                if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

                if (jQuery(this).parents(".ui-draggable").css('outline-style') == 'none') {
                    jQuery(this).find('.ovl').css('z-index', '1');
                }
            }, function () {
                jQuery(this).find('.ovl').css('z-index', '-1');
            });

            //Add "Focus" event
            $element.find(selectable).unbind('focus');
            $element.find(selectable).focus(function () {

                var zoom = $element.data('contentbuilder').settings.zoom;

                var selectable = $element.data('contentbuilder').settings.selectable;

                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

                /****** Row Controls ******/
                jQuery(".ui-draggable").removeClass('code');
                if( jQuery(this).parents("[data-mode='code']").length > 0 ) { //Mode: code
                    jQuery(this).parents(".ui-draggable").addClass('code');
                }

                if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) { //Mode: readonly
                    jQuery(this).parents(".ui-draggable").addClass('code');
                }

                jQuery(".ui-draggable").removeClass('ui-dragbox-outlined');
                jQuery(this).parents(".ui-draggable").addClass('ui-dragbox-outlined');
                if(is_firefox) jQuery(this).parents(".ui-draggable").addClass('firefox');

                jQuery('.row-tool').stop(true, true).fadeOut(0);
                if( jQuery(this).parents(".ui-draggable").find("[data-html-edit='off']").length > 0  || !$element.data('contentbuilder').settings.sourceEditor){
                    jQuery(this).parents(".ui-draggable").find('.row-tool .row-html').css({ display: 'none' });
                }
                jQuery(this).parents(".ui-draggable").find('.row-tool').stop(true, true).css({ display: 'none' }).fadeIn(300);
            });

            //Add "Click to Remove" event (row)
            $element.children("div").find('.row-remove').unbind();
            $element.children("div").find('.row-remove').click(function () {

                /**** Custom Modal ****/
                jQuery('#md-delrowconfirm').css('max-width', '550px');
                jQuery('#md-delrowconfirm').simplemodal();
                jQuery('#md-delrowconfirm').data('simplemodal').show();

                $activeRow = jQuery(this).parents('.ui-draggable');

                jQuery('#btnDelRowOk').unbind('click');
                jQuery('#btnDelRowOk').bind('click', function (e) {

                    jQuery('#md-delrowconfirm').data('simplemodal').hide();

                    $activeRow.fadeOut(400, function () {

                        //Clear Controls
                        jQuery("#divToolImg").stop(true, true).fadeOut(0); /* CUSTOM */
                        jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                        jQuery("#divRteLink").stop(true, true).fadeOut(0);
                        jQuery("#divFrameLink").stop(true, true).fadeOut(0);

                        $activeRow.remove();

                        //Apply builder behaviors
                        //$element.data('contentbuilder').applyBehavior();

                        //Trigger Render event
                        $element.data('contentbuilder').settings.onRender();

                    });

                });
                jQuery('#btnDelRowCancel').unbind('click');
                jQuery('#btnDelRowCancel').bind('click', function (e) {

                    jQuery('#md-delrowconfirm').data('simplemodal').hide();

                });
                /**** /Custom Modal ****/

            });

            //Add "Click to Remove" event (row)
            $element.children("div").find('.row-copy').unbind();
            $element.children("div").find('.row-copy').click(function () {
            
                    $activeRow = jQuery(this).parents('.ui-draggable');
                    jQuery('#temp-contentbuilder').html($activeRow.html());
                    jQuery('#temp-contentbuilder').find('[contenteditable]').removeAttr('contenteditable');
                    jQuery('#temp-contentbuilder *[class=""]').removeAttr('class');
                    jQuery('#temp-contentbuilder *[style=""]').removeAttr('style');
                    jQuery('#temp-contentbuilder .ovl').remove();
                    /*jQuery('#temp-contentbuilder').find('p').each(function () {
                        if (jQuery.trim(jQuery(this).text()) == '') jQuery(this).remove();
                    });*/
                    jQuery('#temp-contentbuilder .row-tool').remove();
                    var html = jQuery('#temp-contentbuilder').html().trim();

                    //Insert
                    $activeRow.after(html);

                    //Re-Init
                    $element.children("*").each(function () {

                        if (!jQuery(this).hasClass('ui-draggable')) {
                            jQuery(this).wrap("<div class='ui-draggable'></div>");
                        }
                    });

                    $element.children('.ui-draggable').each(function () {
                        if (jQuery(this).find('.row-tool').length == 0) {
                            jQuery(this).append('<div class="row-tool">' +
                            '<div class="row-handle"><i class="cb-icon-move"></i></div>' +
                            '<div class="row-html"><i class="cb-icon-code"></i></div>' +
                            '<div class="row-copy"><i class="cb-icon-plus"></i></div>' +
                            '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' +
                            '</div>');
                        }
                    });

                    $element.children('.ui-draggable').each(function () {
                        if (jQuery(this).children('*').length == 1) {
                            jQuery(this).remove();
                        }
                    });

                    //Apply builder behaviors
                    $element.data('contentbuilder').applyBehavior();

                    //Trigger Render event
                    $element.data('contentbuilder').settings.onRender();
            });

            //Add "Click to View HTML" event (row)
            $element.children("div").find('.row-html').unbind();
            $element.children("div").find('.row-html').click(function () {

                /**** Custom Modal ****/
                jQuery('#md-html').css('width', '45%');
                jQuery('#md-html').simplemodal();
                jQuery('#md-html').data('simplemodal').show();

                $activeRow = jQuery(this).parents('.ui-draggable').children('*').not('.row-tool');

                if($activeRow.data('mode')=='code' && $activeRow.attr('data-html') != undefined){ //Mode: code
       
                    jQuery('#txtHtml').val(decodeURIComponent($activeRow.attr('data-html')));
   
                } else {

                    jQuery('#temp-contentbuilder').html($activeRow.html());

                    jQuery('#temp-contentbuilder').find('[contenteditable]').removeAttr('contenteditable');
                    jQuery('#temp-contentbuilder *[class=""]').removeAttr('class');
                    jQuery('#temp-contentbuilder *[style=""]').removeAttr('style');
                    jQuery('#temp-contentbuilder .ovl').remove();
                    /*jQuery('#temp-contentbuilder').find('p').each(function () {
                        if (jQuery.trim(jQuery(this).text()) == '') jQuery(this).remove();
                    });*/
                    var html = jQuery('#temp-contentbuilder').html().trim();
                    html = html.replace(/<font/g,'<span').replace(/<\/font/g,'</span');
                    jQuery('#txtHtml').val(html);

                }

                jQuery('#btnHtmlOk').unbind('click');
                jQuery('#btnHtmlOk').bind('click', function (e) {

                    if($activeRow.data('mode')=='code'){ //Mode: code

                        $activeRow.attr('data-html',encodeURIComponent(jQuery('#txtHtml').val()));
                        $activeRow.html('');

                    } else {

                        $activeRow.html(jQuery('#txtHtml').val());

                    }


                    jQuery('#md-html').data('simplemodal').hide();

                    //Apply builder behaviors
                    $element.data('contentbuilder').applyBehavior();

                    //Trigger Render event
                    $element.data('contentbuilder').settings.onRender();

                });
                /**** /Custom Modal ****/

            });

        };

		this.destroy = function () {
            if(!$element.data('contentbuilder')) return;
            var sHTML = $element.data('contentbuilder').html();
            $element.html(sHTML);
			
			// ---by jack
			$element.sortable("destroy"); //destroy sortable
			
			//del element from cb_list
			var cbarr = cb_list.split(","), newcbarr = [];
			for(var i=0; i < cbarr.length; i++) {
				if(cbarr[i] != "#"+$element.attr("id")) {
					newcbarr.push(cbarr[i]);
				}
			}
			cb_list = newcbarr.join(",");
			// ---end by jack
			
            //$element.css('zoom', 1);
            //$element.css('-moz-transform', 'scale(1)');
            $element.removeClass('connectSortable');
            $element.css({ 'min-height': '' });
			
			// ---by jack
			if(cb_list=="") {
				jQuery('#divCb').remove();				
			}
			// ---end by jack
			
			$element.removeData('contentbuilder');
            $element.removeData('contenteditor');
            $element.unbind();
            jQuery(document).unbind('mousedown');
        };

        this.init();

    };

    jQuery.fn.contentbuilder = function (options) {
        return this.each(function () {

            if (undefined == jQuery(this).data('contentbuilder')) {
                var plugin = new jQuery.contentbuilder(this, options);
                jQuery(this).data('contentbuilder', plugin);

            }
        });
    };
})(jQuery);


/*******************************************************************************************/


(function (jQuery) {

    var $activeLink;
    var $activeElement;
    var $activeFrame;
    var instances = [];

    function instances_count() {
        //alert(instances.length);
    };

    jQuery.fn.count = function () {
        //alert(instances.length);
    };

    jQuery.contenteditor = function (element, options) {

        var defaults = {
            editable: "h1,h2,h3,h4,h5,h6,p,ul,ol,small,.edit",
            editMode: "default",
            hasChanged: false,
            onRender: function () { },
            outline: false,
            fileselect: '',
            toolbar: 'top',
            toolbarDisplay: 'auto',
            colors: ["#ffffc5","#e9d4a7","#ffd5d5","#ffd4df","#c5efff","#b4fdff","#c6f5c6","#fcd1fe","#ececec",                            
                "#f7e97a","#d09f5e","#ff8d8d","#ff80aa","#63d3ff","#7eeaed","#94dd95","#ef97f3","#d4d4d4",                         
                "#fed229","#cc7f18","#ff0e0e","#fa4273","#00b8ff","#0edce2","#35d037","#d24fd7","#888888",                         
                "#ff9c26","#955705","#c31313","#f51f58","#1b83df","#0bbfc5","#1aa71b","#ae19b4","#333333"]
        };

        this.settings = {};

        var $element = jQuery(element),
             element = element;

        this.init = function () {

            this.settings = jQuery.extend({}, defaults, options);

            //Custom File Select
            var bUseCustomFileSelect = false;
            if(this.settings.fileselect!='') bUseCustomFileSelect=true;

            /**** Localize All ****/
            if (jQuery('#divCb').length == 0) {
                jQuery('body').append('<div id="divCb"></div>');
            }

            var toolbar_attr = '';
            if(this.settings.toolbar=='left')toolbar_attr=' class="rte-side"';
            if(this.settings.toolbar=='right')toolbar_attr=' class="rte-side right"';

            var html_rte = '<div id="rte-toolbar"' + toolbar_attr + '>' +
					    '<a href="#" data-rte-cmd="bold"> <i class="cb-icon-bold"></i> </a>' +
					    '<a href="#" data-rte-cmd="italic"> <i class="cb-icon-italic"></i> </a>' +
					    '<a href="#" data-rte-cmd="underline"> <i class="cb-icon-underline"></i> </a>' +
					    '<a href="#" data-rte-cmd="strikethrough"> <i class="cb-icon-strike"></i> </a>' +
                        '<a href="#" data-rte-cmd="color"> <i class="cb-icon-color"></i> </a>' +
                        '<a href="#" data-rte-cmd="fontsize"> <i class="cb-icon-fontsize"></i> </a>' +
                        '<a href="#" data-rte-cmd="removeFormat"> <i class="cb-icon-eraser"></i> </a>' +
                        '<a href="#" data-rte-cmd="formatPara"> <i class="cb-icon-header"></i> </a>' +
                        '<a href="#" data-rte-cmd="font"> <i class="cb-icon-font"></i> </a>' +
                        '<a href="#" data-rte-cmd="align"> <i class="cb-icon-align-justify"></i> </a>' +
                        '<a href="#" data-rte-cmd="list"> <i class="cb-icon-list-bullet" style="font-size:14px;line-height:1.3"></i> </a>' +
					    '<a href="#" data-rte-cmd="createLink"> <i class="cb-icon-link"></i> </a>' +
                        '<a href="#" data-rte-cmd="unlink"> <i class="cb-icon-unlink"></i> </a>' +
                        '<a href="#" data-rte-cmd="html"> <i class="cb-icon-code"></i> </a>' +
					    /*'<a href="#" data-rte-cmd="removeElement"> <i class="cb-icon-trash"></i> </a>' +*/
				'</div>' +
				'' +
				'<div id="divRteLink">' +
					'<i class="cb-icon-link"></i> Edit Link' +
				'</div>' +
				'' +
				'<div id="divFrameLink">' +
					'<i class="cb-icon-link"></i> Edit Link' +
				'</div>' +
                '' +
                '<div class="md-modal" id="md-createlink">' +
			        '<div class="md-content">' +
				        '<div class="md-body">' +
                            '<div class="md-label">URL:</div>' +
                            (bUseCustomFileSelect ? '<input type="text" id="txtLink" class="inptxt" style="float:left;width:50%;" value="http:/' + '/"></input><i class="cb-icon-link md-btnbrowse" id="btnLinkBrowse" style="width:10%;"></i>' : '<input type="text" id="txtLink" class="inptxt" value="http:/' + '/" style="float:left;width:60%"></input>') +
                            '<br style="clear:both">' +
                            '<div class="md-label">Text:</div>' +
                            '<input type="text" id="txtLinkText" class="inptxt" style="float:right;width:60%"></input>' +
                            '<br style="clear:both">' +
                            '<div class="md-label">Target:</div>' +
                            '<label style="float:left;" for="chkNewWindow" class="inpchk"><input type="checkbox" id="chkNewWindow"></input> New Window</label>' +
				        '</div>' +
					    '<div class="md-footer">' +
                            '<button id="btnLinkOk"> Ok </button>' +
                        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-createsrc">' +
			        '<div class="md-content">' +
				        '<div class="md-body">' +
                            '<input type="text" id="txtSrc" class="inptxt" value="http:/' + '/"></input>' +
				        '</div>' +
					    '<div class="md-footer">' +
                            '<button id="btnSrcOk"> Ok </button>' +
                        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-align" style="background:#fff;padding:15px 0px 15px 15px;border-radius:12px">' +
			        '<div class="md-content">' +
				        '<div class="md-body">' +
                            '<button class="md-pickalign" data-align="left"> <i class="cb-icon-align-left"></i> <span>Left</span> </button>' +
                            '<button class="md-pickalign" data-align="center"> <i class="cb-icon-align-center"></i> <span>Center</span> </button>' +
                            '<button class="md-pickalign" data-align="right"> <i class="cb-icon-align-right"></i> <span>Right</span> </button>' +
                            '<button class="md-pickalign" data-align="justify"> <i class="cb-icon-align-justify"></i> <span>Full</span> </button>' +                          
				        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-list" style="background:#fff;padding:15px 0px 15px 15px;border-radius:12px">' +
			        '<div class="md-content">' +
				        '<div class="md-body">' +
                            '<button class="md-picklist half" data-list="indent" style="margin-right:0px"> <i class="cb-icon-indent-left"></i> </button>' +
                            '<button class="md-picklist half" data-list="outdent"> <i class="cb-icon-indent-right"></i> </button>' +                             
                            '<button class="md-picklist" data-list="insertUnorderedList"> <i class="cb-icon-list-bullet"></i> <span>Bullet</span> </button>' +
                            '<button class="md-picklist" data-list="insertOrderedList"> <i class="cb-icon-list-numbered"></i> <span>Numbered</span> </button>' +
                            '<button class="md-picklist" data-list="normal"> <i class="cb-icon-cancel"></i> <span>None</span> </button>' +
				        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-fonts" style="border-radius:12px">' +
			        '<div class="md-content" style="border-radius:12px">' +
				        '<div class="md-body">' +
                            '<iframe id="ifrFonts" style="width:100%;height:371px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' +
                            '<button class="md-pickfontfamily" data-font-family="" data-provider="" style="display:none"></button>' +
                        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-fontsize" style="border-radius:12px">' +
			        '<div class="md-content" style="border-radius:12px">' +
				        '<div class="md-body">' +
                            '<iframe id="ifrFontSize" style="width:100%;height:319px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' +
                            '<button class="md-pickfontsize" data-font-size="" style="display:none"></button>' +
                        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-headings" style="border-radius:12px">' +
			        '<div class="md-content" style="border-radius:12px">' +
				        '<div class="md-body">' +
                            '<iframe id="ifrHeadings" style="width:100%;height:335px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' +
                            '<button class="md-pickheading" data-heading="" style="display:none"></button>' +
                        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-color" style="background:#fff;padding:15px 0px 15px 15px;border-radius:12px">' +
			        '<div class="md-content">' +
				        '<div class="md-body">' +
                            '<div style="width:100%">' +                            
                            '<select id="selColorApplyTo" style="width:85%"><option value="1">Text Color</option><option value="2">Background</option><option value="3">Block Background</option></select>' +
                            '<button id="btnCleanColor" style="cursor: pointer;background: #FFFFFF;border: none;margin: 0 0 0 10px;vertical-align: middle;"><i class="cb-icon-eraser" style="color:#555;font-size:25px"></i></button>' +
                            '</div>' +
                            '[COLORS]' +
				        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-html">' +
			        '<div class="md-content">' +
				        '<div class="md-body">' +
                            '<textarea id="txtHtml" class="inptxt" style="height:350px;"></textarea>' +
				        '</div>' +
					    '<div class="md-footer">' +
                            '<button id="btnHtmlOk"> Ok </button>' +
                        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div class="md-modal" id="md-fileselect">' +
			        '<div class="md-content">' +
				        '<div class="md-body">' +
                            (bUseCustomFileSelect ? '<iframe id="ifrFileBrowse" style="width:100%;height:400px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' : '') +
				        '</div>' +
			        '</div>' +
		        '</div>' +
                '<input type="hidden" id="active-input" />' +
                '' +
                '<div class="md-modal" id="md-delrowconfirm">' +
			        '<div class="md-content">' +
				        '<div class="md-body">' +                            
                            '<div style="padding:12px 20px 25px;text-align:center;">' +
                            '<p>Are you sure you want to delete this block?</p>' +
                            '<button class="btn btn-default" id="btnDelRowCancel"> CANCEL </button>' +
                            '<button class="btn btn-primary" id="btnDelRowOk" style="margin-left:12px"> OK </button>' +
                            '</div>' +
				        '</div>' +
			        '</div>' +
		        '</div>' +
                '' +
                '<div id="temp-contenteditor"></div>' +
                '';

            var html_colors = '';
            for(var i=0;i<this.settings.colors.length;i++){
                if(this.settings.colors[i]=='#ececec'){
                    html_colors+='<button class="md-pick" style="background:' + this.settings.colors[i] + ';border:#e7e7e7 1px solid"></button>';
                }else{
                    html_colors+='<button class="md-pick" style="background:' + this.settings.colors[i] + ';border:' + this.settings.colors[i] + ' 1px solid"></button>';
                }
            }
            html_rte = html_rte.replace('[COLORS]', html_colors);
   
            if (jQuery('#rte-toolbar').length == 0) {

                jQuery('#divCb').append(html_rte);

                this.prepareRteCommand('bold');
                this.prepareRteCommand('italic');
                this.prepareRteCommand('underline');
                this.prepareRteCommand('strikethrough');
                this.prepareRteCommand('undo');
                this.prepareRteCommand('redo');
            }


            var isCtrl = false;

            $element.bind('keyup', function (e) {
                $element.data('contenteditor').realtime();
            });
            $element.bind('mouseup', function (e) {
                $element.data('contenteditor').realtime();
            });
            /* Paste Content: Right Click */
            jQuery(document).on("paste",'#' + $element.attr('id'),function(e) {
                pasteContent($activeElement);
            });

            $element.bind('keydown', function (e) { 

                // Fix Select-All on <p> and then delete or backspace it.
                if (e.which == 46 || e.which == 8) {
                    var el;
                    try{
                        if (window.getSelection) {
                            el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                        }
                        else if (document.selection) {
                            el = document.selection.createRange().parentElement();
                        }

                        if(el.nodeName.toLowerCase()=='p'){
                            var t = '';
                            if(window.getSelection){
                                t = window.getSelection().toString();
                            }else if(document.getSelection){
                                t = document.getSelection().toString();
                            }else if(document.selection){
                                t = document.selection.createRange().text;
                            }
                            if(t==el.innerText) {
                                jQuery(el).html('<br>');
                                return false;
                            }
                        }
                    } catch(e) {}
                }
            
                /* Paste Content: CTRL-V */
                if (e.which == 17) {
                    isCtrl = true;
                    return;
                }
                if ((e.which == 86 && isCtrl == true) || (e.which == 86 && e.metaKey)) {

                    pasteContent($activeElement);

                }

                /* CTRL-A */
                if (e.ctrlKey) {
                    if (e.keyCode == 65 || e.keyCode == 97) { // 'A' or 'a'
                        e.preventDefault();

                        var is_ie = detectIE();
                        var el;
                        try{
                            if (window.getSelection) {
                                el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                            }
                            else if (document.selection) {
                                el = document.selection.createRange().parentElement();
                            }
                        } catch(e) {
                            return;
                        }
                        if (is_ie) {                        
                            var range = document.body.createTextRange();
                            range.moveToElementText(el);
                            range.select();
                        }
                        else {
                            var range = document.createRange();
                            range.selectNodeContents(el);
                            var oSel = window.getSelection();
                            oSel.removeAllRanges();
                            oSel.addRange(range);
                        }
                    }
                } 
                               

            }).keyup(function (e) {
                if (e.which == 17) {
                    isCtrl = false; // no Ctrl
                }
            });

            // finish editing on click outside
            jQuery(document).on('mousedown', function (event) {

                var bEditable = false;

                if (jQuery('#rte-toolbar').css('display') == 'none') return;

                var el = jQuery(event.target).prop("tagName").toLowerCase();

                jQuery(event.target).parents().each(function (e) {
                    if (jQuery(this).is('[contenteditable]') ||                        
                        jQuery(this).hasClass('md-modal') ||
                        jQuery(this).attr('id') == 'divCb'                        
                        ) {
                        bEditable = true;
                        return;
                    }
                });

                if (jQuery(event.target).is('[contenteditable]')) {
                    bEditable = true;
                    return;
                }

                /*
                if ((jQuery(event.target).is('[contenteditable]') ||
                    jQuery(event.target).css('position') == 'absolute' ||
                    jQuery(event.target).css('position') == 'fixed' ||
                    jQuery(event.target).attr('id') == 'rte-toolbar') &&
                    el != 'img' &&
                    el != 'hr'
                    ) {
                    bEditable = true;
                    return;
                }

                jQuery(event.target).parents().each(function (e) {

                    if (jQuery(this).is('[contenteditable]') ||
                        jQuery(this).css('position') == 'absolute' ||
                        jQuery(this).css('position') == 'fixed' ||
                        jQuery(this).attr('id') == 'rte-toolbar'
                        ) {
                        bEditable = true;
                        return;
                    }

                });
                */
                
                if (!bEditable) {
                    $activeElement = null;

                    if ($element.data('contenteditor').settings.toolbarDisplay=='auto') {
                        jQuery('#rte-toolbar').css('display', 'none');
                    }

                    if ($element.data('contenteditor').settings.outline) {
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).css('outline', '');
                            jQuery(instances[i]).find('*').css('outline', '');
                        }
                    }
              
                    $element.data('contentbuilder').clearControls();

                }
            });

            
        };

        this.realtime = function(){

            var is_ie = detectIE();

            var el;
            try{
                if (window.getSelection) {//https://www.jabcreations.com/blog/javascript-parentnode-of-selected-text
                    el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                }
                else if (document.selection) {
                    el = document.selection.createRange().parentElement();
                }
            } catch(e) {
                return;
            }

            if( jQuery(el).parents("[data-mode='code']").length > 0 ) return; //Mode: code

            if( jQuery(el).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

            if(el.nodeName.toLowerCase()=='a'){
                if (is_ie) {
                    //already selected when clicked
                    /*if (document.selection.type != "Control") {
                        try {
                            var range = document.body.createTextRange();
                            range.moveToElementText(el);
                            range.select();
                        } catch (e) { return; }
                    }*/
                }
                else {
                    /*var range = document.createRange();
                    range.selectNodeContents(el);
                    var oSel = window.getSelection();
                    oSel.removeAllRanges();
                    oSel.addRange(range);*/
                }
                jQuery("#divRteLink").addClass('forceshow');
            } else {
                jQuery("#divRteLink").removeClass('forceshow');
            }

        };

        this.render = function () {

            //var zoom = $element.css('zoom');
            var zoom;
            if (localStorage.getItem("zoom") != null) {
                zoom = localStorage.zoom;
            } else {
                zoom = $element.css('zoom');
            }

            if (zoom == undefined) zoom = 1;
            localStorage.zoom = zoom;

            var editable = $element.data('contenteditor').settings.editable;
            if (editable == '') {

                $element.attr('contenteditable', 'true');

                $element.unbind('mousedown');
                $element.bind('mousedown', function (e) {

                    $activeElement = jQuery(this);

                    jQuery("#rte-toolbar").stop(true, true).fadeIn(200);

                    if ($element.data('contenteditor').settings.outline) {
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).css('outline', '');
                            jQuery(instances[i]).find('*').css('outline', '');
                        }
                        jQuery(this).css('outline', 'rgba(0, 0, 0, 0.43) dashed 1px');
                    }

                    /*if (jQuery(this).prop("tagName").toLowerCase() == 'a') {
                    jQuery('#rte-toolbar a[data-rte-cmd="createLink"]').css('display', 'none');
                    } else {
                    jQuery('#rte-toolbar a[data-rte-cmd="createLink"]').css('display', 'inline-block');
                    }*/

                });

            } else {

                $element.find(editable).each(function () {

                    if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code

                    if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

                    var editMode = $element.data('contenteditor').settings.editMode;
                    if (editMode == 'default') {
                        
                        //do nothing (parent will set editable)

                    } else {

                        var attr = jQuery(this).attr('contenteditable');

                        if (typeof attr !== typeof undefined && attr !== false) {

                        } else {

                            jQuery(this).attr('contenteditable', 'true');

                        }

                    }

                });
     
                $element.find(editable).unbind('mousedown');
                $element.find(editable).bind('mousedown', function (e) {
              
                    $activeElement = jQuery(this);

                    jQuery("#rte-toolbar").stop(true, true).fadeIn(200);
                    if ($element.data('contenteditor').settings.outline) {
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).css('outline', '');
                            jQuery(instances[i]).find('*').css('outline', '');
                        }
                        jQuery(this).css('outline', 'rgba(0, 0, 0, 0.43) dashed 1px');
                    }

                    /*if (jQuery(this).prop("tagName").toLowerCase() == 'a') {
                    jQuery('#rte-toolbar a[data-rte-cmd="createLink"]').css('display', 'none');
                    } else {
                    jQuery('#rte-toolbar a[data-rte-cmd="createLink"]').css('display', 'inline-block');
                    }*/

                });


                //Kalau di dalam .edit ada contenteditable, hapus, krn tdk perlu & di IE membuat keluar handler.
                $element.find('.edit').find(editable).removeAttr('contenteditable');

            }


            //$element.find('a').attr('contenteditable', 'true');
            $element.find('a').each(function(){
                if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code
                if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

                jQuery(this).attr('contenteditable', 'true');
            });


            var editMode = $element.data('contenteditor').settings.editMode;
            if (editMode == 'default') {

                $element.find("h1,h2,h3,h4,h5,h6").unbind('keydown'); //keypress
                $element.find("h1,h2,h3,h4,h5,h6").bind('keydown', function (e) {

                    if (e.keyCode == 13) {
                    
                        var is_ie = detectIE();
                        if (is_ie && is_ie<=10) {
                            var oSel = document.selection.createRange();
                            if (oSel.parentElement) {
                                oSel.pasteHTML('<br>');
                                e.cancelBubble = true;
                                e.returnValue = false;
                                oSel.select();
                                oSel.moveEnd("character", 1);
                                oSel.moveStart("character", 1);
                                oSel.collapse(false);
                                return false;
                            }
                        } else {
                            //document.execCommand('insertHTML', false, '<br><br>');
                            //return false;

                            var oSel = window.getSelection();
                            var range = oSel.getRangeAt(0);
                            range.extractContents();
                            range.collapse(true);
                            var docFrag = range.createContextualFragment('<br>');
                            //range.collapse(false);
                            var lastNode = docFrag.lastChild;
                            range.insertNode(docFrag);
                            //try { oEditor.document.designMode = "on"; } catch (e) { }
                            range.setStartAfter(lastNode);
                            range.setEndAfter(lastNode);

                            //workaround.for unknown reason, chrome need 2 br to make new line if cursor located at the end of document.
                            if (range.endContainer.nodeType == 1) {
                                // 
                                if (range.endOffset == range.endContainer.childNodes.length - 1) {
                                    range.insertNode(range.createContextualFragment("<br />"));
                                    range.setStartAfter(lastNode);
                                    range.setEndAfter(lastNode);
                                }
                            }
                            //

                            var comCon = range.commonAncestorContainer;
                            if (comCon && comCon.parentNode) {
                                try { comCon.parentNode.normalize(); } catch (e) { }
                            }

                            oSel.removeAllRanges();
                            oSel.addRange(range);

                            return false;
                        }

                    }

                });


                //For All Browsers ( Make PARENT editable )
                $element.find("h1,h2,h3,h4,h5,h6,p,img").each(function(){
                    if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code
                    if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

                    jQuery(this).parent().attr('contenteditable',true); //Convert to natural editing
                });
                $element.find(".column").each(function(){ //This specific to css GRID (cell has class="column")
                    if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code
                    if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

                    jQuery(this).attr('contenteditable',true); //Convert to natural editing
                });


                //Fix few problems (on Chrome, Opera)
                $element.find("div").unbind('keyup');
                $element.find("div").bind('keyup', function (e) {

                    var el;
                    var curr;
                    if (window.getSelection) {
                        curr = window.getSelection().getRangeAt(0).commonAncestorContainer;
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                    }
                    else if (document.selection) {
                        curr = document.selection.createRange();
                        el = document.selection.createRange().parentElement();
                    }
                
                    if (e.keyCode == 13 && !event.shiftKey){                    
                        var is_ie = detectIE();
                        if (is_ie>0) {
                    
                        } else {
                            //So that enter at the end of list returns <p>
                            var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
                            var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
                            var isOpera = window.opera;
                            var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                            if(isChrome || isOpera) { 
                                //Without this, pressing ENTER at the end of list will returns <p> on Chrome but then it become <div> (On Opera it returns <div>)
                                //With this, we change it into <p>
                                if(jQuery(el).prop("tagName").toLowerCase()=='p' || jQuery(el).prop("tagName").toLowerCase() =='div') {
                                    document.execCommand('formatBlock', false, '<p>');
                                }
                            }
                            if(isFirefox) {
                                //On FF (when enter at the end of list) jQuery(curr).html() returns undefined
                                if(!jQuery(curr).html()) document.execCommand('formatBlock', false, '<p>');
                            }
                        }
                    }

                    //Safe image delete by applying paragraph (no empty div)
                    if(e.keyCode == 8 || e.keyCode == 46) { //Delete key
                        if(jQuery(el).prop("tagName").toLowerCase() =='div'){ 
                            document.execCommand('formatBlock', false, '<p>');
                        }
                    }

                }); 


            } else {


                //Apply BR on Paragraph Enter
                //p enter ganti div gak bisa di-edit, kalo pake p buggy di IE, jadi pake <br>
                $element.find("p").unbind('keydown'); //keypress
                $element.find("p").bind('keydown', function (e) {
                    /*if (e.keyCode == 13) {
                    jQuery(this).parent().attr('contenteditable', 'true');
                    }*/

                    if (e.keyCode == 13 && $element.find("li").length == 0) {  // don't apply br on li 

                        var UA = navigator.userAgent.toLowerCase();
                        var LiveEditor_isIE = (UA.indexOf('msie') >= 0) ? true : false;
                        if (LiveEditor_isIE) {
                            var oSel = document.selection.createRange();
                            if (oSel.parentElement) {
                                oSel.pasteHTML('<br>');
                                e.cancelBubble = true;
                                e.returnValue = false;
                                oSel.select();
                                oSel.moveEnd("character", 1);
                                oSel.moveStart("character", 1);
                                oSel.collapse(false);
                                return false;
                            }
                        } else {
                            //document.execCommand('insertHTML', false, '<br><br>');
                            //return false;

                            var oSel = window.getSelection();
                            var range = oSel.getRangeAt(0);
                            range.extractContents();
                            range.collapse(true);
                            var docFrag = range.createContextualFragment('<br>');
                            //range.collapse(false);
                            var lastNode = docFrag.lastChild;
                            range.insertNode(docFrag);
                            //try { oEditor.document.designMode = "on"; } catch (e) { }
                            range.setStartAfter(lastNode);
                            range.setEndAfter(lastNode);

                            //workaround.for unknown reason, chrome need 2 br to make new line if cursor located at the end of document.
                            if (range.endContainer.nodeType == 1) {
                                // 
                                if (range.endOffset == range.endContainer.childNodes.length - 1) {
                                    range.insertNode(range.createContextualFragment("<br />"));
                                    range.setStartAfter(lastNode);
                                    range.setEndAfter(lastNode);
                                }
                            }
                            //

                            var comCon = range.commonAncestorContainer;
                            if (comCon && comCon.parentNode) {
                                try { comCon.parentNode.normalize(); } catch (e) { }
                            }

                            oSel.removeAllRanges();
                            oSel.addRange(range);

                            return false;
                        }

                    }
                });

            }


            jQuery('#rte-toolbar a[data-rte-cmd="removeElement"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="removeElement"]').click(function (e) {

                $activeElement.remove();

                $element.data('contenteditor').settings.hasChanged = true;
                $element.data('contenteditor').render();

                e.preventDefault();
            });

            jQuery('#rte-toolbar a[data-rte-cmd="color"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="color"]').click(function (e) {

                var savedSel = saveSelection();

                /**** Custom Modal ****/
                jQuery('#md-color').css('max-width', '465px');
                jQuery('#md-color').simplemodal();
                jQuery('#md-color').data('simplemodal').show();
                e.preventDefault();

                //Prepare 
                var text = getSelected();                          

                jQuery('.md-pick').unbind('click');
                jQuery('.md-pick').click(function(){

                    restoreSelection(savedSel);

                    var el;
                    var curr;
                    if (window.getSelection) {
                        curr = window.getSelection().getRangeAt(0).commonAncestorContainer;
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                    }
                    else if (document.selection) {
                        curr = document.selection.createRange();
                        el = document.selection.createRange().parentElement();
                    }

                    var selColMode = jQuery('#selColorApplyTo').val(); //1 color 2 background 3 background block

                    if (jQuery.trim(text) != '' && jQuery(curr).text() != text) {
                        if(selColMode==1) {
                            //Set text color
                            document.execCommand("ForeColor",false,jQuery(this).css("background-color"));
                        }
                        if(selColMode==2) {
                            //Set text background
                            document.execCommand("BackColor",false,jQuery(this).css("background-color"));
                        }
                        //Cleanup FONTs
                        var fontElements = document.getElementsByTagName("font");
                        for (var i = 0, len = fontElements.length; i < len; ++i) {
                            var s = fontElements[i].color;
                            if(s!='') {
                                fontElements[i].removeAttribute("color");
                                fontElements[i].style.color = s;
                            }
                        }
                        //Cleanup multiple span (IE)
                        var is_ie = detectIE();
                        if (is_ie) {
                            $activeElement.find('span').each(function(){
                                if(jQuery(this).find('span').length==1){
                                    if(jQuery(this).text()==jQuery(this).find('span:first').text()){
                                        var innerspanstyle = jQuery(this).find('span:first').attr('style');
                                        jQuery(this).html(jQuery(this).find('span:first').html());
                                        var newstyle = jQuery(this).attr('style')+';'+innerspanstyle;
                                        jQuery(this).attr('style',newstyle);
                                    }
                                }
                            });
                        }
                                          
                    }
                    else if (jQuery(curr).text() == text) {//selection fully mode on text AND element. Use element then.
                        if(selColMode==1) {
                            //Set element color
                            if(jQuery(curr).html()){
                                jQuery(curr).css('color', jQuery(this).css("background-color"));
                            } else {
                                jQuery(curr).parent().css('color', jQuery(this).css("background-color"));
                            }
                        }
                        if(selColMode==2) {
                            //Set element background
                            if(jQuery(curr).html()){
                                jQuery(curr).css('background-color', jQuery(this).css("background-color"));
                            } else {
                                jQuery(curr).parent().css('background-color', jQuery(this).css("background-color"));
                            }                            
                        }
                    }
                    else{
                        if(selColMode==1) {
                            //Set element color
                            jQuery(el).css('color', jQuery(this).css("background-color"));
                        }
                        if(selColMode==2) {
                            //Set element background
                            jQuery(el).css('background-color', jQuery(this).css("background-color"));
                        }
                    };
                    if(selColMode==3) {
                        //Set block background
                        //jQuery(el).parents('.ui-draggable').children('div').first().css('background-color', jQuery(this).css("background-color") );
                        jQuery(el).parents('.ui-draggable').children().first().css('background-color', jQuery(this).css("background-color") );
                    }
                    /*if(selColMode==4) {
                        //Set content background
                        $element.css('background-color', jQuery(this).css("background-color") );
                    }*/

                    jQuery('#md-color').data('simplemodal').hide();
                });

                jQuery('#btnCleanColor').unbind('click');
                jQuery('#btnCleanColor').click(function(){
                     
                    restoreSelection(savedSel);

                    var el;
                    var curr;
                    if (window.getSelection) {
                        curr = window.getSelection().getRangeAt(0).commonAncestorContainer;
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                    }
                    else if (document.selection) {
                        curr = document.selection.createRange();
                        el = document.selection.createRange().parentElement();
                    }

                    var selColMode = jQuery('#selColorApplyTo').val(); //1 color 2 background 3 background block

                    if (jQuery.trim(text) != '' && jQuery(curr).text() != text) {
                        if(selColMode==1) {
                            //Set text color
                            document.execCommand("ForeColor",false,'');
                        }
                        if(selColMode==2) {
                            //Set text background
                            document.execCommand("BackColor",false,'');
                        }
                        //Cleanup FONTs
                        var fontElements = document.getElementsByTagName("font");
                        for (var i = 0, len = fontElements.length; i < len; ++i) {
                            var s = fontElements[i].color;
                            fontElements[i].removeAttribute("color");
                            fontElements[i].style.color = s;
                        }
                    }
                    else if (jQuery(curr).text() == text) {//selection fully mode on text AND element. Use element then.
                        if(selColMode==1) {
                            //Set element color
                            if(jQuery(curr).html()){
                                jQuery(curr).css('color', '');
                            } else {
                                jQuery(curr).parent().css('color', '');
                            }
                        }
                        if(selColMode==2) {
                            //Set element background
                            if(jQuery(curr).html()){
                                jQuery(curr).css('background-color', '');
                            } else {
                                jQuery(curr).parent().css('background-color', '');
                            }
                        }
                    }
                    else{
                        if(selColMode==1) {
                            //Set element color
                            jQuery(el).css('color', '');
                        }
                        if(selColMode==2) {
                            //Set element background
                            jQuery(el).css('background-color', '');
                        }
                    };

                    if(selColMode==3) {
                        //Set block background
                        //jQuery(curr).parents('.ui-draggable').children('div').first().css('background-color', '' );
                        jQuery(curr).parents('.ui-draggable').children().first().css('background-color', '' );
                    }

                    jQuery('#md-color').data('simplemodal').hide();

                });
                /**** /Custom Modal ****/
            });


            jQuery('#rte-toolbar a[data-rte-cmd="fontsize"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="fontsize"]').click(function (e) {

                var savedSel = saveSelection();

                /**** Custom Modal ****/
                jQuery('#md-fontsize').css('max-width', '190px');
                jQuery('#md-fontsize').simplemodal();
                jQuery('#md-fontsize').data('simplemodal').show();
                e.preventDefault();
                
                if(jQuery('#ifrFontSize').attr('src').indexOf('fontsize.html') == -1) {
                    jQuery('#ifrFontSize').attr('src',sScriptPath+'fontsize.html');
                }

                //Prepare 
                var text = getSelected();  

                jQuery('.md-pickfontsize').unbind('click');
                jQuery('.md-pickfontsize').click(function(){

                    restoreSelection(savedSel);

                    var el;
                    var curr;
                    if (window.getSelection) {
                        curr = window.getSelection().getRangeAt(0).commonAncestorContainer;
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                    }
                    else if (document.selection) {
                        curr = document.selection.createRange();
                        el = document.selection.createRange().parentElement();
                    }

                    var s = jQuery(this).attr('data-font-size');

                    if (jQuery.trim(text) != '' && jQuery(curr).text() != text) {                        
                        document.execCommand("fontSize", false, "7");
                        var fontElements = document.getElementsByTagName("font");
                        for (var i = 0, len = fontElements.length; i < len; ++i) {
                            if (fontElements[i].size == "7") {
                                fontElements[i].removeAttribute("size");
                                fontElements[i].style.fontSize = s;
                            }
                        }
                    }
                    else if (jQuery(curr).text() == text) {//selection fully mode on text AND element. Use element then.
                        if(jQuery(curr).html()){
                            jQuery(curr).css('font-size', s);
                        } else {
                            jQuery(curr).parent().css('font-size', s);
                        }
                    }
                    else{
                        jQuery(el).css('font-size', s);
                    };

                    jQuery(this).blur();

                    $element.data('contenteditor').settings.hasChanged = true;

                    e.preventDefault();

                    jQuery('#md-fontsize').data('simplemodal').hide();

                });
                /**** /Custom Modal ****/
            });


            jQuery('#rte-toolbar a[data-rte-cmd="formatPara"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="formatPara"]').click(function (e) {

                var savedSel = saveSelection();

                /**** Custom Modal ****/
                jQuery('#md-headings').css('max-width', '225px');
                jQuery('#md-headings').simplemodal();
                jQuery('#md-headings').data('simplemodal').show();
                e.preventDefault();
                
                if(jQuery('#ifrHeadings').attr('src').indexOf('headings.html') == -1) {
                    jQuery('#ifrHeadings').attr('src',sScriptPath+'headings.html');
                }

                jQuery('.md-pickheading').unbind('click');
                jQuery('.md-pickheading').click(function(){

                    restoreSelection(savedSel);

                    var s = jQuery(this).attr('data-heading');

                    $element.attr('contenteditable', true);
                    document.execCommand('formatBlock', false, '<' + s + '>');
                    $element.removeAttr('contenteditable');
                    $element.data('contenteditor').render();

                    jQuery(this).blur();

                    $element.data('contenteditor').settings.hasChanged = true;

                    e.preventDefault();

                    jQuery('#md-headings').data('simplemodal').hide();

                });
                /**** /Custom Modal ****/
            });


            jQuery('#rte-toolbar a[data-rte-cmd="removeFormat"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="removeFormat"]').click(function (e) {

                document.execCommand('removeFormat', false, null);
                document.execCommand('removeFormat', false, null);

                jQuery(this).blur();

                $element.data('contenteditor').settings.hasChanged = true;

                e.preventDefault();
            });


            jQuery('#rte-toolbar a[data-rte-cmd="unlink"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="unlink"]').click(function (e) {

                document.execCommand('unlink', false, null);
                jQuery("#divRteLink").removeClass('forceshow');

                jQuery(this).blur();

                $element.data('contenteditor').settings.hasChanged = true;

                e.preventDefault();
            });

            var storedEl;
            jQuery('#rte-toolbar a[data-rte-cmd="html"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="html"]').click(function (e) {

                var el;
                if (window.getSelection) {
                    el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                }
                else if (document.selection) {
                    el = document.selection.createRange().parentElement();
                }

                var found=false;
                jQuery(el).parents().each(function () {
                    if (jQuery(this).data('contentbuilder')) {
                        jQuery(this).data('contentbuilder').viewHtml(); 
                        
                        found=true;
                        storedEl = el;
                    }
                });

                //In case of not focus
                if(!found && storedEl){
                    el = storedEl;
                    jQuery(el).parents().each(function () {
                        if (jQuery(this).data('contentbuilder')) {
                            jQuery(this).data('contentbuilder').viewHtml(); 
                        }
                    });
                }
                e.preventDefault();

            });


            jQuery('#rte-toolbar a[data-rte-cmd="font"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="font"]').click(function (e) {
                
                var savedSel = saveSelection();

                /**** Custom Modal ****/
                jQuery('#md-fonts').css('max-width', '300px');
                jQuery('#md-fonts').simplemodal();
                jQuery('#md-fonts').data('simplemodal').show();
                e.preventDefault();

                if(jQuery('#ifrFonts').attr('src').indexOf('fonts.html') == -1) {
                    jQuery('#ifrFonts').attr('src',sScriptPath+'fonts.html');
                }

                jQuery('.md-pickfontfamily').unbind('click');
                jQuery('.md-pickfontfamily').click(function(){
                    
                    restoreSelection(savedSel);

                    var el;
                    if (window.getSelection) {
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                        //TODO
                        if (el.nodeName != 'H1' && el.nodeName != 'H2' && el.nodeName != 'H3' &&
                            el.nodeName != 'H4' && el.nodeName != 'H5' && el.nodeName != 'H6' &&
                            el.nodeName != 'P') {
                            el = el.parentNode;
                        }
                    }
                    else if (document.selection) {
                        el = document.selection.createRange().parentElement();
                        if (el.nodeName != 'H1' && el.nodeName != 'H2' && el.nodeName != 'H3' &&
                            el.nodeName != 'H4' && el.nodeName != 'H5' && el.nodeName != 'H6' &&
                            el.nodeName != 'P') {
                            el = el.parentElement();
                        }
                    }

                    var s = jQuery(this).attr('data-font-family');                    
                    jQuery(el).css('font-family', s);
                    var fontname = s.split(',')[0];
                    var provider = jQuery(this).attr('data-provider');
                    if(provider=='google'){
                        var bExist = false;
                        var links=document.getElementsByTagName("link"); 
                        for(var i=0;i<links.length;i++) {                        
                            var sSrc=links[i].href.toLowerCase();                        
                            sSrc = sSrc.replace(/\+/g,' ').replace(/%20/g,' '); 
                            if(sSrc.indexOf(fontname.toLowerCase())!=-1) bExist=true;
                        }
                        if(!bExist) $element.append('<link href="//fonts.googleapis.com/css?family='+fontname+'" rel="stylesheet" property="stylesheet" type="text/css">');
                    }

                    //TODO: make function
                    //Cleanup Google font css link
                    $element.find('link').each(function(){
                        var sSrc=jQuery(this).attr('href').toLowerCase();
                        if(sSrc.indexOf('googleapis')!=-1) {
                            //get fontname
                            sSrc = sSrc.replace(/\+/g,' ').replace(/%20/g,' '); 
                            var fontname = sSrc.substr( sSrc.indexOf('family=') + 7 );
                            if(fontname.indexOf(':') != -1){
                                fontname = fontname.split(':')[0];
                            }
                            if(fontname.indexOf('|') != -1){
                                fontname = fontname.split('|')[0];
                            }
                            //check if fontname used in content
                            var tmp = '';
                            jQuery(cb_list).each(function(){
                                tmp += jQuery(this).data('contentbuilder').html().toLowerCase();
                            });
                            //var tmp = $element.html().toLowerCase();
                            var count = tmp.split(fontname).length;
                            if(count<3){
                                //not used                              
                                jQuery(this).attr('rel','_del');
                            }
                        }
                    });
                    $element.find('[rel="_del"]').remove();//del not used google font css link

                    jQuery(this).blur();

                    $element.data('contenteditor').settings.hasChanged = true;

                    e.preventDefault();

                    jQuery('#md-fonts').data('simplemodal').hide();
                });
                /**** /Custom Modal ****/
            });

            jQuery('#rte-toolbar a[data-rte-cmd="align"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="align"]').click(function (e) {
                
                var savedSel = saveSelection();

                /**** Custom Modal ****/
                jQuery('#md-align').css('max-width', '185px');
                jQuery('#md-align').simplemodal();
                jQuery('#md-align').data('simplemodal').show();
                e.preventDefault();

                jQuery('.md-pickalign').unbind('click');
                jQuery('.md-pickalign').click(function(){
                    
                    restoreSelection(savedSel);
           
                    var el;
                    if (window.getSelection) {
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                        //TODO
                        if (el.nodeName != 'H1' && el.nodeName != 'H2' && el.nodeName != 'H3' &&
                            el.nodeName != 'H4' && el.nodeName != 'H5' && el.nodeName != 'H6' &&
                            el.nodeName != 'P') {
                            el = el.parentNode;
                        }
                    }
                    else if (document.selection) {
                        el = document.selection.createRange().parentElement();
                        if (el.nodeName != 'H1' && el.nodeName != 'H2' && el.nodeName != 'H3' &&
                            el.nodeName != 'H4' && el.nodeName != 'H5' && el.nodeName != 'H6' &&
                            el.nodeName != 'P') {
                            el = el.parentElement();
                        }
                    }

                    var s = jQuery(this).data('align');
                    el.style.textAlign = s;

                    jQuery(this).blur();

                    $element.data('contenteditor').settings.hasChanged = true;

                    e.preventDefault();

                    jQuery('#md-align').data('simplemodal').hide();
                });
                /**** /Custom Modal ****/
            });

            jQuery('#rte-toolbar a[data-rte-cmd="list"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="list"]').click(function (e) {

                var savedSel = saveSelection();

                /**** Custom Modal ****/
                jQuery('#md-list').css('max-width', '185px');
                jQuery('#md-list').simplemodal();
                jQuery('#md-list').data('simplemodal').show();
                e.preventDefault();

                jQuery('.md-picklist').unbind('click');
                jQuery('.md-picklist').click(function(){

                    restoreSelection(savedSel);

                    var s = jQuery(this).data('list');

                    try {
                        if(s=='normal') {
                            document.execCommand('outdent', false, null);
                            document.execCommand('outdent', false, null);
                            document.execCommand('outdent', false, null);                            
                        } else {
                            document.execCommand(s, false, null);
                        }
                    } catch (e) {
                        //FF fix
                        $activeElement.parents('div').addClass('edit');
                        var el;
                        if (window.getSelection) {
                            el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                            el = el.parentNode;
                        }
                        else if (document.selection) {
                            el = document.selection.createRange().parentElement();
                            el = el.parentElement();
                        }
                        //alert(el.nodeName)
                        el.setAttribute('contenteditable', true);
                        if(s=='normal') {
                            document.execCommand('outdent', false, null);
                            document.execCommand('outdent', false, null);
                            document.execCommand('outdent', false, null);
                        } else {
                            document.execCommand(s, false, null);
                        }
                        el.removeAttribute('contenteditable');
                        $element.data('contenteditor').render();
                    }

                    jQuery(this).blur();

                    $element.data('contenteditor').settings.hasChanged = true;

                    e.preventDefault();

                    jQuery('#md-list').data('simplemodal').hide();
                });
                /**** /Custom Modal ****/
            });

            jQuery('#rte-toolbar a[data-rte-cmd="createLink"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="createLink"]').click(function (e) {

                // source: 	http://stackoverflow.com/questions/6251937/how-to-get-selecteduser-highlighted-text-in-contenteditable-element-and-replac
                //   		http://stackoverflow.com/questions/4652734/return-html-from-a-user-selection/4652824#4652824
                var html = "";
                if (typeof window.getSelection != "undefined") {
                    var sel = window.getSelection();
                    if (sel.rangeCount) {
                        var container = document.createElement("div");
                        for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                            container.appendChild(sel.getRangeAt(i).cloneContents());
                        }
                        html = container.innerHTML;
                    }
                } else if (typeof document.selection != "undefined") {
                    if (document.selection.type == "Text") {
                        html = document.selection.createRange().htmlText;
                    }
                }

                if (html == '') {
                    alert('Please select some text.');
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return;
                }

                var el;
                if (window.getSelection) {//https://www.jabcreations.com/blog/javascript-parentnode-of-selected-text
                    el = window.getSelection().getRangeAt(0).commonAncestorContainer;
                }
                else if (document.selection) {
                    el = document.selection.createRange();
                }
                if(el.nodeName.toLowerCase()=='a'){
                    $activeLink = jQuery(el);
                } else {
                    document.execCommand('createLink', false, 'http://dummy');
                    $activeLink = jQuery("a[href='http://dummy']").first();
                    $activeLink.attr('href', 'http://');
                }

                /**** Custom Modal ****/
                jQuery('#md-createlink').css('max-width', '800px');
                jQuery('#md-createlink').simplemodal({
                    onCancel: function () {
                        if ($activeLink.attr('href') == 'http://')
                            $activeLink.replaceWith($activeLink.html());
                    }
                });
                jQuery('#md-createlink').data('simplemodal').show();

                jQuery('#txtLink').val($activeLink.attr('href'));
                jQuery('#txtLinkText').val($activeLink.html());
                if($activeLink.attr('target')=='_blank'){
                    jQuery('#chkNewWindow').prop('checked', true);
                } else {
                    jQuery('#chkNewWindow').removeAttr('checked');
                }

                jQuery('#btnLinkOk').unbind('click');
                jQuery('#btnLinkOk').bind('click', function (e) {
                    $activeLink.attr('href', jQuery('#txtLink').val());

                    if (jQuery('#txtLink').val() == 'http://' || jQuery('#txtLink').val() == '') {
                        $activeLink.replaceWith($activeLink.html());
                    }
                    $activeLink.html(jQuery('#txtLinkText').val());
                    if(jQuery('#chkNewWindow').is(":checked")){
                        $activeLink.attr('target','_blank');
                    } else {
                        $activeLink.removeAttr('target');
                    }

                    jQuery('#md-createlink').data('simplemodal').hide();

                    //$element.data('contenteditor').settings.hasChanged = true;
                    //$element.data('contenteditor').render();
                    for (var i = 0; i < instances.length; i++) {
                        jQuery(instances[i]).data('contenteditor').settings.hasChanged = true;
                        jQuery(instances[i]).data('contenteditor').render();
                    }

                });
                /**** /Custom Modal ****/

                e.preventDefault(); //spy wkt rte's link btn di-click, browser scroll tetap.

            });

            $element.find(".embed-responsive").unbind('hover');
            $element.find(".embed-responsive").hover(function (e) {

                if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code

                if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly
            
                var zoom = localStorage.zoom;
                if (zoom == 'normal') zoom = 1;
                if (zoom == undefined) zoom = 1;

                //IE fix
                zoom = zoom + ''; //Fix undefined
                if (zoom.indexOf('%') != -1) {
                    zoom = zoom.replace('%', '') / 100;
                }
                if (zoom == 'NaN') {
                    zoom = 1;
                }

                zoom = zoom*1;

                var _top; var _left;
                var scrolltop = jQuery(window).scrollTop();
                var offsettop = jQuery(this).offset().top;
                var offsetleft = jQuery(this).offset().left;
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                var is_ie = detectIE();
                var browserok = true;
                if (is_firefox||is_ie) browserok = false;
                if(browserok){
                    //Chrome 37, Opera 24
                    _top = ((offsettop - 20) * zoom) + (scrolltop - scrolltop * zoom);
                    _left = offsetleft * zoom;
                } else {
                    if(is_ie){
                        //IE 11 (Adjustment required)

                        //Custom formula for adjustment in IE11
                        var space = $element.getPos().top;
                        var adjy_val = (-space/1.1)*zoom + space/1.1;
                        var space2 = $element.getPos().left;
                        var adjx_val = -space2*zoom + space2; 

                        var p = jQuery(this).getPos();
                        _top = ((p.top - 20) * zoom) + adjy_val;
                        _left = (p.left * zoom) + adjx_val;
                    }  
                    if(is_firefox) {
                        //Firefox (No Adjustment required)
                        _top = offsettop - 20;
                        _left = offsetleft;
                    }
                }
                jQuery("#divFrameLink").css("top", _top + "px");
                jQuery("#divFrameLink").css("left", _left + "px");


                jQuery("#divFrameLink").stop(true, true).css({ display: 'none' }).fadeIn(20);

                $activeFrame = jQuery(this).find('iframe');

                jQuery("#divFrameLink").unbind('click');
                jQuery("#divFrameLink").bind('click', function (e) {

                    /**** Custom Modal ****/
                    jQuery('#md-createsrc').css('max-width', '800px');
                    jQuery('#md-createsrc').simplemodal();
                    jQuery('#md-createsrc').data('simplemodal').show();
   
                    jQuery('#txtSrc').val($activeFrame.attr('src'));

                    jQuery('#btnSrcOk').unbind('click');
                    jQuery('#btnSrcOk').bind('click', function (e) {
                        
                        var srcUrl = jQuery('#txtSrc').val();

                        var youRegex = /^http[s]?:\/\/(((www.youtube.com\/watch\?(feature=player_detailpage&)?)v=)|(youtu.be\/))([^#\&\?]*)/;
                        var vimeoRegex = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/)|(video\/))?([0-9]+)\/?/;
                        var youRegexMatches = youRegex.exec(srcUrl);
                        var vimeoRegexMatches = vimeoRegex.exec(srcUrl); 
                        if (youRegexMatches != null || vimeoRegexMatches != null) {
                            if (youRegexMatches != null && youRegexMatches.length >= 7) {
                                var youMatch = youRegexMatches[6];
                                srcUrl = '//www.youtube.com/embed/' + youMatch + '?rel=0';
                            }
                            if (vimeoRegexMatches != null && vimeoRegexMatches.length >= 7) {
                                var vimeoMatch = vimeoRegexMatches[6];
                                srcUrl = '//player.vimeo.com/video/' + vimeoMatch;
                            }
                        }
                        $activeFrame.attr('src', srcUrl);

                        if (jQuery('#txtSrc').val() == '') {
                            $activeFrame.attr('src', '');
                        }

                        jQuery('#md-createsrc').data('simplemodal').hide();

                        //$element.data('contenteditor').settings.hasChanged = true;
                        //$element.data('contenteditor').render();
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).data('contenteditor').settings.hasChanged = true;
                            jQuery(instances[i]).data('contenteditor').render();
                        }

                    });
                    /**** /Custom Modal ****/

                });

                jQuery("#divFrameLink").hover(function (e) {
                    jQuery(this).stop(true, true).css("display", "block"); // Spy tdk flickr
                }, function () {
                    jQuery(this).stop(true, true).fadeOut(0);
                });

            }, function (e) {
                jQuery("#divFrameLink").stop(true, true).fadeOut(0);
            });

            $element.find('a').not('.not-a').unbind('hover');
            $element.find('a').not('.not-a').hover(function (e) {

                if( jQuery(this).parents("[data-mode='code']").length > 0 ) return; //Mode: code

                if( jQuery(this).parents("[data-mode='readonly']").length > 0 ) return; //Mode: readonly

                if (jQuery(this).children('img').length == 1 && jQuery(this).children().length == 1) return;

                var zoom = localStorage.zoom;
                if (zoom == 'normal') zoom = 1;
                if (zoom == undefined) zoom = 1;

                //IE fix
                zoom = zoom + ''; //Fix undefined
                if (zoom.indexOf('%') != -1) {
                    zoom = zoom.replace('%', '') / 100;
                }
                if (zoom == 'NaN') {
                    zoom = 1;
                }
                
                zoom = zoom*1;

                var _top; var _left;
                var scrolltop = jQuery(window).scrollTop();
                var offsettop = jQuery(this).offset().top;
                var offsetleft = jQuery(this).offset().left;
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                var is_ie = detectIE();
                var browserok = true;
                if (is_firefox||is_ie) browserok = false;
                if(browserok){
                    //Chrome 37, Opera 24
                    _top = ((offsettop - 23) * zoom) + (scrolltop - scrolltop * zoom);
                    _left = offsetleft * zoom;
                } else {
                    if(is_ie){
                        //IE 11 (Adjustment required)

                        //Custom formula for adjustment in IE11
                        var space = $element.getPos().top;
                        var adjy_val = (-space/1.1)*zoom + space/1.1; 
                        var space2 = $element.getPos().left;
                        var adjx_val = -space2*zoom + space2; 
                        
                        var p = jQuery(this).getPos();
                        _top = ((p.top - 23) * zoom) + adjy_val;
                        _left = (p.left * zoom) + adjx_val;
                    } 
                    if(is_firefox) {
                        //Firefox (No Adjustment required)
                        _top = offsettop - 23;
                        _left = offsetleft;
                    }
                }
                jQuery("#divRteLink").css("top", _top + "px");
                jQuery("#divRteLink").css("left", _left + "px");


                jQuery("#divRteLink").stop(true, true).css({ display: 'none' }).fadeIn(20);

                $activeLink = jQuery(this);

                jQuery("#divRteLink").unbind('click');
                jQuery("#divRteLink").bind('click', function (e) {

                    /**** Custom Modal ****/
                    jQuery('#md-createlink').css('max-width', '550px');
                    jQuery('#md-createlink').simplemodal({
                        onCancel: function () {
                            if ($activeLink.attr('href') == 'http://')
                                $activeLink.replaceWith($activeLink.html());
                        }
                    });
                    jQuery('#md-createlink').data('simplemodal').show();

                    jQuery('#txtLink').val($activeLink.attr('href'));
                    jQuery('#txtLinkText').val($activeLink.html());
                    if($activeLink.attr('target')=='_blank'){
                        jQuery('#chkNewWindow').prop('checked', true);
                    } else {
                        jQuery('#chkNewWindow').removeAttr('checked');
                    }

                    jQuery('#btnLinkOk').unbind('click');
                    jQuery('#btnLinkOk').bind('click', function (e) {

                        $activeLink.attr('href', jQuery('#txtLink').val());

                        if (jQuery('#txtLink').val() == 'http://' || jQuery('#txtLink').val() == '') {
                            $activeLink.replaceWith($activeLink.html());
                        }
                        $activeLink.html(jQuery('#txtLinkText').val());
                        if(jQuery('#chkNewWindow').is(":checked")){
                            $activeLink.attr('target','_blank');
                        } else {
                            $activeLink.removeAttr('target');
                        }

                        jQuery('#md-createlink').data('simplemodal').hide();

                        //$element.data('contenteditor').settings.hasChanged = true;
                        //$element.data('contenteditor').render();
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).data('contenteditor').settings.hasChanged = true;
                            jQuery(instances[i]).data('contenteditor').render();
                        }

                    });
                    /**** /Custom Modal ****/

                });


                jQuery("#divRteLink").hover(function (e) {
                    jQuery(this).stop(true, true).css("display", "block"); // Spy tdk flickr
                }, function () {
                    jQuery(this).stop(true, true).fadeOut(0);
                });

            }, function (e) {
                jQuery("#divRteLink").stop(true, true).fadeOut(0);
            });

            //Custom File Select
            jQuery("#btnLinkBrowse").unbind('click');
            jQuery("#btnLinkBrowse").bind('click', function (e) {

                jQuery('#ifrFileBrowse').attr('src',$element.data('contenteditor').settings.fileselect);

                //Clear Controls
                jQuery("#divToolImg").stop(true, true).fadeOut(0);
                jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                jQuery("#divRteLink").stop(true, true).fadeOut(0);
                jQuery("#divFrameLink").stop(true, true).fadeOut(0);

                jQuery('#active-input').val('txtLink');
           
                /**** Custom Modal ****/
                jQuery('#md-fileselect').css('width', '65%');
                jQuery('#md-fileselect').simplemodal();
                jQuery('#md-fileselect').data('simplemodal').show();
                /**** /Custom Modal ****/

            });

            $element.data('contenteditor').settings.onRender();

        };

        this.prepareRteCommand = function (s) {
            jQuery('#rte-toolbar a[data-rte-cmd="' + s + '"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="' + s + '"]').click(function (e) {
                try {
                    document.execCommand(s, false, null);
                } catch (e) {
                    //FF fix
                    $element.attr('contenteditable', true);
                    document.execCommand(s, false, null);
                    $element.removeAttr('contenteditable');
                    $element.data('contenteditor').render();
                }

                jQuery(this).blur();

                $element.data('contenteditor').settings.hasChanged = true;

                e.preventDefault();
            });
        };


        this.init();
    };

    jQuery.fn.contenteditor = function (options) {

        return this.each(function () {

            instances.push(this);

            if (undefined == jQuery(this).data('contenteditor')) {
                var plugin = new jQuery.contenteditor(this, options);
                jQuery(this).data('contenteditor', plugin);

            }

        });
    };
})(jQuery);

function pasteContent($activeElement) {

    var savedSel = saveSelection();

    jQuery('#idContentWord').remove();
    var tmptop = $activeElement.offset().top;
    jQuery('#divCb').append("<div style='position:absolute;z-index:-1000;top:" + tmptop + "px;left:-1000px;width:1px;height:1px;overflow:auto;' name='idContentWord' id='idContentWord' contenteditable='true'></div>");

    var pasteFrame = document.getElementById("idContentWord");
    pasteFrame.focus();

    setTimeout(function () {
        try {
            restoreSelection(savedSel);
            var $node = jQuery(getSelectionStartNode());

            // Insert pasted text
            if (jQuery('#idContentWord').length == 0) return; //protection

            var sPastedText = '';

            var bRichPaste = false;

            if(jQuery('#idContentWord table').length > 0 ||
                jQuery('#idContentWord img').length > 0 ||
                jQuery('#idContentWord p').length > 0 ||
                jQuery('#idContentWord a').length > 0){
                bRichPaste = true;
            }

            if(bRichPaste){
                       
                //Clean Word
                sPastedText = jQuery('#idContentWord').html();
                sPastedText = cleanHTML(sPastedText);

                jQuery('#idContentWord').html(sPastedText);
                if(jQuery('#idContentWord').children('p,h1,h2,h3,h4,h5,h6,ul,li').length>1){
                    //Fix text that doesn't have paragraph
                    jQuery('#idContentWord').contents().filter(function() {
                        return (this.nodeType == 3 && jQuery.trim(this.nodeValue)!='');
                    }).wrap( "<p></p>" ).end().filter("br").remove();
                }
                sPastedText = '<div class="edit">'+ jQuery('#idContentWord').html() + '</div>';

            } else {
                jQuery('#idContentWord').find('p,h1,h2,h3,h4,h5,h6').each(function(){
                    jQuery(this).html(jQuery(this).html()+' '); //add space (&nbsp;)
                });

                sPastedText = jQuery('#idContentWord').text();
            }
                            
            jQuery('#idContentWord').remove();

            var oSel = window.getSelection();
            var range = oSel.getRangeAt(0);
            range.extractContents();
            range.collapse(true);
            var docFrag = range.createContextualFragment(sPastedText);
            var lastNode = docFrag.lastChild;

            range.insertNode(docFrag);

            range.setStartAfter(lastNode);
            range.setEndAfter(lastNode);
            range.collapse(false);
            var comCon = range.commonAncestorContainer;
            if (comCon && comCon.parentNode) {
                try { comCon.parentNode.normalize(); } catch (e) { };
            }
            oSel.removeAllRanges();
            oSel.addRange(range);

        } catch (e) {

            jQuery('#idContentWord').remove();
        };

    }, 200);

}

// source: http://stackoverflow.com/questions/5605401/insert-link-in-contenteditable-element 
var savedSel;
function saveSelection() {
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            var ranges = [];
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                ranges.push(sel.getRangeAt(i));
            }
            return ranges;
        }
    } else if (document.selection && document.selection.createRange) {
        return document.selection.createRange();
    }
    return null;
};
function restoreSelection(savedSel) {
    if (savedSel) {
        if (window.getSelection) {
            sel = window.getSelection();
            sel.removeAllRanges();
            for (var i = 0, len = savedSel.length; i < len; ++i) {
                sel.addRange(savedSel[i]);
            }
        } else if (document.selection && savedSel.select) {
            savedSel.select();
        }
    }
};
// source: http://stackoverflow.com/questions/2459180/how-to-edit-a-link-within-a-contenteditable-div 
function getSelectionStartNode() {
    var node, selection;
    if (window.getSelection) { // FF3.6, Safari4, Chrome5 (DOM Standards)
        selection = getSelection();
        node = selection.anchorNode;
    }
    if (!node && document.selection) { // IE
        selection = document.selection;
        var range = selection.getRangeAt ? selection.getRangeAt(0) : selection.createRange();
        node = range.commonAncestorContainer ? range.commonAncestorContainer :
			   range.parentElement ? range.parentElement() : range.item(0);
    }
    if (node) {
        return (node.nodeName == "#text" ? node.parentNode : node);
    }
};
//
var getSelectedNode = function () {
    var node, selection;
    if (window.getSelection) {
        selection = getSelection();
        node = selection.anchorNode;
    }
    if (!node && document.selection) {
        selection = document.selection;
        var range = selection.getRangeAt ? selection.getRangeAt(0) : selection.createRange();
        node = range.commonAncestorContainer ? range.commonAncestorContainer :
               range.parentElement ? range.parentElement() : range.item(0);
    }
    if (node) {
        return (node.nodeName == "#text" ? node.parentNode : node);
    }
};

function getSelected() {
    if (window.getSelection) {
        return window.getSelection();
    }
    else if (document.getSelection) {
        return document.getSelection();
    }
    else {
        var selection = document.selection && document.selection.createRange();
        if (selection.text) {
            return selection.text;
        }
        return false;
    }
    return false;
};

/*******************************************************************************************/


(function (jQuery) {

    var tmpCanvas;
    var nInitialWidth;
    var nInitialHeight;
    var $imgActive;

    jQuery.imageembed = function (element, options) {

        var defaults = {
            hiquality: false,
            imageselect: '',
            fileselect: '',
            imageEmbed: true,
            linkDialog: true,
            zoom: 0,
            onChanged: function () { }
        };

        this.settings = {};

        var $element = jQuery(element),
                    element = element;

        this.init = function () {

            this.settings = jQuery.extend({}, defaults, options);

            /**** Localize All ****/
            if (jQuery('#divCb').length == 0) {
                jQuery('body').append('<div id="divCb"></div>');
            }

            var html_photo_file = '';
            var html_photo_file2 = '';
            if (this.settings.imageEmbed) {
                if (navigator.appName.indexOf('Microsoft') != -1) {
                    html_photo_file = '<div id="divToolImg"><div class="fileinputs"><input type="file" name="file" class="my-file" /><div class="fakefile"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAC+klEQVRoQ+2au24aQRSGz+ySkEvPA9AQubNEhXgCSogEShmZGkSQpTS8AjUNSAjXlCRNStpQ8QK8AI6UOLazM5lZvGRvswsz43hYz0iWZe3uzPnOf25rQOVymcAzWsgAZ1xto3DGBQajsFE4Yx4wIZ0xQSM4RmGjcMY8YEI6Y4LKFy0H/9TCJ7b1VsiOo0PaAAv5Wf4ho/CBPjQhneYokRyezWZQKpW4WzuOA71eD5bLZdrx++vahnSz2YRutwu5XC4RZrPZQL1eP33g4XAI1Wo1FeRYlbVQ+FA1U+kfblitVtBut2Nvf3LgQqEAk8kE2G9VC2MM4/EYRqNRZMsnBy4WizCdTiGfz6vidffhqaw98Ha7hU6nA+v1OuCQfr8PLBV46ySB/bAeoL8qJ0GfHLA/D8P9OOmap/jJAXvq1mq12NB1lW404LL/GVqtD5QTPfwwZEJz+DtcXHwEDPf0z3+f+2mbw17oxvZjhIBgGz71LqFSqcQ6xK8wgT+AyZ0L/t+AMflNz3MiNYZXpXkKI2SDhfKw3V67xYwXAdGQJhT6lj77SqgbHP3ywMLMITeB8GIn84C9PJ3P5/s+vYPdGbxYLGAwGABv3k4aPkSIBYAZMg0tfBs4L6kP+yvy7OoKzt6dg3+UTJrQtABmpOHQThs8PGjbeuMrSuDmbdLLhTbAYZXTgJmTEMrBj+sbbs6yPb1KzMIewOJOWiLh7Nog85UH/7vxobO0bb12QYJrV4jCxZA56OuXb26Oq1pSwOGwTgtPz2gLvaRqv9gzOORXpAiyiywN3jdagXtlwaWACbnf9UWBxdRjbWmnLA1l3qK92kYs79UsOeCYaq3GrOAuokNGnC1SwLRWg4NpT37kpREwHUIwzb9HXs8LWKccZsKK/Nv24IBwYdkIGm5jB+8QuVEyh+WA2XDBqjVygfyvheJAaU9KA6cdoNt1A6ybIqrtMQqr9qhu+xmFdVNEtT1GYdUe1W0/o7Buiqi2xyis2qO67WcU1k0R1fb8BZv85KDCNGIQAAAAAElFTkSuQmCC" /></div></div></div>';
                    html_photo_file2 = '';
                } else {
                    html_photo_file = '<div style="display:none"><input type="file" name="file" class="my-file"></div>';
                    html_photo_file2 = '<div id="divToolImg">' +
                            '<i id="lnkEditImage" class="cb-icon-camera"></i>' +
                        '</div>';
                }
            }

            var html_photo_tool = '<div id="divTempContent" style="display:none"></div>' +
                    '<div class="overlay-bg" style="position:fixed;top:0;left:0;width:1;height:1;z-index:10000;zoom 1;background:#fff;opacity:0.8"></div>' +
                    '<div id="divImageEdit" style="position:absolute;display:none;z-index:10000">' +
                        '<div id="my-mask" style="width:200px;height:200px;overflow:hidden;">' +
                            '<img id="my-image" src="" style="max-width:none" />' +
                        '</div>' +
                        '<div id="img-control" style="margin-top:1px;position:absolute;top:-27px;left:0px;width:170px;opacity:0.8">' +
					        '<button id="btnImageCancel" type="button" value="Cancel" ><i class="cb-icon-back"></i></button>' +
                            '<button id="btnZoomOut" type="button" value="-" ><i class="cb-icon-minus"></i></button>' +
                            '<button id="btnZoomIn" type="button" value="+" ><i class="cb-icon-plus"></i></button>' +
                            '<button id="btnChangeImage" type="button" value="Ok" ><i class="cb-icon-ok"></i> Ok</button>' +
                        '</div>' +
                    '</div>' +
                    '<div style="display:none">' +
                        '<canvas id="myCanvas"></canvas>' +
				        '<canvas id="myTmpCanvas"></canvas>' +
                    '</div>' +
                    '<form id="canvasform" method="post" action="" target="canvasframe" enctype="multipart/form-data">' +
                        html_photo_file +
                        '<input id="hidImage" name="hidImage" type="hidden" />' +
                        '<input id="hidPath" name="hidPath" type="hidden" />' +
                        '<input id="hidFile" name="hidFile" type="hidden" />' +
				        '<input id="hidRefId" name="hidRefId" type="hidden" />' +
				        '<input id="hidImgType" name="hidImgType" type="hidden" />' +
                    '</form>' +
                    '<iframe id="canvasframe" name="canvasframe" style="width:1px;height:1px;border:none;visibility:hidden;position:absolute"></iframe>';

            //Custom Image Select
            var bUseCustomImageSelect = false;
            if(this.settings.imageselect!='') bUseCustomImageSelect=true;

            //Custom File Select
            var bUseCustomFileSelect = false;
            if(this.settings.fileselect!='') bUseCustomFileSelect=true;

            var html_hover_icons = html_photo_file2 +
                    '<div id="divToolImgSettings">' +
                        '<i id="lnkImageSettings" class="cb-icon-link"></i>' +
                    '</div>' +
                    '<div id="divToolImgLoader">' +
                        '<i id="lnkImageLoader" class="cb-icon-spin animate-spin"></i>' +
                    '</div>' +
                    '' +
                    '<div class="md-modal" id="md-img">' +
			            '<div class="md-content">' +
				            '<div class="md-body">' +
                                '<div style="background:#fff;border-bottom:#eee;font-family: sans-serif;color: #333;font-size:12px;letter-spacing: 2px;">' +
                                    '<div style="text-align:center;padding:15px;box-sizing:border-box;background:#f3f3f3;border-bottom:#ddd 1px solid;">' +
                                    '<span id="tabImgLnk" style="padding: 3px 20px;border-radius:30px;background:#515151;text-decoration:none;color:#fff;margin-right:15px">IMAGE</span>' +
                                    '<span id="tabImgPl" style="padding: 3px 20px;border-radius:30px;background:#fafafa;text-decoration:underline;color:#333;cursor:pointer">BLANK PLACEHOLDER</span>' +
                                    '</div>' +
                                '</div>' +
                                '<div id="divImgPl" style="overflow-y:auto;overflow-x:hidden;display:none;box-sizing:border-box;padding:10px 10px 10px">';
                                    html_hover_icons += '<div style="padding:12px 20px 20px;width:100%;text-align:center;">';
                                    html_hover_icons += 'DIMENSION (WxH): &nbsp; <select id="selImgW">';
                                    var valW =50; 
                                    for(var i=0;i<231;i++) {
                                        var selected = '';
                                        if(i==90) selected = ' selected="selected"';
                                        html_hover_icons +=  '<option value="' + valW + '"' + selected + '>' + valW + 'px</option>';
                                        valW += 5;
                                    }
                                    html_hover_icons += '</select> &nbsp; ';

                                    html_hover_icons += '<select id="selImgH">';
                                    var valH =50; 
                                    for(var i=0;i<111;i++) {
                                        var selected = '';
                                        if(i==40) selected = ' selected="selected"';
                                        html_hover_icons +=  '<option value="' + valH + '"' + selected + '>' + valH + 'px</option>';
                                        valH += 5;
                                    }
                                    html_hover_icons += '</select> &nbsp; ';

                                    html_hover_icons += '<select id="selImgStyle">';
                                    html_hover_icons +=  '<option value="square">Square</option>';
                                    html_hover_icons +=  '<option value="circle">Circle</option>';
                                    html_hover_icons += '</select>';
                                    html_hover_icons += '<button class="btn btn-default" id="btnInsertPlh" style="margin-left:12px"> REPLACE </button>';
                                    html_hover_icons += '</div>' +
                                '</div>' +
                                '<div id="divImgLnk">' +
                                    '<div class="md-label">Image URL:</div>' +
                                    (bUseCustomImageSelect ? '<input type="text" id="txtImgUrl" class="inptxt" style="float:left;width:50%"></input><i class="cb-icon-link md-btnbrowse" id="btnImageBrowse" style="width:10%;"></i>' : '<input type="text" id="txtImgUrl" class="inptxt" style="float:left;width:60%"></input>') +
                                    '<br style="clear:both">' +
                                    '<div class="md-label">Alternate Text:</div>' +
                                    '<input type="text" id="txtAltText" class="inptxt" style="float:right;width:60%"></input>' +
                                    '<br style="clear:both">' +
                                    '<div class="md-label">Navigate URL:</div>' +
                                    (bUseCustomFileSelect ? '<input type="text" id="txtLinkUrl" class="inptxt" style="float:left;width:50%"></input><i class="cb-icon-link md-btnbrowse" id="btnFileBrowse" style="width:10%;"></i>' : '<input type="text" id="txtLinkUrl" class="inptxt" style="float:left;width:60%"></input>') +
				                '</div>' +
                            '</div>' +
					        '<div id="divImgLnkOk" class="md-footer">' +
                                '<button id="btnImgOk"> Ok </button>' +
                            '</div>' +
			            '</div>' +
		            '</div>' +
                    '' +
                    '<div class="md-modal" id="md-imageselect">' +
			            '<div class="md-content">' +
				            '<div class="md-body">' +
                                (bUseCustomImageSelect ? '<iframe id="ifrImageBrowse" style="width:100%;height:400px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' : '') +
				            '</div>' +
			            '</div>' +
		            '</div>' +
                    '';
                    if (jQuery('#md-fileselect').length==0) {
                        html_hover_icons += '<div class="md-modal" id="md-fileselect">' +
			                '<div class="md-content">' +
				                '<div class="md-body">' +
                                    (bUseCustomFileSelect ? '<iframe id="ifrFileBrowse" style="width:100%;height:400px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' : '') +
				                '</div>' +
			                '</div>' +
		                '</div>'; 
                    }
                    if (jQuery('#active-input').length==0) {
                        html_hover_icons += '<input type="hidden" id="active-input" />';
                    }

            if (jQuery('#divToolImg').length == 0) {
                if (this.settings.imageEmbed) {
                    jQuery('#divCb').append(html_photo_tool);
                }
                jQuery('#divCb').append(html_hover_icons);
            }


            tmpCanvas = document.getElementById('myTmpCanvas');

            $element.hover(function (e) {
                
                var zoom;

                if (localStorage.getItem("zoom") != null) {
                    zoom = localStorage.zoom;
                } else {
                    zoom = $element.parents('[style*="zoom"]').css('zoom');
                    if (zoom == 'normal') zoom = 1;
                    if (zoom == undefined) zoom = 1;
                }

                //FF fix
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                //if (is_firefox) zoom = '1';

                //IE fix
                zoom = zoom + ''; //Fix undefined
                if (zoom.indexOf('%') != -1) {
                    zoom = zoom.replace('%', '') / 100;
                }
                if (zoom == 'NaN') {
                    zoom = 1;
                }

                localStorage.zoom = zoom;

                zoom = zoom*1;

                if(cb_list=='') zoom = 1;//if contentbuilder not used

                /*var adjy = $element.data('imageembed').settings.adjy*1;
                var adjy_val = (-adjy/0.2)*zoom + (adjy/0.2);
                var adjH = -30;
                var adjW = -30;
                var p = jQuery(this).getPos();

                jQuery("#divToolImg").css("top", ((p.top + parseInt(jQuery(this).css('height')) / 2) + adjH) * zoom + adjy_val + "px");
                jQuery("#divToolImg").css("left", ((p.left + parseInt(jQuery(this).css('width')) / 2) + adjW) * zoom + "px");
                jQuery("#divToolImg").stop(true, true).css({ display: 'none' }).fadeIn(20);

                jQuery("#divToolImgSettings").css("top", (((p.top + parseInt(jQuery(this).css('height')) / 2) + adjH) * zoom) + _top_adj + adjy_val + "px");
                jQuery("#divToolImgSettings").css("left", (((p.left + parseInt(jQuery(this).css('width')) / 2) + adjW) * zoom) + "px");
                jQuery("#divToolImgSettings").stop(true, true).css({ display: 'none' }).fadeIn(20);*/

                if($element.data("imageembed").settings.zoom==1){
                    zoom = 1;
                }

                /* Get position for image controls */
                var _top; var _top2; var _left;
                var scrolltop = jQuery(window).scrollTop();
                var offsettop = jQuery(this).offset().top;
                var offsetleft = jQuery(this).offset().left;
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                var is_ie = detectIE();
                var browserok = true;
                if (is_firefox||is_ie) browserok = false;

                var _top_adj = !jQuery(this).data("imageembed").settings.imageEmbed ? 9 : -35;

                if(browserok){
                    //Chrome 37, Opera 24
                    _top = ((offsettop + parseInt(jQuery(this).css('height')) / 2) - 15) * zoom  + (scrolltop - scrolltop * zoom) ;
                    _left = ((offsetleft + parseInt(jQuery(this).css('width')) / 2) - 15) * zoom;
                    _top2 = _top + _top_adj;
                } else {
                    if(is_ie){
                        //IE 11 (Adjustment required)

                        //Custom formula for adjustment in IE11
                        var space = 0; var space2 = 0;
                        $element.parents().each(function () {
                            if (jQuery(this).data('contentbuilder')) {
                                space = jQuery(this).getPos().top;
                                space2 = jQuery(this).getPos().left;
                            }
                        });
                        var adjy_val = -space*zoom + space;
                        var adjx_val = -space2*zoom + space2; 

                        var p = jQuery(this).getPos();
                        _top = ((p.top - 15 + parseInt(jQuery(this).css('height')) / 2)) * zoom + adjy_val;
                        _left = ((p.left - 15 + parseInt(jQuery(this).css('width')) / 2)) * zoom + adjx_val;
                        _top2 = _top + _top_adj;

                    }
                    if(is_firefox) {
                        //Firefox (No Adjustment required)
                        var imgwidth = parseInt(jQuery(this).css('width'));
                        var imgheight = parseInt(jQuery(this).css('height'));
                        
                        _top = offsettop - 15 + imgheight*zoom/2;
                        _left = offsetleft - 15 + imgwidth*zoom/2;
                        _top2 = _top + _top_adj;
                    }
                }

                /* <img data-fixed="1" src=".." /> (image must be fixed, cannot be replaced) */
                var fixedimage = false;
                $imgActive = jQuery(this);
                if($imgActive.attr('data-fixed')==1) {
                    fixedimage = true;
                }

                /* Show Image Controls */
                if(cb_edit && !fixedimage){
                    jQuery("#divToolImg").css("top", _top + "px");
                    jQuery("#divToolImg").css("left", _left + "px");
                    jQuery("#divToolImg").stop(true, true).css({ display: 'none' }).fadeIn(20);

                    if( jQuery(this).data("imageembed").settings.linkDialog ) {
                        jQuery("#divToolImgSettings").css("top", _top2 + "px");
                        jQuery("#divToolImgSettings").css("left", _left + "px");
                        jQuery("#divToolImgSettings").stop(true, true).css({ display: 'none' }).fadeIn(20);
                    } else {
                        jQuery("#divToolImgSettings").css("top", "-10000px"); //hide it
                    }
                }
                
                /* Browse local Image */
                jQuery("#divToolImg").unbind('click');
                jQuery("#divToolImg").bind('click', function (e) {

                    jQuery(this).data('image', $imgActive); //img1: Simpan wkt click browse, krn @imgActive berubah2 tergantung hover

                    jQuery('input.my-file[type=file]').click();

                    e.preventDefault();
                    e.stopImmediatePropagation();
                });
                jQuery("#divToolImg").unbind('hover');
                jQuery("#divToolImg").hover(function (e) {
                    jQuery("#divToolImg").stop(true, true).css("display", "block"); /* Spy tdk flickr */
                    jQuery("#divToolImgSettings").stop(true, true).css("display", "block"); /* Spy tdk flickr */
                }, function () {
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                });
                $element.find('figcaption').unbind('hover');
                $element.find('figcaption').hover(function (e) {
                    jQuery("#divToolImg").stop(true, true).css("display", "block"); /* Spy tdk flickr */
                    jQuery("#divToolImgSettings").stop(true, true).css("display", "block"); /* Spy tdk flickr */
                }, function () {
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                });
                jQuery("#divToolImgSettings").unbind('hover');
                jQuery("#divToolImgSettings").hover(function (e) {
                    jQuery("#divToolImg").stop(true, true).css("display", "block"); /* Spy tdk flickr */
                    jQuery("#divToolImgSettings").stop(true, true).css("display", "block"); /* Spy tdk flickr */
                }, function () {
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                });

                /* Open Image Settings Dialog */
                jQuery("#lnkImageSettings").unbind('click');
                jQuery("#lnkImageSettings").bind('click', function (e) {

                    jQuery(this).data('image', $imgActive); //img1: Simpan wkt click browse, krn @imgActive berubah2 tergantung hover

                    //Clear Controls
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);

                    /**** Custom Modal ****/
                    jQuery('#md-img').css('max-width', '800px');
                    jQuery('#md-img').simplemodal();
                    jQuery('#md-img').data('simplemodal').show();

                    //Check if hovered element is <img> or <figure>
                    var $img = $element;
                    if ($element.prop("tagName").toLowerCase() == 'figure') {
                        $img = $element.find('img:first');
                    }

                    //Get image properties (src, alt & link)
                    jQuery('#txtImgUrl').val($img.attr('src'));
                    jQuery('#txtAltText').val($img.attr('alt'));
                    jQuery('#txtLinkUrl').val('');
                    if ($img.parents('a:first') != undefined) {
                        jQuery('#txtLinkUrl').val($img.parents('a:first').attr('href'));
                    }


                    /*
                    jQuery('#tabImgLnk').css({'text-decoration':'','cursor':'','background':'#515151','color':'#fff'});
                    jQuery('#tabImgPl').css({'text-decoration':'underline','cursor':'pointer','background':'#fafafa','color':'#333'});
                    jQuery('#divImgPl').css('display', 'none');
                    jQuery('#divImgLnk').css('display', 'block');
                    jQuery('#divImgLnkOk').css('display', 'block');
                    */
                  

                    jQuery('#btnImgOk').unbind('click');
                    jQuery('#btnImgOk').bind('click', function (e) {

                        //Get Content Builder plugin
                        var builder;
                        $element.parents().each(function () {
                            if (jQuery(this).data('contentbuilder')) {
                                builder = jQuery(this).data('contentbuilder');
                            }
                        });

                        //Remove wxh from blank placeholder if replaced with other image
                        if( $img.attr('src').indexOf('scripts/image.png') != -1 && jQuery('#txtImgUrl').val().indexOf('scripts/image.png') == -1 ){
                            $img.css('width', '');
                            $img.css('height', '');
                        }

                        //Set image properties
                        $img.attr('src', jQuery('#txtImgUrl').val());
                        $img.attr('alt', jQuery('#txtAltText').val());

                        if (jQuery('#txtLinkUrl').val() == 'http://' || jQuery('#txtLinkUrl').val() == '') {
                            //remove link
                            $img.parents('a:first').replaceWith($img.parents('a:first').html());
                        } else {
                            if ($img.parents('a:first').length == 0) {
                                //create link
                                $img.wrap('<a href="' + jQuery('#txtLinkUrl').val() + '"></a>');
                            } else {
                                //apply link
                                $img.parents('a:first').attr('href', jQuery('#txtLinkUrl').val());
                            }
                        }

                        //Apply Content Builder Behavior
                        if (builder) builder.applyBehavior();

                        jQuery('#md-img').data('simplemodal').hide();

                    });


                    var actualW = $img[0].naturalWidth; //parseInt($img.css('width'));
                    var actualH = $img[0].naturalHeight; //parseInt($img.css('height'));
                    
                    //If it is image placeholder with specified css width/height                 
                    if( $img.attr('src').indexOf('scripts/image.png') != -1 ){
                        for(var i=0;i<$img.attr("style").split(";").length;i++) {
                            var cssval = $img.attr("style").split(";")[i];
                            if(jQuery.trim(cssval.split(":")[0]) == "width") {
                                actualW = parseInt(jQuery.trim(cssval.split(":")[1]));
                            } 
                            if(jQuery.trim(cssval.split(":")[0]) == "height") {
                                actualH = parseInt(jQuery.trim(cssval.split(":")[1]));
                            }
                        }
                    }

                    var valW =50; 
                    for(var i=0;i<231;i++) {                        
                        if(valW>=actualW) {
                            i = 231; //stop
                            jQuery('#selImgW').val(valW);
                            }
                        valW += 5;
                    }
                    var valH =50; 
                    for(var i=0;i<111;i++) {                        
                        if(valH>=actualH) {
                            i = 111; //stop
                            jQuery('#selImgH').val(valH);
                        }
                        valH += 5;
                    }
                    if(parseInt($img.css('border-radius'))==500) {
                        jQuery('#selImgStyle').val('circle');
                        jQuery('#selImgH').css('display','none');
                    } else {
                        jQuery('#selImgStyle').val('square');
                        jQuery('#selImgH').css('display','inline');                        
                    }


                    jQuery('#selImgStyle').unbind('change');
                    jQuery('#selImgStyle').bind('change', function (e) {
                        if(jQuery('#selImgStyle').val()=='circle'){
                            jQuery('#selImgH').css('display','none');
                            jQuery('#selImgH').val(jQuery('#selImgW').val());
                        } else {
                            jQuery('#selImgH').css('display','inline');
                            jQuery('#selImgH').val(jQuery('#selImgW').val());
                        }
                    });
                    jQuery('#selImgW').unbind('change');
                    jQuery('#selImgW').bind('change', function (e) {
                        if(jQuery('#selImgStyle').val()=='circle'){
                            jQuery('#selImgH').val(jQuery('#selImgW').val());
                        }
                    });
                    jQuery('#btnInsertPlh').unbind('click');
                    jQuery('#btnInsertPlh').bind('click', function (e) {
                        //Get Content Builder plugin
                        var builder;
                        $element.parents().each(function () {
                            if (jQuery(this).data('contentbuilder')) {
                                builder = jQuery(this).data('contentbuilder');
                            }
                        });

                        //Set image properties                      
                        $img.attr('src', sScriptPath + 'image.png');
                        $img.attr('alt', jQuery('#txtAltText').val());
                        $img.css('width', jQuery('#selImgW').val() + 'px');
                        $img.css('height', jQuery('#selImgH').val() + 'px');

                        if(jQuery('#selImgStyle').val()=='circle'){
                            $img.css('border-radius','500px');
                        } else {
                            $img.css('border-radius','');
                            $img.removeClass('circle');
                        }

                        //Apply Content Builder Behavior
                        if (builder) builder.applyBehavior();

                        jQuery('#md-img').data('simplemodal').hide();             
                    });
                    /**** /Custom Modal ****/

                    e.preventDefault();
                    e.stopImmediatePropagation();
                });

                //Open Custom Image Select
                jQuery("#btnImageBrowse").unbind('click');
                jQuery("#btnImageBrowse").bind('click', function (e) {

                    jQuery('#ifrImageBrowse').attr('src',$element.data('imageembed').settings.imageselect);

                    //Clear Controls
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                    jQuery("#divRteLink").stop(true, true).fadeOut(0);
                    jQuery("#divFrameLink").stop(true, true).fadeOut(0);

                    jQuery('#active-input').val('txtImgUrl');
       
                    /**** Custom Modal ****/
                    jQuery('#md-imageselect').css('width', '65%');
                    jQuery('#md-imageselect').simplemodal();
                    jQuery('#md-imageselect').data('simplemodal').show();
                    /**** /Custom Modal ****/

                });

                //Open Custom File Select
                jQuery("#btnFileBrowse").unbind('click');
                jQuery("#btnFileBrowse").bind('click', function (e) {

                    jQuery('#ifrFileBrowse').attr('src',$element.data('imageembed').settings.fileselect);

                    //Clear Controls
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                    jQuery("#divRteLink").stop(true, true).fadeOut(0);
                    jQuery("#divFrameLink").stop(true, true).fadeOut(0);

                    jQuery('#active-input').val('txtLinkUrl');

                    /**** Custom Modal ****/
                    jQuery('#md-fileselect').css('width', '65%');
                    jQuery('#md-fileselect').simplemodal();
                    jQuery('#md-fileselect').data('simplemodal').show();
                    /**** /Custom Modal ****/

                });

                /* On Change, call the IMAGE EMBEDDING PROCESS */
                jQuery('.my-file[type=file]').unbind('change');
                jQuery('.my-file[type=file]').bind('change', function (e) {
                    
                    changeImage(e);
 
                    jQuery('#my-image').attr('src', ''); //reset

                    if (!$imgActive.parent().attr('data-gal')) {
                        //alert('no lightbox');
                        jQuery(this).clearInputs(); //=> won't upload the large file (by clearing file input.my-file)
                    }

                });            
                
                /* Image Settings Dialog Tabs */
                jQuery('#tabImgLnk').unbind('click');
                jQuery('#tabImgLnk').bind('click', function (e) {
                    jQuery('#tabImgLnk').css({'text-decoration':'','cursor':'','background':'#515151','color':'#fff'});
                    jQuery('#tabImgPl').css({'text-decoration':'underline','cursor':'pointer','background':'#fafafa','color':'#333'});
                    jQuery('#divImgPl').fadeOut(300, function(){
                        jQuery('#divImgLnk').fadeIn(0);
                        jQuery('#divImgLnkOk').fadeIn(0);
                    });
                });
                jQuery('#tabImgPl').unbind('click');
                jQuery('#tabImgPl').bind('click', function (e) {
                    jQuery('#tabImgLnk').css({'text-decoration':'underline','cursor':'pointer','background':'#fafafa','color':'#333'});
                    jQuery('#tabImgPl').css({'text-decoration':'','cursor':'','background':'#515151','color':'#fff'});
                    jQuery('#divImgLnk').fadeOut(0);
                    jQuery('#divImgLnkOk').fadeOut(0, function(){
                        jQuery('#divImgPl').fadeIn(300);
                    });             
                });

            }, function (e) {
                jQuery("#divToolImg").stop(true, true).fadeOut(0);
                jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
            });

        };


        /* IMAGE EMBEDDING PROCESS */
        var changeImage = function (e) {
            if (typeof FileReader == "undefined") return true;

            var elem = jQuery(this);
            var files = e.target.files;

            var hiquality = false;
            try {
                hiquality = $element.data('imageembed').settings.hiquality;
            } catch (e) { };

            for (var i = 0, file; file = files[i]; i++) {

                var imgname = file.name;
                var extension = imgname.substr((imgname.lastIndexOf('.') + 1)).toLowerCase();
                if (extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif' || extension == 'bmp') {

                } else {
                    alert('Please select an image');
                    return;
                }

                if (file.type.match('image.*')) {

                    //Start Loading Image
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                    jQuery('.overlay-bg').css('width', '100%');
                    jQuery('.overlay-bg').css('height', '100%');
                    jQuery('body').css('overflow', 'hidden');
                    jQuery("#divToolImgLoader").css('top', jQuery('#divToolImg').css('top'));
                    jQuery("#divToolImgLoader").css('left', jQuery('#divToolImg').css('left'));
                    jQuery("#divToolImgLoader").css('display', 'block');

                    var reader = new FileReader();
                    reader.onload = (function (theFile) {
                        return function (e) {

                            //Embedding Image Step 1: Read the image (base64 string)
                            var image = e.target.result; 

                            $imgActive = jQuery("#divToolImg").data('image'); //img2: Selang antara klik browse & select image, hover diabaikan. $imgActive di-set dgn image yg active wkt klik browse.

                            var zoom = localStorage.zoom;
                            
                            if($element.data('imageembed').settings.zoom==1){
                                zoom = 1;
                            }

                            
                            //Embedding Image Step 2: Resize the div image mask according to image placeholder dimension (proportion)
                            //and enlarge it to the actual image placeholder (in case the image placeholder get smaller in mobile screen)
                            //so that embedding image from mobile will still embed actual (larger) dimension to be seen on desktop

                            var enlarge;
                            if ($imgActive.prop("tagName").toLowerCase() == 'img') {
                                enlarge = $imgActive[0].naturalWidth / $imgActive.width(); //2
                            } else if ($imgActive.prop("tagName").toLowerCase() == 'figure') { //new fix
                                enlarge = $imgActive.find('img')[0].naturalWidth / $imgActive.find('img').width(); 
                            }

                            //If it is image placeholder with specified css width/height
                            var specifiedCssWidth=0;
                            var specifiedCssHeight=0; 
                            if ($imgActive.prop("tagName").toLowerCase() == 'img') {

                                if($imgActive.attr("src").indexOf("scripts/image.png")!=-1){
                                    for(var i=0;i<$imgActive.attr("style").split(";").length;i++) {
                                        var cssval = $imgActive.attr("style").split(";")[i];
                                        if(jQuery.trim(cssval.split(":")[0]) == "width") {
                                            specifiedCssWidth = parseInt(jQuery.trim(cssval.split(":")[1]));

                                            enlarge = specifiedCssWidth / $imgActive.width();
                                        } 
                                        if(jQuery.trim(cssval.split(":")[0]) == "height") {
                                            specifiedCssHeight = parseInt(jQuery.trim(cssval.split(":")[1]));
                                        } 
                                    }
                                }

                            } else if ($imgActive.prop("tagName").toLowerCase() == 'figure') { //new fix
         
                                if($imgActive.find('img').attr("src").indexOf("scripts/image.png")!=-1){
                                    for(var i=0;i<$imgActive.find('img').attr("style").split(";").length;i++) {
                                        var cssval = $imgActive.find('img').attr("style").split(";")[i];
                                        if(jQuery.trim(cssval.split(":")[0]) == "width") {
                                            specifiedCssWidth = parseInt(jQuery.trim(cssval.split(":")[1]));
                                            enlarge = specifiedCssWidth / $imgActive.find('img').width();
                                        } 
                                        if(jQuery.trim(cssval.split(":")[0]) == "height") {
                                            specifiedCssHeight = parseInt(jQuery.trim(cssval.split(":")[1]));
                                        } 
                                    }
                                }

                            } 

                            if ($imgActive.prop("tagName").toLowerCase() == 'img') {
                                jQuery("#my-mask").css('width', $imgActive.width() * enlarge + 'px'); //multiply width & height with enlarge value
                                jQuery("#my-mask").css('height', $imgActive.height() * enlarge + 'px');
                            } else {
                                jQuery("#my-mask").css('width', $imgActive.innerWidth() * enlarge + 'px');
                                jQuery("#my-mask").css('height', $imgActive.innerHeight() * enlarge + 'px');
                            }

                            //If it is image placeholder with specified css width/height
                            if(specifiedCssWidth!=0) jQuery("#my-mask").css('width', specifiedCssWidth + 'px');
                            if(specifiedCssHeight!=0) jQuery("#my-mask").css('height', specifiedCssHeight + 'px');

                            jQuery("#my-mask").css('zoom', zoom / enlarge); //divide zoom with enlarge value
                            jQuery("#my-mask").css('-moz-transform', 'scale(' + zoom / enlarge + ')');

                            var oimg = new Image();
                            oimg.onload = function (evt) {

                                $imgActive = jQuery("#divToolImg").data('image'); //img2: Selang antara klik browse & select image, hover diabaikan. $imgActive di-set dgn image yg active wkt klik browse.

                                //Embedding Image Step 3: Get dimension (programmatically) for chosen image to fit with its image placeholder
                                nInitialWidth = this.width; //chosen image width
                                nInitialHeight = this.height; //chosen image height

                                var newW;
                                var newY;

                                /* source: http://stackoverflow.com/questions/3987644/resize-and-center-image-with-jquery */
                                var maskWidth = $imgActive.width(); //image placeholder width
                                var maskHeight = $imgActive.height(); //image placeholder height

                                var photoAspectRatio = nInitialWidth / nInitialHeight;
                                var canvasAspectRatio = maskWidth / maskHeight;
                                if (photoAspectRatio < canvasAspectRatio) {
                                    newW = maskWidth;
                                    newY = (nInitialHeight * maskWidth) / nInitialWidth;
                                }
                                else {
                                    newW = (nInitialWidth * maskHeight) / nInitialHeight;
                                    newY = maskHeight;
                                }

                                //Embedding Image Step 4: Apply the dimension and enlarge it according to the enlarge value
                                //so that embedding image from mobile will still embed actual (larger) dimension to be seen on desktop
                                newW = newW * enlarge; //multiply width & height with 2
                                newY = newY * enlarge;

                                this.width = newW;
                                this.height = newY;

                                //Embedding Image Step 5: Load chosen image in an IMG element ('<div id="my-mask"><img id="my-image"></div>) 
                                //and set with the new dimension. Remember we have made the container (<div id="my-mask">) 2 times bigger.
                                jQuery('#my-image').attr('src', image);
                                jQuery('#my-image').on('load', function () {

                                    jQuery('.overlay-bg').css('width', '100%');
                                    jQuery('.overlay-bg').css('height', '100%');
                                    jQuery('body').css('overflow', 'hidden');

                                    $imgActive = jQuery("#divToolImg").data('image'); //img2: Selang antara klik browse & select image, hover diabaikan. $imgActive di-set dgn image yg active wkt klik browse.

                                    jQuery("#my-image").css('top', '0px');
                                    jQuery("#my-image").css('left', '0px');

                                    jQuery("#my-image").css('width', newW + 'px'); //Set with the new dimension
                                    jQuery("#my-image").css('height', newY + 'px');

                                    var zoom = localStorage.zoom;

                                    zoom = zoom*1;

                                    if($element.data('imageembed').settings.zoom==1){
                                        zoom = 1;
                                    }

                                    //Embedding Image Step 6: Show image control (zoom, etc) with correct position 
                                    /*var adjy = $element.data('imageembed').settings.adjy*1;
                                    var adjy_val = (-adjy/0.183)*zoom + (adjy/0.183);

                                    var p = $imgActive.getPos();
                                    jQuery('#divImageEdit').css('display', 'inline-block');
                                    if ($imgActive.attr('class') == 'img-polaroid') {
                                        jQuery("#divImageEdit").css("top", (p.top + 5) * zoom + adjy_val + "px");
                                        jQuery("#divImageEdit").css("left", (p.left + 5) * zoom + "px");
                                    } else {
                                        jQuery("#divImageEdit").css("top", (p.top) * zoom + adjy_val + "px");
                                        jQuery("#divImageEdit").css("left", (p.left) * zoom + "px");
                                    }*/
                                    var _top; var _left; var _top_polaroid; var _left_polaroid;
                                    var scrolltop = jQuery(window).scrollTop();
                                    var offsettop = $imgActive.offset().top;
                                    var offsetleft = $imgActive.offset().left;
                                    var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                                    var is_ie = detectIE();
                                    var browserok = true;
                                    if (is_firefox||is_ie) browserok = false;
                                    if(browserok){
                                        //Chrome 37, Opera 24
                                        _top = (offsettop * zoom) + (scrolltop - scrolltop * zoom);
                                        _left = offsetleft * zoom;
                                        _top_polaroid = ((offsettop + 5) * zoom) + (scrolltop - scrolltop * zoom);
                                        _left_polaroid = (offsetleft + 5) * zoom;
                                    } else {
                                        if(is_ie){
                                            //IE 11 (Adjustment required)

                                            //Custom formula for adjustment in IE11
                                            var space = 0;var space2 = 0;
                                            $element.parents().each(function () {
                                                if (jQuery(this).data('contentbuilder')) {
                                                    space = jQuery(this).getPos().top;
                                                    space2 = jQuery(this).getPos().left;
                                                }
                                            });
                                            var adjy_val = -space*zoom + space; 
                                            var adjx_val = -space2*zoom + space2; 

                                            var p = $imgActive.getPos();
                                            _top = (p.top * zoom) + adjy_val;
                                            _left = (p.left * zoom) + adjx_val;
                                            _top_polaroid = ((p.top + 5) * zoom) + adjy_val;
                                            _left_polaroid = ((p.left + 5) * zoom) + adjx_val;
                                        } 
                                        if(is_firefox) {
                                            //Firefox (No Adjustment required)
                                            /*
                                            In Firefox, if my-mask is zoomed, it will be centered within it's container divImageEdit.
                                            Only because of this, an adjustment is needed for divImageEdit & img-control
                                            */
                                            var imgwidth = parseInt($imgActive.css('width'));
                                            var imgheight = parseInt($imgActive.css('height'));
                                            var adjx_val = imgwidth/2 - (imgwidth/2)*zoom;
                                            var adjy_val = imgheight/2 - (imgheight/2)*zoom;

                                            jQuery('#img-control').css('top',5+adjy_val + 'px');
                                            jQuery('#img-control').css('left',7+adjx_val + 'px');

                                            _top = offsettop-adjy_val;
                                            _left = offsetleft-adjx_val;
                                            _top_polaroid = offsettop-adjy_val + 5;
                                            _left_polaroid = offsetleft-adjx_val + 5;
                                        }
                                    }
                                    jQuery('#divImageEdit').css('display', 'inline-block');
                                    if ($imgActive.attr('class') == 'img-polaroid') {
                                        jQuery("#divImageEdit").css("top", _top_polaroid + "px");
                                        jQuery("#divImageEdit").css("left", _left_polaroid + "px");
                                    } else {
                                        jQuery("#divImageEdit").css("top", _top + "px");
                                        jQuery("#divImageEdit").css("left", _left + "px");
                                    }

                                    if(parseInt(jQuery("#divImageEdit").css("top"))<25) {
                                        jQuery('#img-control').css('top','auto');
                                        jQuery('#img-control').css('bottom', "-24px");
                                    }

                                    //Embedding Image Step 7: Enable "DRAG TO PAN" image within its mask ('<div id="my-mask"><img id="my-image"></div>) 
                                    //Remember that the image can be bigger (in proportion) than the mask (which has the same dimension with image placeholder)
                                    panSetup();

                                    //Embedding Image Step 8: The resulting "DRAG TO PAN" will be transfered to a temporary canvas (<canvas id="myTmpCanvas">)
                                    tmpCanvas.width = newW;
                                    tmpCanvas.height = newY;
                                    var imageObj = jQuery("#my-image")[0];
                                    var context = tmpCanvas.getContext('2d');

                                    var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                                    if (is_firefox) sleep(700);//fix bug on Firefox
                              
                                    //fix bug on iOs
                                    if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
                                        try {
                                            var mpImg = new MegaPixImage(imageObj);
                                            mpImg.render(tmpCanvas, { width: imageObj.width, height: imageObj.height });
                                        } catch(e) {
                                            context.drawImage(imageObj, 0, 0, newW, newY)
                                        }
                                    } else {
                                        context.drawImage(imageObj, 0, 0, newW, newY);
                                    }

                                    //Embedding Image Step 9: Do the cropping (image cropped based on placeholder dimension)
                                    //and move from "myTmpCanvas" to "myCanvas" (<canvas id="myCanvas"><canvas id="myTmpCanvas">)
                                    crop();
                                    if ($imgActive.attr('class') == 'img-circle') {
                                        jQuery('#my-mask').css('-webkit-border-radius', '500px');
                                        jQuery('#my-mask').css('-moz-border-radius', '500px');
                                        jQuery('#my-mask').css('border-radius', '500px');
                                    } else {
                                        jQuery('#my-mask').css('-webkit-border-radius', '0px');
                                        jQuery('#my-mask').css('-moz-border-radius', '0px');
                                        jQuery('#my-mask').css('border-radius', '0px');
                                    }

                                    jQuery('#my-image').unbind('load'); //spy tdk load berulang2

                                    if ($imgActive.prop("tagName").toLowerCase() == 'img') {

                                    } else {
                                        jQuery('#btnZoomIn').click(); jQuery('#btnZoomIn').click(); //fix bug
                                    }

                                    //Finished Loading Image
                                    jQuery("#divToolImgLoader").css('display', 'none');

                                });

                                //Embedding Image Step 10 (finish): When user click "Ok", read the result (base64 string) from "myCanvas" 
                                //and assign it to image placeholder ($imgActive)
                                jQuery('#btnChangeImage').unbind('click');
                                jQuery('#btnChangeImage').bind('click', function () {
                                    var canvas = document.getElementById('myCanvas');

                                    $imgActive = jQuery("#divToolImg").data('image'); //img2: Selang antara klik browse & select image, hover diabaikan. $imgActive di-set dgn image yg active wkt klik browse.

                                    //Embed Image
                                    var image;
                                    if (hiquality == false) {
                                        if (extension == 'jpg' || extension == 'jpeg') {
                                            image = canvas.toDataURL("image/jpeg", 0.9);
                                        } else {
                                            image = canvas.toDataURL("image/png", 1);
                                        }
                                    } else {
                                        image = canvas.toDataURL("image/png", 1);
                                    }

                                    if ($imgActive.prop("tagName").toLowerCase() == 'img') {
                                        $imgActive.attr('src', image);
                                        $imgActive.data('filename', imgname); //Set data attribute for filename
                                    } else if ($imgActive.prop("tagName").toLowerCase() == 'figure') {
                                        $imgActive.find('img').attr('src', image);
                                        $imgActive.find('img').data('filename', imgname); //Set data attribute for filename
                                    } else {
                                        $imgActive.css('background-image', 'url(data:' + image + ')');
                                        $imgActive.data('filename', imgname); //Set data attribute for filename
                                    }

                                    jQuery('#divImageEdit').css('display', 'none');
                                    jQuery('.overlay-bg').css('width', '1px');
                                    jQuery('.overlay-bg').css('height', '1px');
                                    jQuery('body').css('overflow', '');

                                    if ($imgActive.prop("tagName").toLowerCase() == 'img') {
                                        $imgActive.css('width', '');
                                        $imgActive.css('height', '');
                                    } else if ($imgActive.prop("tagName").toLowerCase() == 'figure') {
                                        $imgActive.find('img').css('width', '');
                                        $imgActive.find('img').css('height', '');
                                    }

                                    $element.data('imageembed').settings.onChanged(); 

                                });
                                jQuery('#btnImageCancel').unbind('click');
                                jQuery('#btnImageCancel').bind('click', function () {
                                    var canvas = document.getElementById('myCanvas');

                                    $imgActive = jQuery("#divToolImg").data('image'); //img2: Selang antara klik browse & select image, hover diabaikan. $imgActive di-set dgn image yg active wkt klik browse.

                                    jQuery('#divImageEdit').css('display', 'none');
                                    jQuery('.overlay-bg').css('width', '1px');
                                    jQuery('.overlay-bg').css('height', '1px');
                                    jQuery('body').css('overflow', '');


                                });


                                jQuery('#btnZoomIn').unbind('click');
                                jQuery('#btnZoomIn').bind('click', function () {

                                    var nCurrentWidth = parseInt(jQuery("#my-image").css('width'));
                                    var nCurrentHeight = parseInt(jQuery("#my-image").css('height'));

                                    //if (nInitialWidth <= (nCurrentWidth / 0.9)) return;
                                    //if (nInitialHeight <= (nCurrentHeight / 0.9)) return;

                                    jQuery("#my-image").css('width', (nCurrentWidth / 0.9) + 'px');
                                    jQuery("#my-image").css('height', (nCurrentHeight / 0.9) + 'px');

                                    panSetup();

                                    tmpCanvas.width = (nCurrentWidth / 0.9);
                                    tmpCanvas.height = (nCurrentHeight / 0.9);

                                    var imageObj = jQuery("#my-image")[0];
                                    var context = tmpCanvas.getContext('2d');
                                    context.drawImage(imageObj, 0, 0, (nCurrentWidth / 0.9), (nCurrentHeight / 0.9));

                                    crop();

                                });

                                jQuery('#btnZoomOut').unbind('click');
                                jQuery('#btnZoomOut').bind('click', function () {

                                    var nCurrentWidth = parseInt(jQuery("#my-image").css('width'));
                                    var nCurrentHeight = parseInt(jQuery("#my-image").css('height'));

                                    if ( (nCurrentWidth / 1.1) < jQuery("#my-mask").width()) return;
                                    if ( (nCurrentHeight / 1.1) < jQuery("#my-mask").height()) return;

                                    //if ((nCurrentWidth / 1.1) >= parseInt(jQuery("#my-mask").css('width')) && (nCurrentHeight / 1.1) >= parseInt(jQuery("#my-mask").css('height'))) {
                                    jQuery("#my-image").css('width', (nCurrentWidth / 1.1) + 'px');
                                    jQuery("#my-image").css('height', (nCurrentHeight / 1.1) + 'px');

                                    panSetup();

                                    tmpCanvas.width = (nCurrentWidth / 1.1);
                                    tmpCanvas.height = (nCurrentHeight / 1.1);

                                    var imageObj = jQuery("#my-image")[0];
                                    var context = tmpCanvas.getContext('2d');

                                    context.drawImage(imageObj, 0, 0, (nCurrentWidth / 1.1), (nCurrentHeight / 1.1));

                                    crop();

                                    //}
                                });

                            };
                            oimg.src = image;

                        };
                    })(file);
                    reader.readAsDataURL(file);
                }
            }

        };

        var crop = function () {
            //Crop & move from "myTmpCanvas" to "myCanvas" (<canvas id="myCanvas"><canvas id="myTmpCanvas">)
            var x = parseInt(jQuery("#my-image").css('left'));
            var y = parseInt(jQuery("#my-image").css('top'));

            var dw = parseInt(jQuery("#my-mask").css('width'));
            var dh = parseInt(jQuery("#my-mask").css('height'));

            var canvas = document.getElementById('myCanvas');
            var context = canvas.getContext('2d');
            canvas.width = dw;
            canvas.height = dh;

            var imageObj = jQuery("#my-image")[0];
            var sourceX = -1 * x;
            var sourceY = -1 * y;

            if (sourceY > (tmpCanvas.height - dh)) sourceY = tmpCanvas.height - dh;
            if (sourceX > (tmpCanvas.width - dw)) sourceX = tmpCanvas.width - dw;

            context.drawImage(tmpCanvas, sourceX, sourceY, dw, dh, 0, 0, dw, dh);
        };

        /* source: http://stackoverflow.com/questions/1590840/drag-a-zoomed-image-within-a-div-clipping-mask-using-jquery-draggable */
        var panSetup = function () {

            jQuery("#my-image").css({ top: 0, left: 0 });

            var maskWidth = jQuery("#my-mask").width();
            var maskHeight = jQuery("#my-mask").height();
            var imgPos = jQuery("#my-image").offset();
            var imgWidth = jQuery("#my-image").width();
            var imgHeight = jQuery("#my-image").height();

            var x1 = (imgPos.left + maskWidth) - imgWidth;
            var y1 = (imgPos.top + maskHeight) - imgHeight;
            var x2 = imgPos.left;
            var y2 = imgPos.top;

            jQuery("#my-image").draggable({
                revert: false, containment: [x1, y1, x2, y2], drag: function () {

                    crop();
                }
            });
            jQuery("#my-image").css({ cursor: 'move' });
        };

        this.init();

    };

    jQuery.fn.imageembed = function (options) {
        return this.each(function () {

            if (undefined == jQuery(this).data('imageembed')) {
                var plugin = new jQuery.imageembed(this, options);
                jQuery(this).data('imageembed', plugin);

            }
        });
    };
})(jQuery);


/* Utils */
function makeid() {//http://stackoverflow.com/questions/1349404/generate-a-string-of-5-random-characters-in-javascript
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < 5; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}
function sleep(milliseconds) {//http://www.phpied.com/sleep-in-javascript/
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}


/*******************************************************************************************/


/* 
source:
http://stackoverflow.com/questions/1043957/clearing-input-type-file-using-jquery
https://github.com/malsup/form/blob/master/jquery.form.js
*/
jQuery.fn.clearFields = jQuery.fn.clearInputs = function (includeHidden) {
    var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; // 'hidden' is not in this list
    return this.each(function () {
        var t = this.type, tag = this.tagName.toLowerCase();
        if (re.test(t) || tag == 'textarea') {
            this.value = '';
        }
        else if (t == 'checkbox' || t == 'radio') {
            this.checked = false;
        }
        else if (tag == 'select') {
            this.selectedIndex = -1;
        }
        else if (t == "file") {
            if (/MSIE/.test(navigator.userAgent)) {
                jQuery(this).replaceWith(jQuery(this).clone(true));
            } else {
                jQuery(this).val('');
            }
        }
        else if (includeHidden) {
            // includeHidden can be the value true, or it can be a selector string
            // indicating a special test; for example:
            //  jQuery('#myForm').clearForm('.special:hidden')
            // the above would clean hidden inputs that have the class of 'special'
            if ((includeHidden === true && /hidden/.test(t)) ||
                (typeof includeHidden == 'string' && jQuery(this).is(includeHidden)))
                this.value = '';
        }
    });
};



/* Simple Modal - Inspired by modalEffects.js from http://www.codrops.com , http://tympanus.net/codrops/2013/06/25/nifty-modal-window-effects/ */
var zindex = 10000;
(function (jQuery) {

    jQuery.simplemodal = function (element, options) {

        var defaults = {
            onCancel: function () { }
        };

        this.settings = {};

        var $element = jQuery(element),
             element = element;

        var $ovlid;

        this.init = function () {

            this.settings = jQuery.extend({}, defaults, options);

            //var html_overlay = '<div class="md-overlay"></div>';
            //if (jQuery('.md-overlay').length == 0) jQuery('body').append(html_overlay);

            /**** Localize All ****/
            if (jQuery('#divCb').length == 0) {
                jQuery('body').append('<div id="divCb"></div>');
            }

        };

        this.hide = function () {
            $element.css('display', 'none');
            $element.removeClass('md-show');
            $ovlid.remove();//
			
			zindex = zindex-2;
        };

        this.show = function () {
			
			zindex = zindex+1;
			
            var rnd = makeid();
            var html_overlay = '<div id="md-overlay-' + rnd + '" class="md-overlay" style="z-index:' + zindex + '"></div>';
            jQuery('#divCb').append(html_overlay);
            $ovlid = jQuery('#md-overlay-' + rnd);

            /*setTimeout(function () {
                $element.addClass('md-show');
            }, 1);*/
			
			zindex = zindex+1;
			$element.css('z-index',zindex);
			
            $element.addClass('md-show');
            $element.stop(true, true).css('display', 'none').fadeIn(200);

            jQuery('#md-overlay-' + rnd).unbind();
            jQuery('#md-overlay-' + rnd).click(function () {

                $element.stop(true, true).fadeOut(100, function(){
                    $element.removeClass('md-show');
                });
                $ovlid.remove();//

				zindex = zindex-2;
				
                $element.data('simplemodal').settings.onCancel();
            });

        };

        this.init();
    };

    jQuery.fn.simplemodal = function (options) {

        return this.each(function () {

            if (undefined == jQuery(this).data('simplemodal')) {
                var plugin = new jQuery.simplemodal(this, options);
                jQuery(this).data('simplemodal', plugin);

            }

        });
    };
})(jQuery);



/* source: http://stackoverflow.com/questions/1002934/jquery-x-y-document-coordinates-of-dom-object */
jQuery.fn.getPos = function () {
    var o = this[0];
    var left = 0, top = 0, parentNode = null, offsetParent = null;
    offsetParent = o.offsetParent;
    var original = o;
    var el = o;
    while (el.parentNode != null) {
        el = el.parentNode;
        if (el.offsetParent != null) {
            var considerScroll = true;
            if (window.opera) {
                if (el == original.parentNode || el.nodeName == "TR") {
                    considerScroll = false;
                }
            }
            if (considerScroll) {
                if (el.scrollTop && el.scrollTop > 0) {
                    top -= el.scrollTop;
                }
                if (el.scrollLeft && el.scrollLeft > 0) {
                    left -= el.scrollLeft;
                }
            }
        }
        if (el == offsetParent) {
            left += o.offsetLeft;
            if (el.clientLeft && el.nodeName != "TABLE") {
                left += el.clientLeft;
            }
            top += o.offsetTop;
            if (el.clientTop && el.nodeName != "TABLE") {
                top += el.clientTop;
            }
            o = el;
            if (o.offsetParent == null) {
                if (o.offsetLeft) {
                    left += o.offsetLeft;
                }
                if (o.offsetTop) {
                    top += o.offsetTop;
                }
            }
            offsetParent = o.offsetParent;
        }
    }
    return {
        left: left,
        top: top
    };
};

//Clean Word. Source: http://patisserie.keensoftware.com/en/pages/remove-word-formatting-from-rich-text-editor-with-javascript
//http://community.sitepoint.com/t/strip-unwanted-formatting-from-pasted-content/16848/3
//Other: http://www.1stclassmedia.co.uk/developers/clean-ms-word-formatting.php
function cleanHTML(input) {
    var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
    var output = input.replace(stringStripper, ' ');

    var commentSripper = new RegExp('<!--(.*?)-->', 'g');
    var output = output.replace(commentSripper, '');
    var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>', 'gi');

    output = output.replace(tagStripper, '');

    var badTags = ['style', 'script', 'applet', 'embed', 'noframes', 'noscript'];

    for (var i = 0; i < badTags.length; i++) {
        tagStripper = new RegExp('<' + badTags[i] + '.*?' + badTags[i] + '(.*?)>', 'gi');
        output = output.replace(tagStripper, '');
    }

    var badAttributes = ['style', 'start'];
    for (var i = 0; i < badAttributes.length; i++) {
        var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"', 'gi');
        output = output.replace(attributeStripper, '');
    }
    return output;
}

function detectIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');
    var trident = ua.indexOf('Trident/');

    if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }

    if (trident > 0) {
        // IE 11 (or newer) => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }

    // other browser
    return false;
}

/*! rangeslider.js - v1.2.1 | (c) 2015 @andreruffert | MIT license | https://github.com/andreruffert/rangeslider.js */
!function (a) { "use strict"; "function" == typeof define && define.amd ? define(["jquery"], a) : a("object" == typeof exports ? require("jquery") : jQuery) } (function (a) { "use strict"; function b() { var a = document.createElement("input"); return a.setAttribute("type", "range"), "text" !== a.type } function c(a, b) { var c = Array.prototype.slice.call(arguments, 2); return setTimeout(function () { return a.apply(null, c) }, b) } function d(a, b) { return b = b || 100, function () { if (!a.debouncing) { var c = Array.prototype.slice.apply(arguments); a.lastReturnVal = a.apply(window, c), a.debouncing = !0 } return clearTimeout(a.debounceTimeout), a.debounceTimeout = setTimeout(function () { a.debouncing = !1 }, b), a.lastReturnVal } } function e(a) { return a && (0 === a.offsetWidth || 0 === a.offsetHeight || a.open === !1) } function f(a) { for (var b = [], c = a.parentNode; e(c); ) b.push(c), c = c.parentNode; return b } function g(a, b) { function c(a) { "undefined" != typeof a.open && (a.open = a.open ? !1 : !0) } var d = f(a), e = d.length, g = [], h = a[b]; if (e) { for (var i = 0; e > i; i++) g[i] = d[i].style.cssText, d[i].style.display = "block", d[i].style.height = "0", d[i].style.overflow = "hidden", d[i].style.visibility = "hidden", c(d[i]); h = a[b]; for (var j = 0; e > j; j++) d[j].style.cssText = g[j], c(d[j]) } return h } function h(b, e) { if (this.$window = a(window), this.$document = a(document), this.$element = a(b), this.options = a.extend({}, l, e), this.polyfill = this.options.polyfill, this.onInit = this.options.onInit, this.onSlide = this.options.onSlide, this.onSlideEnd = this.options.onSlideEnd, this.polyfill && k) return !1; this.identifier = "js-" + i + "-" + j++, this.startEvent = this.options.startEvent.join("." + this.identifier + " ") + "." + this.identifier, this.moveEvent = this.options.moveEvent.join("." + this.identifier + " ") + "." + this.identifier, this.endEvent = this.options.endEvent.join("." + this.identifier + " ") + "." + this.identifier, this.toFixed = (this.step + "").replace(".", "").length - 1, this.$fill = a('<div class="' + this.options.fillClass + '" />'), this.$handle = a('<div class="' + this.options.handleClass + '" />'), this.$range = a('<div class="' + this.options.rangeClass + '" id="' + this.identifier + '" />').insertAfter(this.$element).prepend(this.$fill, this.$handle), this.$element.css({ position: "absolute", width: "1px", height: "1px", overflow: "hidden", opacity: "0" }), this.handleDown = a.proxy(this.handleDown, this), this.handleMove = a.proxy(this.handleMove, this), this.handleEnd = a.proxy(this.handleEnd, this), this.init(); var f = this; this.$window.on("resize." + this.identifier, d(function () { c(function () { f.update() }, 300) }, 20)), this.$document.on(this.startEvent, "#" + this.identifier + ":not(." + this.options.disabledClass + ")", this.handleDown), this.$element.on("change." + this.identifier, function (a, b) { if (!b || b.origin !== f.identifier) { var c = a.target.value, d = f.getPositionFromValue(c); f.setPosition(d) } }) } var i = "rangeslider", j = 0, k = b(), l = { polyfill: !0, rangeClass: "rangeslider", disabledClass: "rangeslider--disabled", fillClass: "rangeslider__fill", handleClass: "rangeslider__handle", startEvent: ["mousedown", "touchstart", "pointerdown"], moveEvent: ["mousemove", "touchmove", "pointermove"], endEvent: ["mouseup", "touchend", "pointerup"] }; h.prototype.init = function () { this.update(!0), this.$element[0].value = this.value, this.onInit && "function" == typeof this.onInit && this.onInit() }, h.prototype.update = function (a) { a = a || !1, a && (this.min = parseFloat(this.$element[0].getAttribute("min") || 0), this.max = parseFloat(this.$element[0].getAttribute("max") || 100), this.value = parseFloat(this.$element[0].value || this.min + (this.max - this.min) / 2), this.step = parseFloat(this.$element[0].getAttribute("step") || 1)), this.handleWidth = g(this.$handle[0], "offsetWidth"), this.rangeWidth = g(this.$range[0], "offsetWidth"), this.maxHandleX = this.rangeWidth - this.handleWidth, this.grabX = this.handleWidth / 2, this.position = this.getPositionFromValue(this.value), this.$element[0].disabled ? this.$range.addClass(this.options.disabledClass) : this.$range.removeClass(this.options.disabledClass), this.setPosition(this.position) }, h.prototype.handleDown = function (a) { if (a.preventDefault(), this.$document.on(this.moveEvent, this.handleMove), this.$document.on(this.endEvent, this.handleEnd), !((" " + a.target.className + " ").replace(/[\n\t]/g, " ").indexOf(this.options.handleClass) > -1)) { var b = this.getRelativePosition(a), c = this.$range[0].getBoundingClientRect().left, d = this.getPositionFromNode(this.$handle[0]) - c; this.setPosition(b - this.grabX), b >= d && b < d + this.handleWidth && (this.grabX = b - d) } }, h.prototype.handleMove = function (a) { a.preventDefault(); var b = this.getRelativePosition(a); this.setPosition(b - this.grabX) }, h.prototype.handleEnd = function (a) { a.preventDefault(), this.$document.off(this.moveEvent, this.handleMove), this.$document.off(this.endEvent, this.handleEnd), this.$element.trigger("change", { origin: this.identifier }), this.onSlideEnd && "function" == typeof this.onSlideEnd && this.onSlideEnd(this.position, this.value) }, h.prototype.cap = function (a, b, c) { return b > a ? b : a > c ? c : a }, h.prototype.setPosition = function (a) { var b, c; b = this.getValueFromPosition(this.cap(a, 0, this.maxHandleX)), c = this.getPositionFromValue(b), this.$fill[0].style.width = c + this.grabX + "px", this.$handle[0].style.left = c + "px", this.setValue(b), this.position = c, this.value = b, this.onSlide && "function" == typeof this.onSlide && this.onSlide(c, b) }, h.prototype.getPositionFromNode = function (a) { for (var b = 0; null !== a; ) b += a.offsetLeft, a = a.offsetParent; return b }, h.prototype.getRelativePosition = function (a) { var b = this.$range[0].getBoundingClientRect().left, c = 0; return "undefined" != typeof a.pageX ? c = a.pageX : "undefined" != typeof a.originalEvent.clientX ? c = a.originalEvent.clientX : a.originalEvent.touches && a.originalEvent.touches[0] && "undefined" != typeof a.originalEvent.touches[0].clientX ? c = a.originalEvent.touches[0].clientX : a.currentPoint && "undefined" != typeof a.currentPoint.x && (c = a.currentPoint.x), c - b }, h.prototype.getPositionFromValue = function (a) { var b, c; return b = (a - this.min) / (this.max - this.min), c = b * this.maxHandleX }, h.prototype.getValueFromPosition = function (a) { var b, c; return b = a / (this.maxHandleX || 1), c = this.step * Math.round(b * (this.max - this.min) / this.step) + this.min, Number(c.toFixed(this.toFixed)) }, h.prototype.setValue = function (a) { a !== this.value && this.$element.val(a).trigger("input", { origin: this.identifier }) }, h.prototype.destroy = function () { this.$document.off("." + this.identifier), this.$window.off("." + this.identifier), this.$element.off("." + this.identifier).removeAttr("style").removeData("plugin_" + i), this.$range && this.$range.length && this.$range[0].parentNode.removeChild(this.$range[0]) }, a.fn[i] = function (b) { var c = Array.prototype.slice.call(arguments, 1); return this.each(function () { var d = a(this), e = d.data("plugin_" + i); e || d.data("plugin_" + i, e = new h(this, b)), "string" == typeof b && e[b].apply(e, c) }) } });

/*! jQuery UI Touch Punch 0.2.3 | Copyright 2011–2014, Dave Furfero | Dual licensed under the MIT or GPL Version 2 licenses. */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(7(4){4.w.8=\'H\'G p;c(!4.w.8){f}d 6=4.U.D.L,g=6.g,h=6.h,a;7 5(2,r){c(2.k.F.J>1){f}2.B();d 8=2.k.q[0],l=p.N(\'O\');l.S(r,i,i,V,1,8.W,8.X,8.Y,8.A,b,b,b,b,0,C);2.z.E(l)}6.m=7(2){d 3=e;c(a||!3.I(2.k.q[0])){f}a=i;3.j=b;5(2,\'K\');5(2,\'s\');5(2,\'M\')};6.n=7(2){c(!a){f}e.j=i;5(2,\'s\')};6.o=7(2){c(!a){f}5(2,\'P\');5(2,\'Q\');c(!e.j){5(2,\'R\')}a=b};6.g=7(){d 3=e;3.u.T({v:4.9(3,\'m\'),x:4.9(3,\'n\'),y:4.9(3,\'o\')});g.t(3)};6.h=7(){d 3=e;3.u.Z({v:4.9(3,\'m\'),x:4.9(3,\'n\'),y:4.9(3,\'o\')});h.t(3)}})(4);',62,62,'||event|self|jQuery|simulateMouseEvent|mouseProto|function|touch|proxy|touchHandled|false|if|var|this|return|_mouseInit|_mouseDestroy|true|_touchMoved|originalEvent|simulatedEvent|_touchStart|_touchMove|_touchEnd|document|changedTouches|simulatedType|mousemove|call|element|touchstart|support|touchmove|touchend|target|clientY|preventDefault|null|mouse|dispatchEvent|touches|in|ontouchend|_mouseCapture|length|mouseover|prototype|mousedown|createEvent|MouseEvents|mouseup|mouseout|click|initMouseEvent|bind|ui|window|screenX|screenY|clientX|unbind'.split('|'),0,{}));