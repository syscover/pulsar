/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.DEFAULTS, {
    fontFamily: {
      'Arial,Helvetica,sans-serif': 'Arial',
      'Georgia,serif': 'Georgia',
      'Impact,Charcoal,sans-serif': 'Impact',
      'Tahoma,Geneva,sans-serif': 'Tahoma',
      '\'Times New Roman\',Times,serif': 'Times New Roman',
      'Verdana,Geneva,sans-serif': 'Verdana'
    },
    fontFamilySelection: false
  })

  $.FroalaEditor.PLUGINS.fontFamily = function (editor) {
    function apply (val) {
      editor.commands.applyProperty('font-family', val);
    }

    function refreshOnShow($btn, $dropdown) {
      var val = $(editor.selection.element()).css('font-family');
      $dropdown.find('.fr-command.fr-active').removeClass('fr-active');
      $dropdown.find('.fr-command[data-param1="' + val + '"]').addClass('fr-active');

      var $list = $dropdown.find('.fr-dropdown-list');
      var $active = $dropdown.find('.fr-active').parent();
      if ($active.length) {
        $list.parent().scrollTop($active.offset().top - $list.offset().top - ($list.parent().outerHeight() / 2 - $active.outerHeight() / 2));
      }
      else {
        $list.parent().scrollTop(0);
      }
    }

    function refresh($btn, $dropdown) {
      var val = $(editor.selection.element()).css('font-family');
      $btn.find('> span').text($dropdown.find('.fr-command[data-param1="' + val.replace(/"/g,'\'').replace(/ /g, '') + '"]').text() || 'Times New Roman');
    }

    return {
      apply: apply,
      refreshOnShow: refreshOnShow,
      refresh: refresh
    }
  }

  // Register the font size command.
  $.FroalaEditor.RegisterCommand('fontFamily', {
    type: 'dropdown',
    displaySelection: function (editor) {
      return editor.opts.fontFamilySelection;
    },
    displaySelectionWidth: 120,
    defaultSelection: 'Times New Roman',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options = this.opts.fontFamily;
      for (var val in options) {
        c += '<li><a class="fr-command" data-cmd="fontFamily" data-param1="' + val + '" style="font-family: ' + options[val] + '" title="' + options[val] + '">' + options[val] + '</a></li>';
      }
      c += '</ul>';

      return c;
    },
    title: 'Font Family',
    callback: function (cmd, val) {
      this.fontFamily.apply(val);
    },
    refresh: function ($btn, $dropdown) {
      this.fontFamily.refresh($btn, $dropdown);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.fontFamily.refreshOnShow($btn, $dropdown);
    }
  })

  // Add the font size icon.
  $.FroalaEditor.DefineIcon('fontFamily', {
    NAME: 'font'
  });

})(jQuery);
