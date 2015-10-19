/*
 *	GetFile v4.3.1 - 2015-10-06
 *	jQuery Upload Plugin
 *	(c) 2015 Syscover S.L. - http://www.syscover.com/
 *	All rights reserved
 */

"use strict";

(function ($) {
	var GetFile = {
		options: {
			urlPlugin:					'.',		                                // URL relative where is Get File plugin folder
			folder:						null,										// Path to the target folder (from the document root)
            tmpFolder:					null,										// Path to the temporary folder (from the document root)
            srcFolder:					null,										// Path to the temporary folder when load image from source (from the document root)
            srcFile:					null,		                                // Value to crop any image from your server
			encryption:					false,										// File name encryption
			filename:					null,										// Desired final file name, without extension
			outputExtension:			null,										// Conversion to a image format (gif|jpg|png)
            quality:                    100,                                        // Quality image if change extension, 0 to 100, only to jpg image
            multiple:                   false,                                      // set multiple files upload
            maxFileSize:                false,                                      // set maximun file size in bytes for each file uploaded
            maxFilesSizes:              false,                                      // set maximun file size in bytes for all uploads
            activateTmpDelete:          true,                                       // activate delete of temp files
            spinner:                    true,                                       // Use splinner while upload files
            mimesAccept:				[											// Accepted file types
				'image/*',
				'video/*',
				'audio/*',
                'text/plain',
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
                quality:                100,                                        // Quality image, 0 to 100, only to jpg image
                overwrite:              false,                                      // Overwrite file if exist
                aspectRatio:			null,									    // Crop window aspect ratio, if the width and height are instance, aspectRatio not take
                setData:                null                                        // Set default crop area, object
                                                                                    // {x: the offset left of the cropped area,
                                                                                    //  y: the offset top of the cropped area,
                                                                                    //  width: the width of the cropped area,
                                                                                    //  height: the height of the cropped area,
                                                                                    //  rotate: the rotated degrees of the image}
			},
			resize: {
				active:					false,										// Resize function is active
				width:					null,										// Final image width
				height:					null,										// Final image height
                quality:                75,                                         // Quality image, 0 to 100, only to jpg image
				constrainProportions:	true										// Keeps image proportions
			},
            copies: [
                {
                    width:					null,
                    height:					null,
                    quality:                75,                                         // Quality image, 0 to 100, only to jpg image
                    constrainProportions:	true,
                    prefix:					null,
                    folder:					null,
                    outputExtension:		null
                }
            ],
            lang: {
                cropWindowTitle:		'Crop image',
                previewTitle:			'Preview',
                cropButtonText:			'Crop',
                cancelButtonText:		'Cancel'
            },
            selectorItems: {
                container:		        '#cropper-modal',                           // Selector, crop frame and preview container
                cropButton:			    '#gfCropButton',                            // Selector, button that starts the crop process
                cancelButton:			'#gfCancelButton',                          // Selector, button that closes the crop window
                crop:		            '.img-container',                           // Selector, frame that contains the crop
                image:                  '.img-original',                            // Selector, Original image
                preview:		        '.img-preview'                              // Selector, frame that contains the preview
            }
		},

		items: {
			container:				null,							                // Crop frame and preview container
			cropButton:				null,								            // Button that starts the crop process
			cancelButton:			null,							                // Button that closes the crop window
			crop:					null,						                    // Frame that contains the crop
            image:					null,						                    // Original image
			preview:				null					                        // Frame that contains the preview
		},

		properties: {
            files:                  null,                                           // Variable to set multiple files response
			coords:					null,											// Internal use variable
			tmpDelete:				345600,											// Minimum time that the temporary files are kept (by default, 345600 seconds = 4 days)
			tmpName:				'',											 	// Name of the file copied into the temporary folder from server
            oldName:                null,                                           // Original name of file
            extension:              null,                                           // Original extension of file
            mime:                   null,                                           // Original type mime of file
            size:                   null,                                           // Size from image uploaded
            isImage:                null,                                           // Check if is image file uploaded
            width:                  null,                                           // Original image width
            height:                 null,                                           // Original image height
            pixelRatio:             null,                                           // Pixel ratio from screen
            src:                    null,                                           // Source of file
            response:               null                                            // Variable where contain error response
		},

		callback: null,

		init: function(options, callback, element)
		{
            var that = this;

            // set option plugin
            this.setOptions(options);

            var dataRequest = {};
            dataRequest.urlPlugin = options.urlPlugin;

            // Get HTML popup
            $.ajax({
                cache:      false,
                dataType:   'html',
                type:       'POST',
                url:        this.options.urlPlugin + '/getfile/views/popup.php',
                data:       dataRequest,
                success:  function(data)
                {
                    // Load popup if container haven't elements
                    if(that.options.crop.active && $(that.options.selectorItems.container).length == 0)
                    {
                        $('body').append(data);
                    }

                    //load items from popup
                    that.items = {
                        container:				$(that.options.selectorItems.container),
                        cropButton:				$(that.options.selectorItems.cropButton),
                        cancelButton:			$(that.options.selectorItems.cancelButton),
                        crop:					$(that.options.selectorItems.crop),
                        image:                  $(that.options.selectorItems.image),
                        preview:				$(that.options.selectorItems.preview)
                    }

                    that.items.cancelButton.on('click', function() {$('#cropper-modal').modal('hide')});

                    if(that.options.srcFolder != null)
                    {
                        that.loadSrc();
                    }
                },
                error:function(data)
                {
                    var data = {
                        success: false,
                        error:   12,
                        message: 'The path: ' + that.url + ' in the ajax request is not correct, please check the ajax function data'
                    };
                    callback(data);
                }
            });


            // The mimesAccept property is overwritten, since we don't want it to be combined
            if(options.mimesAccept)
            {
                this.options.mimesAccept = options.mimesAccept;
            }

            // Callback instantiation
            this.callback = callback;

            // Save the element reference, both as a jQuery reference and a normal reference
            // Instance of the jQuery object to which the plugin is applied
            this.element = $(element);

            // check if plugin is called from element or with set image source
            if(this.options.srcFolder == null)
            {
                if(this.options.multiple)
                {
                    var args = {
                        multiple: true,
                        logging: false
                    };
                }
                else
                {
                    var args = {
                        logging: false
                    };
                }

                // Upload button and event
                if(this.options.mimesAccept == false || Object.prototype.toString.call(this.options.mimesAccept) === '[object Array]')
                {
                    window.fd.logging = false;
                    fd.jQuery();

                    $(this.element).filedrop(args).filedrop().event('send', function(fileList) {
                        that.upload(fileList);
                    });
                }
                else
                {
                    var data = {
                        success: false,
                        error:   11,
                        message: 'The mimesAccept setting must be an array or false. Setting it to false might be dangerous, since it means accepting all file types'
                    };

                    if(this.callback != null)
                    {
                        this.callback(data);
                    }
                }
            }

            return this;
		},

        setOptions: function(options)
        {
            var that = this;

            // extend options.copies
            if(options.copies == undefined)
            {
                this.options.copies = [];
            }
            else
            {
                $.each(options.copies, function(i, copie){
                    options.copies[i] = $.extend({}, that.options.copies[0], copie||{});
                });
            }

            // extend options.crop
            if(options.crop != undefined)
            {
                options.crop = $.extend({}, this.options.crop, options.crop||{});
            }

            // Options load
            this.options = $.extend({}, this.options, options||{});
        },

        setLangPopup: function(){
            // set lang on popup
            $('#gfCropWindowTitle').html(this.options.lang.cropWindowTitle);
            $('#gfPreviewTitle').html(this.options.lang.previewTitle);
            $('#gfCropButton').html(this.options.lang.cropButtonText);
            $('#gfCancelButton').html(this.options.lang.cancelButtonText);
        },

		upload: function(fileList)
		{
            var that = this;

            if(this.options.spinner)
            {
                $.cssLoader.show({
                    urlPlugin: this.options.urlPlugin + '/getfile/libs',
                    useLayer: false,
                    theme: 'carousel'
                });
            }

            // Object which will contain the data that will be sent
			var data = new FormData();

			// Create the form to send by ajax
            if(this.options.multiple)
            {
                var filesSizes = 0;
                fileList.each(function(file)
                {
                    filesSizes += file.size;
                });

                if((this.options.maxFilesSizes != null && this.options.maxFilesSizes != false) && filesSizes > this.options.maxFilesSizes)
                {
                    if(this.options.spinner) $.cssLoader.hide();

                    //Error, file bigger than allowed
                    var realMb  = filesSizes / 1048576;
                    var limitMb = this.options.maxFilesSizes / 1048576;

                    var response = {
                        success: false,
                        error:   16,
                        message: 'The files whith weighing ' + realMb.toFixed(2) + ' Mb they does not been uploaded to the server, to exceed the maximum files sizes allowed of ' + limitMb.toFixed(2) + ' Mb'
                    };

                    this.callback(response);

                    return false;
                }

                var nFiles = 0;
                var response = [];
                fileList.each(function(file)
                {
                    if(((that.options.maxFileSize != null && that.options.maxFileSize != false) && file.size > that.options.maxFileSize))
                    {
                        var realMb  = file.size / 1048576;
                        var limitMb = that.options.maxFileSize / 1048576;

                        response.push({
                            success: false,
                            error:   15,
                            message: 'The file ' + file.name + ' weighing ' + realMb.toFixed(2) + ' Mb has not been uploaded to the server, to exceed the maximum file size allowed of ' + limitMb.toFixed(2) + ' Mb'
                        });
                    }
                    else
                    {
                        data.append('file' + nFiles, file.nativeFile);
                        nFiles++;
                    }
                });

                if(nFiles > 0)
                {
                    if (response.length > 0)
                    {
                        this.properties.response = response;
                    }
                    data.append('nFiles', nFiles);
                }
                else
                {
                    if(this.options.spinner) $.cssLoader.hide();

                    response = {
                        "success":  false,
                        "multiple": true,
                        "files":    response
                    }
                    this.callback(response);
                    return false;
                }
            }
            else
            {
                var file = fileList.first();

                if(((this.options.maxFileSize != null && this.options.maxFileSize != false) && file.size > this.options.maxFileSize) || ((this.options.maxFilesSizes != null && this.options.maxFilesSizes != false) && file.size > this.options.maxFilesSizes))
                {
                    if(this.options.spinner) $.cssLoader.hide();

                    //Error, file bigger than allowed
                    var realMb  = event.target.files[0].size / 1048576;
                    var limitMb = this.options.maxFileSize / 1048576;

                    var response = {
                        success: false,
                        error:   15,
                        message: 'The file ' + event.target.files[0].name + ' weighing ' + realMb.toFixed(2) + ' Mb has not been uploaded to the server, to exceed the maximum file size allowed of ' + limitMb.toFixed(2) + ' Mb'
                    };

                    this.callback(response);

                    return false;
                }
                else
                {
                    data.append('file',	file.nativeFile);
                }
            }

            data.append('multiple',			    this.options.multiple);
            data.append('action',			    'upload');
			data.append('folder',			    this.options.folder);
			data.append('tmpFolder',		    this.options.tmpFolder);
			data.append('cropActive',		    this.options.crop.active);
            data.append('cropOverwrite',		this.options.crop.overwrite);
            data.append('resizeActive',		    this.options.resize.active);
            data.append('outputExtension',	    this.options.outputExtension);
			data.append('encryption',		    this.options.encryption);
			data.append('filename',			    this.options.filename);
			data.append('mimesAccept',		    this.options.mimesAccept);
            data.append('activateTmpDelete',    this.options.activateTmpDelete);
			data.append('tmpDelete',		    this.properties.tmpDelete);

			$.ajax({
				url:						this.options.urlPlugin + '/getfile/php/Controllers/server.php',
				data:						data,
				type:						'POST',
				dataType:					'json',
				cache:						false,
				contentType:				false,
				processData:				false,
                xhr: function()
                {
                    var xhr = new window.XMLHttpRequest();

                    //Upload progress
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable)
                        {
                            //Do something with upload progress
                            if(that.callback != null)
                            {
                                var percentage = (evt.loaded / evt.total * 100 | 0);

                                var data = {
                                    success:    true,
                                    action:     "loading",
                                    total:      evt.total,
                                    loaded:     evt.loaded,
                                    percentage: percentage
                                };

                                that.callback(data);
                            }
                        }
                    }, false);

                    return xhr;
                },
                error:function(e)
                {
                    if(that.options.spinner) $.cssLoader.hide();

                    if(that.options.multiple)
                    {
                        var data = [];
                        $.each(fileList, function(index, file)
                        {
                            var realMb = file.size / 1048576;
                            var dataError = {
                                success:    false,
                                error:      13,
                                message:    'The file ' + file.name + ' weighing ' + realMb.toFixed(2) + ' Mb has not been uploaded to the server, to exceed the maximum file size allowed to server: ' + that.phpIni.postMaxSize,
                                exception:  e
                            };
                            data.push(dataError);
                        });

                        that.callback(data);
                    }
                    else
                    {
                        var file    = fileList.first();
                        var realMb  = file.size / 1048576;

                        var data = {
                            success:    false,
                            error:      13,
                            message:    'The file ' + file.name + ' weighing ' + realMb.toFixed(2) + ' Mb has not been uploaded to the server, to exceed the maximum file size allowed to server:' + that.phpIni.postMaxSize,
                            exception:  e
                        };
                        that.callback(data);
                    }
                },
                // Actions done once the image is in the tmp directory
				success: function(data)
				{
                    // throw event getfile:afterUpload
                    that.element.trigger('getfile:afterUpload', {
                        success: true,
                        data: data
                    });

                    if(that.options.multiple)
                    {
                        that.properties.files       = data.files;
                    }
                    else
                    {
                        that.properties.tmpName     = data.name;					    // Temporary name assigned to the file
                        that.properties.size        = data.size;                        // Size from image uploaded
                        that.properties.oldName     = data.oldName;                     // Original name from image uploaded
                        that.properties.extension   = data.extension;                   // Extension from file
                        that.properties.mime        = data.mime;                        // MIME type from file
                        that.properties.isImage     = data.isImage;                     // Check if is image
                    }

                    if(that.options.crop.active && data.isImage && that.options.multiple == false)
					{
                        that.element.one('getfile:beforeCrop', function(event){

                            if(!event.isDefaultPrevented())
                            {
                                that.properties.width       = data.width;                   // Original image width if is image crop
                                that.properties.height      = data.height;                  // Original image height if is image crop
                                that.properties.pixelRatio  = window.devicePixelRatio;

                                $('#cropper-modal').off('shown.bs.modal').on('shown.bs.modal', function () {
                                    that.crop(that.options.tmpFolder + '/' + data.name);
                                }).off('hidden.bs.modal').on('hidden.bs.modal', function () {
                                    // destroy elements after crop
                                    $(that.items.image).prop('src', '');
                                    $(that.items.image).cropper('destroy');
                                });

                                // off click to avoid duplicity event
                                that.items.cropButton.off('click').on('click', function () {
                                    that.executeCrop();
                                });

                                that.setLangPopup();
                                $('#cropper-modal').modal('show');
                            }
                        });

                        // throw event getfile:beforeCrop window
                        that.element.trigger('getfile:beforeCrop', {
                            success: true,
                            data: data
                        });
					}
					else if(that.options.resize.active && data.isImage || that.options.resize.active && that.options.multiple == true)
					{
                        that.element.one('getfile:beforeResize', function(event){
                            if(!event.isDefaultPrevented())
                            {
                                if (that.options.multiple)
                                {
                                    var hasImage = false;
                                    $.each(that.properties.files, function (i, file) {
                                        if (file.isImage == true) {
                                            hasImage = true;
                                            that.executeResize();
                                            return false;
                                        }
                                    });

                                    // Execution is finished and callback is called if hasn't image
                                    if (!hasImage && that.callback != null) that.callback(data);
                                }
                                else
                                {
                                    // Resize module is executed if active
                                    that.executeResize();
                                }
                            }
                        });

                        // throw event getfile:beforeResize
                        that.element.trigger('getfile:beforeResize', {
                            success: true,
                            data: data
                        });
					}
					else if(that.options.outputExtension != null && data.isImage || that.options.outputExtension != null && that.options.multiple == true)	// Image file extension change
					{
                        if(that.options.multiple)
                        {
                            var hasImage = false;
                            $.each(that.properties.files, function(i, file){
                                if(file.isImage == true)
                                {
                                    hasImage = true;
                                    that.executeChangeExtension();
                                    return false;
                                }
                            });

                            // Execution is finished and callback is called if hasn't image
                            if(!hasImage && that.callback != null) that.callback(data);
                        }
                        else
                        {
                            // Resize module is executed if active
                            that.executeChangeExtension();
                        }
					}
                    else if(that.options.copies.length > 0 && data.isImage || that.options.copies.length > 0 && that.options.multiple == true)
                    {
                        if(that.options.multiple)
                        {
                            var hasImage = false;
                            $.each(that.properties.files, function(i, file){
                                if(file.isImage == true)
                                {
                                    hasImage = true;
                                    that.executeCopies(data);
                                    return false;
                                }
                            });

                            // Execution is finished and callback is called if hasn't image
                            if(!hasImage && that.callback != null) that.callback(data);
                        }
                        else
                        {
                            // Resize module is executed if active
                            that.executeCopies(data);
                        }
                    }
					else
					{
                        if(that.callback != null)
                        {
                            if(that.options.multiple)
                            {
                                $.merge(data.files, response);
                            }
                            // Execution is finished and callback is called
                            that.callback(data);
                        }
					}
                    // Hide loader
                    if(that.options.spinner) $.cssLoader.hide();
				}
			});
		},

        loadSrc: function()
        {
            var that = this;
            this.options.tmpFolder  = this.options.srcFolder;
            this.properties.src     = this.options.tmpFolder + '/' + this.options.srcFile;

            $('#cropper-modal').off('shown.bs.modal').on('shown.bs.modal', function () {
                that.crop(that.properties.src);
            }).off('hidden.bs.modal').on('hidden.bs.modal', function () {
                // destroy elements after crop
                $(that.items.image).prop('src', '');
                $(that.items.image).cropper('destroy');
            });

            $.ajax({
                url: this.options.urlPlugin + '/getfile/php/Controllers/server.php',
                data: {
                    action: 'getinfosrc',
                    src:    that.properties.src
                },
                type: 'POST',
                dataType: 'json',
                cache: false,
                success: function (data)
                {
                    that.element.one('getfile:beforeCrop', function(event){

                        if(!event.isDefaultPrevented())
                        {
                            that.properties.tmpName = data.oldName;				    // Temporary name assigned to the file
                            that.properties.size = data.size;                       // Size from image uploaded
                            that.properties.oldName = data.oldName;                 // Original name from image uploaded
                            that.properties.extension = data.extension;             // Extension from file
                            //that.properties.mime = data.mime;                       // MIME type from file
                            //that.properties.isImage = data.isImage;                 // Check if is image
                            that.properties.width = data.width;                     // Original image width if is image crop
                            that.properties.height = data.height;                   // Original image height if is image crop
                            that.properties.pixelRatio = window.devicePixelRatio;

                            // off click to avoid duplicity event
                            that.items.cropButton.off('click').on('click', function () {
                                that.executeCrop()
                            });

                            that.setLangPopup();
                            $('#cropper-modal').modal('show');
                        }
                    });

                    // throw event getfile:beforeCropwindow
                    that.element.trigger('getfile:beforeCrop', {
                        success: true,
                        data: data
                    });
                }
            });
        },

		crop: function(imageUrl)
		{
            var that = this;

            if (!$.fn.cropper)
            {
				var data = {
					success: false,
					error:   3,
					message: 'Cropper library not loaded'
				};

                if(this.callback != null)
                {
                    this.callback(data);
                }
			}

            // Check if values is correct
            // Checking if width and height are defined; in case of being defined, they're used for aspect ratio
            if(this.options.crop.height && this.options.crop.width)
            {
                this.options.crop.aspectRatio = this.options.crop.width / this.options.crop.height;
            }
            else
            {
                // If there is no width or height and there is no aspect ratio, an error is thrown
                if(isNaN(this.options.crop.aspectRatio))
                {
                    var data = {
                        success: false,
                        error:   4,
                        message: 'Width and Height or Ratio must be defined'
                    };

                    if(this.callback != null)
                    {
                        this.callback(data);
                    }
                }
            }

            $(this.items.image).prop('src', imageUrl);

            // start cropper
            $(this.items.image).cropper({
                aspectRatio:    this.options.crop.aspectRatio,
                preview:        '.img-preview',
                strict:         true,
                built: function () {
                    if(that.options.crop.setData != null)
                    {
                        $(this).cropper('setData', that.options.crop.setData);
                    }
                },
                crop: function(data)
                {
                    that.properties.coords = {
                        x: Math.round(data.x),
                        y: Math.round(data.y),
                        width: Math.round(data.width),
                        height: Math.round(data.height),
                        rotate: Math.round(data.rotate)
                    }
                }
            });

            var isIE11 = !!navigator.userAgent.match(/Trident.*rv[ :]*11\./)
            if(isIE11)
            {
                this.properties.tmpNameIE11     = this.properties.tmpName;
                this.properties.sizeIE11        = this.properties.size;
                this.properties.oldNameIE11     = this.properties.oldName;
                this.properties.extensionIE11   = this.properties.extension;
                this.properties.mimeIE11        = this.properties.mime;
                this.properties.isImageIE11     = this.properties.isImage;
            }
		},

		executeCrop: function()
		{
            var that = this;

            $.ajax({
				url:					this.options.urlPlugin + '/getfile/php/Controllers/server.php',
				type:		   			'POST',
				dataType:	   			'json',
				cache:					false,
                data: {
					action:				'crop',
                    filename:			this.options.filename,
                    tmpName:			this.properties.tmpName == undefined? this.properties.tmpNameIE11 : this.properties.tmpName,
                    size:			    this.properties.size == undefined? this.properties.sizeIE11 : this.properties.size,
                    oldName:            this.properties.oldName == undefined? this.properties.oldNameIE11 : this.properties.oldName,
                    extension:          this.properties.extension == undefined? this.properties.extensionIE11 : this.properties.extension,
                    mime:               this.properties.mime == undefined? this.properties.mimeIE11 : this.properties.mime,
                    isImage:            this.properties.isImage == undefined? this.properties.isImageIE11 : this.properties.isImage,
                    coords:				this.properties.coords,
                    cropWidth:			this.options.crop.width,
                    cropHeight:			this.options.crop.height,
                    quality:            this.options.crop.quality,
                    overwrite:          this.options.crop.overwrite,
                    aspectRatio:		this.options.crop.aspectRatio,
					tmpFolder:			this.options.tmpFolder,
					folder:				this.options.folder,
					outputExtension:	this.options.outputExtension,
					copies:				this.options.copies
				},
				success: function(data)
                {
                    $('#cropper-modal').modal('hide');

                    // throw event getfile:afterCrop
                    that.element.trigger('getfile:afterCrop', {
                        success: true,
                        data: data
                    });

                    if(that.callback != null)
                    {
                        that.callback(data);
                    }
				},
                error: function(data)
                {
                    if(that.callback != null)
                    {
                        that.callback(data.responseJSON);
                    }
                }
			});
		},

        showCropWindow: function()
        {
            var that = this;

            that.element.one('getfile:beforeCrop', function(event){

                if(!event.isDefaultPrevented())
                {
                    // off click to avoid duplicity event
                    that.items.cropButton.off('click').on('click', function(){
                        that.executeCrop();
                    });

                    that.setLangPopup();
                    $('#cropper-modal').modal('show');
                }
            });

            // throw event getfile:beforeCrop window
            that.element.trigger('getfile:beforeCrop', {
                success: true,
                data: that.properties
            });
        },

		executeResize: function()
		{
            var that = this;

            if(this.options.multiple)
            {
                var data = {
                    action:					'resize',
                    multiple:               true,
                    files:				    this.properties.files,
                    tmpFolder:				this.options.tmpFolder,
                    folder:					this.options.folder,
                    quality:                this.options.resize.quality,
                    width:					this.options.resize.width,
                    height:					this.options.resize.height,
                    constrainProportions:	this.options.resize.constrainProportions,
                    outputExtension:		this.options.outputExtension,
                    copies:					this.options.copies
                };
            }
            else
            {
                var data = {
                    action:					'resize',
                    multiple:               false,
                    tmpName:				this.properties.tmpName,
                    size:			        this.properties.size,
                    oldName:                this.properties.oldName,
                    extension:              this.properties.extension,
                    mime:                   this.properties.mime,
                    isImage:                this.properties.isImage,
                    tmpFolder:				this.options.tmpFolder,
                    folder:					this.options.folder,
                    quality:                this.options.resize.quality,
                    width:					this.options.resize.width,
                    height:					this.options.resize.height,
                    constrainProportions:	this.options.resize.constrainProportions,
                    outputExtension:		this.options.outputExtension,
                    copies:					this.options.copies
                };
            }

			$.ajax({
				url:					this.options.urlPlugin+'/getfile/php/Controllers/server.php',
				type:		   			'POST',
				dataType:	   			'json',
				cache:					false,
				data:                   data,
				success: function(data)
                {
                    // throw event getfile:afterResize
                    that.element.trigger('getfile:afterResize', {
                        success: true,
                        data: data
                    });

                    if(that.callback != null)
                    {
                        if(that.options.multiple && that.properties.response != null)
                        {
                            $.merge(data.files, that.properties.response);
                        }
                        that.callback(data);
                    }
				},
                error: function(data)
                {
                    if(that.callback != null)
                    {
                        that.callback(data.responseJSON);
                    }
                }
			});
		},

		executeChangeExtension: function()
		{
            var that = this;

            if(this.options.multiple)
            {
                var data = {
                    action:					'change',
                    multiple:               true,
                    files:				    this.properties.files,
                    tmpFolder:				this.options.tmpFolder,
                    folder:					this.options.folder,
                    outputExtension:		this.options.outputExtension,
                    quality:                this.options.quality,
                    copies:					this.options.copies
                };
            }
            else
            {
                var data = {
                    action:					'change',
                    multiple:               false,
                    tmpName:				this.properties.tmpName,
                    size:			        this.properties.size,
                    oldName:                this.properties.oldName,
                    extension:              this.properties.extension,
                    mime:                   this.properties.mime,
                    isImage:                this.properties.isImage,
                    tmpFolder:				this.options.tmpFolder,
                    folder:					this.options.folder,
                    outputExtension:		this.options.outputExtension,
                    quality:                this.options.quality,
                    copies:					this.options.copies
                };
            }

			$.ajax({
				url:					this.options.urlPlugin+'/getfile/php/Controllers/server.php',
				type:					'POST',
				dataType:				'json',
				cache:		  			false,
				data:                   data,
				success: function(data)
                {
                    // throw event getfile:afterChangeExtension
                    that.element.trigger('getfile:afterChangeExtension', {
                        success: true,
                        data: data
                    });

                    if(that.callback != null)
                    {
                        if(that.options.multiple && that.properties.response != null)
                        {
                            $.merge(data.files, that.properties.response);
                        }
                        that.callback(data);
                    }
				},
                error: function(data)
                {
                    if(that.callback != null)
                    {
                        that.callback(data.responseJSON);
                    }
                }
			});
		},

        executeCopies: function(data)
        {
            var that = this;

            data.action     = 'copies';
            data.folder     = this.options.folder;
            data.copies     = this.options.copies;

            if(this.options.multiple)
            {
                data.multiple   = true;
                data.files      = this.properties.files;
            }
            else
            {
                data.multiple   = false;
            }

            $.ajax({
                url:					this.options.urlPlugin + '/getfile/php/Controllers/server.php',
                type:					'POST',
                dataType:				'json',
                cache:		  			false,
                data:                   data,
                success: function(data)
                {
                    if(that.callback != null)
                    {
                        if(that.options.multiple && that.properties.response != null)
                        {
                            $.merge(data.files, that.properties.response);
                        }
                        that.callback(data);
                    }
                },
                error: function(data)
                {
                    if(that.callback != null)
                    {
                        that.callback(data.responseJSON);
                    }
                }
            });
        },

        delete: function($filenames, callback)
        {
            $.ajax({
                url:					this.options.urlPlugin + '/getfile/php/Controllers/server.php',
                type:		   			'POST',
                dataType:	   			'json',
                cache:					false,
                data: {
                    action:				'delete',
                    filenames:          $filenames
                },
                success: function(data)
                {
                    if(callback != null)
                    {
                        callback(data);
                    }
                }
            });
        }
	};

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

	/*
	 * Start the plugin
	 */
    $.fn.getFile = function(options, callback) {

        if(!GetFile.hasOwnProperty('phpIni'))
        {
            // Get PHP ini Values
            $.ajax({
                url:        options.urlPlugin + '/getfile/php/Controllers/server.php',
                type:		'POST',
                dataType:	'json',
                cache:		false,
                data: {
                    action: 'getvars'
                },
                success:  function(data)
                {
                    GetFile.phpIni = data;
                }
            });
        }

        if (!this.data('getFile'))
        {
            return this.data('getFile', Object.create(GetFile).init(options, callback, this));
        }
        else
        {
            return this.data('getFile');
        }
    };

    $.getFile = function(options, callback) {

        if(!GetFile.hasOwnProperty('phpIni'))
        {
            // Get PHP ini Values
            $.ajax({
                url:        options.urlPlugin + '/getfile/php/Controllers/server.php',
                type:		'POST',
                dataType:	'json',
                cache:		false,
                data: {
                    action: 'getvars'
                },
                success:  function(data)
                {
                    GetFile.phpIni = data;
                }
            });
        }
        
        $.data(document, 'getFile', Object.create(GetFile).init(options, callback, document));

        return $(document);
    };
}( jQuery ));