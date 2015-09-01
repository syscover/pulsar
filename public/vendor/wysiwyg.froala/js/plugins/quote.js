/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.FroalaEditor.PLUGINS.quote = function (editor) {
    function _deepestParent(node) {
      while (node.parentNode && node.parentNode != editor.$el.get(0)) {
        node = node.parentNode;
      }

      return node;
    }

    function _increase () {
      // Get blocks.
      var blocks = editor.selection.blocks();

      // Normalize blocks.
      for (var i = 0; i < blocks.length; i++) {
        blocks[i] = _deepestParent(blocks[i]);
      }

      // Save selection to restore it later.
      editor.selection.save();

      var $quote = $('<blockquote>');
      $quote.insertBefore(blocks[0]);
      for (var i = 0; i < blocks.length; i++) {
        $quote.append(blocks[i]);
      }

      // Unwrap temp divs.
      editor.html.unwrap();

      editor.selection.restore();
    }

    function _decrease () {
      // Get blocks.
      var blocks = editor.selection.blocks();

      for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].tagName != 'BLOCKQUOTE') {
          blocks[i] = $(blocks[i]).parentsUntil(editor.$el, 'BLOCKQUOTE').get(0);
        }
      }

      editor.selection.save();

      for (var i = 0; i < blocks.length; i++) {
        if (blocks[i]) {
          $(blocks[i]).replaceWith(blocks[i].innerHTML);
        }
      }

      // Unwrap temp divs.
      editor.html.unwrap();

      editor.selection.restore();
    }

    function apply (val) {
      // Wrap.
      editor.selection.save();
      editor.html.wrap(true, true);
      editor.selection.restore();

      if (val == 'increase') {
        _increase();
      }
      else if (val == 'decrease') {
        _decrease();
      }


    }

    return {
      apply: apply
    }
  }

  // Register the quote command.
  $.FroalaEditor.RegisterShortcut(222, 'quote', 'increase');
  $.FroalaEditor.RegisterShortcut(222, 'quote', 'decrease', true);
  $.FroalaEditor.RegisterCommand('quote', {
    title: 'Quote',
    type: 'dropdown',
    options: {
      'increase': 'Increase',
      'decrease': 'Decrease'
    },
    callback: function (cmd, val) {
      this.quote.apply(val);
    }
  })

  // Add the quote icon.
  $.FroalaEditor.DefineIcon('quote', {
    NAME: 'quote-left'
  });

})(jQuery);
