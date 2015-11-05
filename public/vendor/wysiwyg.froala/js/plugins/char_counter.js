/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {
    charCounterMax: -1,
    charCounterCount: true
  });


  $.FroalaEditor.PLUGINS.charCounter = function (editor) {
    var $counter;

    /**
     * Get the char number.
     */
    function _charNumber () {
      return editor.$el.text().length;
    }

    /**
     * Check chars on typing.
     */
    function _checkCharNumber (e) {
      // Continue if infinite characters;
      if (editor.opts.charCounterMax < 0) return true;

      // Continue if enough characters.
      if (_charNumber() < editor.opts.charCounterMax) return true;

      // Stop if the key will produce a new char.
      var keyCode = e.which;
      if (!editor.keys.ctrlKey(e) && editor.keys.isCharacter(keyCode)) {
        e.preventDefault();
        e.stopPropagation();
        editor.events.trigger('charCounter.exceeded');
        return false;
      }

      return true;
    }

    /**
     * Check chars on paste.
     */
    function _checkCharNumberOnPaste (html) {
      if (editor.opts.charCounterMax < 0) return html;

      var len = $('<div>').html(html).text().length;
      if (len + _charNumber() <= editor.opts.charCounterMax) return html;

      editor.events.trigger('charCounter.exceeded');

      return '';
    }

    /**
     * Update the char counter.
     */
    function _updateCharNumber () {
      if (editor.opts.charCounterCount) {
        var chars = _charNumber() + (editor.opts.charCounterMax > 0 ?  '/' + editor.opts.charCounterMax : '');

        $counter.text(chars);

        if (editor.opts.toolbarBottom) {
          $counter.css('margin-bottom', editor.$tb.outerHeight(true))
        }
      }
    }

    /*
     * Initialize.
     */
    function _init () {
      if (!editor.$wp) return false;

      $counter = $('<span class="fr-counter"></span>');
      editor.$box.append($counter);

      editor.events.on('keydown', _checkCharNumber, true);
      editor.events.on('paste.afterCleanup', _checkCharNumberOnPaste);
      editor.events.on('keyup', _updateCharNumber);
      editor.events.on('contentChanged', _updateCharNumber);
      editor.events.on('charCounter.update', _updateCharNumber);

      _updateCharNumber();

      editor.events.on('destroy', function () {
        $(editor.original_window).off('resize.char' + editor.id);
        $counter.removeData().remove();
      });
    }

    return {
      _init: _init
    }
  }
})(jQuery);
