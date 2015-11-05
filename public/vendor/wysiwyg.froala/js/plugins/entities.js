/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {
    entities: '&amp;&lt;&gt;&quot;&apos;&iexcl;&cent;&pound;&curren;&yen;&brvbar;&sect;&uml;&copy;&ordf;&laquo;&not;&shy;&reg;&macr;&deg;&plusmn;&sup2;&sup3;&acute;&micro;&para;&middot;&cedil;&sup1;&ordm;&raquo;&frac14;&frac12;&frac34;&iquest;&Agrave;&Aacute;&Acirc;&Atilde;&Auml;&Aring;&AElig;&Ccedil;&Egrave;&Eacute;&Ecirc;&Euml;&Igrave;&Iacute;&Icirc;&Iuml;&ETH;&Ntilde;&Ograve;&Oacute;&Ocirc;&Otilde;&Ouml;&times;&Oslash;&Ugrave;&Uacute;&Ucirc;&Uuml;&Yacute;&THORN;&szlig;&agrave;&aacute;&acirc;&atilde;&auml;&aring;&aelig;&ccedil;&egrave;&eacute;&ecirc;&euml;&igrave;&iacute;&icirc;&iuml;&eth;&ntilde;&ograve;&oacute;&ocirc;&otilde;&ouml;&divide;&oslash;&ugrave;&uacute;&ucirc;&uuml;&yacute;&thorn;&yuml;&OElig;&oelig;&Scaron;&scaron;&Yuml;&fnof;&circ;&tilde;&Alpha;&Beta;&Gamma;&Delta;&Epsilon;&Zeta;&Eta;&Theta;&Iota;&Kappa;&Lambda;&Mu;&Nu;&Xi;&Omicron;&Pi;&Rho;&Sigma;&Tau;&Upsilon;&Phi;&Chi;&Psi;&Omega;&alpha;&beta;&gamma;&delta;&epsilon;&zeta;&eta;&theta;&iota;&kappa;&lambda;&mu;&nu;&xi;&omicron;&pi;&rho;&sigmaf;&sigma;&tau;&upsilon;&phi;&chi;&psi;&omega;&thetasym;&upsih;&piv;&ensp;&emsp;&thinsp;&zwnj;&zwj;&lrm;&rlm;&ndash;&mdash;&lsquo;&rsquo;&sbquo;&ldquo;&rdquo;&bdquo;&dagger;&Dagger;&bull;&hellip;&permil;&prime;&Prime;&lsaquo;&rsaquo;&oline;&frasl;&euro;&image;&weierp;&real;&trade;&alefsym;&larr;&uarr;&rarr;&darr;&harr;&crarr;&lArr;&uArr;&rArr;&dArr;&hArr;&forall;&part;&exist;&empty;&nabla;&isin;&notin;&ni;&prod;&sum;&minus;&lowast;&radic;&prop;&infin;&ang;&and;&or;&cap;&cup;&int;&there4;&sim;&cong;&asymp;&ne;&equiv;&le;&ge;&sub;&sup;&nsub;&sube;&supe;&oplus;&otimes;&perp;&sdot;&lceil;&rceil;&lfloor;&rfloor;&lang;&rang;&loz;&spades;&clubs;&hearts;&diams;'
  });


  $.FroalaEditor.PLUGINS.entities = function (editor) {
    var _reg_exp;
    var _map;
    var $iframe;

    function _encode (el) {
      if (el.nodeType == Node.COMMENT_NODE) return '<!--' + el.nodeValue + '-->';
      if (el.nodeType != Node.ELEMENT_NODE) return el.outerHTML;
      if (el.tagName == 'IFRAME') return el.outerHTML;

      var contents = el.childNodes;

      if (contents.length === 0 && (editor.opts.fullPage || el.tagName != 'BODY')) return el.outerHTML;

      var str = '';
      for (var i = 0; i < contents.length; i++) {
        if (contents[i].nodeType == Node.TEXT_NODE) {
          var text = contents[i].textContent;
          if (text.match(_reg_exp)) {
            var new_text = '';
            for (var j = 0; j < text.length; j++) {
              if (_map[text[j]]) new_text += _map[text[j]];
              else new_text += text[j];
            }
            str += new_text.replace(/\u00A0/g, '&nbsp;');
          }
          else {
            str += text.replace(/\u00A0/g, '&nbsp;');
          }
        }
        else {
          str += _encode(contents[i]);
        }
      }

      if (editor.opts.fullPage || el.tagName != 'BODY') {
        return editor.node.openTagString(el) + str + editor.node.closeTagString(el);
      }
      else {
        return str;
      }
    }

    /**
     * Encode entities.
     */
    function _encodeEntities (html) {
      // Replace script tag with comments.
      var scripts = [];
      html = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, function (str) {
        scripts.push(str);
        return '<!--[FROALA.EDITOR.SCRIPT ' + (scripts.length - 1) + ']-->';
      });

      html = html.replace(/<img((?:[\w\W]*?)) src="/g, '<img$1 data-src="');

      if (!editor.opts.fullPage) html = '<html><head></head><body>' + html + '</body></html>';

      $iframe = $('<iframe style="width:0; height:0; position: absolute; left: -2000px; display: none;">');
      $('body').append($iframe);
      $iframe.get(0).contentWindow.document.open();
      $iframe.get(0).contentWindow.document.write(html);
      $iframe.get(0).contentWindow.document.close();

      var el;
      if (editor.opts.fullPage) {
        el = $iframe.contents().find('html').get(0);
      }
      else {
        el = $iframe.get(0).contentDocument.getElementsByTagName('body')[0];
      }

      var encoded_html = _encode(el);
      if (editor.opts.fullPage) {
        var doctype = editor.html.getDoctype($iframe.get(0).contentWindow.document);

        encoded_html = doctype + encoded_html;
      }

      // Replace script comments with the original script.
      encoded_html = encoded_html.replace(/<!--\[FROALA\.EDITOR\.SCRIPT ([\d]*)]-->/gi, function (str, a1) {
        return scripts[parseInt(a1, 10)];
      });

      encoded_html = encoded_html.replace(/<img((?:[\w\W]*?)) data-src="/g, '<img$1 src="');

      $iframe.remove();

      return encoded_html;
    }

    /*
     * Initialize.
     */
    function _init () {
      if (editor.opts.htmlSimpleAmpersand) {
        editor.opts.entities = editor.opts.entities.replace('&amp;', '');
      }

      // Do escape.
      var entities_text = $('<div>').html(editor.opts.entities).text();
      var entities_array = editor.opts.entities.split(';');
      _map = {};
      _reg_exp = '';
      for (var i = 0; i < entities_text.length; i++) {
        var chr = entities_text.charAt(i);
        _map[chr] = entities_array[i] + ';';
        _reg_exp += '\\' + chr + (i < entities_text.length - 1 ? '|' : '');
      }
      _reg_exp = new RegExp('(' + _reg_exp + ')', 'g');

      editor.events.on('html.get', _encodeEntities, true);
    }

    return {
      _init: _init
    }
  }
})(jQuery);
