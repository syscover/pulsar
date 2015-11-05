/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.DEFAULTS, {
    inlineStyles: {
      'Big Red': 'font-size: 20px; color: red;',
      'Small Blue': 'font-size: 14px; color: blue;'
    }
  })

  $.FroalaEditor.PLUGINS.inlineStyle = function (editor) {
    function apply (val) {
      if (editor.selection.text() !== '') {
        editor.html.insert($.FroalaEditor.START_MARKER + '<span style="' + val + '">' + editor.selection.text() + '</span>' + $.FroalaEditor.END_MARKER);
      }
      else {
        editor.html.insert('<span style="' + val + '">' + $.FroalaEditor.INVISIBLE_SPACE + $.FroalaEditor.MARKERS + '</span>');
      }
    }

    return {
      apply: apply
    }
  }

  // Register the inline style command.
  $.FroalaEditor.RegisterCommand('inlineStyle', {
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.inlineStyles;
      for (var val in options) {
        c += '<li><span style="' + options[val] + '"><a class="fr-command" data-cmd="inlineStyle" data-param1="' + options[val] + '" title="' + val + '">' + val + '</a></span></li>';
      }
      c += '</ul>';

      return c;
    },
    title: 'Inline Style',
    callback: function (cmd, val) {
      this.inlineStyle.apply(val);
    }
  })

  // Add the font size icon.
  $.FroalaEditor.DefineIcon('inlineStyle', {
    NAME: 'paint-brush'
  });

})(jQuery);
