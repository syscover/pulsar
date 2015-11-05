/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.FroalaEditor.PLUGINS.fullscreen = function (editor) {
    var old_scroll;

    /**
     * Check if fullscreen mode is active.
     */
    function isActive () {
      return editor.$box.hasClass('fr-fullscreen');
    }

    /**
     * Turn fullscreen on.
     */
    var $placeholder;
    function _on () {
      old_scroll = $(editor.original_window).scrollTop();
      editor.$box.toggleClass('fr-fullscreen');
      $('body').toggleClass('fr-fullscreen');
      $placeholder = $('<div style="display: none;"></div>');
      editor.$box.after($placeholder).appendTo($('body'));

      if (editor.helpers.isMobile()) {
        editor.$tb.data('parent', editor.$tb.parent());
        editor.$tb.prependTo(editor.$box);
        if (editor.$tb.data('sticky-dummy')) {
          editor.$tb.after(editor.$tb.data('sticky-dummy'));
        }
      }

      editor.$wp.css('max-height', '');
      editor.$wp.css('height', $(editor.original_window).height() - (editor.opts.toolbarInline ? 0 : editor.$tb.outerHeight()));

      if (editor.opts.toolbarInline) editor.toolbar.showInline();

      editor.events.trigger('charCounter.update');
      editor.$window.trigger('scroll.sticky' + editor.id);
    }

    /**
     * Turn fullscreen off.
     */
    function _off () {
      $placeholder.replaceWith(editor.$box);
      editor.$box.toggleClass('fr-fullscreen');
      $('body').toggleClass('fr-fullscreen');

      editor.$tb.prependTo(editor.$tb.data('parent'));
      if (editor.$tb.data('sticky-dummy')) {
        editor.$tb.after(editor.$tb.data('sticky-dummy'));
      }

      editor.$wp.css('height', '');
      editor.size.refresh();

      $(editor.original_window).scrollTop(old_scroll)

      if (editor.opts.toolbarInline) editor.toolbar.showInline();

      editor.events.trigger('charCounter.update');
      editor.$window.trigger('scroll.sticky' + editor.id);
    }

    /**
     * Exec fullscreen.
     */
    function toggle () {
      if (!isActive()) {
        _on();
      }
      else {
        _off();
      }

      refresh(editor.$tb.find('.fr-command[data-cmd="fullscreen"]'));
    }

    function refresh ($btn) {
      var active = isActive();

      $btn.toggleClass('fr-active', active);
      $btn.find('i')
        .toggleClass('fa-expand', !active)
        .toggleClass('fa-compress', active);
    }

    function _init () {
      if (!editor.$wp) return false;

      $(editor.original_window).on('resize.fullscreen' + editor.id, function () {
        if (isActive()) {
          _off();
          _on();
        }
      });

      editor.events.on('toolbar.hide', function () {
        if (isActive() && editor.helpers.isMobile()) return false;
      })

      editor.events.on('destroy', function () {
        $(editor.original_window).off('resize.fullscreen' + editor.id);
      });
    }

    return {
      _init: _init,
      toggle: toggle,
      refresh: refresh,
      isActive: isActive
    }
  }

  // Register the font size command.
  $.FroalaEditor.RegisterCommand('fullscreen', {
    title: 'Fullscreen',
    undo: false,
    focus: false,
    forcedRefresh: true,
    callback: function () {
      this.fullscreen.toggle();
    },
    refresh: function ($btn) {
      this.fullscreen.refresh($btn);
    }
  })

  // Add the font size icon.
  $.FroalaEditor.DefineIcon('fullscreen', {
    NAME: 'expand'
  });

})(jQuery);
