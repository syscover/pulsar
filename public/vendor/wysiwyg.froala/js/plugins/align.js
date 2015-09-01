/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.FroalaEditor.PLUGINS.align = function (editor) {
    function apply (val) {
      var blocks = editor.selection.blocks();
      for (var i = 0; i < blocks.length; i++) {
        $(blocks[i]).css('text-align', val);
      }
    }

    function refresh ($btn) {
      var blocks = editor.selection.blocks();
      if (blocks.length) {
        var alignment = $(blocks[0]).css('text-align');

        // Detect rtl.
        if (alignment == 'start') {
          var $div = $('<div dir="auto" css="width: 100px; text-align: initial;"><span id="s1">.</span><span id="s2">.</span></div>');
          $('body').append($div);

          var l1 = $div.find('#s1').get(0).getBoundingClientRect().left;
          var l2 = $div.find('#s2').get(0).getBoundingClientRect().left;

          $div.remove();

          alignment = l1 < l2 ? 'left' : 'right';
        }

        if (alignment == 'left') {
          $btn.find('i').attr('class', 'fa fa-align-left');
        }
        else if (alignment == 'right') {
          $btn.find('i').attr('class', 'fa fa-align-right');
        }
        else if (alignment == 'center') {
          $btn.find('i').attr('class', 'fa fa-align-center');
        }
        else {
          $btn.find('i').attr('class', 'fa fa-align-justify');
        }
      }
    }

    function refreshOnShow($btn, $dropdown) {
      var blocks = editor.selection.blocks();
      if (blocks.length) {
        var alignment = $(blocks[0]).css('text-align');

        $dropdown.find('a.fr-command[data-val="' + alignment + '"]').addClass('fr-active');
      }
    }

    return {
      apply: apply,
      refresh: refresh,
      refreshOnShow: refreshOnShow
    }
  }

  $.FroalaEditor.DefineIcon('align', { NAME: 'align-left' });
  $.FroalaEditor.RegisterCommand('align', {
    type: 'dropdown',
    title: 'Align',
    options: {
      'left': 'Align Left',
      'center': 'Align Center',
      'right': 'Align Right',
      'justify': 'Align Justify'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FroalaEditor.COMMANDS.align.options;
      for (var val in options) {
        c += '<li><a class="fr-command" data-cmd="align" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '"><i class="fa fa-align-' + val + '"></i></a></li>';
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
