/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.POPUP_TEMPLATES, {
    'video.insert': '[_BUTTONS_][_BY_URL_LAYER_][_EMBED_LAYER_]',
    'video.edit': '[_BUTTONS_]',
    'video.size': '[_BUTTONS_][_SIZE_LAYER_]'
  })

  $.extend($.FroalaEditor.DEFAULTS, {
    videoInsertButtons: ['videoBack', '|', 'videoByURL', 'videoEmbed'],
    videoEditButtons: ['videoDisplay', 'videoAlign', 'videoSize', 'videoRemove'],
    videoResize: true,
    videoSizeButtons: ['videoBack', '|'],
    videoTextNear: true,
    videoDefaultAlign: 'center',
    videoDefaultDisplay: 'block',
    videoIframeStyle: '.fr-video{text-align:center;position:relative}.fr-video:after{position:absolute;content:"";z-index:1;top:0;left:0;right:0;bottom:0;cursor:pointer}.fr-video.fr-active>*{z-index:2;position:relative}.fr-video.fr-dvb{display:block;clear:both}.fr-video.fr-dvb.fr-fvl{text-align:left}.fr-video.fr-dvb.fr-fvr{text-align:right}.fr-video.fr-dvi{display:inline-block}.fr-video.fr-dvi.fr-fvl{float:left}.fr-video.fr-dvi.fr-fvr{float:right}'
  });

  $.FroalaEditor.VIDEO_PROVIDERS = [
    {
      test_regex: /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/,
      url_regex: /(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/)?([0-9a-zA-Z_\-]+)(.+)?/g,
      url_text: '//www.youtube.com/embed/$1',
      html: '<iframe width="640" height="360" src="{url}" frameborder="0" allowfullscreen></iframe>'
    },
    {
      test_regex: /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/,
      url_regex: /(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com)\/(?:channels\/[A-z]+\/|groups\/[A-z]+\/videos\/)?(.+)/g,
      url_text: '//player.vimeo.com/video/$1',
      html: '<iframe width="640" height="360" src="{url}" frameborder="0" allowfullscreen></iframe>'
    },
    {
      test_regex: /^.+(dailymotion.com|dai.ly)\/(video|hub)?\/?([^_]+)[^#]*(#video=([^_&]+))?/,
      url_regex: /(?:https?:\/\/)?(?:www\.)?(?:dailymotion\.com|dai\.ly)\/(?:video|hub)?\/?(.+)/g,
      url_text: '//www.dailymotion.com/embed/video/$1',
      html: '<iframe width="640" height="360" src="{url}" frameborder="0" allowfullscreen></iframe>'
    },
    {
      test_regex: /^.+(screen.yahoo.com)\/(videos-for-you|popular)?\/[^_&]+/,
      url_regex: '',
      url_text: '',
      html: '<iframe width="640" height="360" src="{url}?format=embed" frameborder="0" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true" allowtransparency="true"></iframe>'
    }
  ];

  $.FroalaEditor.PLUGINS.video = function (editor) {
    var $overlay;
    var $handler;
    var $video_resizer;
    var $current_video;

    /**
     * Refresh the image insert popup.
     */
    function _refreshInsertPopup () {
      var $popup = editor.popups.get('video.insert');

      var $url_input = $popup.find('.fr-video-by-url-layer input');
      $url_input.val('').trigger('change');

      var $embed_area = $popup.find('.fr-video-embed-layer textarea');
      $embed_area.val('').trigger('change');
    }

    /**
     * Show the video insert popup.
     */
    function showInsertPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="insertVideo"]');

      var $popup = editor.popups.get('video.insert');
      if (!$popup) $popup = _initInsertPopup();

      if (!$popup.hasClass('fr-active')) {
        editor.popups.refresh('video.insert');
        editor.popups.setContainer('video.insert', editor.$tb);

        if (editor.core.hasFocus()) {
          editor.selection.save();
        }

        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
        editor.popups.show('video.insert', left, top, $btn.outerHeight());
      }
    }

    /**
     * Show the image edit popup.
     */
    function _showEditPopup () {
      var $popup = editor.popups.get('video.edit');
      if (!$popup) $popup = _initEditPopup();

      editor.popups.setContainer('video.edit', $(editor.opts.scrollableContainer));
      editor.popups.refresh('video.edit');

      var $video_obj = $current_video.find('iframe, embed, video');
      var left = $video_obj.offset().left + $video_obj.outerWidth() / 2;
      var top = $video_obj.offset().top + $video_obj.outerHeight();

      editor.popups.show('video.edit', left, top, $video_obj.outerHeight());
    }

    function _initInsertPopup () {
      // Image buttons.
      var video_buttons = '';
      if (editor.opts.videoInsertButtons.length > 1) {
        video_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.videoInsertButtons) + '</div>';
      }

      // Video by url layer.
      var by_url_layer = '';
      if (editor.opts.videoInsertButtons.indexOf('videoByURL') >= 0) {
        by_url_layer = '<div class="fr-video-by-url-layer fr-layer fr-active" id="fr-video-by-url-layer-' + editor.id + '"><div class="fr-input-line"><input type="text" placeholder="http://" tabIndex="1"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="videoInsertByURL" tabIndex="2">' + editor.language.translate('Insert') + '</button></div></div>'
      }

      // Video embed layer.
      var embed_layer = '';
      if (editor.opts.videoInsertButtons.indexOf('videoEmbed') >= 0) {
        embed_layer = '<div class="fr-video-embed-layer fr-layer" id="fr-video-embed-layer-' + editor.id + '"><div class="fr-input-line"><textarea type="text" placeholder="' + editor.language.translate('Embedded Code') + '" tabIndex="1" rows="5"></textarea></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="videoInsertEmbed" tabIndex="2">' + editor.language.translate('Insert') + '</button></div></div>'
      }

      var template = {
        buttons: video_buttons,
        by_url_layer: by_url_layer,
        embed_layer: embed_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('video.insert', template);

      editor.popups.onRefresh('video.insert', _refreshInsertPopup);
      editor.popups.onHide('video.insert', _hideInsertPopup);

      return $popup;
    }

    /**
     * Show the image upload layer.
     */
    function showLayer (name) {
      var $popup = editor.popups.get('video.insert');

      var left;
      var top;
      if (!$current_video && !editor.opts.toolbarInline) {
        var $btn = editor.$tb.find('.fr-command[data-cmd="insertVideo"]');
        left = $btn.offset().left + $btn.outerWidth() / 2;
        top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
      }

      if (editor.opts.toolbarInline) {
        // Set top to the popup top.
        top = $popup.offset().top - editor.helpers.getPX($popup.css('margin-top'));

        // If the popup is above apply height correction.
        if ($popup.hasClass('fr-above')) {
          top += $popup.outerHeight();
        }
      }

      // Show the new layer.
      $popup.find('.fr-layer').removeClass('fr-active');
      $popup.find('.fr-' + name + '-layer').addClass('fr-active');

      editor.popups.show('video.insert', left, top, 0);
    }

    /**
     * Refresh the insert by url button.
     */
    function refreshByURLButton ($btn) {
      var $popup = editor.popups.get('video.insert');
      if ($popup.find('.fr-video-by-url-layer').hasClass('fr-active')) {
        $btn.addClass('fr-active');
      }
    }

    /**
     * Refresh the insert embed button.
     */
    function refreshEmbedButton ($btn) {
      var $popup = editor.popups.get('video.insert');
      if ($popup.find('.fr-video-embed-layer').hasClass('fr-active')) {
        $btn.addClass('fr-active');
      }
    }

    /**
     * Hide video insert popup.
     */
    function _hideInsertPopup () {
      if (!$current_video && editor.$el.find('.fr-marker').length) {
        editor.events.focus();
        editor.selection.restore();
      }
    }

    /**
     * Insert video embedded object.
     */
    function insert (embedded_code) {
      // Make sure we have focus.
      editor.events.focus(true);
      editor.selection.restore();

      editor.html.insert('<span class="fr-jiv fr-video fr-dv' + (editor.opts.videoDefaultDisplay[0]) + (editor.opts.videoDefaultAlign != 'center' ? ' fr-fv' + editor.opts.videoDefaultAlign[0] : '') + '">' + embedded_code + '<span>');

      editor.popups.hide('video.insert');

      var $video = editor.$el.find('.fr-jiv');
      $video.removeClass('fr-jiv');

      editor.events.trigger('video.inserted', [$video]);
    }

    /**
     * Insert video by URL.
     */
    function insertByURL (link) {
      if (typeof link == 'undefined') {
        var $popup = editor.popups.get('video.insert');
        link = $popup.find('.fr-video-by-url-layer input[type="text"]').val() || '';
      }

      var video = null;
      for (var i = 0; i < $.FroalaEditor.VIDEO_PROVIDERS.length; i++) {
        var vp = $.FroalaEditor.VIDEO_PROVIDERS[i];
        if (vp.test_regex.test(link)) {
          video = link.replace(vp.url_regex, vp.url_text);
          video = vp.html.replace(/\{url\}/, video);
          break;
        }
      }

      if (video) {
        insert(video);
      }
      else {
        editor.events.trigger('video.linkError', [link]);
      }
    }

    /**
     * Insert embedded video.
     */
    function insertEmbed (code) {
      if (typeof code == 'undefined') {
        var $popup = editor.popups.get('video.insert');
        code = $popup.find('.fr-video-embed-layer textarea').val() || '';
      }

      if (code.length === 0) {
        editor.events.trigger('video.codeError', [code]);
      }
      else {
        insert(code);
      }
    }

    /**
     * Mouse down to start resize.
     */
    function _handlerMousedown (e) {
      e.preventDefault();
      e.stopPropagation();

      var c_x = e.pageX || (e.originalEvent.touches ? e.originalEvent.touches[0].pageX : null);
      var c_y = e.pageY || (e.originalEvent.touches ? e.originalEvent.touches[0].pageY : null);

      if (!c_x || !c_y) {
        return false;
      }

      $handler = $(this);
      $handler.data('start-x', c_x);
      $handler.data('start-y', c_y);
      $overlay.show();
      editor.popups.hideAll();
    }

    /**
     * Do resize.
     */
    function _handlerMousemove (e) {
      if ($handler) {
        e.preventDefault()

        var c_x = e.pageX || (e.originalEvent.touches ? e.originalEvent.touches[0].pageX : null);
        var c_y = e.pageY || (e.originalEvent.touches ? e.originalEvent.touches[0].pageY : null);

        if (!c_x || !c_y) {
          return false;
        }

        var s_x = $handler.data('start-x');
        var s_y = $handler.data('start-y');

        $handler.data('start-x', c_x);
        $handler.data('start-y', c_y);

        var diff_x = c_x - s_x;
        var diff_y = c_y - s_y;

        var $video_obj = $current_video.find('iframe, embed, video');

        var width = $video_obj.width();
        var height = $video_obj.height();
        if ($handler.hasClass('fr-hnw') || $handler.hasClass('fr-hsw')) {
          diff_x = 0 - diff_x;
        }

        if ($handler.hasClass('fr-hnw') || $handler.hasClass('fr-hne')) {
          diff_y = 0 - diff_y;
        }

        $video_obj.css('width', width + diff_x);
        $video_obj.css('height', height + diff_y);
        $video_obj.removeAttr('width');
        $video_obj.removeAttr('height');

        _repositionResizer();
      }
    }

    /**
     * Stop resize.
     */
    function _handlerMouseup (e) {
      if ($handler) {
        if (e) e.preventDefault();
        $handler = null;
        $overlay.hide();
        _repositionResizer();
        _showEditPopup();
      }
    }

    /**
     * Create resize handler.
     */
    function _getHandler (pos) {
      return '<div class="fr-handler fr-h' + pos + '"></div>';
    }

    /**
     * Init video resizer.
     */
    function _initResizer() {
      $video_resizer = $('<div class="fr-video-resizer"></div>');
      editor.$wp.append($video_resizer);

      $(editor.original_window).on('resize.video' + editor.id, _exitEdit);

      editor.events.on('destroy', function () {
        $video_resizer.html('').removeData().remove();
        $(editor.original_window).off('resize.video' + editor.id);
      }, true);

      if (editor.opts.videoResize) {
        $video_resizer.append(_getHandler('nw') + _getHandler('ne') + _getHandler('sw') + _getHandler('se'))

        var doc = $video_resizer.get(0).ownerDocument;
        $video_resizer.on(editor._mousedown + '.vidresize' + editor.id, '.fr-handler', _handlerMousedown);
        $(doc).on(editor._mousemove + '.vidresize' + editor.id, _handlerMousemove);
        $(doc.defaultView || doc.parentWindow).on(editor._mouseup + '.vidresize' + editor.id, _handlerMouseup);

        $overlay = $('<div class="fr-video-overlay"></div>');
        $(doc).find('body').append($overlay);

        $overlay.on('mouseleave', _handlerMouseup);

        editor.events.on('destroy', function () {
          $video_resizer.off(editor._mousedown + '.vidresize' + editor.id);
          $(doc).off(editor._mousemove + '.vidresize' + editor.id);
          $(doc.defaultView || doc.parentWindow).off(editor._mouseup + '.vidresize' + editor.id);
          $overlay.off('mouseleave').remove();
        }, true);
      }
    }

    /**
     * Reposition resizer.
     */
    function _repositionResizer () {
      if (!$video_resizer) _initResizer();

      var $video_obj = $current_video.find('iframe, embed, video');

      $video_resizer
        .css('top', (editor.opts.iframe ? $video_obj.offset().top - 1 : $video_obj.offset().top - editor.$wp.offset().top - 1) + editor.$wp.scrollTop())
        .css('left', (editor.opts.iframe ? $video_obj.offset().left - 1 : $video_obj.offset().left - editor.$wp.offset().left - 1) + editor.$wp.scrollLeft())
        .css('width', $video_obj.outerWidth())
        .css('height', $video_obj.height())
        .addClass('fr-active')
    }

    /**
     * Edit video.
     */
    function _edit (e) {
      e.preventDefault();
      e.stopPropagation();

      if (editor.edit.isDisabled()) {
        return false;
      }

      e.stopPropagation();
      editor.toolbar.disable();

      // Hide keyboard.
      if (editor.helpers.isMobile()) {
        editor.events.disableBlur();
        editor.$el.blur();
        editor.events.enableBlur();
      }

      $current_video = $(this);
      $(this).addClass('fr-active');

      if (editor.opts.iframe) {
        editor.height.syncIframe();
      }

      _repositionResizer();
      _showEditPopup();

      editor.selection.clear();
      editor.button.bulkRefresh();

      editor.events.trigger('image.hideResizer');
    }

    /**
     * Exit edit.
     */
    function _exitEdit (force_exit) {
      if (force_exit === true) exit_flag = true;

      if ($current_video && exit_flag) {
        $video_resizer.removeClass('fr-active');

        editor.toolbar.enable();

        $current_video.removeClass('fr-active');
        $current_video = null;
      }

      exit_flag = false;
    }

    var exit_flag = false;
    function _markExit () {
      exit_flag = true;
    }

    function _unmarkExit () {
      exit_flag = false;
    }

    /**
     * Init the video events.
     */
    function _initEvents () {
      editor.events.on('mousedown', _markExit);
      editor.events.on('window.mousedown', _markExit);
      editor.events.on('window.touchmove', _unmarkExit);
      editor.events.on('mouseup', _exitEdit);
      editor.events.on('window.mouseup', _exitEdit);
      editor.events.on('commands.mousedown', function ($btn) {
        if ($btn.parents('.fr-toolbar').length > 0) {
          _exitEdit();
        }
      });
      editor.events.on('video.hideResizer', _exitEdit);
    }

    /**
     * Init the video edit popup.
     */
    function _initEditPopup () {
      // Image buttons.
      var video_buttons = '';
      if (editor.opts.videoEditButtons.length > 1) {
        video_buttons += '<div class="fr-buttons">';
        video_buttons += editor.button.buildList(editor.opts.videoEditButtons);
        video_buttons += '</div>';
      }

      var template = {
        buttons: video_buttons
      }

      var $popup = editor.popups.create('video.edit', template);

      editor.$wp.on('scroll.video-edit', function () {
        if ($current_video && editor.popups.isVisible('video.edit')) {
          _showEditPopup();
        }
      });

      editor.events.on('destroy', function () {
        editor.$wp.off('scroll.video-edit');
      });

      return $popup;
    }

    /**
     * Refresh the size popup.
     */
    function _refreshSizePopup () {
      if ($current_video) {
        var $popup = editor.popups.get('video.size');
        var $video_obj = $current_video.find('iframe, embed, video')
        $popup.find('input[name="width"]').val($video_obj.get(0).style.width || $video_obj.attr('width')).trigger('change');
        $popup.find('input[name="height"]').val($video_obj.get(0).style.height || $video_obj.attr('height')).trigger('change');
      }
    }

    /**
     * Show the size popup.
     */
    function showSizePopup () {
      var $popup = editor.popups.get('video.size');
      if (!$popup) $popup = _initSizePopup();

      editor.popups.refresh('video.size');
      editor.popups.setContainer('video.size', $(editor.opts.scrollableContainer));
      var $video_obj = $current_video.find('iframe, embed, video')
      var left = $video_obj.offset().left + $video_obj.width() / 2;
      var top = $video_obj.offset().top + $video_obj.height();

      editor.popups.show('video.size', left, top, $video_obj.height());
    }

    /**
     * Init the image upload popup.
     */
    function _initSizePopup () {
      // Image buttons.
      var video_buttons = '';
      video_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.videoSizeButtons) + '</div>';

      // Size layer.
      var size_layer = '';
      size_layer = '<div class="fr-video-size-layer fr-layer fr-active" id="fr-video-size-layer-' + editor.id + '"><div class="fr-video-group"><div class="fr-input-line"><input type="text" name="width" placeholder="' + editor.language.translate('Width') + '" tabIndex="1"></div><div class="fr-input-line"><input type="text" name="height" placeholder="' + editor.language.translate('Height') + '" tabIndex="1"></div></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="videoSetSize" tabIndex="2">' + editor.language.translate('Update') + '</button></div></div>';

      var template = {
        buttons: video_buttons,
        size_layer: size_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('video.size', template);

      editor.popups.onRefresh('video.size', _refreshSizePopup);

      editor.$wp.on('scroll.video-size', function () {
        if ($current_video && editor.popups.isVisible('video.size')) {
          showSizePopup();
        }
      });

      editor.events.on('destroy', function () {
        editor.$wp.off('scroll.video-size');
      });

      return $popup;
    }

    /**
     * Align image.
     */
    function align (val) {
      $current_video.removeClass('fr-fvr fr-fvl');
      if (val == 'left') {
        $current_video.addClass('fr-fvl');
      }
      else if (val == 'right') {
        $current_video.addClass('fr-fvr');
      }

      _repositionResizer();
      _showEditPopup();
    }

    /**
     * Refresh the align icon.
     */
    function refreshAlign ($btn) {
      if ($current_video.hasClass('fr-fvl')) {
        $btn.find('> i').attr('class', 'fa fa-align-left');
      }
      else if ($current_video.hasClass('fr-fvr')) {
        $btn.find('> i').attr('class', 'fa fa-align-right');
      }
      else {
        $btn.find('> i').attr('class', 'fa fa-align-justify');
      }
    }

    /**
     * Refresh the align option from the dropdown.
     */
    function refreshAlignOnShow ($btn, $dropdown) {
      var alignment = 'justify';
      if ($current_video.hasClass('fr-fvl')) {
        alignment = 'left';
      }
      else if ($current_video.hasClass('fr-fvr')) {
        alignment = 'right';
      }

      $dropdown.find('.fr-command[data-param1="' + alignment + '"]').addClass('fr-active');
    }

    /**
     * Align image.
     */
    function display (val) {
      $current_video.removeClass('fr-dvi fr-dvb');
      if (val == 'inline') {
        $current_video.addClass('fr-dvi');
      }
      else if (val == 'block') {
        $current_video.addClass('fr-dvb');
      }

      _repositionResizer();
      _showEditPopup();
    }

    /**
     * Refresh the image display selected option.
     */
    function refreshDisplayOnShow ($btn, $dropdown) {
      var d = 'block';
      if ($current_video.hasClass('fr-dvi')) {
        d = 'inline';
      }

      $dropdown.find('.fr-command[data-param1="' + d + '"]').addClass('fr-active');
    }

    /**
     * Remove current selected video.
     */
    function remove () {
      if ($current_video) {
        if (editor.events.trigger('video.beforeRemove', [$current_video]) !== false) {
          var $video = $current_video;
          editor.popups.hideAll();
          _exitEdit(true);

          editor.selection.setBefore($video.get(0)) || editor.selection.setAfter($video.get(0));
          $video.remove();
          editor.selection.restore();

          editor.html.fillEmptyBlocks(true);

          editor.events.trigger('video.removed', [$video]);
        }
      }
    }

    /**
     * Convert style to classes.
     */
    function _convertStyleToClasses ($video) {
      if (!$video.hasClass('fr-dvi') && !$video.hasClass('fr-dvb')) {
        var flt = $video.css('float');
        $video.css('float', 'none');
        if ($video.css('display') == 'block') {
          $video.css('float', flt);
          if (parseInt($video.css('margin-left'), 10) === 0 && ($video.attr('style') || '').indexOf('margin-right: auto') >= 0) {
            $video.addClass('fr-fvl');
          }
          else if (parseInt($video.css('margin-right'), 10) === 0 && ($video.attr('style') || '').indexOf('margin-left: auto') >= 0) {
            $video.addClass('fr-fvr');
          }

          $video.addClass('fr-dvb');
        }
        else {
          $video.css('float', flt);
          if ($video.css('float') == 'left') {
            $video.addClass('fr-fvl');
          }
          else if ($video.css('float') == 'right') {
            $video.addClass('fr-fvr');
          }

          $video.addClass('fr-dvi');
        }

        $video.css('margin', '');
        $video.css('float', '');
        $video.css('display', '');
        $video.css('z-index', '');
        $video.css('position', '');
        $video.css('overflow', '');
        $video.css('vertical-align', '');
      }

      if (!editor.opts.videoTextNear) {
        $video.removeClass('fr-dvi').addClass('fr-dvb');
      }
    }

    /**
     * Convert classes to style.
     */
    function _convertClassesToStyle ($video) {
      var style = {
        'z-index': 1,
        position: 'relative',
        overflow: 'auto'
      };

      if ($video.hasClass('fr-dvb')) {
        $.extend(style, {
          'vertical-align': 'top',
          display: 'block'
        });

        if ($video.hasClass('fr-fvr')) {
          $.extend(style, {
            'float': 'none',
            'margin-right': '0',
            'margin-left': 'auto'
          });
        }
        else if ($video.hasClass('fr-fvl')) {
          $.extend(style, {
            'float': 'none',
            'margin-left': '0',
            'margin-right': 'auto'
          });
        }
        else {
          $.extend(style, {
            'float': 'none',
            margin: 'auto'
          });
        }
      }
      else {
        $.extend(style, {
          display: 'inline-block'
        });

        if ($video.hasClass('fr-fvr')) {
          $.extend(style, {
            'float': 'right'
          });
        }
        else if ($video.hasClass('fr-fvl')) {
          $.extend(style, {
            'float': 'left'
          });
        }
        else {
          $.extend(style, {
            'float': 'none'
          });
        }
      }

      $video.removeClass('fr-dvb fr-dvi fr-fvr fr-fvl fr-fvn');
      if ($video.attr('class') === '') $video.removeAttr('class');

      $video.css(style);
    }

    /**
     * Refresh video list.
     */
    function _refreshVideoList () {
      // Find possible candidates that are not wrapped.
      editor.$el.find('video').filter(function () {
        return $(this).parents('span.fr-video').length === 0;
      }).wrap('<span class="fr-video"></span>');

      editor.$el.find('embed, iframe').filter(function () {
        if ($(this).parents('span.fr-video').length > 0) return false;

        var link = $(this).attr('src');
        for (var i = 0; i < $.FroalaEditor.VIDEO_PROVIDERS.length; i++) {
          var vp = $.FroalaEditor.VIDEO_PROVIDERS[i];
          if (vp.test_regex.test(link)) {
            return true;
          }
        }
        return false;
      }).map(function () {
        return $(this).parents('object').length === 0 ? this : $(this).parents('object').get(0);
      }).wrap('<span class="fr-video"></span>');

      var videos = editor.$el.find('span.fr-video');
      for (var i = 0; i < videos.length; i++) {
        _convertStyleToClasses($(videos[i]));
      }
    }

    /**
     * Prepare images for HTML output.
     */
    function _beforeGetHTML () {
      editor.$el.find('span.fr-video').each(function () {
        _convertClassesToStyle($(this));
      });
    }

    /**
     * Prepare images for HTML output.
     */
    function _afterGetHTML () {
      editor.$el.find('span.fr-video').each(function () {
        _convertStyleToClasses($(this));
      });
    }

    function _init () {
      _initEvents();

      editor.events.on('html.set', _refreshVideoList);
      _refreshVideoList();

      // Change HTML if the user doesn't want to use classes.
      if (!editor.opts.useClasses) {
        editor.events.on('html.beforeGet', _beforeGetHTML);
        editor.events.on('html.afterGet', _afterGetHTML);
      }

      // Full page.
      if (editor.opts.iframe) {
        editor.events.on('html.set', function () {
          editor.core.injectStyle(editor.opts.videoIframeStyle);
        });
        editor.core.injectStyle(editor.opts.videoIframeStyle);
      }

      editor.$el.on('mousedown', 'span.fr-video', function (e) {
        e.stopPropagation();
      })
      editor.$el.on('click touchstart', 'span.fr-video', _edit);

      editor.events.on('keydown', function (e) {
        var key_code = e.which;
        if ($current_video && (key_code == $.FroalaEditor.KEYCODE.BACKSPACE || key_code == $.FroalaEditor.KEYCODE.DELETE)) {
          e.preventDefault();
          remove();
          return false;
        }

        if ($current_video && key_code == $.FroalaEditor.KEYCODE.ESC) {
          _exitEdit(true);
          e.preventDefault();
          return false;
        }

        if ($current_video && !editor.keys.ctrlKey(e)) {
          e.preventDefault();
          return false;
        }
      }, true);

      // Make sure we don't leave empty tags.
      editor.events.on('keydown', function () {
        editor.$el.find('span.fr-video:empty').remove();
      })
    }

    /**
     * Get back to the video main popup.
     */
    function back () {
      if ($current_video) {
        $current_video.trigger('click');
      }
      else {
        editor.popups.hide('video.insert');
        editor.toolbar.showInline();
      }
    }

    /**
     * Set size based on the current video size.
     */
    function setSize (width, height) {
      if ($current_video) {
        var $popup = editor.popups.get('video.size');
        var $video_obj = $current_video.find('iframe, embed, video');
        $video_obj.css('width', width || $popup.find('input[name="width"]').val());
        $video_obj.css('height', height || $popup.find('input[name="height"]').val());

        if ($video_obj.get(0).style.width) $video_obj.removeAttr('width');
        if ($video_obj.get(0).style.height) $video_obj.removeAttr('height');

        $popup.find('input').blur();
        setTimeout(function () {
          $current_video.trigger('click');
        }, editor.helpers.isAndroid() ? 50 : 0);
      }
    }

    function get () {
      return $current_video;
    }

    return {
      _init: _init,
      showInsertPopup: showInsertPopup,
      showLayer: showLayer,
      refreshByURLButton: refreshByURLButton,
      refreshEmbedButton: refreshEmbedButton,
      insertByURL: insertByURL,
      insertEmbed: insertEmbed,
      insert: insert,
      align: align,
      refreshAlign: refreshAlign,
      refreshAlignOnShow: refreshAlignOnShow,
      display: display,
      refreshDisplayOnShow: refreshDisplayOnShow,
      remove: remove,
      showSizePopup: showSizePopup,
      back: back,
      setSize: setSize,
      get: get
    }
  }

  // Register the font size command.
  $.FroalaEditor.RegisterCommand('insertVideo', {
    title: 'Insert Video',
    undo: false,
    focus: false,
    refreshAfterCallback: false,
    popup: true,
    callback: function () {
      if (!this.popups.isVisible('video.insert')) {
        this.video.showInsertPopup();
      }
      else {
        this.popups.hide('video.insert');
      }
    }
  })

  // Add the font size icon.
  $.FroalaEditor.DefineIcon('insertVideo', {
    NAME: 'video-camera'
  });

  // Image by URL button inside the insert image popup.
  $.FroalaEditor.DefineIcon('videoByURL', { NAME: 'link' });
  $.FroalaEditor.RegisterCommand('videoByURL', {
    title: 'By URL',
    undo: false,
    focus: false,
    callback: function () {
      this.video.showLayer('video-by-url');
    },
    refresh: function ($btn) {
      this.video.refreshByURLButton($btn);
    }
  })

  // Image by URL button inside the insert image popup.
  $.FroalaEditor.DefineIcon('videoEmbed', { NAME: 'code' });
  $.FroalaEditor.RegisterCommand('videoEmbed', {
    title: 'Embedded Code',
    undo: false,
    focus: false,
    callback: function () {
      this.video.showLayer('video-embed');
    },
    refresh: function ($btn) {
      this.video.refreshEmbedButton($btn);
    }
  })

  $.FroalaEditor.RegisterCommand('videoInsertByURL', {
    undo: true,
    focus: true,
    callback: function () {
      this.video.insertByURL();
    }
  })

  $.FroalaEditor.RegisterCommand('videoInsertEmbed', {
    undo: true,
    focus: true,
    callback: function () {
      this.video.insertEmbed();
    }
  })

  // Image display.
  $.FroalaEditor.DefineIcon('videoDisplay', { NAME: 'star' })
  $.FroalaEditor.RegisterCommand('videoDisplay', {
    title: 'Display',
    type: 'dropdown',
    options: {
      inline: 'Inline',
      block: 'Break Text'
    },
    callback: function (cmd, val) {
      this.video.display(val);
    },
    refresh: function ($btn) {
      if (!this.opts.videoTextNear) $btn.addClass('fr-hidden');
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.video.refreshDisplayOnShow($btn, $dropdown);
    }
  })

  // Image align.
  $.FroalaEditor.DefineIcon('videoAlign', { NAME: 'align-center' })
  $.FroalaEditor.RegisterCommand('videoAlign', {
    type: 'dropdown',
    title: 'Align',
    options: {
      left: 'Align Left',
      justify: 'None',
      right: 'Align Right'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FroalaEditor.COMMANDS.videoAlign.options;
      for (var val in options) {
        c += '<li><a class="fr-command" data-cmd="videoAlign" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '"><i class="fa fa-align-' + val + '"></i></a></li>';
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.video.align(val);
    },
    refresh: function ($btn) {
      this.video.refreshAlign($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.video.refreshAlignOnShow($btn, $dropdown);
    }
  })

  // Video remove.
  $.FroalaEditor.DefineIcon('videoRemove', { NAME: 'trash' })
  $.FroalaEditor.RegisterCommand('videoRemove', {
    title: 'Remove',
    callback: function () {
      this.video.remove();
    }
  })

  // Video size.
  $.FroalaEditor.DefineIcon('videoSize', { NAME: 'arrows-alt' })
  $.FroalaEditor.RegisterCommand('videoSize', {
    undo: false,
    focus: false,
    title: 'Change Size',
    callback: function () {
      this.video.showSizePopup();
    }
  });

  // Video back.
  $.FroalaEditor.DefineIcon('videoBack', { NAME: 'arrow-left' });
  $.FroalaEditor.RegisterCommand('videoBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    callback: function () {
      this.video.back();
    },
    refresh: function ($btn) {
      var $current_video = this.video.get();
      if (!$current_video && !this.opts.toolbarInline) {
        $btn.addClass('fr-hidden');
        $btn.next('.fr-separator').addClass('fr-hidden');
      }
      else {
        $btn.removeClass('fr-hidden');
        $btn.next('.fr-separator').removeClass('fr-hidden');
      }
    }
  });

  $.FroalaEditor.RegisterCommand('videoSetSize', {
    undo: true,
    focus: false,
    callback: function () {
      this.video.setSize();
    }
  })
})(jQuery);
