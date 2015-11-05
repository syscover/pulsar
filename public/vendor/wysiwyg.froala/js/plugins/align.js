/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.FroalaEditor.PLUGINS.align = function (editor) {
    function apply (val) {
      // Wrap.
      editor.selection.save();
      editor.html.wrap(true, true, true);
      editor.selection.restore();

      var blocks = editor.selection.blocks();

      for (var i = 0; i < blocks.length; i++) {
        $(blocks[i]).css('text-align', val).removeClass('fr-temp-div');
        if ($(blocks[i]).attr('class') === '') $(blocks[i]).removeAttr('class');
      }

      editor.selection.save();
      editor.html.unwrap();
      editor.selection.restore();
    }

    function get ($block) {
      var alignment = $block.css('text-align');

      // Detect rtl.
      if (alignment == 'start') {
        var $div = $('<div dir="auto" style="text-align: initial; position: fixed; left: -3000px;"><span id="s1">.</span><span id="s2">.</span></div>');
        $('body').append($div);

        var l1 = $div.find('#s1').get(0).getBoundingClientRect().left;
        var l2 = $div.find('#s2').get(0).getBoundingClientRect().left;

        $div.remove();

        alignment = l1 < l2 ? 'left' : 'right';
      }

      return alignment;
    }

    function refresh ($btn) {
      var blocks = editor.selection.blocks();
      if (blocks.length) {
        var alignment = get($(blocks[0]));

        $btn.find('> *:first').replaceWith(editor.icon.create('align-' + alignment));
      }
    }

    function refreshOnShow($btn, $dropdown) {
      var blocks = editor.selection.blocks();
      if (blocks.length) {
        var alignment = get($(blocks[0]));

        $dropdown.find('a.fr-command[data-param1="' + alignment + '"]').addClass('fr-active');
      }
    }

    return {
      apply: apply,
      refresh: refresh,
      refreshOnShow: refreshOnShow
    }
  }

  $.FroalaEditor.DefineIcon('align', { NAME: 'align-left' });
  $.FroalaEditor.DefineIcon('align-left', { NAME: 'align-left' });
  $.FroalaEditor.DefineIcon('align-right', { NAME: 'align-right' });
  $.FroalaEditor.DefineIcon('align-center', { NAME: 'align-center' });
  $.FroalaEditor.DefineIcon('align-justify', { NAME: 'align-justify' });
  $.FroalaEditor.RegisterCommand('align', {
    type: 'dropdown',
    title: 'Align',
    options: {
      left: 'Align Left',
      center: 'Align Center',
      right: 'Align Right',
      justify: 'Align Justify'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FroalaEditor.COMMANDS.align.options;
      for (var val in options) {
        c += '<li><a class="fr-command" data-cmd="align" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.icon.create('align-' + val) + '</a></li>';
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.align.apply(val);
    },
    refresh: function ($btn) {
      this.align.refresh($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.align.refreshOnShow($btn, $dropdown);
    }
  })

})(jQuery);
