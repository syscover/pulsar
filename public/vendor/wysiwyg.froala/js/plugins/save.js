/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {
    saveInterval: 1000,
    saveURL: null,
    saveParams: {},
    saveParam: 'body',
    saveMethod: 'POST'
  });


  $.FroalaEditor.PLUGINS.save = function (editor) {
    var _timeout = null;
    var _last_html = null;
    var _force = false;

    var BAD_LINK = 1;
    var ERROR_ON_SERVER = 2;

    var error_messages = {};
    error_messages[BAD_LINK] = 'Missing saveURL option.';
    error_messages[ERROR_ON_SERVER] = 'Something went wrong during save.';

    /**
     * Throw an image error.
     */
    function _throwError (code, response) {
      editor.events.trigger('save.error', [{
        code: code,
        message: error_messages[code]
      }, response]);
    }

    function save (html) {
      if (typeof html == 'undefined') html = editor.html.get();

      // Trigger before save event.
      if (editor.events.trigger('save.before') == false) return false;

      if (editor.opts.saveURL) {
        var params = {};
        for (var key in editor.opts.saveParams) {
          var param = editor.opts.saveParams[key];
          if (typeof(param) == 'function') {
            params[key] = param.call(this);
          } else {
            params[key] = param;
          }
        }

        var dt = {};
        dt[editor.opts.saveParam] = html;

        $.ajax({
          type: editor.opts.saveMethod,
          url: editor.opts.saveURL,
          data: $.extend(dt, params),
          crossDomain: editor.opts.requestWithCORS,
          xhrFields: {
            withCredentials: editor.opts.requestWithCORS
          },
          headers: editor.opts.requestHeaders
        })
        .done(function (data) {
          // data
          editor.events.trigger('save.after', [data]);
        })
        .fail(function (xhr) {
          // (error)
          _throwError(ERROR_ON_SERVER, xhr.response);
        });
      } else {
        // (error)
        _throwError(BAD_LINK);
      }
    }

    function _mightSave () {
      clearTimeout(_timeout);
      _timeout = setTimeout(function () {
        var html = editor.html.get();
        if (_last_html != html || _force) {
          _last_html = html;
          _force = false;

          save(html);
        }
      }, editor.opts.saveInterval);
    }

    /**
     * Reset the saving interval.
     */
    function reset () {
      _mightSave();
      _force = false;
    }

    /**
     * Force saving at the end of the current interval.
     */
    function force () {
      _force = true;
    }

    /*
     * Initialize.
     */
    function _init () {
      if (editor.opts.saveInterval) {
        _last_html = editor.html.get();
        editor.events.on('contentChanged', _mightSave);
        editor.events.on('keydown', function () {
          clearTimeout(_timeout);
        })
      }
    }

    return {
      _init: _init,
      save: save,
      reset: reset,
      force: force
    }
  }

  $.FroalaEditor.DefineIcon('save', { NAME: 'floppy-o' });
  $.FroalaEditor.RegisterCommand('save', {
    title: 'Save',
    undo: false,
    focus: false,
    refreshAfterCallback: false,
    callback: function () {
      this.save.save();
    }
  });
})(jQuery);
