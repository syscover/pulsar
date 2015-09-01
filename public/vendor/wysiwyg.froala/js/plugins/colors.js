/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.POPUP_TEMPLATES, {
    'colors.picker': '[_TABS_][_TEXT_COLORS_][_BACKGROUND_COLORS_]'
  })

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {
    colorsText: [
      '#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
      '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
      '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
      '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', 'REMOVE'
    ],
    colorsBackground: [
      '#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
      '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
      '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
      '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', 'REMOVE'
    ],
    colorsStep: 7,
    colorsDefaultTab: 'text'
  });

  $.FroalaEditor.PLUGINS.colors = function (editor) {
    /*
     * Show the colors popup.
     */
    function _showColorsPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="color"]');

      var $popup = editor.popups.get('colors.picker');
      if (!$popup) $popup = _initColorsPopup();

      if (!$popup.hasClass('fr-active')) {
        // Colors popup
        editor.popups.setContainer('colors.picker', editor.$tb);

        // Refresh selected color.
        _refreshColor($popup.find('.fr-selected-tab').attr('data-param1'));

        // Colors popup left and top position.
        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
        editor.popups.show('colors.picker', left, top, $btn.outerHeight());
      }
    }

    /*
     * Hide colors popup.
     */
    function _hideColorsPopup () {
      // Hide popup.
      editor.popups.hide('colors.picker');
    }

    /**
     * Init the colors popup.
     */
    function _initColorsPopup () {
      var template = {
        tabs: _colorsTabsHTML(),
        text_colors: _colorPickerHTML('text'),
        background_colors: _colorPickerHTML('background')
      };

      // Create popup.
      var $popup = editor.popups.create('colors.picker', template);

      return $popup;
    }

    /*
     * HTML for the color picker text and background tabs.
     */
    function _colorsTabsHTML () {
      var tabs_html = '<div class="fr-colors-tabs">';

      // Text tab.
      tabs_html += '<span class="fr-colors-tab ' + (editor.opts.colorsDefaultTab == 'background' ? '' : 'fr-selected-tab ') + 'fr-command" data-param1="text" data-cmd="colorChangeSet" title="' + editor.language.translate('Text') + '">' + editor.language.translate('Text') + '</span>';

      // Background tab.
      tabs_html += '<span class="fr-colors-tab ' + (editor.opts.colorsDefaultTab == 'background' ? 'fr-selected-tab ' : '') + 'fr-command" data-param1="background" data-cmd="colorChangeSet" title="' + editor.language.translate('Background') + '">' + editor.language.translate('Background') + '</span>';

      return tabs_html + '</div>';
    }

    /*
     * HTML for the color picker colors.
     */
    function _colorPickerHTML (tab) {
      // Get colors according to tab name.
      var colors = (tab == 'text' ? editor.opts.colorsText : editor.opts.colorsBackground);

      // Create colors html.
      var colors_html = '<div class="fr-color-set fr-' + tab + '-color' + ((editor.opts.colorsDefaultTab == tab || (editor.opts.colorsDefaultTab != 'text' && editor.opts.colorsDefaultTab != 'background' && tab == 'text')) ? ' fr-selected-set' : '') + '">';

      // Add colors.
      for (var i = 0; i < colors.length; i++) {
        if (i !== 0 && i % editor.opts.colorsStep === 0) {
          colors_html += '<br>';
        }

        if (colors[i] != 'REMOVE') {
          colors_html += '<span class="fr-command fr-select-color" style="background: ' + colors[i] + ';" data-cmd="' + tab + 'Color" data-param1="' + colors[i] + '"></span>';
        }

        else {
          colors_html += '<span class="fr-command fr-select-color" data-cmd="' + tab + 'Color" data-param1="REMOVE" title="' + editor.language.translate('Clear Formatting') + '"><i class="fa fa-eraser"></i></span>';
        }
      }

      return colors_html + '</div>';
    }

    /*
     * Show the current selected color.
     */
    function _refreshColor (tab) {
      var $popup = editor.popups.get('colors.picker');
      var $element = $(editor.selection.element());

      // The color css property.
      if (tab == 'background') {
        var color_type = 'background-color';
      }
      else {
        var color_type = 'color';
      }

      // Remove current color selection.
      $popup.find('.fr-' + tab + '-color .fr-select-color').removeClass('fr-selected-color');

      // Find the selected color.
      while ($element.get(0) != editor.$el.get(0)) {
        // Transparent or black.
        if ($element.css(color_type) == 'transparent' || $element.css(color_type) == 'rgba(0, 0, 0, 0)') {
          $element = $element.parent();
        }

        // Select the correct color.
        else {
          $popup.find('.fr-' + tab + '-color .fr-select-color[data-param1="' + editor.helpers.RGBToHex($element.css(color_type)) + '"]').addClass('fr-selected-color');
          break;
        }
      }
    }

    /*
     * Change the colors' tab.
     */
    function _changeSet ($tab, val) {
      // Only on the tab that is not selected yet. On left click only.
      if (!$tab.hasClass('fr-selected-tab')) {
        // Switch selected tab.
        $tab.siblings().removeClass('fr-selected-tab');
        $tab.addClass('fr-selected-tab');

        // Switch the color set.
        $tab.parents('.fr-popup').find('.fr-color-set').removeClass('fr-selected-set');
        $tab.parents('.fr-popup').find('.fr-color-set.fr-' + val + '-color').addClass('fr-selected-set');

        // Refresh selected color.
        _refreshColor(val);
      }
    }

    /*
     * Change background color.
     */
    function background (val) {
      // Set background  color.
      if (val != 'REMOVE') {
        editor.events.focus();
        editor.selection.restore();

        editor.commands.applyProperty('background-color', val);
      }

      // Remove background color.
      else {
        editor.commands.applyProperty('background-color', '#123456');

        editor.selection.save();
        editor.$el.find('span').each(function (index, span) {
          var $span = $(span);
          var color = $span.css('background-color');

          if (color === '#123456' || editor.helpers.RGBToHex(color) === '#123456') {
            $span.replaceWith($span.html());
          }
        });
        editor.selection.restore();
      }

      _hideColorsPopup();
    }

    /*
     * Change text color.
     */
    function text (val) {
      // Set text color.
      if (val != 'REMOVE') {
        editor.commands.applyProperty('color', val)
      }

      // Remove text color.
      else {
        editor.commands.applyProperty('color', '#123456');

        editor.selection.save();
        editor.$el.find('span').each(function (index, span) {
          var $span = $(span);
          var color = $span.css('color');

          if (color === '#123456' || editor.helpers.RGBToHex(color) === '#123456') {
            $span.replaceWith($span.html());
          }
        });
        editor.selection.restore();
      }

      _hideColorsPopup();
    }

    /*
     * Init color picker.
     */
    function _init () {
    }

    return {
      _init: _init,
      showColorsPopup: _showColorsPopup,
      hideColorsPopup: _hideColorsPopup,
      changeSet: _changeSet,
      background: background,
      text: text
    }
  }

  // Toolbar colors button.
  $.FroalaEditor.DefineIcon('colors', { NAME: 'tint' });
  $.FroalaEditor.RegisterCommand('color', {
    title: 'Colors',
    undo: false,
    focus: false,
    refreshOnCallback: false,
    callback: function () {
      this.colors.showColorsPopup();
    }
  });

  // Select text color command.
  $.FroalaEditor.RegisterCommand('textColor', {
    undo: true,
    callback: function (cmd, val) {
      this.colors.text(val);
    }
  });

  // Select background color command.
  $.FroalaEditor.RegisterCommand('backgroundColor', {
    undo: true,
    callback: function (cmd, val) {
      this.colors.background(val);
    }
  });

  $.FroalaEditor.RegisterCommand('colorChangeSet', {
    undo: false,
    focus: false,
    callback: function (cmd, val) {
      var $tab = this.popups.get('colors.picker').find('.fr-command[data-cmd="' + cmd + '"][data-param1="' + val + '"]');
      this.colors.changeSet($tab, val);
    }
  })
})(jQuery);
