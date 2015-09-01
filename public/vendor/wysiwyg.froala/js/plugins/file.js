/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.POPUP_TEMPLATES, {
    'file.insert': '[_BUTTONS_][_UPLOAD_LAYER_][_PROGRESS_BAR_]',
  })

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {
    fileUploadURL: 'http://i.froala.com/upload',
    fileUploadParam: 'file',
    fileUploadParams: {},
    fileUploadToS3: false,
    fileUploadMethod: 'POST',
    fileMaxSize: 10 * 1024 * 1024,
    fileAllowedTypes: ['*'],
    fileInsertButtons: ['fileBack', '|'],
    fileUseSelectedText: false
  });


  $.FroalaEditor.PLUGINS.file = function (editor) {
    var BAD_LINK = 1;
    var MISSING_LINK = 2;
    var ERROR_DURING_UPLOAD = 3;
    var BAD_RESPONSE = 4;
    var MAX_SIZE_EXCEEDED = 5;
    var BAD_FILE_TYPE = 6;
    var NO_CORS_IE = 7;

    var error_messages = {
      BAD_LINK: 'File cannot be loaded from the passed link.',
      MISSING_LINK: 'No link in upload response.',
      ERROR_DURING_UPLOAD: 'Error during file upload.',
      BAD_RESPONSE: 'Parsing response failed.',
      MAX_SIZE_EXCEEDED: 'File is too large.',
      BAD_FILE_TYPE: 'File file type is invalid.',
      NO_CORS_IE: 'Files can be uploaded only to same domain in IE 8 and IE 9.'
    }

    function showInsertPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="insertFile"]');

      var $popup = editor.popups.get('file.insert');
      if (!$popup) $popup = _initInsertPopup();

      hideProgressBar();
      if (!$popup.hasClass('fr-active')) {
        editor.popups.refresh('file.insert');
        editor.popups.setContainer('file.insert', editor.$tb);

        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 0 : $btn.outerHeight());
        editor.popups.show('file.insert', left, top, $btn.outerHeight());

        editor.selection.save();
      }
    }

    /**
     * Show progress bar.
     */
    function showProgressBar () {
      var $popup = editor.popups.get('file.insert');
      $popup.find('.fr-layer.fr-active').removeClass('fr-active').addClass('fr-pactive');
      $popup.find('.fr-file-progress-bar-layer').addClass('fr-active');

      _setProgressMessage('Uploading', 0);
    }

    /**
     * Hide progress bar.
     */
    function hideProgressBar () {
      if (editor.popups.isVisible('file.insert')) {
        var $popup = editor.popups.get('file.insert');
        $popup.find('.fr-layer.fr-pactive').addClass('fr-active').removeClass('fr-pactive');
        $popup.find('.fr-file-progress-bar-layer').removeClass('fr-active');
      }
    }

    /**
     * Set a progress message.
     */
    function _setProgressMessage (message, progress) {
      var $popup = editor.popups.get('file.insert');
      var $layer = $popup.find('.fr-file-progress-bar-layer');
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
      var $popup = editor.popups.get('file.insert');
      var $layer = $popup.find('.fr-file-progress-bar-layer');
      $layer.addClass('fr-error')
      $layer.find('h3').text(message);
    }

    /**
     * Insert the uploaded file.
     */
    function insert (link, text, response) {
      editor.edit.on();

      // Focus in the editor.
      editor.events.focus(true);
      editor.selection.restore();

      // Insert the link.
      editor.html.insert('<a href="' + link + '" id="fr-inserted-file" class="fr-file">' + (text || editor.selection.text()) + '</a>');

      // Get the file.
      var $file = editor.$el.find('#fr-inserted-file');
      $file.removeAttr('id');

      editor.popups.hide('file.insert');

      editor.undo.saveStep();

      editor.events.trigger('file.inserted', [$file, response]);
    }

    /**
     * Parse file response.
     */
    function _parseResponse (response) {
      try {
        if (editor.events.trigger('file.uploaded', [response], true) == false) {
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
     * Parse file response.
     */
    function _parseXMLResponse (response) {
      try {
        var link = $(response).find('Location').text();
        var key = $(response).find('Key').text();

        if (editor.events.trigger('file.uploadedToS3', [link, key, response], true) == false) {
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
     * File was uploaded to the server and we have a response.
     */
    function _fileUploaded (text) {
      var status = this.status;
      var response = this.response;
      var responseXML = this.responseXML;
      var responseText = this.responseText;

      try {
        if (editor.opts.fileUploadToS3) {
          if (status == 201) {
            var link = _parseXMLResponse(responseXML);
            if (link) {
              insert(link, text, response || responseXML);
            }
          } else {
            _throwError(BAD_RESPONSE, response || responseXML);
          }
        }
        else {
          if (status >= 200 && status < 300) {
            var resp = _parseResponse(responseText);
            if (resp) {
              insert(resp.link, text, response || responseText);
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
     * File upload error.
     */
    function _fileUploadError () {
      _throwError(BAD_RESPONSE, this.response || this.responseText || this.responseXML);
    }

    /**
     * File upload progress.
     */
    function _fileUploadProgress (e) {
      if (e.lengthComputable) {
        var complete = (e.loaded / e.total * 100 | 0);
        _setProgressMessage('Uploading', complete);
      }
    }

    /**
     * Throw an file error.
     */
    function _throwError (code, response) {
      editor.edit.on();
      _showErrorMessage(editor.language.translate('Something went wrong. Please try again.'));

      editor.events.trigger('file.error', [{
        code: code,
        message: error_messages[code]
      }, response]);
    }

    function upload (files) {
      // Check if we should cancel the file upload.
      if (editor.events.trigger('file.beforeUpload', [files]) == false) {
        return false;
      }

      // Make sure we have what to upload.
      if (typeof files != 'undefined' && files.length > 0) {
        var file = files[0];

        // Check file max size.
        if (file.size > editor.opts.fileMaxSize) {
          _throwError(MAX_SIZE_EXCEEDED);
          return false;
        }

        // Check file types.
        if (editor.opts.fileAllowedTypes.indexOf('*') < 0 && editor.opts.fileAllowedTypes.indexOf(file.type.replace(/file\//g,'')) < 0) {
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
          if (editor.opts.fileUploadToS3 !== false) {
            form_data.append('key', editor.opts.fileUploadToS3.keyStart + (new Date()).getTime() + '-' + (file.name || 'untitled'));
            form_data.append('success_action_status', '201');
            form_data.append('X-Requested-With', 'xhr');
            form_data.append('Content-Type', file.type);

            for (key in editor.opts.fileUploadToS3.params) {
              form_data.append(key, editor.opts.fileUploadToS3.params[key]);
            }
          }

          // Add upload params.
          for (var key in editor.opts.fileUploadParams) {
            form_data.append(key, editor.opts.fileUploadParams[key]);
          }

          // Set the file in the request.
          form_data.append(editor.opts.fileUploadParam, file);

          // Create XHR request.
          var url = editor.opts.fileUploadURL;
          if (editor.opts.fileUploadToS3) {
            url = 'https://' + editor.opts.fileUploadToS3.region + '.amazonaws.com/' + editor.opts.fileUploadToS3.bucket;
          }
          var xhr = editor.core.getXHR(url, editor.opts.fileUploadMethod);

          // Set upload events.
          xhr.onload = function () {
            _fileUploaded.call(xhr, [(editor.opts.fileUseSelectedText ? null : file.name)]);
          };
          xhr.onerror = _fileUploadError;
          xhr.upload.onprogress = _fileUploadProgress;

          showProgressBar();
          editor.edit.off();

          // Send data.
          xhr.send(form_data);
        }
      }
    }

    function _bindInsertEvents ($popup) {
      // Drag over the dropable area.
      $popup.on('dragover dragenter', '.fr-file-upload-layer', function () {
        $(this).addClass('fr-drop');
        return false;
      });

      // Drag end.
      $popup.on('dragleave dragend', '.fr-file-upload-layer', function () {
        $(this).removeClass('fr-drop');
        return false;
      });

      // Drop.
      $popup.on('drop', '.fr-file-upload-layer', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).removeClass('fr-drop');

        var dt = e.originalEvent.dataTransfer;
        if (dt && dt.files) {
          upload(dt.files);
        }
      });

      $popup.on('change', '.fr-file-upload-layer input[type="file"]', function (e) {
        if (this.files) {
          upload(this.files);
        }

        // IE 9 case.
        else {

        }

        // Chrome fix.
        $(this).val('');
      });
    }

    function _hideInsertPopup () {
      hideProgressBar();
    }

    function _initInsertPopup () {
      // Image buttons.
      var file_buttons = '';
      if (editor.opts.linkInsertButtons.length >= 1) {
        file_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.fileInsertButtons) + '</div>';
      }

      // File upload layer.
      var upload_layer = '';
      upload_layer = '<div class="fr-file-upload-layer fr-layer fr-active" id="fr-file-upload-layer-' + editor.id + '"><strong>' + editor.language.translate('Drop file') + '</strong><br>(' + editor.language.translate('or click') + ')<form><input type="file" name="' + editor.opts.fileUploadParam + '" accept="/*" tabIndex="-1"></form></div>'


      // Progress bar.
      var progress_bar_layer = '<div class="fr-file-progress-bar-layer fr-layer"><h3 class="fr-message">Uploading</h3><div class="fr-loader"><span class="fr-progress"></span></div><div class="fr-action-buttons"><button class="fr-command" data-cmd="fileDismissError" tabIndex="2">OK</button></div></div>';

      var template = {
        buttons: file_buttons,
        upload_layer: upload_layer,
        progress_bar: progress_bar_layer
      };

      // Set the template in the popup.
      var $popup = editor.popups.create('file.insert', template);

      editor.popups.onHide('file.insert', _hideInsertPopup);
     _bindInsertEvents($popup);

      return $popup;
    }

    function _onRemove (link) {
      if ($(link).hasClass('fr-file')) {
        return editor.events.trigger('file.unlink', [link]);
      }
    }

    function _initEvents() {
      var preventDefault = function (e) {
        e.preventDefault();
      }

      editor.events.on('dragenter', preventDefault);
      editor.events.on('dragover', preventDefault);

      // Drop inside the editor.
      editor.events.on('drop', function (e) {
        editor.popups.hideAll();

        // Check if we are dropping files.
        var dt = e.originalEvent.dataTransfer;
        if (dt && dt.files && dt.files.length) {
          var file = dt.files[0];
          if (file && file.type) {
            // Dropped file is an file that we allow.
            if (editor.opts.fileAllowedTypes.indexOf(file.type) >= 0 || editor.opts.fileAllowedTypes.indexOf('*') >= 0) {
              editor.markers.insertAtPoint(e.originalEvent);
              editor.markers.remove();

              // Hide popups.
              editor.popups.hideAll();

              // Show the file insert popup.
              var $popup = editor.popups.get('file.insert');
              if (!$popup) $popup = _initInsertPopup();
              editor.popups.setContainer('file.insert', $('body'));
              editor.popups.show('file.insert', e.originalEvent.pageX, e.originalEvent.pageY);
              showProgressBar();

              // Upload files.
              upload(dt.files);

              // Cancel anything else.
              e.preventDefault();
              e.stopPropagation();
            }
          }
        }
      });
    }

    function back () {
      editor.events.disableBlur();
      editor.selection.restore();
      editor.events.enableBlur();

      editor.popups.hide('file.insert');
      editor.toolbar.showInline();
    }

    /*
     * Initialize.
     */
    function _init () {
      _initEvents();

      editor.events.on('link.beforeRemove', _onRemove);
    }

    return {
      _init: _init,
      showInsertPopup: showInsertPopup,
      upload: upload,
      insert: insert,
      back: back
    }
  }

  // Insert file button.
  $.FroalaEditor.DefineIcon('insertFile', { NAME: 'file-o' });
  $.FroalaEditor.RegisterCommand('insertFile', {
    title: 'Upload File',
    undo: false,
    callback: function () {
      this.file.showInsertPopup();
    }
  });

  $.FroalaEditor.DefineIcon('fileBack', { NAME: 'arrow-left' });
  $.FroalaEditor.RegisterCommand('fileBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    refreshAfterCallback: false,
    callback: function () {
      this.file.back();
    },
    refresh: function ($btn) {
      if (!this.opts.toolbarInline) {
        $btn.addClass('fr-hidden');
        $btn.next('.fr-separator').addClass('fr-hidden');
      }
      else {
        $btn.removeClass('fr-hidden');
        $btn.next('.fr-separator').removeClass('fr-hidden');
      }
    }
  });
})(jQuery);
