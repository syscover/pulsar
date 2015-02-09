/*
*	GetFile v1.1 - 2014-09-19
*	jQuery Upload Plugin
*	By Jose Carlos Rodriguez
*	(c) 2014 Syscover S.L. - http://www.syscover.com/
*	All rights reserved
*/

"use strict";

(function ( $ ) {
	var GetFile = {
		options: {
			urlPlugin:					'.',		                                // URL relative where is Get File plugin folder
			folder:						null,										// Path to the target folder (from the document root)
			tmpFolder:					null,										// Path to the temporary folder (from the document root)
			encryption:					false,										// File name encryption
			filename:					null,										// Desired final file name, without extension
			outputExtension:			null,										// Conversion to a image format (gif|jpg|png)
            mimesAccept:				[											// Accepted file types
				'image/*',
				'video/*',
				'audio/*',
				'application/pdf',
				'application/msword',
				'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			],
			crop: {
				active:					false,										// Crop function is active
				width:					null,										// Final image width
				height:					null,										// Final image height
				aspectRatio:			null,										// Crop window aspect ratio, si instancia width y height el aspectRatio no se tiene en cuenta
				minSize:				null,										// Minimum crop size
				maxSize:				null,										// Maximum crop size
				setSelect:				null										// Default crop window coordinates
			},
			resize: {
				active:					false,										// Resize function is active
				width:					null,										// Final image width
				height:					null,										// Final image height
				constrainProportions:	true										// Mantiene las proporciones de la imagen
			},
			sizes: [
			/*
			{
				width:					100,
				height:					100,
				constrainProportions:	true,
				prefix:					'@2',
				folder:					null,
				outputExtension:		null
			}
			*/
			],
            lang: {
                cropWindowTitle:		'Cop image',
                previewTitle:			'Preview',
                cropButtonText:			'Crop',
                cancelButtonText:		'Cancel'
            },
            selectorItems: {
                container:		        '#wrapGetFile',                             // Selector, crop frame and preview container
                cropButton:			    '#gfCropButton',                            // Selector, button that starts the crop process
                cancelButton:			'#gfCancelButton',                          // Selector, button that closes the crop window
                crop:		            '.imgs-crop-container',                     // Selector, frame that contains the crop
                preview:		        '.imgs-preview-container'                   // Selector, frame that contains the preview
            }
		},

		items: {
			container:				null,							                // Crop frame and preview container
			cropButton:				null,								            // Button that starts the crop process
			cancelButton:			null,							                // Button that closes the crop window
			crop:					null,						                    // Frame that contains the crop
			preview:				null					                        // Frame that contains the preview
		},

		properties: {
			coords:					null,											// Internal use variable
			tmpDelete:				345600,											// Minimum time that the temporary files are kept (by default, 345600 seconds = 4 days)
			tmpName:				'',											 	// Name of the file copied into the temporary folder from server
            oldName:                null,                                           // Original name of file
            extension:              null,                                           // Original extension of file
            mime:                   null,                                           // Original type mime of file
            size:                   null,                                           // Size from image uploaded
            isImage:                null,                                           // Check if is image file uploaded
            mow:					null,										    // Original frame width
            moh:					800,										    // Original frame height
            mpw:					null,										    // Preview frame width
            mph:					800,										    // Preview frame height
            row:					null,											// Real width of the image that's going to be cropped
			roh:					null,											// Real height of the image that's going to be cropped
			rpw:					null,											// Real preview width
			rph:					null,											// Real preview height
            width:                  null,                                           // Original image width
            height:                 null,                                           // Original image height
            pixelRatio:             null                                            // Pixel ratio from screen
		},

		callback: null,

		init: function(options, callback, elem)
		{
            this.options = $.extend({}, this.options, options||{});	// Options load

            if($(this.options.selectorItems.container).length == 0) // Load popup if container haven't elements
            {
                var dataRequest = this.options.lang;
                dataRequest.urlPlugin = options.urlPlugin;

                $.ajax({
                    async:      false,
                    cache:      false,
                    dataType:   'html',
                    type:       'POST',
                    url:        this.options.urlPlugin+'/getfile/views/popup.php',
                    data:       dataRequest,
                    success:  function(data){
                        $('body').append(data);
                    },
                    error:function(objXMLHttpRequest){
                        //error
                    }
                });
            }

            //load items from popup
            this.items = {
                container:				$(this.options.selectorItems.container),
                cropButton:				$(this.options.selectorItems.cropButton),
                cancelButton:			$(this.options.selectorItems.cancelButton),
                crop:					$(this.options.selectorItems.crop),
                preview:				$(this.options.selectorItems.preview)
            }

			if(options.mimesAccept)	                                                // The mimesAccept property is overwritten, since we don't want it to be combined
			{
				this.options.mimesAccept = options.mimesAccept;
			}

			this.callback = callback;												// Callback instantiation

			// Save the element reference, both as a jQuery reference and a normal reference
			this.elem = $(elem);													// Instance of the jQuery object to which the plugin is applied

            if(this.elem.prop('className').search("btngf-getFile") > -1){
                var className = "input-getfile"
            }
            else
            {
                var className = "input-getfile-alone"
            }

			// Upload button and event
            if(this.options.mimesAccept == false)
            {
                this.elem.append('<input id="inputGetFile" type="file" class="' + className + '">').on('change', $.proxy(this.upload, this));
            }
            else
            {
                if(Object.prototype.toString.call(this.options.mimesAccept) === '[object Array]')
                {
                    this.elem.append('<input id="inputGetFile" type="file" class="' + className + '" accept="' + this.options.mimesAccept.join() + '">').on('change', $.proxy(this.upload, this));
                }
                else
                {
                    var data = {
                        success: false,
                        error:   11,
                        message: 'The mimesAccept setting must be an array or false. Setting it to false might be dangerous, since it means accepting all file types'
                    };
                    this.callback(data);
                }
            }

			return this;
		},

		upload: function(event)
		{
            $.cssLoader.show({
                urlPlugin:   '/getfile/libs',
                useLayer:   false,
                theme:      'carousel'
            });

			var data = new FormData(); // Object which will contain the data that will be sent

			// Create the form to send by ajax
			data.append('action',			'upload');
			data.append('file',				event.target.files[0]);
			data.append('folder',			this.options.folder);
			data.append('tmpFolder',		this.options.tmpFolder);
			data.append('cropActive',		this.options.crop.active);
            data.append('resizeActive',		this.options.resize.active);
            data.append('outputExtension',	this.options.outputExtension);
			data.append('encryption',		this.options.encryption);
			data.append('filename',			this.options.filename);
			data.append('mimesAccept',		this.options.mimesAccept);
			data.append('tmpDelete',		this.properties.tmpDelete);


			$.ajax({
				url:						this.options.urlPlugin+'/getfile/php/Controllers/server.php',
				data:						data,
				type:						'POST',
				dataType:					'json',
				context:					this,
				cache:						false,
				contentType:				false,
				processData:				false,
				success: function(data)												// Actions done once the image is in the tmp directory
				{
                    this.elem.find('#inputGetFile').val('');					    // Value of file input is reset for another upload

					this.properties.tmpName     = data.name;					    // Temporary name assigned to the file
                    this.properties.size        = data.size;                        // Size from image uploaded
                    this.properties.oldName     = data.oldName;                     // Original name from image uploaded
                    this.properties.extension   = data.extension;                   // Extension from file
                    this.properties.mime        = data.mime;                        // MIME type from file
                    this.properties.isImage     = data.isImage;                     // Check if is image

					if(this.options.crop.active && data.isImage)
					{
                        this.properties.width       = data.width;                   // Original image width if is image crop
                        this.properties.height      = data.height;                  // Original image height if is image crop
                        this.properties.pixelRatio  = window.devicePixelRatio;

                        $.colorbox({
                            inline:     true,
                            href:       this.items.container,
                            width:      '100%',
                            height:     '100%',
                            scrolling:  false
                        });

                        var cblc            = $('#cboxLoadedContent'),              // Elements from crop
                            gfPreview       = $('#gfPreview'),
                            gfFooter        = $('#gfFooter'),
                            contentWidth    = cblc.width(),
                            contentHeight   = cblc.height(),
                            landScape       = false;

                        if($(window).width() * this.properties.pixelRatio > 990)
                        {
                            var freeSpace       = contentWidth - 70;
                            this.properties.mow = Math.round(freeSpace * 0.7);                                          // Calculating the width of each frame
                            this.properties.mpw = Math.round(freeSpace * 0.3);
                            landScape           = true;

                        }
                        else
                        {
                            var freeSpace       = contentWidth;
                            this.properties.mow = Math.round(freeSpace * 0.9);                                          // Calculating the width of each frame
                            this.properties.mpw = Math.round(freeSpace * 0.6);
                            landScape           = false;
                        }

                        if(!MobileEsp.DetectTierTablet() && !MobileEsp.DetectTierIphone())                              // In the desktop case, we match the highest maximum screen size
                        {
                            this.properties.moh = contentHeight-110;
                        }

                        this.crop(this.options.tmpFolder + '/' + data.name);		                                    // Crop is initialized

                        gfPreview.removeClass();
						gfFooter.removeClass();

                        if (landScape)							                    // Landscape display
						{
							gfPreview.addClass('gf-horizontal');
						}
						else														// Portrait display
						{
							gfPreview.addClass('gf-vertical');
							gfFooter.addClass('gf-vertical');

                            var cblHeight = Math.round(this.properties.mph + this.properties.moh + 175);

                            $.colorbox.resize({
                                width:      '100%',
                                height:     cblHeight
                            });
						}

					}
					else if(this.options.resize.active && data.isImage)
					{
						this.executeResize();										// Resize module is executed if active
					}
					else if(this.options.outputExtension != null && data.isImage)	// Image file extension change
					{
						this.executeChangeExtension(data.tmpFolder + '/' + data.name);
					}
					else
					{
						this.callback(data);										// Execution is finished and callback is called
					}

                    $.cssLoader.hide();										        // Hide loader
				}
			});
		},

		crop: function(imageUrl)
		{

            if (!$.Jcrop){
				var data = {
					success: false,
					error:   3,
					message: 'JCrop library not loaded'
				};
				this.callback(data);
			}

			this.removeCrop();													    // Work canvas is initialized

            // Check if values is correct
            if(this.options.crop.height && this.options.crop.width)				    // Checking if width and height are defined; in case of being defined, they're used for aspect ratio
            {
                this.options.crop.aspectRatio = this.options.crop.width / this.options.crop.height;
            }
            else
            {
                if(isNaN(this.options.crop.aspectRatio))						    // If there is no width or height and there is no aspect ratio, an error is thrown
                {
                    var data = {
                        success: false,
                        error:   4,
                        message: 'Width and Height or Ratio must be defined'
                    };
                    this.callback(data);
                }
            }

            // Calculating the sizes of the preview image, depending on its orientation
            // Check if preview is horizontal or vertical
            if(this.options.crop.aspectRatio >= 1)								                                        // If ratio is greater than 1, width is set and preview height is calculated, preview is horizontal
            {
                this.properties.rpw = this.properties.mpw;
                this.properties.rph = Math.round(this.properties.rpw / this.options.crop.aspectRatio);
            }
            else
            {																	                                        // If ratio os less than 1, height is set and preview width is calculated, preview is vertical
                this.properties.rph = this.properties.mph;
                this.properties.rpw = Math.round(this.properties.rph * this.options.crop.aspectRatio);

                if(this.properties.rpw > this.properties.mpw)                                                           // If width is greater than the frame's width, the width is set and the height is proportionately calculated
                {
                    this.properties.rpw = this.properties.mpw;
                    this.properties.rph = Math.round(this.properties.rpw / this.options.crop.aspectRatio);
                }
            }

            this.properties.mph = this.properties.rph;


            // Calculating the sizes of the original image to crop
            var ratImg      = this.properties.width / this.properties.height;

            if(this.properties.width > this.properties.mow)                                                             // Reduce the width in the case of non-mobile or tablet and height scrolling do if necessary
            {
                this.properties.row = this.properties.mow;
                this.properties.roh = Math.round(this.properties.mow / ratImg);
            }
            else
            {
                this.properties.row = this.properties.width;
                this.properties.roh = this.properties.height;
            }

            if(this.properties.roh > this.properties.moh)                                                               // We ensure the highest does not exceed the maximum height of the frame
            {
                this.properties.roh = this.properties.moh;
                this.properties.row = Math.round(this.properties.moh * ratImg);
            }

            this.properties.moh = this.properties.roh;


            this.items.preview.css({											                                        // Preview configuration
                "width"  : this.properties.rpw+"px",
                "height" : this.properties.rph+"px"
            });

            this.items.crop.css({												                                        // Crop configuration
                "width"  : this.properties.mow+4+"px",								                                    // 4px are added to avoid the horizontal scroll (done with overflow: scroll) from overlapping part of the picture
                "height" : this.properties.moh+"px"
            });

            // Images are loaded in each frame
            var image = $('<img id="imgOrig" width="'+this.properties.row+'" height="'+this.properties.roh+'" src="'+imageUrl+'">').appendTo(this.items.crop);
            var image2 = $('<img id="imgPreview" src="'+imageUrl+'">').appendTo(this.items.preview);

            $('#imgOrig').Jcrop({
                onChange:       $.proxy(this.showPreview, this),
                onSelect:       $.proxy(this.showPreview, this),
                bgColor:        'black',
                bOpacity:       .65,
                aspectRatio:    this.options.crop.aspectRatio,
                minSize:        this.options.crop.minSize,
                maxSize:        this.options.crop.maxSize,
                setSelect:      this.options.crop.setSelect
            });

            this.items.cropButton.on('click', $.proxy(this.executeCrop, this));
            this.items.cancelButton.on('click', function() {$.colorbox.close();});
		},

		removeCrop: function()
		{
			$(this.items.crop).html('');
			$(this.items.preview).html('');
			this.items.cropButton.off('click');
			this.items.cancelButton.off('click');
		},

		showPreview: function(coords)												// Show the thumbnail on resize
		{
			var rx = this.properties.rpw / coords.w;
			var ry = this.properties.rph / coords.h;

			$('#imgPreview').css({
				width: Math.round(rx * $('#imgOrig').width()) + 'px',
				height: Math.round(ry * $('#imgOrig').height()) + 'px',
				marginLeft: '-' + Math.round(rx * coords.x) + 'px',
				marginTop: '-' + Math.round(ry * coords.y) + 'px'
			});

			this.properties.coords = coords;										// Coordinates are updated
		},

		executeCrop: function()
		{
			$.ajax({
				url:					this.options.urlPlugin+'/getfile/php/Controllers/server.php',
				type:		   			'POST',
				dataType:	   			'json',
				context:				this,
				cache:					false,
				data: {
					action:				'crop',
                    tmpName:			this.properties.tmpName,
                    size:			    this.properties.size,
                    oldName:            this.properties.oldName,
                    extension:          this.properties.extension,
                    mime:               this.properties.mime,
                    isImage:            this.properties.isImage,
					coords:				this.properties.coords,
                    cropWidth:			this.options.crop.width,
                    cropHeight:			this.options.crop.height,
                    aspectRatio:		this.options.crop.aspectRatio,
					tmpFolder:			this.options.tmpFolder,
					folder:				this.options.folder,
					row:				this.properties.row,
					roh:				this.properties.roh,
					outputExtension:	this.options.outputExtension,
					sizes:				this.options.sizes
				},
				success: function(data){
					if(data.success) {
						$.colorbox.close();
						this.callback(data);
					}
				}
			});
		},

		executeResize: function()
		{
			$.ajax({
				url:					this.options.urlPlugin+'/getfile/php/Controllers/server.php',
				type:		   			'POST',
				dataType:	   			'json',
				context:				this,
				cache:					false,
				data: {
					action:					'resize',
					tmpName:				this.properties.tmpName,
                    size:			        this.properties.size,
                    oldName:                this.properties.oldName,
                    extension:              this.properties.extension,
                    mime:                   this.properties.mime,
                    isImage:                this.properties.isImage,
					tmpFolder:				this.options.tmpFolder,
					folder:					this.options.folder,
					width:					this.options.resize.width,
					height:					this.options.resize.height,
					constrainProportions:	this.options.resize.constrainProportions,
					outputExtension:		this.options.outputExtension,
					sizes:					this.options.sizes
				},
				success: function(data){
					if(data.success){
						this.callback(data);
					}
				}
			});
		},

		executeChangeExtension: function(imageUrl)
		{
			var img = new Image();
			img.src = imageUrl;														// Image is loaded to get its original width and height

			$.ajax({
				url:					this.options.urlPlugin+'/getfile/php/Controllers/server.php',
				type:					'POST',
				dataType:				'json',
				context:				this,
				cache:		  			false,
				data: {
					action:					'change',
					tmpName:				this.properties.tmpName,
					size:			        this.properties.size,
                    oldName:                this.properties.oldName,
                    extension:              this.properties.extension,
                    mime:                   this.properties.mime,
                    isImage:                this.properties.isImage,
                    tmpFolder:				this.options.tmpFolder,
					folder:					this.options.folder,
					row:					this.properties.row,
					roh:					this.properties.roh,
					outputExtension:		this.options.outputExtension
				},
				success: function(data){
					if(data.success){
						this.callback(data);
					}
				}
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
	$.fn.getFile = function(options, callback) {
		return this.each(function() {
			if (!$.data(this, 'getFile')) {
				$.data(this, 'getFile', Object.create(GetFile).init(options, callback, this));
			}
		});
	};
}( jQuery ));