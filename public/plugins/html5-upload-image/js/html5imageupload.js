/*
 * HTML 5 Image upload script
 * by STBeets
 * bought on CodeCanyon: http://codecanyon.net/item/html-5-upload-image-ratio-with-drag-and-drop/8712634?ref=stbeets
 * 
 * Version: 1.4.3
 * 
 */

(function (window, $, undefined) {
	"use strict";
	
	$.html5imageupload = function html5imageupload(options, element) {

		this.element		= element; 
		this.options		= $.extend(true, {}, $.html5imageupload.defaults, options, $(this.element).data());
		this.input			= $(this.element).find('input[type=file]');
		
		var $window 		= $(window);
		var _self	 		= this;
		this.interval		= null;
		this.drag			= false;
		this.widthPercentage	= 100;
		
		//buttons
		this.button				= {}
		this.button.edit		= '<div class="btn btn-info btn-edit" title="' + (this.options.editTitle || 'Edit') + '"><i class="glyphicon glyphicon-pencil"></i></div>'
		this.button.saving		= '<div class="btn btn-warning saving">' + (this.options.saveLabel || 'Saving...') + ' <i class="glyphicon glyphicon-time"></i></div>';
		this.button.zoomin		= '<div class="btn btn-default btn-zoom-in" title="' + (this.options.zoominTitle || 'Zoom in') + '"><i class="glyphicon glyphicon-resize-full"></i></div>';
		this.button.zoomout		= '<div class="btn btn-default btn-zoom-out" title="' + (this.options.zoomoutTitle || 'Zoom out') + '"><i class="glyphicon glyphicon-resize-small"></i></div>';
		this.button.zoomreset	= '<div class="btn btn-default btn-zoom-reset" title="' + (this.options.zoomresetTitle || 'Fullsize') + '"><i class="glyphicon glyphicon-fullscreen"></i></div>';
//		this.button.rotatecw	= '<div class="btn btn-default btn-rotate-cw" title="' + (this.options.cwTitle || 'Rotate clockwise') + '"><i class="glyphicon glyphicon-share"></i></div>';
//		this.button.rotateccw	= '<div class="btn btn-default btn-rotate-ccw" title="' + (this.options.ccwTitle || 'Rotate counter clockwise') + '"><i class="glyphicon glyphicon-share icon-flipped"></i></div>';
		this.button.cancel		= '<div class="btn btn-danger btn-cancel" title="' + (this.options.cancelTitle || 'Cancel') + '"><i class="glyphicon glyphicon-remove"></i></div>';
		this.button.done		= '<div class="btn btn-success btn-ok" title="' + (this.options.okTitle || 'Ok') + '"><i class="glyphicon glyphicon-ok"></i></div>';
		this.button.del			= '<div class="btn btn-danger btn-del" title="' + (this.options.delTitle || 'Delete') + '"><i class="glyphicon glyphicon-trash"></i></div>';
		
		this.button.download	= '<a class="btn btn-warning download"><i class="glyphicon glyphicon-download"></i> ' + (this.options.downloadLabel || 'Download') + '</a>';
		
		//this.imageExtensions	= {png: 'png', bmp: 'bmp', gif: 'gif', jpg: ['jpg','jpeg'], tiff: 'tiff'};
		this.imageMimes 		= { png: 'image/png', bmp: 'image/bmp', gif: 'image/gif', jpg: 'image/jpeg', jpeg: 'image/jpeg', tiff: 'image/tiff' };  
		
		_self._init();
	}
	
	$.html5imageupload.defaults = {
		width:				null,
		height: 			null,
		image:				null,  
		ghost:				true,
		originalsize:		true,
		url:				false,
		removeurl:			null,
		data:				{},
		canvas:				true,
		canvasImageOnly:	false,
		ajax:				true,
		resize: 			false,
		dimensionsonly:		false,
		editstart:			false,
		saveOriginal:		false,
		save:				true,
		download:			false,
		
		smaller: 			false,
		smallerWidth:		false,
		smallerHeight:		false,
		background:			null,
		
		
		onAfterZoomImage:	null,
		onAfterInitImage:	null,
		onAfterMoveImage:	null,
		onAfterProcessImage: null,
		onAfterResetImage:	null,
		onAfterCancel:		null,
		onAfterRemoveImage:	null,
		onAfterSelectImage:	null,
		
	}
		
	$.html5imageupload.prototype = {
		_init: function() {
			
			var _self			= this;
			var options			= _self.options;
			var element			= _self.element
			var input			= _self.input;
			
			if (empty($(element))) {
				return false;
			} else {
				$(element).children().css({position: 'absolute'})
			}
			
			//the engine of this script
			if (! (window.FormData && ("upload" in ($.ajaxSettings.xhr())))) {
				$(element).empty().attr('class','').addClass('alert alert-danger').html('HTML5 Upload Image: Sadly.. this browser does not support the plugin, update your browser today!');
				return false;
			}
			
			if (!empty(options.width) && empty(options.height) && $(element).innerHeight() <= 0) {
				$(element).empty().attr('class','').addClass('alert alert-danger').html('HTML5 Upload Image: Image height is not set and can not be calculated...');
				return false;
			}
			if (!empty(options.height) && empty(options.width) && $(element).innerWidth() <= 0) {
				$(element).empty().attr('class','').addClass('alert alert-danger').html('HTML5 Upload Image: Image width is not set and can not be calculated...');
				return false;
			} 
			/*if (!empty(options.height) && !empty(options.width) && !empty($(element).innerHeight() && !empty($(element).innerWidth()))) {
				//all sizes are filled in
				console.log(options.width)
				console.log(options.height)
				console.log(options.width / options.height)
				
				console.log($(element).outerWidth())
				console.log($(element).outerHeight())
				
				console.log($(element).outerWidth() / $(element).outerHeight())
				
				if ((options.width / options.height) != ($(element).outerWidth() / $(element).outerHeight())) {
					$(element).empty().attr('class','').addClass('alert alert-danger').html('HTML5 Upload Image: The ratio of all sizes (CSS and image) are not the same...');
					return false;
				}
			}*/
			
			//copy styles
			$(element).data('style',$(element).attr('style'));
			$(element).data('class',$(element).attr('class'));
			
			//////////////
			//check sizes
			var width, height, imageWidth, imageHeight;
			
			options.width		= 
			imageWidth			= options.width || $(element).outerWidth();
			options.height		=
			imageHeight			= options.height || $(element).outerHeight();
			
			if ($(element).innerWidth() > 0) {
				width			= $(element).outerWidth();
			} else if ($(element).innerHeight() > 0) {
				width			= null
			} else if (!empty(options.width)) {
				width			= options.width;
			}
			
			if ($(element).innerHeight() > 0) {
				height			= $(element).outerHeight();
			} else if ($(element).innerWidth() > 0) {
				height			= null
			} else if (!empty(options.height)) {
				height			= options.height;
			}
			
			height			= height || width / (imageWidth / imageHeight);
			width			= width || height / (imageHeight / imageWidth);
			
			/* is small window, add class small */
			if (width < 240) {
				$(element).addClass('smalltools smalltext');
			}
			
			$(element).css({height:height, width: width});
			_self.widthPercentage		= ($(element).outerWidth() / $(element).offsetParent().width()) * 100;
			
			if (options.resize == true) {
				$(window).resize(function() {
					_self.resize();
				})
			}
			_self._bind();
			
			if (options.required || $(input).prop('required')) {
				_self.options.required			= true;
				$(input).prop('required',true)
			}
			
			if (!options.ajax) {
				_self._formValidation();
			}
			
			if (!empty(options.image) && options.editstart == false) {
				
				$(element).data('name',options.image).append($('<img />').addClass('preview').attr('src',options.image));
				
				var tools			= $('<div class="preview tools"></div>');
				var edit			= $('' + this.button.edit + '');
				var del				= $('' + this.button.del + '');
				/*$(edit).unbind('click').click(function(e) {
					e.preventDefault();
					$(element).children().show();
					$(element).find('.final').remove();
					$(input).data('valid',false);
				})*/
				$(del).unbind('click').click(function(e) {
					e.preventDefault();
					_self.reset();
				})
				
				/*if (options.buttonEdit != false) {
					$(tools).append(edit)
				}*/
				
				if (options.buttonDel != false) {
					$(tools).append(del)
				}
				
				$(element).append(tools);
			} else if (!empty(options.image)) {
				_self.readImage(options.image, options.image, options.image,_self.imageMimes[options.image.split('.').pop()]); //$(img).attr('src'),)
			}
			
			if (_self.options.onAfterInitImage) _self.options.onAfterInitImage.call(_self,_self);
		},
		_bind: function() {
			var _self			= this;
			var element			= _self.element;
			var input			= _self.input;
			
			//bind the events
			$(element).unbind('dragover drop mouseout').on({
				dragover: function(event){
					_self.handleDrag(event)
				},
				drop: function(event){
					_self.handleFile(event, $(this))
				},
				mouseout: function() {
					$(document).unbind('mouseup touchend').on('mouseup touchend', function(e) {
						e.preventDefault()
						_self.imageUnhandle();//
					})
					
				}
			});
			
			$(input).unbind('change').change(function(event){
				_self.drag		= false;
				_self.handleFile(event, $(element))
			});
			
			
		},
		
		handleFile: function(event, element) {
			event.stopPropagation();
			event.preventDefault();
			
			var _self		= this;
			var options		= this.options; 
		    var files 		= (_self.drag == false) ? event.originalEvent.target.files : event.originalEvent.dataTransfer.files; // FileList object.
		    _self.drag		= false;
		    
		   // _self.reset();
		    if (options.removeurl != null && !empty($(element).data('name'))) {
				$.ajax({
					type: 'POST',
					url: options.removeurl,
					data: {image: $(element).data('name') },
					success: function(response) {
						if (_self.options.onAfterRemoveImage) _self.options.onAfterRemoveImage.call(_self, response, _self);
					}
				})
			}
		    
		    $(element).removeClass('notAnImage').addClass('loading');//.unbind('dragover').unbind('drop');
		    
		    for (var i = 0, f; f = files[i]; i++) {
		    	if (!f.type.match('image.*')) {
		    		$(element).addClass('notAnImage');
		    		continue;
		        }

		        var reader = new FileReader();
		        
		        reader.onload = (function(theFile) {
		        	//console.log(theFile);
		        	return function(e) {
		        		$(element).find('img').remove(); 
		        		_self.readImage(reader.result, e.target.result, theFile.name, theFile.type);
		        	};
		        })(f);
		        reader.readAsDataURL(f);
		    }
		    if (_self.options.onAfterSelectImage) _self.options.onAfterSelectImage.call(_self, response, _self);
		},
		
		readImage: function(image, src, name, mimeType) {
			
			var _self		= this;
			var options		= this.options; 
			var element		= this.element;
		    _self.drag		= false;
			
			var img 		= new Image;
    		img.onload 		= function(tmp) {
    			//console.log(tmp);
    			var imgElement		= $('<img src="' + src + '" name="' + name + '" />');
    			var width, height, useWidth, useHeight, ratio, elementRatio;
    			
    			width				= 
    			useWidth			= img.width;
    			height				= 
    			useHeight			= img.height;
    			ratio				= width / height;
    			elementRatio		= $(element).outerWidth() / $(element).outerHeight();
    			
    			//resize image
    			if (options.originalsize == false) {
    				
    				//need to add the option is smaller = true, dont resize
    				//also if the image == 100% the size of the element, dont add extra space
    				
    				useWidth		= $(element).outerWidth() + 40;
    				useHeight		= useWidth / ratio;
    				
    				if (useHeight < $(element).outerHeight()) {
    					useHeight	= $(element).outerHeight() + 40;
    					useWidth	= useHeight * ratio;
    				}
    			} else if (useWidth < $(element).outerWidth() || useHeight < $(element).outerHeight()) {
    				
    				if (options.smaller == true) {
    					//do nothing
    				} else {
	    				if (ratio < elementRatio) {
	    					useWidth	= $(element).outerWidth();
	    					useHeight	= useWidth / ratio;
	    				} else {
	    					useHeight	= $(element).outerHeight();
	    					useWidth	= useHeight * ratio;
	    				}
    				}
    			}
    			
    			var left			= parseFloat(($(element).outerWidth() - useWidth) / 2)// * -1;
    			var top				= parseFloat(($(element).outerHeight() - useHeight) / 2)// * -1;
    			
    			
    			imgElement.css({left: left, top: top, width: useWidth, height: useHeight })
    			
    			_self.image			= $(imgElement).clone().data({ mime: mimeType, width: width, height: height, ratio: ratio, left: left, top: top, useWidth: useWidth, useHeight: useHeight}).addClass('main').bind('mousedown touchstart',function(event) { _self.imageHandle(event)});
    			_self.imageGhost	= (options.ghost) ? $(imgElement).addClass('ghost') : null;

    			//place the images
    			$(element).append($('<div class="cropWrapper"></div>').append($(_self.image)));
    			if (!empty(_self.imageGhost)) {
    				$(element).append(_self.imageGhost);
    			}
    			
    			//$(element).unbind('dragover').unbind('drop');
    			_self._tools();
    			
    			//clean up
    			$(element).removeClass('loading');
    		}
    		img.src					= image;
		},
		
		handleDrag: function(event) {
			var _self			= this;
			_self.drag			= true;
			event.stopPropagation();
			event.preventDefault();
			event.originalEvent.dataTransfer.dropEffect = 'copy';
		},
		imageHandle: function(e) {
			e.preventDefault(); // disable selection
			var event		= (e.originalEvent.touches || e.originalEvent.changedTouches) ? e.originalEvent.touches[0] || e.originalEvent.changedTouches[0] : e;

			var _self			= this;
			var element			= this.element;
			var image			= this.image;
			var options			= this.options;
			
			var height			= image.outerHeight(),
			width				= image.outerWidth(),
			
			cursor_y			= image.offset().top + height - event.pageY,
			cursor_x			= image.offset().left + width - event.pageX;
			
			$(document).on({
				
				'dragstart mousemove touchmove': function(e) {
					
					$('body').css({cursor:'move'});
					
					e.stopImmediatePropagation()
					e.preventDefault();
					
					var event		= (e.originalEvent.touches || e.originalEvent.changedTouches) ? e.originalEvent.touches[0] || e.originalEvent.changedTouches[0] : e;

					var imgTop 		= event.pageY + cursor_y - height,
					imgLeft 		= event.pageX + cursor_x - width;
					var hasBorder	=  ($(element).outerWidth() != $(element).innerWidth());
					
					if (options.smaller == false) {
					
						if(parseInt(imgTop - $(element).offset().top) > 0) { 
							imgTop		= $(element).offset().top + ((hasBorder) ? 1 : 0);
						} else if (imgTop + height < $(element).offset().top + $(element).outerHeight()) {
					    	imgTop		= $(element).offset().top + $(element).outerHeight() - height + ((hasBorder) ? 1 : 0);
					    }
						     
					    if (parseInt(imgLeft - $(element).offset().left) > 0) {
					    	imgLeft		= $(element).offset().left + ((hasBorder) ? 1 : 0);;
					    } else if (imgLeft + width < $(element).offset().left + $(element).outerWidth()) {
					    	imgLeft = $(element).offset().left + $(element).outerWidth() - width + ((hasBorder) ? 1 : 0);;
					    }
					}
					
				    image.offset({
						top:	imgTop,
						left:	imgLeft
					})
					_self._ghost();
				},
				'dragend mouseup touchend': function() {
					_self.imageUnhandle();
				}
			})
			
		},
		imageUnhandle: function() {
			var _self			= this;
			var image			= _self.image;
			
			$(document).unbind('mousemove touchmove');
			$('body').css({cursor:''});
			if (_self.options.onAfterMoveImage) _self.options.onAfterMoveImage.call(_self,_self);
		},
		imageZoom :function(x){
			var _self 				= this;
			var element				= _self.element;
			var image				= _self.image;
			var options				= _self.options;
			
			if (empty(image)) {
				_self._clearTimers();
				return false;
			}
			
			var ratio 				= image.data('ratio');
			var newWidth 			= image.outerWidth() + x;
			var newHeight 			= newWidth / ratio;
			
			if (options.smaller == false) {
				
				//smaller then element?
				if (newWidth < $(element).outerWidth()) {
					newWidth			= $(element).outerWidth();
					newHeight			= newWidth / ratio;
				}
				if (newHeight < $(element).outerHeight()) {
					newHeight			= $(element).outerHeight();
					newWidth			= newHeight * ratio;
				}
			} else {
				if (newWidth < 1 || newHeight < 1) {
					if (newWidth > newHeight) {
						newWidth		= 1;
						newHeight		= newWidth / ratio;
					} else {
						newHeight		= 1
						newWidth		= newHeight * ratio;
					}
				}
			}
			
			var newTop 				= image.css('top').replace(/[^-\d\.]/g, '') - (newHeight - image.outerHeight()) / 2;
			var newLeft 			= image.css('left').replace(/[^-\d\.]/g, '') - (newWidth - image.outerWidth()) / 2;
			
			
			if (options.smaller == false) {
			
				if ($(element).offset().left - newLeft < $(element).offset().left) {
					newLeft				= 0;
				} else if ($(element).outerWidth() > newLeft + $(image).outerWidth() && x <= 0) {
					newLeft				= $(element).outerWidth() - newWidth;
				}
				
				if ($(element).offset().top - newTop < $(element).offset().top) {
					newTop				= 0;
				} else if ($(element).outerHeight() > newTop + $(image).outerHeight() && x <= 0) {
					newTop				= $(element).outerHeight() - newHeight;
				}
			}
			image.css({width: newWidth, height: newHeight, top: newTop, left: newLeft })
			_self._ghost();
			

		},
		imageCrop: function() {
			var _self				= this;
			var element				= _self.element;
			var image				= _self.image;
			var input				= _self.input;
			var options				= _self.options;
			
			var factor				= (options.width != $(element).outerWidth()) ? options.width / $(element).outerWidth() : 1;
			
			var finalWidth, finalHeight, finalTop, finalLeft, imageWidth, imageHeight, imageOriginalWidth, imageOriginalHeight;
			
			finalWidth				= options.width;
			finalHeight				= options.height;
			
			finalTop				= parseInt(Math.round(parseInt($(image).css('top')) * factor))
			finalLeft				= parseInt(Math.round(parseInt($(image).css('left')) * factor))
			imageWidth				= parseInt(Math.round($(image).width() * factor));
			imageHeight				= parseInt(Math.round($(image).height() * factor));
			imageOriginalWidth		= $(image).data('width');
			imageOriginalHeight		= $(image).data('height');
			
			
			
			finalTop				= finalTop || 0;
			finalLeft				= finalLeft || 0;
			
			var obj					= {name: $(image).attr('name'), imageOriginalWidth: imageOriginalWidth, imageOriginalHeight: imageOriginalHeight, imageWidth: imageWidth, imageHeight: imageHeight, width: finalWidth, height: finalHeight, left: finalLeft, top: finalTop}

			$(element).find('.tools').children().toggle();
			$(element).find('.tools').append($(_self.button.saving));
			
			if (options.canvas == true) {
				
	            var canvas          = $('<canvas class="final" id="canvas_' + $(input).attr('name') + '" width="' + finalWidth + '" height="' + finalHeight + '" style="position:absolute; top: 0; bottom: 0; left: 0; right: 0; z-index:100; width: 100%; height: 100%;"></canvas>');
	            
	            $(element).append(canvas);
	            
	            var canvasContext   = $(canvas)[0].getContext('2d');
	            var imageObj        = new Image();

	            //canvasContext.fillStyle = "rgba(255, 255, 255, 0)";
	            //canvasContext.clearRect(0,0,finalWidth,finalHeight);
	            
	            imageObj.onload = function() {
	            	
	            	
	                var canvasTmp			= $('<canvas width="' + imageWidth + '" height="' + imageHeight + '"></canvas>');
	                var canvasTmpContext    = $(canvasTmp)[0].getContext('2d');
	                var tmpImage			= $('<img src="" />');

	                //canvasTmpContext.fillStyle = "rgba(255, 255, 255, 0)";
	                //canvasTmpContext.clearRect(0,0,imageWidth,imageHeight);
	                canvasTmpContext.drawImage(imageObj, 0, 0, imageWidth, imageHeight);
	                //$(element).append(canvasTmp);
	                var tmpObj				= new Image();
	                tmpObj.onload = function() {
	                	
	                	if (options.canvasImageOnly == true) {
	                		var _imageWidth		= imageWidth;
	                		var _imageHeight	= imageHeight;
	                		if (finalLeft < 0) {
	                			_imageWidth		+= finalLeft;
	                		} else if (finalLeft + imageWidth > finalWidth) {
	                			_imageWidth		= finalWidth - finalLeft
	                		}
	                		if (finalTop  < 0) {
	                			_imageHeight	+= finalTop;
	                		} else if (finalTop + imageHeight > finalHeight) {
	                			_imageHeight	= finalHeight - finalTop
	                		}
	                		
	                		var canvasImageOnly			= $('<canvas width="' + _imageWidth + '" height="' + _imageHeight + '"></canvas>');
	    	                var canvasImageOnlyContext  = $(canvasImageOnly)[0].getContext('2d');
	    	                canvasImageOnlyContext.drawImage(tmpObj, ((finalLeft < 0) ? finalLeft : 0), ((finalTop < 0) ? finalTop : 0), imageWidth, imageHeight);
	    	                
	                	}
		                	
	                	if (imageWidth < finalWidth || imageHeight < finalHeight) {
	                		canvasContext.drawImage(tmpObj, finalLeft, finalTop, imageWidth, imageHeight);
	                	} else {
	                	   	canvasContext.drawImage(tmpObj, finalLeft*-1, finalTop*-1, finalWidth, finalHeight, 0,0,finalWidth, finalHeight);
	                	}
	                	
	                	
	                	var dataUrl		= (options.canvasImageOnly == true) ? $(canvasImageOnly)[0].toDataURL(image.data('mime')) : $(canvas)[0].toDataURL(image.data('mime'))
	                	
	                			
	                	if (options.save == false) {
	                		
	                		$(element).find('.tools .saving').remove();
							$(element).find('.tools').children().toggle();
	                		
	                		if (_self.options.onSave) _self.options.onSave.call(_self,$.extend(obj, options.data, {data: dataUrl}));
	        				_self.imageFinal();
	                		
	                	} else if (options.ajax == true) {
	                		
	                		_self._ajax($.extend({data: dataUrl}, obj));
	                		
	                	} else {
	                		
	                		var json		= JSON.stringify($.extend({data: dataUrl},obj));
	        				$(input).after($('<input type="text" name="' + $(input).attr('name') + '_values" class="final" />').val(json));
	        				
	        				$(input).data('required',$(input).prop('required'));
	        				$(input).prop('required',false);
	        				$(input).wrap('<form>').parent('form').trigger('reset');
	        				$(input).unwrap();
	        				
	        				$(element).find('.tools .saving').remove();
							$(element).find('.tools').children().toggle();
	        				
	        				_self.imageFinal();
	                	}
	                	
	                }
	                tmpObj.src			= $(canvasTmp)[0].toDataURL(image.data('mime'));
	                

	            };
	            imageObj.src = $(image).attr('src');
	            
	            if (options.saveOriginal === true) {
	            	//console.log($(image).attr('src'));
	            	obj			= $.extend({original: $(image).attr('src')}, obj);
	            }
	            
			} else {
				if (options.ajax == true) {
					
					_self._ajax($.extend({data: $(image).attr('src'),saveOriginal: options.saveOriginal}, obj));
					
				} else {
					var finalImage		= $(element).find('.cropWrapper').clone();
					$(finalImage).addClass('final').show().unbind().children().unbind()
					$(element).append($(finalImage));

					_self.imageFinal();
					
					var json		= JSON.stringify(obj);
					$(input).after($('<input type="text" name="' + $(input).attr('name') + '_values" class="final" />').val(json));
				}
			}
		},
		_ajax: function(obj) {
			var _self				= this;
			var element				= _self.element;
			var image				= _self.image;
			var options				= _self.options;
			
			if (options.dimensionsonly == true) {
				delete obj.data;
			}
			
			$.ajax({
				type: 'POST',
				url: options.url,
				data: $.extend(obj, options.data),
				success: function(response) {
					
					if (response.status == "success") {
						var file		= response.url.split('?');
						$(element).find('.tools .saving').remove();
						$(element).find('.tools').children().toggle();
						$(element).data('name',file[0])
						$(element).data('filename',response.filename)
						if (options.canvas != true) {
							$(element).append($('<img src="' + file[0] + '" class="final" style="width: 100%" />'));
						}
						
						_self.imageFinal();
					} else {
						$(element).find('.tools .saving').remove();
						$(element).find('.tools').children().toggle();
						$(element).append($('<div class="alert alert-danger">' + response.error + '</div>').css({bottom: '10px',left: '10px',right: '10px',position: 'absolute', zIndex: 99}));
						setTimeout(function() { _self.responseReset();},2000);
					}
				},
				error: function(response, status) {
					$(element).find('.tools .saving').remove();
					$(element).find('.tools').children().toggle();
					$(element).append($('<div class="alert alert-danger"><strong>' + response.status + '</strong> ' + response.statusText + '</div>').css({bottom: '10px',left: '10px',right: '10px',position: 'absolute', zIndex: 99}));
					setTimeout(function() { _self.responseReset();},2000);
				}
			})
		},
		imageReset: function() {
			var _self			= this;
			var image			= _self.image;
			var element			= _self.element;
			
			$(image).css({width: image.data('useWidth'), height: image.data('useHeight'), top: image.data('top'), left: image.data('left')})
			_self._ghost();
			
			if (_self.options.onAfterResetImage) _self.options.onAfterResetImage.call(_self,_self);
		},
		imageFinal: function() {
			var _self			= this;
			var element			= _self.element;
			var input			= _self.input;
			var options			= _self.options;
			
			//remove all children except final
			$(element).addClass('done');
			$(element).children().not('.final').hide();
			
			//create tools element
			var tools		= $('<div class="tools final">');
			
			//edit option after crop
			if (options.buttonEdit != false) {
				$(tools).append($(_self.button.edit).click(function() {
					$(element).children().show();
					$(element).find('.final').remove();
					$(input).data('valid',false);
				}));
			}
			
			//delete option after crop
			if (options.buttonDel != false) {
				$(tools).append($(_self.button.del).click(function(e){
					_self.reset();
				}))
			}
			
			//append tools to element
			$(element).append(tools);
			$(element).unbind();
			
			//download
			if (options.download == true) {
				var download	= $('<div class="download final"/>');
				$(download).append($(_self.button.download).attr('download',$(_self.image).attr('name')).click(function(e) {
					$(this).attr('href',$(element).find('canvas.final')[0].toDataURL(_self.image.data('mime')));
				}))
				$(element).append(download);
			}
			
			//set input to valid for form upload
			$(input).unbind().data('valid',true);
			
			//custom function after process image;
			if (_self.options.onAfterProcessImage) _self.options.onAfterProcessImage.call(_self,_self);
		},
		responseReset: function() {
			var _self			= this;
			var element			= _self.element;
			
			//remove responds from ajax event
			$(element).find('.alert').remove(); 
			
		},
		reset: function() {
			var _self			= this;
			var element			= _self.element;
			var input			= _self.input;
			var options			= _self.options;
			_self.image			= null;
			
			
			$(element).find('.preview').remove();
			$(element).removeClass('loading done').children().show().not('input[type=file]').remove();
			$(input).wrap('<form>').parent('form').trigger('reset');
			$(input).unwrap();
			$(input).prop('required',$(input).data('required') || options.required || false).data('valid',false);
			_self._bind();
			
			if (options.removeurl != null && !empty($(element).data('name'))) {
				$.ajax({
					type: 'POST',
					url: options.removeurl,
					data: {image: $(element).data('name') },
					success: function(response) {
						if (_self.options.onAfterRemoveImage) _self.options.onAfterRemoveImage.call(_self, response, _self);
					}
				})
			}
			$(element).data('name',null);
			
			if (_self.imageGhost) {
				$(_self.imageGhost).remove();
				_self.imageGhost	= null;
			}
			
			if (_self.options.onAfterCancel) _self.options.onAfterCancel.call(_self);
		},
		resize: function() {
			var _self			= this;
			var options			= _self.options;
			var element			= _self.element;
			var image			= _self.image;
			
			if (options.resize !== true) return false;
			
			var oldWidth		= $(element).outerWidth();
			var width			= $(element).offsetParent().width() * (_self.widthPercentage / 100);
			var factor			= width / oldWidth
			var height			= $(element).outerHeight() * factor;
			
			
			
			$(element).css({height:height, width: width});
			
			if (width < 240) {
				if (!$(element).hasClass('smalltools smalltext')) {
					$(element).addClass('smalltools smalltext smalladded');
				}
			} else {
				if ($(element).hasClass('smalladded')) {
					$(element).removeClass('smalltools smalltext smalladded');
				}
			}
			
			if (!empty(image)) {
				//console.log(image.offset());
				$(image).css({left: $(image).css('left').replace(/[^-\d\.]/g, '') * factor + 'px', top: $(image).css('top').replace(/[^-\d\.]/g, '')  * factor + 'px'})
				$(image).width($(image).width() * factor);
				$(image).height($(image).height()  * factor);
				
				_self._ghost();
			}
			//console.log('resize plugin');
			return true;
		},
/*		rotate: function(degrees) {
			var _self			= this;
			var element			= _self.element;
			var image			= _self.image
			
			$(image).addClass('rotate_90');
			var tmp				= $(image).data('width');
			$(image).data('width',$(image).data('height'));
			$(image).data('height',tmp);
		},*/
		reinit: function() {
			
			var _self			= this;
			var element			= _self.element;
			
			this.resize();
			this._bind();
			
			return true;
		},
		modal: function() {
			var _self			= this;
			var element			= _self.element;
			
			$(element).attr('style',$(element).data('style'));
			$(element).attr('class',$(element).data('class'));
			
			_self._init();
			
			return this;
		},
		
		_ghost: function() {
			var _self			= this;
			var options			= _self.options;
			var image			= _self.image;
			var ghost			= _self.imageGhost;
			
			//if set to true, mirror all drag events 
			//function in one place, much needed
			if (options.ghost == true && !empty(ghost)) {
				$(ghost).css({width: image.css('width'), height: image.css('height'), top: image.css('top'), left: image.css('left')})
			}
		},
		_tools: function() {
			var _self			= this;
			var element			= _self.element;
			var tools			= $('<div class="tools"></div>');
			var options			= _self.options;
			
			//zoomin button
			if (options.buttonZoomin != false) {
				$(tools).append($(_self.button.zoomin).on({
					'touchstart mousedown': function(e) { 
						e.preventDefault();
						_self.interval 		= window.setInterval(function(){ 
							_self.imageZoom(2); 
						},1);
					},
					'touchend mouseup mouseleave': function(e) {
						e.preventDefault();
						window.clearInterval(_self.interval);
						if (_self.options.onAfterZoomImage) _self.options.onAfterZoomImage.call(_self,_self);
					}
				}));
			}
			
			//zoomreset button (set the image to the "original" size, same size as when selecting the image
			if (options.buttonZoomreset != false) {
				$(tools).append($(_self.button.zoomreset).on({
					'touchstart click': function(e) {
						e.preventDefault();
						_self.imageReset();
					}
				}));
			}
			
			//zoomout button
			if (options.buttonZoomout != false) {
				$(tools).append($(_self.button.zoomout).on({
					'touchstart mousedown': function(e) { 
						e.preventDefault();
						_self.interval 		= window.setInterval(function(){ 
							_self.imageZoom(-2); 
						},1);
					},
					'touchend mouseup mouseleave': function(e) {
						e.preventDefault();
						window.clearInterval(_self.interval);
						if (_self.options.onAfterZoomImage) _self.options.onAfterZoomImage.call(_self,_self);
					}
				}));
			}
			
/*			if (options.buttonRotateccw != false) {
				$(tools).append($(_self.button.rotateccw).on({
					'touchstart touchend click': function(e) { 
						e.preventDefault();
						_self.rotate(-90); 
					}
				}));
			}
			if (options.buttonRotatecw != false) {
				$(tools).append($(_self.button.rotatecw).on({
					'touchstart touchend click': function(e) { 
						e.preventDefault();
						_self.rotate(90); 
					}
				}));
			}*/
			
			
			//cancel button (removes the image and resets it to the original init event
			if (options.buttonCancel != false) {
				$(tools).append($(_self.button.cancel).on({
					'touchstart touchend click': function(e) { 
						e.preventDefault();
						_self.reset() 
					}
				}));
			}
			//done button (crop the image!) 
			if (options.buttonDone != false) {
				$(tools).append($(_self.button.done).on({
					'touchstart click': function(e) { 
						e.preventDefault();
						_self.imageCrop() 
					}
				}));
			}
			
			$(element).append($(tools));
			
		},
		_clearTimers: function() {
			//function to clear all timers, just to be sure!
			var interval_id = window.setInterval("", 9999);
			for (var i = 1; i < interval_id; i++)
				window.clearInterval(i);
		},
		_formValidation: function() {
			var _self				= this;
			var element				= _self.element;
			var input				= _self.input;
			
			$(element).closest('form').submit(function(e) {
				
				//e.stopPropagation();
				$(this).find('input[type=file]').each(function(i, el) {
					if ($(el).prop('required')) {
						if ($(el).data('valid') !== true) {
							e.preventDefault();
							return false;
						}
					}
				}) 
				
				return true;
			})
			
		}
		
	}
	
	$.fn.html5imageupload = function (options) {
		if ($.data(this, "html5imageupload")) return;
		return $(this).each(function() {
			$.data(this, "html5imageupload",new $.html5imageupload(options, this));
		})
	}
	
})(window, jQuery);


function empty(mixed_var) {
	//discuss at: http://phpjs.org/functions/empty/
	// original by: Philippe Baumann
	//    input by: Onno Marsman
	//    input by: LH
	//    input by: Stoyan Kyosev (http://www.svest.org/)
	// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Onno Marsman
	// improved by: Francesco
	// improved by: Marc Jansen
	// improved by: Rafal Kukawski
	
	var undef, key, i, len;
	var emptyValues = [undef, null, false, 0, '', '0'];

	for (i = 0, len = emptyValues.length; i < len; i++) {
		if (mixed_var === emptyValues[i]) {
			return true;
		}
	}

	if (typeof mixed_var === 'object') {
		for (key in mixed_var) {
			return false;
	    }
	    return true;
	}
	return false;
}







