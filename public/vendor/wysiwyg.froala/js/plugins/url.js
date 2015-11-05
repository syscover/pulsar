/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {

  });

  $.FroalaEditor.URLRegEx = /(\s|^|>)((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+(\.[a-zA-Z]{2,3})?(:\d*)?(\/[^\s<]*)?)(\s|$|<)/gi;

  $.FroalaEditor.PLUGINS.url = function (editor) {

    function _convertURLS (contents) {
      // All content zones.
      contents.each (function () {
        if (this.tagName == 'IFRAME') return;

        // Text node.
        if (this.nodeType == 3) {
          var text = this.textContent.replace(/&nbsp;/gi, '');

          // Check if text is URL.
          if ($.FroalaEditor.URLRegEx.test(text)) {
            // Convert it to A.
            $(this).before(text.replace($.FroalaEditor.URLRegEx, '$1<a href="$2">$2</a>$7'));

            $(this).remove();
          }
        }

        // Other type of node.
        else if (this.nodeType == 1 && ['A', 'BUTTON', 'TEXTAREA'].indexOf(this.tagName) < 0) {
          // Convert urls inside it.
          _convertURLS(editor.node.contents(this));
        }
      })
    }

    /*
     * Initialize.
     */
    function _init () {
      editor.events.on('paste.afterCleanup', function (html) {
        if ($.FroalaEditor.URLRegEx.test(html)) {
          return html.replace($.FroalaEditor.URLRegEx, '$1<a href="$2">$2</a>$7')
        }
      });

      editor.events.on('keyup', function (e) {
        var keycode = e.which;
        if (keycode == $.FroalaEditor.KEYCODE.ENTER || keycode == $.FroalaEditor.KEYCODE.SPACE) {
          _convertURLS(editor.node.contents(editor.$el.get(0)));
        }
      });

      editor.events.on('keydown', function (e) {
        var keycode = e.which;

        if (keycode == $.FroalaEditor.KEYCODE.ENTER) {
          var el = editor.selection.element();

          if ((el.tagName == 'A' || $(el).parents('a').length) && editor.selection.info(el).atEnd) {
            e.stopImmediatePropagation();

            if (el.tagName !== 'A') el = $(el).parents('a')[0];
            $(el).after('&nbsp;' + $.FroalaEditor.MARKERS);
            editor.selection.restore();

            return false;
          }
        }
      });
    }

    return {
      _init: _init
    }
  }
})(jQuery);
