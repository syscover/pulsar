/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.DEFAULTS, {
    paragraphStyles: {
      'text-light': 'Light',
      'text-thin': 'Thin',
      'text-spaced': 'Spaced',
      'text-uppercase': 'Uppercase'
    },
    paragraphMultipleStyles: true
  });

  $.FroalaEditor.PLUGINS.paragraphStyle = function (editor) {
    /**
     * Basic style.
     */
    function _style($blk, val) {
      if (!val) val = 'div class="fr-temp-div"' + (editor.node.isEmpty($blk.get(0), true) ? ' data-empty="true"' : '');
      $blk.replaceWith($('<' + val  + '>').html($blk.html()));
    }

    /**
     * Apply style.
     */
    function apply (val) {
      var styles = '';
      // Remove multiple styles.
      if (!editor.opts.paragraphStylesMultiple) {
        styles = Object.keys(editor.opts.paragraphStyles);
        styles.splice(styles.indexOf(val), 1);
        styles = styles.join(' ');
      }

      var blocks = editor.selection.blocks();

      for (var i = 0; i < blocks.length; i++) {
        $(blocks[i]).removeClass(styles).toggleClass(val);

        if ($(blocks[i]).attr('class') == '') $(blocks[i]).removeAttr('class');
      }
    }

    function refreshOnShow($btn, $dropdown) {
      var blocks = editor.selection.blocks();

      if (blocks.length) {
        var $blk = $(blocks[0]);
        $dropdown.find('.fr-command').each (function () {
          var cls = $(this).data('param1');
          $(this).toggleClass('fr-active', $blk.hasClass(cls));
        })
      }
    }

    return {
      apply: apply,
      refreshOnShow: refreshOnShow
    }
  }

  // Register the font size command.
  $.FroalaEditor.RegisterCommand('paragraphStyle', {
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.paragraphStyles;
      for (var val in options) {
        c += '<li><span ' + val + ' class="' + val + '"><a class="fr-command" data-cmd="paragraphStyle" data-param1="' + val + '" title="' + options[val] + '">' + options[val] + '</a></span></li>';
      }
      c += '</ul>';

      return c;
    },
    title: 'Paragraph Style',
    callback: function (cmd, val) {
      this.paragraphStyle.apply(val);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.paragraphStyle.refreshOnShow($btn, $dropdown);
    }
  })

  // Add the font size icon.
  $.FroalaEditor.DefineIcon('paragraphStyle', {
    NAME: 'magic'
  });

})(jQuery);
