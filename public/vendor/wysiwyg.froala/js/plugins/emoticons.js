/*!
 * froala_editor v2.0.0-rc.1 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.POPUP_TEMPLATES, {
    'emoticons': '[_EMOTICONS_]'
  })

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {
    emoticonsStep: 8,
    emoticonsSet: [
      {code: '&#x1f600;', desc: 'Grinning face'},
      {code: '&#x1f601;', desc: 'Grinning face with smiling eyes'},
      {code: '&#x1f602;', desc: 'Face with tears of joy'},
      {code: '&#x1f603;', desc: 'Smiling face with open mouth'},
      {code: '&#x1f604;', desc: 'Smiling face with open mouth and smiling eyes'},
      {code: '&#x1f605;', desc: 'Smiling face with open mouth and cold sweat'},
      {code: '&#x1f606;', desc: 'Smiling face with open mouth and tightly-closed eyes'},
      {code: '&#x1f607;', desc: 'Smiling face with halo'},

      {code: '&#x1f608;', desc: 'Smiling face with horns'},
      {code: '&#x1f609;', desc: 'Winking face'},
      {code: '&#x1f60a;', desc: 'Smiling face with smiling eyes'},
      {code: '&#x1f60b;', desc: 'Face savoring delicious food'},
      {code: '&#x1f60c;', desc: 'Relieved face'},
      {code: '&#x1f60d;', desc: 'Smiling face with heart-shaped eyes'},
      {code: '&#x1f60e;', desc: 'Smiling face with sunglasses'},
      {code: '&#x1f60f;', desc: 'Smirking face'},

      {code: '&#x1f610;', desc: 'Neutral face'},
      {code: '&#x1f611;', desc: 'Expressionless face'},
      {code: '&#x1f612;', desc: 'Unamused face'},
      {code: '&#x1f613;', desc: 'Face with cold sweat'},
      {code: '&#x1f614;', desc: 'Pensive face'},
      {code: '&#x1f615;', desc: 'Confused face'},
      {code: '&#x1f616;', desc: 'Confounded face'},
      {code: '&#x1f617;', desc: 'Kissing face'},

      {code: '&#x1f618;', desc: 'Face throwing a kiss'},
      {code: '&#x1f619;', desc: 'Kissing face with smiling eyes'},
      {code: '&#x1f61a;', desc: 'Kissing face with closed eyes'},
      {code: '&#x1f61b;', desc: 'Face with stuck out tongue'},
      {code: '&#x1f61c;', desc: 'Face with stuck out tongue and winking eye'},
      {code: '&#x1f61d;', desc: 'Face with stuck out tongue and tightly-closed eyes'},
      {code: '&#x1f61e;', desc: 'Disappointed face'},
      {code: '&#x1f61f;', desc: 'Worried face'},

      {code: '&#x1f620;', desc: 'Angry face'},
      {code: '&#x1f621;', desc: 'Pouting face'},
      {code: '&#x1f622;', desc: 'Crying face'},
      {code: '&#x1f623;', desc: 'Persevering face'},
      {code: '&#x1f624;', desc: 'Face with look of triumph'},
      {code: '&#x1f625;', desc: 'Disappointed but relieved face'},
      {code: '&#x1f626;', desc: 'Frowning face with open mouth'},
      {code: '&#x1f627;', desc: 'Anguished face'},

      {code: '&#x1f628;', desc: 'Fearful face'},
      {code: '&#x1f629;', desc: 'Weary face'},
      {code: '&#x1f62a;', desc: 'Sleepy face'},
      {code: '&#x1f62b;', desc: 'Tired face'},
      {code: '&#x1f62c;', desc: 'Grimacing face'},
      {code: '&#x1f62d;', desc: 'Loudly crying face'},
      {code: '&#x1f62e;', desc: 'Face with open mouth'},
      {code: '&#x1f62f;', desc: 'Hushed face'},

      {code: '&#x1f630;', desc: 'Face with open mouth and cold sweat'},
      {code: '&#x1f631;', desc: 'Face screaming in fear'},
      {code: '&#x1f632;', desc: 'Astonished face'},
      {code: '&#x1f633;', desc: 'Flushed face'},
      {code: '&#x1f634;', desc: 'Sleeping face'},
      {code: '&#x1f635;', desc: 'Dizzy face'},
      {code: '&#x1f636;', desc: 'Face without mouth'},
      {code: '&#x1f637;', desc: 'Face with medical mask'}
    ]
  });

  $.FroalaEditor.PLUGINS.emoticons = function (editor) {
    /*
     * Show the emoticons popup.
     */
    function _showEmoticonsPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="emoticons"]');

      var $popup = editor.popups.get('emoticons');
      if (!$popup) $popup = _initEmoticonsPopup();

      if (!$popup.hasClass('fr-active')) {
        // Colors popup
        editor.popups.refresh('emoticons');
        editor.popups.setContainer('emoticons', editor.$tb);

        // Colors popup left and top position.
        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);

        editor.popups.show('emoticons', left, top, $btn.outerHeight());
      }
    }

    /*
     * Hide emoticons popup.
     */
    function _hideEmoticonsPopup () {
      // Hide popup.
      editor.popups.hide('emoticons');
    }

    /**
     * Init the emoticons popup.
     */
    function _initEmoticonsPopup () {
      var template = {
        emoticons: _emoticonsHTML()
      };

      // Create popup.
      var $popup = editor.popups.create('emoticons', template);

      // Assing tooltips to buttons.
      editor.tooltip.bind($popup, '.fr-emoticon');

      return $popup;
    }

    /*
     * HTML for the emoticons popup.
     */
    function _emoticonsHTML () {
      // Create emoticons html.
      var emoticons_html = '<div>';

      // Add emoticons.
      for (var i = 0; i < editor.opts.emoticonsSet.length; i++) {
        if (i !== 0 && i % editor.opts.emoticonsStep === 0) {
          emoticons_html += '<br>';
        }

        emoticons_html += '<span class="fr-command fr-emoticon" data-cmd="insertEmoticon" title="' + editor.language.translate(editor.opts.emoticonsSet[i].desc) + '" data-param1="' + editor.opts.emoticonsSet[i].code + '">' + editor.opts.emoticonsSet[i].code + '</span>';
      }

      emoticons_html += '</div>';

      return emoticons_html;
    }

    /*
     * Insert emoticon.
     */
    function insert (emoticon) {
      // Insert emoticon.
      editor.html.insert('<span style="font-weight: normal;">' + emoticon + '</span>');

      // Trigger event.
      editor.events.trigger('table.inserted');
    }

    /*
     * Init emoticons.
     */
    function _init () {
      // Replace emoticons with unicode.
      editor.events.on('html.get', function (html) {
        for (var i = 0; i < editor.opts.emoticonsSet.length; i++) {
          var em = editor.opts.emoticonsSet[i];
          var text = $('<div>').html(em.code).text();
          html = html.split(text).join(em.code);
        }

        return html;
      });
    }

    return {
      _init: _init,
      insert: insert,
      showEmoticonsPopup: _showEmoticonsPopup,
      hideEmoticonsPopup: _hideEmoticonsPopup
    }
  }

  // Toolbar emoticons button.
  $.FroalaEditor.DefineIcon('emoticons', { NAME: 'smile-o' });
  $.FroalaEditor.RegisterCommand('emoticons', {
    title: 'Emoticons',
    undo: false,
    focus: false,
    refreshOnCallback: false,
    callback: function () {
      this.emoticons.showEmoticonsPopup();
    }
  });

  // Insert emoticon command.
  $.FroalaEditor.RegisterCommand('insertEmoticon', {
    callback: function (cmd, val) {
      // Insert emoticon.
      this.emoticons.insert(val);

      // Hide emoticons popup.
      this.emoticons.hideEmoticonsPopup();
    }
  });
})(jQuery);
