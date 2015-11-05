/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.DEFAULTS, {
    paragraphStyles: {
      'text-gray': 'Gray',
      'text-bordered': 'Bordered',
      'text-spaced': 'Spaced',
      'text-uppercase': 'Uppercase'
    },
    paragraphMultipleStyles: true,
    paragraphIframeStyle: '.text-gray{color:#AAA !important;}.text-bordered{border-top:solid 1px #222;border-bottom:solid 1px #222;padding: 10px 0;}.text-spaced{letter-spacing:1px;}.text-uppercase{text-transform:uppercase;}'
  });

  $.FroalaEditor.PLUGINS.paragraphStyle = function (editor) {
    /**
     * Apply style.
     */
    function apply (val) {
      var styles = '';
      // Remove multiple styles.
      if (!editor.opts.paragraphMultipleStyles) {
        styles = Object.keys(editor.opts.paragraphStyles);
        styles.splice(styles.indexOf(val), 1);
        styles = styles.join(' ');
      }

      editor.selection.save();
      editor.html.wrap(true, true, true);
      editor.selection.restore();

      var blocks = editor.selection.blocks();

      // Save selection to restore it later.
      editor.selection.save();

      for (var i = 0; i < blocks.length; i++) {
        $(blocks[i]).removeClass(styles).toggleClass(val);

        if ($(blocks[i]).hasClass('fr-temp-div')) $(blocks[i]).removeClass('fr-temp-div');
        if ($(blocks[i]).attr('class') === '') $(blocks[i]).removeAttr('class');
      }

      // Unwrap temp divs.
      editor.html.unwrap();

      // Restore selection.
      editor.selection.restore();
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

    function _init () {
      // Full page.
      if (editor.opts.iframe) {
        editor.events.on('html.set', function () {
          editor.core.injectStyle(editor.opts.paragraphIframeStyle);
        });
        editor.core.injectStyle(editor.opts.paragraphIframeStyle);
      }
    }

    return {
      _init: _init,
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
        c += '<li><a class="fr-command ' + val + '" data-cmd="paragraphStyle" data-param1="' + val + '" title="' + options[val] + '">' + options[val] + '</a></li>';
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
