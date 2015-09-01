/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.POPUP_TEMPLATES, {
    'image.insert': '[_BUTTONS_][_UPLOAD_LAYER_][_BY_URL_LAYER_][_PROGRESS_BAR_]',
    'image.edit': '[_BUTTONS_]',
    'image.alt': '[_BUTTONS_][_ALT_LAYER_]',
    'image.size': '[_BUTTONS_][_SIZE_LAYER_]'
  })

  $.extend($.FroalaEditor.DEFAULTS, {
    imageInsertButtons: ['imageBack', '|', 'imageUpload', 'imageByURL'],
    imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize'],
    imageAltButtons: ['imageBack', '|'],
    imageSizeButtons: ['imageBack', '|'],
    imageUploadURL: 'http://i.froala.com/upload',
    imageUploadParam: 'file',
    imageUploadParams: {},
    imageUploadToS3: false,
    imageUploadMethod: 'POST',
    imageMaxSize: 10 * 1024 * 1024,
    imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],
    imageResize: true,
    imageResizeWithPercent: false,
    imageMove: true,
    imageDefaultWidth: '300',
    imageDefaultAlign: 'center',
    imageDefaultDisplay: 'block',
    imageStyles: {
      'fr-rounded': 'Rounded',
      'fr-bordered': 'Bordered'
    },
    imageMultipleStyles: true,
    imageTextNear: true,
    imagePaste: true,
    imageIframeStyle: 'body img {max-width:100%;}body img{z-index:3;position:relative;overflow:auto;cursor:pointer}body img.fr-dib{margin:auto;display:block;float:none;vertical-align:top}body img.fr-dib.fr-fil{margin:5px auto 5px 0}body img.fr-dib.fr-fir{margin:5px 0 5px auto}body img.fr-dii{margin:auto;display:inline-block;float:none}body img.fr-dii.fr-fil{margin:5px 5px 5px 0;float:left}body img.fr-dii.fr-fir{margin:5px 0 5px 5px;float:right}'
  });

  $.FroalaEditor.PLUGINS.image = function (editor) {
    var $current_image;
    var $image_resizer;
    var $handler;
    var $overlay;

    var BAD_LINK = 1;
    var MISSING_LINK = 2;
    var ERROR_DURING_UPLOAD = 3;
    var BAD_RESPONSE = 4;
    var MAX_SIZE_EXCEEDED = 5;
    var BAD_FILE_TYPE = 6;
    var NO_CORS_IE = 7;

    var error_messages = {};
    error_messages[BAD_LINK] = 'Image cannot be loaded from the passed link.',
    error_messages[MISSING_LINK] = 'No link in upload response.',
    error_messages[ERROR_DURING_UPLOAD] = 'Error during file upload.',
    error_messages[BAD_RESPONSE] = 'Parsing response failed.',
    error_messages[MAX_SIZE_EXCEEDED] = 'File is too large.',
    error_messages[BAD_FILE_TYPE] = 'Image file type is invalid.',
    error_messages[NO_CORS_IE] = 'Files can be uploaded only to same domain in IE 8 and IE 9.'

    /**
     * Refresh the image insert popup.
     */
    function _refreshInsertPopup () {
      var $popup = editor.popups.get('image.insert');

      var $url_input = $popup.find('.fr-image-by-url-layer input');
      $url_input.val('');

      if ($current_image) {
        $url_input.val($current_image.attr('src'));
      }

      $url_input.trigger('change');
    }

    /**
     * Show the image upload popup.
     */
    function showInsertPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="insertImage"]');

      var $popup = editor.popups.get('image.insert');
      if (!$popup) $popup = _initInsertPopup();

      hideProgressBar();
      if (!$popup.hasClass('fr-active')) {
        editor.popups.refresh('image.insert');
        editor.popups.setContainer('image.insert', editor.$tb);

        if (editor.$el.is(':focus')) {
          editor.selection.save();
        }

        if ($btn.is(':visible')) {
          var left = $btn.offset().left + $btn.outerWidth() / 2;
          var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
          editor.popups.show('image.insert', left, top, $btn.outerHeight());
        }
        else {
          editor.popups.show('image.insert');
          editor.position.forSelection($popup);
        }
      }
    }

    /**
     * Show the image edit popup.
     */
    function _showEditPopup () {
      var $popup = editor.popups.get('image.edit');
      if (!$popup) $popup = _initEditPopup();

      editor.popups.setContainer('image.edit', $('body'));
      editor.popups.refresh('image.edit');
      var left = $current_image.offset().left + $current_image.outerWidth() / 2;
      var top = $current_image.offset().top + $current_image.outerHeight();

      editor.popups.show('image.edit', left, top, $current_image.outerHeight());
    }

    /**
     * Hide image upload popup.
     */
    function _hideInsertPopup () {
      if (!$current_image && editor.$el.find('.fr-marker').length) {
        editor.events.focus();
        editor.selection.restore();
      }
      hideProgressBar();
    }

    /**
     * Hide image edit popup.
     */
    function _hideEditPopup () {
      editor.popups.hide('image.edit');
    }

    /**
     * Convert classes to style.
     */
    function _convertClassesToStyle ($img) {
      var style = {
        'z-index': 1,
        position: 'relative',
        overflow: 'auto'
      };

      if ($img.hasClass('fr-dib')) {
        $.extend(style, {
          'vertical-align': 'top',
          display: 'block'
        });

        if ($img.hasClass('fr-fir')) {
          $.extend(style, {
            'float': 'none',
            'margin-right': '0',
            'margin-left': 'auto'
          });
        }
        else if ($img.hasClass('fr-fil')) {
          $.extend(style, {
            'float': 'none',
            'margin-left': '0',
            'margin-right': 'auto'
          });
        }
        else {
          $.extend(style, {
            'float': 'none',
            'margin': 'auto'
          });
        }
      }
      else {
        $.extend(style, {
          'display': 'inline-block'
        });

        if ($img.hasClass('fr-fir')) {
          $.extend(style, {
            'float': 'right'
          });
        }
        else if ($img.hasClass('fr-fil')) {
          $.extend(style, {
            'float': 'left'
          });
        }
        else {
          $.extend(style, {
            'float': 'none'
          });
        }
      }

      $img.removeClass('fr-dib fr-dii fr-fir fr-fil fr-fin');
      if ($img.attr('class') == '') $img.removeAttr('class');

      $img.css(style);
    }

    /**
     * Convert style to classes.
     */
    function _convertStyleToClasses ($img) {
      if (!$img.hasClass('fr-dii') && !$img.hasClass('fr-dib')) {
        var flt = $img.css('float');
        $img.css('float', 'none');
        if ($img.css('display') == 'block') {
          $img.css('float', flt);
          if (parseInt($img.css('margin-left'), 10) == 0 && ($img.attr('style') || '').indexOf('margin-right: auto') >= 0) {
            $img.addClass('fr-fil');
          }
          else if (parseInt($img.css('margin-right'), 10) == 0 && ($img.attr('style') || '').indexOf('margin-left: auto') >= 0) {
            $img.addClass('fr-fir');
          }

          $img.addClass('fr-dib');
        }
        else {
          $img.css('float', flt);
          if ($img.css('float') == 'left') {
            $img.addClass('fr-fil');
          }
          else if ($img.css('float') == 'right') {
            $img.addClass('fr-fir');
          }

          $img.addClass('fr-dii');
        }

        $img.css('margin', '');
        $img.css('float', '');
        $img.css('display', '');
        $img.css('z-index', '');
        $img.css('position', '');
        $img.css('overflow', '');
        $img.css('vertical-align', '');
      }

      $img.css('width', $img.width());
      $img.removeAttr('width');

      if (!editor.opts.imageTextNear) {
        $img.removeClass('fr-dii').addClass('fr-dib');
      }
    }

    /**
     * Refresh the image list.
     */
    function _refreshImageList () {
      var images = editor.$el.get(0).tagName == 'IMG' ? [editor.$el.get(0)] : editor.$el.get(0).querySelectorAll('img');

      for (var i = 0; i < images.length; i++) {
        _convertStyleToClasses($(images[i]));

        if (editor.opts.iframe) {
          $(images[i]).on('load', editor.size.syncIframe);
        }
      }
    }

    /**
     * Keep images in sync when content changed.
     */
    var images;
    function _syncImages () {
      var c_images =  Array.prototype.slice.call(editor.$el.get(0).querySelectorAll('img'));

      if (images) {
        for (var i = 0; i < images.length; i++) {
          if (c_images.indexOf(images[i]) < 0) {
            editor.events.trigger('image.removed', $(images[i]));
          }
        }
      }

      images = c_images;
    }

    /**
     * Reposition resizer.
     */
    function _repositionResizer () {
      if (!$image_resizer) {
        _initImageResizer();
      }

      var wrap_correction_top = !editor.$wp ? -1 : editor.$wp.scrollTop() - (editor.$wp.offset().top + 1);
      var wrap_correction_left = !editor.$wp ? -1 : editor.$wp.scrollLeft() - (editor.$wp.offset().left + 1);

      $image_resizer
        .css('top', editor.opts.iframe ? $current_image.offset().top - 1 : $current_image.offset().top + wrap_correction_top)
        .css('left', editor.opts.iframe ? $current_image.offset().left - 1 : $current_image.offset().left + wrap_correction_left)
        .css('width', $current_image.outerWidth())
        .css('height', $current_image.outerHeight())
        .addClass('fr-active')
    }

    /**
     * Create resize handler.
     */
    function _getHandler (pos) {
      return '<div class="fr-handler fr-h' + pos + '"></div>';
    }

    /**
     * Mouse down to start resize.
     */
    function _handlerMousedown (e) {
      e.preventDefault();
      e.stopPropagation();

      $handler = $(this);
      $handler.data('start-x', e.pageX || e.originalEvent.touches[0].pageX);
      $overlay.show();
      editor.popups.hideAll();
    }

    /**
     * Do resize.
     */
    function _handlerMousemove (e) {
      if ($handler) {
        e.preventDefault()

        var c_x = e.pageX || (e.originalEvent.touches ? e.originalEvent.touches[0].pageX : null);

        if (!c_x) {
          return false;
        }

        var s_x = $handler.data('start-x');

        $handler.data('start-x', c_x);

        var diff_x = c_x - s_x;

        var width = $current_image.width();
        if ($handler.hasClass('fr-hnw') || $handler.hasClass('fr-hsw')) {
          diff_x = 0 - diff_x;
        }

        if (editor.opts.imageResizeWithPercent) {
          $current_image.css('width', ((width + diff_x) / $current_image.parent().outerWidth() * 100).toFixed(2) + '%');
        }
        else {
          $current_image.css('width', width + diff_x);
        }

        _repositionResizer();

        editor.events.trigger('image.resize', [get()]);
      }
    }

    /**
     * Stop resize.
     */
    function _handlerMouseup (e) {
      if ($handler) {
        if (e) {
          e.stopPropagation();
        }

        $handler = null;
        $overlay.hide();
        _repositionResizer();
        _showEditPopup();

        editor.events.trigger('image.resizeEnd', [get()]);
      }
    }

    /**
     * Throw an image error.
     */
    function _throwError (code, response) {
      editor.edit.on();
      _showErrorMessage(editor.language.translate('Something went wrong. Please try again.'));

      editor.events.trigger('image.error', [{
        code: code,
        message: error_messages[code]
      }, response]);
    }

    /**
     * Init the image edit popup.
     */
    function _initEditPopup () {
      // Image buttons.
      var image_buttons = '';
      if (editor.opts.imageEditButtons.length > 1) {
        image_buttons += '<div class="fr-buttons">';
        image_buttons += editor.button.buildList(editor.opts.imageEditButtons);
        image_buttons += '</div>';
      }

      var template = {
        buttons: image_buttons
      };

      var $popup = editor.popups.create('image.edit', template);

      if (editor.$wp) {
        editor.$wp.on('scroll.image-edit', function () {
          if ($current_image && editor.popups.isVisible('image.edit')) {
            _showEditPopup();
          }
        });

        editor.events.on('destroy', function () {
          editor.$wp.off('scroll.image-edit');
        });
      }

      return $popup;
    }

    /**
     * Show progress bar.
     */
    function showProgressBar () {
      var $popup = editor.popups.get('image.insert');
      $popup.find('.fr-layer.fr-active').removeClass('fr-active').addClass('fr-pactive');
      $popup.find('.fr-image-progress-bar-layer').addClass('fr-active');
      $popup.find('.fr-buttons').hide();

      _setProgressMessage('Uploading', 0);
    }

    /**
     * Hide progress bar.
     */
    function hideProgressBar (dismiss) {
      var $popup = editor.popups.get('image.insert');

      if ($popup) {
        $popup.find('.fr-layer.fr-pactive').addClass('fr-active').removeClass('fr-pactive');
        $popup.find('.fr-image-progress-bar-layer').removeClass('fr-active');
        $popup.find('.fr-buttons').show();

        if (dismiss) {
          editor.popups.show('image.insert', null, null);
        }
      }
    }

    /**
     * Set a progress message.
     */
    function _setProgressMessage (message, progress) {
      var $popup = editor.popups.get('image.insert');
      var $layer = $popup.find('.fr-image-progress-bar-layer');
      $layer.find('h3').text(message + (progress ? ' ' + progress + '%' : ''));

      $layer.removeClass('fr-error');

      if (progress) {
        $layer.find('div').removeClass('fr-indeterminate');
        $layer.find('div > span').css('width', progress + '%');
      }
      else {
        $layer.find('div').addClass('fr-indeterminate');
      }
    }

    /**
     * Show error message to the user.
     */
    function _showErrorMessage (message) {
      var $popup = editor.popups.get('image.insert');
      var $layer = $popup.find('.fr-image-progress-bar-layer');
      $layer.addClass('fr-error')
      $layer.find('h3').text(message);
    }

    /**
     * Insert image using URL callback.
     */
    function insertByURL () {
      var $popup = editor.popups.get('image.insert');
      var $input = $popup.find('.fr-image-by-url-layer input');

      if ($input.val().length > 0) {
        showProgressBar();
        _setProgressMessage('Loading image');
        insert(editor.helpers.sanitizeURL($input.val()), true, [], $current_image);
        $input.val('');
        $input.blur();
      }
    }

    /**
     * Insert image into the editor.
     */
    function insert(link, sanitize, data, $existing_img, response) {
      editor.edit.off();
      _setProgressMessage('Loading image');

      var image = new Image();
      image.onload = function () {
        if ($existing_img) {
          $img = $existing_img;
          $img.off('load');

          // Remove old data.
          var atts = $img.get(0).attributes;
          for (var i = 0; i < atts.length; i++) {
            var att = atts[i];
            if (att.nodeName.indexOf('data-') == 0) {
              $img.removeAttr(att.nodeName);
            }
          }

          // Set new data.
          if (typeof data != 'undefined') {
            for (var attr in data) {
              if (attr != 'link') {
                $img.attr('data-' + attr, data[attr]);
              }
            }
          }

          $img.on('load', function () {
            // Select the image.
            $img.trigger('click').trigger('touchstart');

            editor.events.trigger('image.loaded', [$img]);
          });

          $img.attr('src', link);

          editor.edit.on();

          editor.undo.saveStep();

          editor.events.trigger('image.replaced', [$img, response]);
        }
        else {
          // Build image data string.
          var data_str = '';
          if (typeof data != 'undefined') {
            for (var attr in data) {
              if (attr != 'link') {
                data_str = ' data-' + attr + '="' + data[attr] + '"';
              }
            }
          }

          var width = editor.opts.imageDefaultWidth;
          if (width != 'auto') {
            width = width + (editor.opts.imageResizeWithPercent ? '%' : 'px');
          }

          // Create image object and set the load event.
          var $img = $('<img class="fr-di' + (editor.opts.imageDefaultDisplay[0]) + (editor.opts.imageDefaultAlign != 'center' ? ' fr-fi' + editor.opts.imageDefaultAlign[0] : '') + '" src="' + link + '"' + data_str + ' style="width: ' + width + ';">');
          $img.on('load', function () {
            // Select the image.
            $img.trigger('click').trigger('touchstart');

            editor.events.trigger('image.loaded', [$img]);
          })

          // Make sure we have focus.
          // Call the event.
          editor.edit.on();
          editor.events.focus(true);
          editor.selection.restore();

          // Collapse selection.
          if (!editor.selection.isCollapsed()) editor.selection.remove();

          // Insert marker and then replace it with the image.
          editor.markers.insert();
          var $marker = editor.$el.find('.fr-marker');
          $marker.replaceWith($img);

          editor.selection.clear();

          editor.undo.saveStep();
          editor.events.trigger('image.inserted', [$img, response]);
        }
      }

      image.onerror = function () {
        _throwError(BAD_LINK);
      }

      image.src = link;
    }

    /**
     * Parse image response.
     */
    function _parseResponse (response) {
      try {
        if (editor.events.trigger('image.uploaded', [response], true) == false) {
          editor.edit.on();
          return false;
        }

        var resp = $.parseJSON(response);
        if (resp.link) {
          return resp;
        } else {
          // No link in upload request.
          _throwError(MISSING_LINK, response);
          return false;
        }
      } catch (ex) {
        // Bad response.
        _throwError(BAD_RESPONSE, response);
        return false;
      }
    }

    /**
     * Parse image response.
     */
    function _parseXMLResponse (response) {
      try {
        var link = $(response).find('Location').text();
        var key = $(response).find('Key').text();

        if (editor.events.trigger('image.uploadedToS3', [link, key, response], true) == false) {
          editor.edit.on();
          return false;
        }

        return link;
      } catch (ex) {
        // Bad response.
        _throwError(BAD_RESPONSE, response);
        return false;
      }
    }

    /**
     * Image was uploaded to the server and we have a response.
     */
    function _imageUploaded ($img) {
      _setProgressMessage('Loading image');

      var status = this.status;
      var response = this.response;
      var responseXML = this.responseXML;
      var responseText = this.responseText;

      try {
        if (editor.opts.imageUploadToS3) {
          if (status == 201) {
            var link = _parseXMLResponse(responseXML);
            if (link) {
              insert(link, false, [], $img, response || responseXML);
            }
          } else {
            _throwError(BAD_RESPONSE, response || responseXML);
          }
        }
        else {
          if (status >= 200 && status < 300) {
            var resp = _parseResponse(responseText);
            if (resp) {
              insert(resp.link, false, resp, $img, response || responseText);
            }
          }
          else {
            _throwError(ERROR_DURING_UPLOAD, response || responseText);
          }
        }
      } catch (ex) {
        // Bad response.
        _throwError(BAD_RESPONSE, response || responseText);
      }
    }

    /**
     * Image upload error.
     */
    function _imageUploadError () {
      _throwError(BAD_RESPONSE, this.response || this.responseText || this.responseXML);
    }

    /**
     * Image upload progress.
     */
    function _imageUploadProgress (e) {
      if (e.lengthComputable) {
        var complete = (e.loaded / e.total * 100 | 0);
        _setProgressMessage('Uploading', complete);
      }
    }

    /**
     * Do image upload.
     */
    function upload (images) {
      // Check if we should cancel the image upload.
      if (editor.events.trigger('image.beforeUpload', [images]) == false) {
        return false;
      }

      // Make sure we have what to upload.
      if (typeof images != 'undefined' && images.length > 0) {
        var image = images[0];

        // Check image max size.
        if (image.size > editor.opts.imageMaxSize) {
          _throwError(MAX_SIZE_EXCEEDED);
          return false;
        }

        // Check image types.
        if (editor.opts.imageAllowedTypes.indexOf(image.type.replace(/image\//g,'')) < 0) {
          _throwError(BAD_FILE_TYPE);
          return false;
        }

        // Create form Data.
        var form_data;
        if (editor.drag_support.formdata) {
          form_data = editor.drag_support.formdata ? new FormData() : null;
        }

        // Prepare form data for request.
        if (form_data) {
          // Upload to S3.
          if (editor.opts.imageUploadToS3 !== false) {
            form_data.append('key', editor.opts.imageUploadToS3.keyStart + (new Date()).getTime() + '-' + (image.name || 'untitled'));
            form_data.append('success_action_status', '201');
            form_data.append('X-Requested-With', 'xhr');
            form_data.append('Content-Type', image.type);

            for (key in editor.opts.imageUploadToS3.params) {
              form_data.append(key, editor.opts.imageUploadToS3.params[key]);
            }
          }

          // Add upload params.
          for (var key in editor.opts.imageUploadParams) {
            form_data.append(key, editor.opts.imageUploadParams[key]);
          }

          // Set the image in the request.
          form_data.append(editor.opts.imageUploadParam, image);

          // Create XHR request.
          var url = editor.opts.imageUploadURL;
          if (editor.opts.imageUploadToS3) {
            url = 'https://' + editor.opts.imageUploadToS3.region + '.amazonaws.com/' + editor.opts.imageUploadToS3.bucket;
          }
          var xhr = editor.core.getXHR(url, editor.opts.imageUploadMethod);

          var $img = $current_image;

          // Set upload events.
          xhr.onload = function () {
            _imageUploaded.call(xhr, $img);
          };
          xhr.onerror = _imageUploadError;
          xhr.upload.onprogress = _imageUploadProgress;

          showProgressBar();
          editor.edit.off();

          // Send data.
          xhr.send(form_data);
        }
      }
    }

    /**
     * Image drop inside the upload zone.
     */
    function _bindInsertEvents ($popup) {
      // Drag over the dropable area.
      $popup.on('dragover dragenter', '.fr-image-upload-layer', function () {
        $(this).addClass('fr-drop');
        return false;
      });

      // Drag end.
      $popup.on('dragleave dragend', '.fr-image-upload-layer', function () {
        $(this).removeClass('fr-drop');
        return false;
      });

      // Drop.
      $popup.on('drop', '.fr-image-upload-layer', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).removeClass('fr-drop');

        var dt = e.originalEvent.dataTransfer;
        if (dt && dt.files) {
          upload(dt.files);
        }
      });

      $popup.on('change', '.fr-image-upload-layer input[type="file"]', function (e) {
        if (this.files) {
          upload(this.files);
        }

        // IE 9 case.
        else {

        }

        // Chrome fix.
        $(this).val('');
        $(this).blur();
      });
    }

    function _initEvents () {
      // Mouse down on image. It might start move.
      editor.$el.on(editor._mousedown, editor.$el.get(0).tagName == 'IMG' ? null : 'img', function (e) {
        editor.selection.clear();

        // Prevent the image resizing.
        if (editor.browser.msie) {
          editor.events.disableBlur();
          editor.$el.attr('contenteditable', false);
        }

        editor.events.trigger('image.mousedown', [e]);

        if (!editor.opts.imageMove) {
          e.preventDefault();
        }

        e.stopPropagation();

        // Add moving class.
        if (editor.opts.imageMove) {
          if (editor.opts.toolbarInline) editor.toolbar.hide();
          $(this).addClass('fr-img-move');
        }
      });

      // Mouse up on an image prevent move.
      editor.$el.on(editor._mouseup, editor.$el.get(0).tagName == 'IMG' ? null : 'img', function (e) {
        // Remove moving class.
        e.stopPropagation();

        if (editor.browser.msie) {
          editor.$el.attr('contenteditable', true);
          editor.events.enableBlur();
        }

        $(this).removeClass('fr-img-move');
      });

      var cancelDrag = function (e) {
        var img = editor.$document.find('img.fr-img-move').get(0);
        if (img) {
          return typeof editor.browser.msie != 'undefined';
        }
        else {
          e.preventDefault();
        }
      }

      var preventDefault = function (e) {
        e.preventDefault();
      }

      editor.events.on('dragenter', preventDefault, true);
      editor.events.on('dragover', cancelDrag, true);

      // Drop inside the editor.
      editor.events.on('drop', function (e) {
        // Check if we have a moving image.
        var img;
        var inst;
        for (var i = 0; i < $.FroalaEditor.INSTANCES.length; i++) {
          img = $.FroalaEditor.INSTANCES[i].$el.find('img.fr-img-move').get(0);
          if (img) {
            inst = $.FroalaEditor.INSTANCES[i];
            break;
          }
        }

        if (img) {
          // Cancel anything else.
          e.preventDefault();
          e.stopPropagation();

          // Close current edit.
          _exitEdit(true);
          if (inst != editor && inst.image) {
            inst.image.exitEdit(true);
            inst.popups.hide('image.edit');
          }

          // Create a clone of the image.
          var $ref;
          var $old_ref;
          if (img.parentNode.tagName == 'A' && img.parentNode.textContent.length == 0) {
            $old_ref = $(img.parentNode);
            $ref = $(img.parentNode).clone();
            $ref.find('img')
              .removeClass('fr-img-move')
              .on('load', _edit);
          }
          else {
            $old_ref = $(img);
            $ref = $(img).clone();
            $ref
              .removeClass('fr-img-move')
              .on('load', _edit);
          }

          // Insert image a the current position.
          editor.markers.insertAtPoint(e.originalEvent);
          var $marker = editor.$el.find('.fr-marker');
          $marker.replaceWith($ref);

          // Remove old image.
          $old_ref.remove();

          return false;
        }
        else {
          _exitEdit(true);
          editor.popups.hideAll();

          // Check if we are dropping files.
          var dt = e.originalEvent.dataTransfer;
          if (dt && dt.files && dt.files.length) {
            var img = dt.files[0];
            if (img && img.type) {
              // Dropped file is an image that we allow.
              if (editor.opts.imageAllowedTypes.indexOf(img.type.replace(/image\//g,'')) >= 0) {
                editor.markers.insertAtPoint(e.originalEvent);
                editor.markers.remove();

                // Hide popups.
                editor.popups.hideAll();

                // Show the image insert popup.
                var $popup = editor.popups.get('image.insert');
                if (!$popup) $popup = _initInsertPopup();
                editor.popups.setContainer('image.insert', $('body'));
                editor.popups.show('image.insert', e.originalEvent.pageX, e.originalEvent.pageY);
                showProgressBar();

                // Upload images.
                upload(dt.files);

                // Cancel anything else.
                e.preventDefault();
                e.stopPropagation();

                return false;
              }
            }
          }
        }
      }, true);

      // Document drop. Remove moving class.
      editor.events.on('document.drop', function (e) {
        if (editor.$el.find('img.fr-img-move').length) {
          e.preventDefault();
          e.stopPropagation();
          editor.$el.find('img.fr-img-move').removeClass('fr-img-move');
        }
      })

      editor.events.on('mousedown', _markExit);
      editor.events.on('window.mousedown', _markExit);

      editor.events.on('window.touchmove', _unmarkExit);

      editor.events.on('mouseup', _exitEdit);
      editor.events.on('window.mouseup', _exitEdit);
      editor.events.on('commands.mousedown', function ($btn) {
        if ($btn.parents('.fr-toolbar').length > 0) {
          _exitEdit();
        }
      });
      editor.events.on('image.hideResizer', _exitEdit);
      editor.events.on('commands.undo', _exitEdit);
      editor.events.on('commands.redo', _exitEdit);

      editor.events.on('destroy', function () {
        editor.$el.off(editor._mouseup, 'img');
      }, true);
    }

    /**
     * Init the image upload popup.
     */
    function _initInsertPopup () {
      // Load template.
      var template = $.FroalaEditor.POPUP_TEMPLATES['imageInsert'];
      if (typeof template == 'function') template = template.apply(editor);

      // Image buttons.
      var image_buttons = '';
      if (editor.opts.imageInsertButtons.length > 1) {
        image_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.imageInsertButtons) + '</div>';
      }

      // Image upload layer.
      var upload_layer = '';
      if (editor.opts.imageInsertButtons.indexOf('imageUpload') >= 0) {
        upload_layer = '<div class="fr-image-upload-layer fr-layer fr-active" id="fr-image-upload-layer-' + editor.id + '"><strong>' + editor.language.translate('Drop image') + '</strong><br>(' + editor.language.translate('or click') + ')<form><input type="file" name="' + editor.opts.imageUploadParam + '" accept="image/*" tabIndex="-1"></form></div>'
      }

      // Image by url layer.
      var by_url_layer = '';
      if (editor.opts.imageInsertButtons.indexOf('imageByURL') >= 0) {
        by_url_layer = '<div class="fr-image-by-url-layer fr-layer" id="fr-image-by-url-layer-' + editor.id + '"><div class="fr-input-line"><input type="text" placeholder="http://" tabIndex="1"></div><div class="fr-action-buttons"><button class="fr-command fr-submit" data-cmd="imageInsertByURL" tabIndex="2">' + editor.language.translate('Insert') + '</button></div></div>'
      }

      // Progress bar.
      var progress_bar_layer = '<div class="fr-image-progress-bar-layer fr-layer"><h3 class="fr-message">Uploading</h3><div class="fr-loader"><span class="fr-progress"></span></div><div class="fr-action-buttons"><button class="fr-command fr-back" data-cmd="imageDismissError" tabIndex="2">OK</button></div></div>';

      var template = {
        buttons: image_buttons,
        upload_layer: upload_layer,
        by_url_layer: by_url_layer,
        progress_bar: progress_bar_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('image.insert', template);

      editor.popups.onRefresh('image.insert', _refreshInsertPopup);
      editor.popups.onHide('image.insert', _hideInsertPopup);

      if (editor.$wp) {
        editor.$wp.on('scroll.image-insert', function () {
          if ($current_image && editor.popups.isVisible('image.insert')) {
            replace();
          }
        });
      }

      editor.events.on('destroy', function () {
        if (editor.$wp) editor.$wp.off('scroll.image-insert');
        $popup.off('dragover dragenter', '.fr-image-upload-layer');
        $popup.off('dragleave dragend', '.fr-image-upload-layer');
        $popup.off('drop', '.fr-image-upload-layer');
        $popup.off('change', '.fr-image-upload-layer input[type="file"]');
      });

      _bindInsertEvents($popup);

      return $popup;
    }

    /**
     * Refresh the ALT popup.
     */
    function _refreshAltPopup () {
      if ($current_image) {
        var $popup = editor.popups.get('image.alt');
        $popup.find('input').val($current_image.attr('alt') || '').trigger('change');
      }
    }

    /**
     * Show the ALT popup.
     */
    function showAltPopup () {
      var $popup = editor.popups.get('image.alt');
      if (!$popup) $popup = _initAltPopup();

      hideProgressBar();
      editor.popups.refresh('image.alt');
      editor.popups.setContainer('image.alt', $('body'));
      var left = $current_image.offset().left + $current_image.width() / 2;
      var top = $current_image.offset().top + $current_image.height();

      editor.popups.show('image.alt', left, top, $current_image.outerHeight());
    }

    /**
     * Init the image upload popup.
     */
    function _initAltPopup () {
      // Image buttons.
      var image_buttons = '';
      image_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.imageAltButtons) + '</div>';

      // Image by url layer.
      var alt_layer = '';
      alt_layer = '<div class="fr-image-alt-layer fr-layer fr-active" id="fr-image-alt-layer-' + editor.id + '"><div class="fr-input-line"><input type="text" placeholder="' + editor.language.translate('Alternate Text') + '" tabIndex="1"></div><div class="fr-action-buttons"><button class="fr-command fr-submit" data-cmd="imageSetAlt" tabIndex="2">' + editor.language.translate('Update') + '</button></div></div>'

      var template = {
        buttons: image_buttons,
        alt_layer: alt_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('image.alt', template);

      editor.popups.onRefresh('image.alt', _refreshAltPopup);

      if (editor.$wp) {
        editor.$wp.on('scroll.image-alt', function () {
          if ($current_image && editor.popups.isVisible('image.alt')) {
            showAltPopup();
          }
        });

        editor.events.on('destroy', function () {
          editor.$wp.off('scroll.image-alt');
        });
      }

      return $popup;
    }

    /**
     * Set ALT based on the values from the popup.
     */
    function setAlt (alt) {
      if ($current_image) {
        var $popup = editor.popups.get('image.alt');
        $current_image.attr('alt', alt || $popup.find('input').val() || '');
        $popup.find('input').blur();
        setTimeout(function () {
          $current_image.trigger('click').trigger('touchstart');
        }, editor.helpers.isAndroid() ? 50 : 0);
      }
    }

    /**
     * Refresh the size popup.
     */
    function _refreshSizePopup () {
      if ($current_image) {
        var $popup = editor.popups.get('image.size');
        $popup.find('input[name="width"]').val($current_image.get(0).style.width).trigger('change');
        $popup.find('input[name="height"]').val($current_image.get(0).style.height).trigger('change');
      }
    }

    /**
     * Show the size popup.
     */
    function showSizePopup () {
      var $popup = editor.popups.get('image.size');
      if (!$popup) $popup = _initSizePopup();

      hideProgressBar();
      editor.popups.refresh('image.size');
      editor.popups.setContainer('image.size', $('body'));
      var left = $current_image.offset().left + $current_image.width() / 2;
      var top = $current_image.offset().top + $current_image.height();

      editor.popups.show('image.size', left, top, $current_image.outerHeight());
    }

    /**
     * Init the image upload popup.
     */
    function _initSizePopup () {
      // Image buttons.
      var image_buttons = '';
      image_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.imageSizeButtons) + '</div>';

      // Size layer.
      var size_layer = '';
      size_layer = '<div class="fr-image-size-layer fr-layer fr-active" id="fr-image-size-layer-' + editor.id + '"><div class="fr-image-group"><div class="fr-input-line"><input type="text" name="width" placeholder="' + editor.language.translate('Width') + '" tabIndex="1"></div><div class="fr-input-line"><input type="text" name="height" placeholder="' + editor.language.translate('Height') + '" tabIndex="1"></div></div><div class="fr-action-buttons"><button class="fr-command fr-submit" data-cmd="imageSetSize" tabIndex="2">' + editor.language.translate('Update') + '</button></div></div>'

      var template = {
        buttons: image_buttons,
        size_layer: size_layer
      };

      // Set the template in the popup.
      var $popup = editor.popups.create('image.size', template);

      editor.popups.onRefresh('image.size', _refreshSizePopup);

      if (editor.$wp) {
        editor.$wp.on('scroll.image-size', function () {
          if ($current_image && editor.popups.isVisible('image.size')) {
            showSizePopup();
          }
        });

        editor.events.on('destroy', function () {
          editor.$wp.off('scroll.image-size');
        });
      }

      return $popup;
    }

    /**
     * Set size based on the current image size.
     */
    function setSize (width, height) {
      if ($current_image) {
        var $popup = editor.popups.get('image.size');
        $current_image.css('width', width || $popup.find('input[name="width"]').val());
        $current_image.css('height', height || $popup.find('input[name="height"]').val());

        $popup.find('input').blur();
        setTimeout(function () {
          $current_image.trigger('click').trigger('touchstart');
        }, editor.helpers.isAndroid() ? 50 : 0);
      }
    }

    /**
     * Show the image upload layer.
     */
    function showLayer (name) {
      var $popup = editor.popups.get('image.insert');

      var left;
      var top;

      // Click on the button from the toolbar without image selected.
      if (!$current_image && !editor.opts.toolbarInline) {
        var $btn = editor.$tb.find('.fr-command[data-cmd="insertImage"]');
        left = $btn.offset().left + $btn.outerWidth() / 2;
        top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
      }

      // Image is selected.
      else if ($current_image) {
        // Set the top to the bottom of the image.
        top = $current_image.offset().top + $current_image.outerHeight();
      }

      // Image is selected and we are in inline mode.
      if (!$current_image && editor.opts.toolbarInline) {
        // Set top to the popup top.
        top = $popup.offset().top - editor.helpers.getPX($popup.css('margin-top'));

        // If the popup is above apply height correction.
        if ($popup.hasClass('fr-above')) {
          top += $popup.outerHeight();
        }
      }

      // Show the new layer.
      $popup.find('.fr-layer').removeClass('fr-active');
      $popup.find('.fr-' + name + '-layer').addClass('fr-active');

      editor.popups.show('image.insert', left, top, ($current_image ? $current_image.outerHeight() : 0));
    }

    /**
     * Refresh the upload image button.
     */
    function refreshUploadButton ($btn) {
      var $popup = editor.popups.get('image.insert');
      if ($popup.find('.fr-image-upload-layer').hasClass('fr-active')) {
        $btn.addClass('fr-active');
      }
    }

    /**
     * Refresh the insert by url button.
     */
    function refreshByURLButton ($btn) {
      var $popup = editor.popups.get('image.insert');
      if ($popup.find('.fr-image-by-url-layer').hasClass('fr-active')) {
        $btn.addClass('fr-active');
      }
    }

    /**
     * Init image resizer.
     */
    function _initImageResizer() {
      $image_resizer = $('<div class="fr-image-resizer"></div>');
      (editor.$wp || $('body')).append($image_resizer);

      $image_resizer.on('mousedown', function (e) {
        e.stopPropagation();
      })

      $(editor.original_window).on('resize.image' + editor.id, function () {
        if (!editor.helpers.isMobile()) {
          _exitEdit(true);
        }
      });

      editor.events.on('destroy', function () {
        $image_resizer.html('').removeData().remove();
        $(editor.original_window).off('resize.image' + editor.id);
      }, true);

      if (editor.opts.imageResize) {
        $image_resizer.append(_getHandler('nw') + _getHandler('ne') + _getHandler('sw') + _getHandler('se'))

        var doc = $image_resizer.get(0).ownerDocument;
        $image_resizer.on(editor._mousedown + '.imgresize' + editor.id, '.fr-handler', _handlerMousedown);
        $(doc).on(editor._mousemove + '.imgresize' + editor.id, _handlerMousemove);
        $(doc.defaultView || doc.parentWindow).on(editor._mouseup+ '.imgresize' + editor.id, _handlerMouseup);

        $overlay = $('<div class="fr-image-overlay"></div>');
        $(doc).find('body').append($overlay);

        $overlay.on('mouseleave', _handlerMouseup)

        editor.events.on('destroy', function () {
          $image_resizer.off(editor._mousedown + '.imgresize' + editor.id);
          $(doc).off(editor._mousemove + '.imgresize' + editor.id);
          $(doc.defaultView || doc.parentWindow).off(editor._mouseup+ '.imgresize' + editor.id);
          $overlay.off('mouseleave').remove();
        }, true);
      }
    }

    /**
     * Remove the current image.
     */
    function remove ($img) {
      $img = $img || $current_image;
      if ($img) {
        if (editor.events.trigger('image.beforeRemove', [$img]) != false) {
          editor.popups.hideAll();
          _exitEdit(true);

          if ($img.get(0) == editor.$el.get(0)) {
            $img.removeAttr('src');
          }
          else {
            if ($img.get(0).parentNode.tagName == 'A') {
              editor.selection.setBefore($img.get(0).parentNode) || editor.selection.setAfter($img.get(0).parentNode);
              $($img.get(0).parentNode).remove();
            }
            else {
              editor.selection.setBefore($img.get(0)) || editor.selection.setAfter($img.get(0));
              $img.remove();
            }

            editor.selection.restore();

            editor.html.fillEmptyBlocks(true);
          }
        }
      }
    }

    /**
     * Prepare images for HTML output.
     */
    function _beforeGetHTML () {
      editor.$el.find('img').each(function () {
        _convertClassesToStyle($(this));
      });
    }

    /**
     * Prepare images for HTML output.
     */
    function _afterGetHTML () {
      editor.$el.find('img').each(function () {
        _convertStyleToClasses($(this));
      });
    }

    /**
     * Initialization.
     */
    function _init () {
      _initEvents();

      editor.$el.on(editor.helpers.isMobile() ? 'touchstart' : 'click', editor.$el.get(0).tagName == 'IMG' ? null : 'img', _edit);

      editor.events.on('keydown', function (e) {
        var key_code = e.which;
        if ($current_image && (key_code == $.FroalaEditor.KEYCODE.BACKSPACE || key_code == $.FroalaEditor.KEYCODE.DELETE)) {
          e.preventDefault();
          remove();
          return false;
        }

        if ($current_image && key_code == $.FroalaEditor.KEYCODE.ESC) {
          _exitEdit(true);
          e.preventDefault();
          return false;
        }

        if ($current_image && !editor.keys.ctrlKey(e)) {
          e.preventDefault();
          return false;
        }
      }, true);

      editor.events.on('paste.before', _clipboardPaste);
      editor.events.on('paste.beforeCleanup', _clipboardPasteCleanup);
      editor.events.on('paste.after', _uploadPastedImages);

      editor.events.on('html.set', _refreshImageList);
      _refreshImageList();

      // Change HTML if the user doesn't want to use classes.
      if (!editor.opts.useClasses) {
        editor.events.on('html.beforeGet', _beforeGetHTML);
        editor.events.on('html.afterGet', _afterGetHTML);
      }

      // Full page.
      if (editor.opts.iframe) {
        editor.events.on('html.set', function () {
          editor.core.injectStyle(editor.opts.imageIframeStyle);
        });
        editor.core.injectStyle(editor.opts.imageIframeStyle);
      }

      if (editor.opts.iframe) {
        editor.events.on('image.loaded', editor.size.syncIframe);
      }

      if (editor.$wp) {
        _syncImages();
        editor.events.on('contentChanged', _syncImages);
      }

      // Editor destroy.
      editor.events.on('destroy', function () {
        editor.$el.off('click touchstart', 'img');
        editor.$el.off('load', 'img.fr-img-dirty');
        editor.$el.off('error', 'img.fr-img-dirty');
      }, true);

      editor.events.on('node.remove', function ($node) {
        if ($node.get(0).tagName == 'IMG') {
          remove($node);
          return false;
        }
      })
    }

    function _uploadPastedImages () {
      if (!editor.opts.imagePaste) {
        editor.$el.find('img[data-fr-image-pasted]').remove();
      }
      else {
        // Safari won't work https://bugs.webkit.org/show_bug.cgi?id=49141
        editor.$el.find('img[data-fr-image-pasted]').each (function (index, img) {
          // Data images.
          if (img.src.indexOf('data:') === 0) {
            if (editor.events.trigger('image.beforePasteUpload', [img]) == false) {
              return false;
            }

            var width = editor.opts.imageDefaultWidth;
            if (width != 'auto') {
              width = width + (editor.opts.imageResizeWithPercent ? '%' : 'px');
            }
            $(img).css('width', width);

            $(img).addClass('fr-dib');

            // Show the progress bar.
            $current_image = $(img);
            _repositionResizer();
            _showEditPopup();
            replace();
            showProgressBar();
            editor.edit.off();

            // Convert image to blob.
            var binary = atob($(img).attr('src').split(',')[1]);
            var array = [];
            for(var i = 0; i < binary.length; i++) {
                array.push(binary.charCodeAt(i));
            }
            var img = new Blob([new Uint8Array(array)], {type: 'image/jpeg'});

            upload([img]);

            $(img).removeAttr('data-fr-image-pasted');
          }

          // Images without http (Safari ones.).
          else if (img.src.indexOf('http') !== 0) {
            editor.selection.save();
            $(img).remove();
            editor.selection.restore();
          }

          else {
            $(img).removeAttr('data-fr-image-pasted');
          }
        });
      }
    }

    function _clipboardPaste (e) {
      if (e && e.clipboardData) {
        if (e.clipboardData.items && e.clipboardData.items[0]) {

          var file = e.clipboardData.items[0].getAsFile();

          if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
              var result = e.target.result;

              editor.html.insert('<img data-fr-image-pasted="true" src="' + result + '" />');

              editor.events.trigger('paste.after');
            };

            reader.readAsDataURL(file);

            return false;
          }
        }
      }
    }

    function _clipboardPasteCleanup (clipboard_html) {
      clipboard_html = clipboard_html.replace(/<img /gi, '<img data-fr-image-pasted="true" ');
      return clipboard_html;
    }

    /**
     * Start edit.
     */
    function _edit (e) {
      if (editor.edit.isDisabled()) {
        e.stopPropagation();
        e.preventDefault();
        return false;
      }

      editor.toolbar.disable();
      e.stopPropagation();
      e.preventDefault();

      // Hide keyboard.
      if (editor.helpers.isMobile()) {
        editor.events.disableBlur();
        editor.$el.blur();
        editor.events.enableBlur();
      }

      if (editor.opts.iframe) {
        editor.size.syncIframe();
      }

      $current_image = $(this);
      _repositionResizer();
      _showEditPopup();

      editor.selection.clear();
      editor.button.bulkRefresh();

      editor.events.trigger('video.hideResizer');
    }

    /**
     * Exit edit.
     */
    function _exitEdit (force_exit) {
      if (force_exit == true) exit_flag = true;

      if ($current_image && exit_flag) {
        editor.toolbar.enable();

        $image_resizer
          .removeClass('fr-active');

        $current_image = null;
      }

      exit_flag = false;
    }

    var exit_flag = false;
    function _markExit () {
      exit_flag = true;
    }

    function _unmarkExit () {
      exit_flag = false;
    }

    /**
     * Align image.
     */
    function align (val) {
      $current_image.removeClass('fr-fir fr-fil');
      if (val == 'left') {
        $current_image.addClass('fr-fil');
      }
      else if (val == 'right') {
        $current_image.addClass('fr-fir');
      }

      _repositionResizer();
      _showEditPopup();
    }

    /**
     * Refresh the align icon.
     */
    function refreshAlign ($btn) {
      if ($current_image) {
        if ($current_image.hasClass('fr-fil')) {
          $btn.find('> i').attr('class', 'fa fa-align-left');
        }
        else if ($current_image.hasClass('fr-fir')) {
          $btn.find('> i').attr('class', 'fa fa-align-right');
        }
        else {
          $btn.find('> i').attr('class', 'fa fa-align-justify');
        }
      }
    }

    /**
     * Refresh the align option from the dropdown.
     */
    function refreshAlignOnShow ($btn, $dropdown) {
      if ($current_image) {
        var alignment = 'justify';

        if ($current_image.hasClass('fr-fil')) {
          alignment = 'left';
        }
        else if ($current_image.hasClass('fr-fir')) {
          alignment = 'right';
        }

        $dropdown.find('.fr-command[data-param1="' + alignment + '"]').addClass('fr-active');
      }
    }

    /**
     * Align image.
     */
    function display (val) {
      $current_image.removeClass('fr-dii fr-dib');
      if (val == 'inline') {
        $current_image.addClass('fr-dii');
      }
      else if (val == 'block') {
        $current_image.addClass('fr-dib');
      }

      _repositionResizer();
      _showEditPopup();
    }

    /**
     * Refresh the image display selected option.
     */
    function refreshDisplayOnShow ($btn, $dropdown) {
      var d = 'block';
      if ($current_image.hasClass('fr-dii')) {
        d = 'inline';
      }

      $dropdown.find('.fr-command[data-param1="' + d + '"]').addClass('fr-active');
    }

    /**
     * Show the replace popup.
     */
    function replace () {
      var $popup = editor.popups.get('image.insert');
      if (!$popup) $popup = _initInsertPopup();

      if (!editor.popups.isVisible('image.insert')) {
        hideProgressBar();
        editor.popups.refresh('image.insert');
        editor.popups.setContainer('image.insert',  $('body'));
      }

      var left = $current_image.offset().left + $current_image.width() / 2;
      var top = $current_image.offset().top + $current_image.height();

      editor.popups.show('image.insert', left, top, $current_image.outerHeight());
    }

    /**
     * Get back to the image main popup.
     */
    function back () {
      if ($current_image) {
        $current_image.trigger('click').trigger('touchstart');
      }
      else {
        editor.popups.hide('image.insert');
        editor.toolbar.showInline();
      }
    }

    /**
     * Get the current image.
     */
    function get () {
      return $current_image;
    }

    /**
     * Apply specific style.
     */
    function applyStyle (val) {
      if (!$current_image) return false;

      // Remove multiple styles.
      if (!editor.opts.imageMultipleStyles) {
        var styles = Object.keys(editor.opts.imageStyles);
        styles.splice(styles.indexOf(val), 1);
        $current_image.removeClass(styles.join(' '));
      }

      $current_image.toggleClass(val);

      $current_image.trigger('click').trigger('touchstart');
    }

    return {
      _init: _init,
      showInsertPopup: showInsertPopup,
      showLayer: showLayer,
      refreshUploadButton: refreshUploadButton,
      refreshByURLButton: refreshByURLButton,
      upload: upload,
      insertByURL: insertByURL,
      align: align,
      refreshAlign: refreshAlign,
      refreshAlignOnShow: refreshAlignOnShow,
      display: display,
      refreshDisplayOnShow: refreshDisplayOnShow,
      replace: replace,
      back: back,
      get: get,
      insert: insert,
      showProgressBar: showProgressBar,
      remove: remove,
      hideProgressBar: hideProgressBar,
      applyStyle: applyStyle,
      showAltPopup: showAltPopup,
      showSizePopup: showSizePopup,
      setAlt: setAlt,
      setSize: setSize,
      exitEdit: _exitEdit
    }
  }

  // Insert image button.
  $.FroalaEditor.DefineIcon('insertImage', { NAME: 'image' });
  $.FroalaEditor.RegisterShortcut(80, 'insertImage');
  $.FroalaEditor.RegisterCommand('insertImage', {
    title: 'Insert Image',
    undo: false,
    focus: false,
    refershAfterCallback: false,
    callback: function () {
      this.image.showInsertPopup();
    }
  });

  // Image upload button inside the insert image popup.
  $.FroalaEditor.DefineIcon('imageUpload', { NAME: 'upload' });
  $.FroalaEditor.RegisterCommand('imageUpload', {
    title: 'Upload Image',
    undo: false,
    focus: false,
    callback: function () {
      this.image.showLayer('image-upload');
    },
    refresh: function ($btn) {
      this.image.refreshUploadButton($btn);
    }
  });

  // Image by URL button inside the insert image popup.
  $.FroalaEditor.DefineIcon('imageByURL', { NAME: 'link' });
  $.FroalaEditor.RegisterCommand('imageByURL', {
    title: 'By URL',
    undo: false,
    focus: false,
    callback: function () {
      this.image.showLayer('image-by-url');
    },
    refresh: function ($btn) {
      this.image.refreshByURLButton($btn);
    }
  })

  // Insert image button inside the insert by URL layer.
  $.FroalaEditor.RegisterCommand('imageInsertByURL', {
    title: 'Insert Image',
    undo: true,
    refreshAfterCallback: false,
    callback: function () {
      this.image.insertByURL();
    },
    refresh: function ($btn) {
      var $current_image = this.image.get();
      if (!$current_image) {
        $btn.text(this.language.translate('Insert'));
      }
      else {
        $btn.text('Replace');
      }
    }
  })

  // Image display.
  $.FroalaEditor.DefineIcon('imageDisplay', { NAME: 'star' })
  $.FroalaEditor.RegisterCommand('imageDisplay', {
    title: 'Display',
    type: 'dropdown',
    options: {
      'inline': 'Inline',
      'block': 'Break Text'
    },
    callback: function (cmd, val) {
      this.image.display(val);
    },
    refresh: function ($btn) {
      if (!this.opts.imageTextNear) $btn.addClass('fr-hidden');
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.image.refreshDisplayOnShow($btn, $dropdown);
    }
  })

  // Image align.
  $.FroalaEditor.DefineIcon('imageAlign', { NAME: 'align-center'})
  $.FroalaEditor.RegisterCommand('imageAlign', {
    type: 'dropdown',
    title: 'Align',
    options: {
      'left': 'Align Left',
      'justify': 'None',
      'right': 'Align Right'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FroalaEditor.COMMANDS.imageAlign.options;
      for (var val in options) {
        c += '<li><a class="fr-command" data-cmd="imageAlign" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '"><i class="fa fa-align-' + val + '"></i></a></li>';
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.image.align(val);
    },
    refresh: function ($btn) {
      this.image.refreshAlign($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.image.refreshAlignOnShow($btn, $dropdown);
    }
  })

  // Image replace.
  $.FroalaEditor.DefineIcon('imageReplace', { NAME: 'exchange' })
  $.FroalaEditor.RegisterCommand('imageReplace', {
    title: 'Replace',
    undo: false,
    focus: false,
    refreshAfterCallback: false,
    callback: function () {
      this.image.replace();
    }
  })

  // Image remove.
  $.FroalaEditor.DefineIcon('imageRemove', { NAME: 'trash' })
  $.FroalaEditor.RegisterCommand('imageRemove', {
    title: 'Remove',
    callback: function () {
      this.image.remove();
    }
  })

  // Image back.
  $.FroalaEditor.DefineIcon('imageBack', { NAME: 'arrow-left' });
  $.FroalaEditor.RegisterCommand('imageBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    callback: function () {
      this.image.back();
    },
    refresh: function ($btn) {
      var $current_image = this.image.get();
      if (!$current_image && !this.opts.toolbarInline) {
        $btn.addClass('fr-hidden');
        $btn.next('.fr-separator').addClass('fr-hidden');
      }
      else {
        $btn.removeClass('fr-hidden');
        $btn.next('.fr-separator').removeClass('fr-hidden');
      }
    }
  });

  $.FroalaEditor.RegisterCommand('imageDismissError', {
    title: 'OK',
    callback: function () {
      this.image.hideProgressBar(true);
    }
  })

  // Image styles.
  $.FroalaEditor.DefineIcon('imageStyle', { NAME: 'magic' })
  $.FroalaEditor.RegisterCommand('imageStyle', {
    title: 'Style',
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.imageStyles;
      for (var cls in options) {
        c += '<li><a class="fr-command" data-cmd="imageStyle" data-param1="' + cls + '">' + options[cls] + '</a></li>';
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.image.applyStyle(val);
    },
    refreshOnShow: function ($btn, $dropdown) {
      var $current_image = this.image.get();

      if ($current_image) {
        $dropdown.find('.fr-command').each (function () {
          var cls = $(this).data('param1');
          $(this).toggleClass('fr-active', $current_image.hasClass(cls));
        })
      }
    }
  })

  // Image alt.
  $.FroalaEditor.DefineIcon('imageAlt', { NAME: 'info' })
  $.FroalaEditor.RegisterCommand('imageAlt', {
    undo: false,
    focus: false,
    title: 'Alternate Text',
    callback: function () {
      this.image.showAltPopup();
    }
  });

  $.FroalaEditor.RegisterCommand('imageSetAlt', {
    undo: true,
    focus: false,
    title: 'Update',
    refreshAfterCallback: false,
    callback: function () {
      this.image.setAlt();
    }
  });

  // Image size.
  $.FroalaEditor.DefineIcon('imageSize', { NAME: 'arrows-alt' })
  $.FroalaEditor.RegisterCommand('imageSize', {
    undo: false,
    focus: false,
    title: 'Change Size',
    callback: function () {
      this.image.showSizePopup();
    }
  });

  $.FroalaEditor.RegisterCommand('imageSetSize', {
    undo: true,
    focus: false,
    callback: function () {
      this.image.setSize();
    }
  })
})(jQuery);
