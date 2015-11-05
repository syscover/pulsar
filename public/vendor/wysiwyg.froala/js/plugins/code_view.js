/*!
 * froala_editor v2.0.0-rc.3 (https://www.froala.com/wysiwyg-editor/v2.0)
 * License http://editor.froala.com/license
 * Copyright 2014-2015 Froala Labs
 */

(function ($) {
  'use strict';

  $.extend($.FroalaEditor.DEFAULTS, {
    codeMirror: true,
    codeMirrorOptions: {
      lineNumbers: true,
      tabMode: 'indent',
      indentWithTabs: true,
      lineWrapping: true,
      mode: 'text/html',
      tabSize: 2
    },
    codeBeautifier: true
  })

  $.FroalaEditor.PLUGINS.codeView = function (editor) {
    var $html_area;
    var code_mirror;

    /**
     * Check if code view is enabled.
     */
    function _enabled () {
      return editor.$box.hasClass('fr-code-view');
    }

    /**
     * Get back to edit mode.
     */
    function _showText ($btn) {
      // Code mirror enabled.
      if (code_mirror) {
        editor.html.set(code_mirror.getValue());
      } else {
        editor.html.set($html_area.val());
      }

      // Blur the element.
      editor.$el.blur();

      // Toolbar no longer disabled.
      editor.$tb.find(' > .fr-command').not($btn).removeClass('fr-disabled');
      $btn.removeClass('fr-active');

      editor.events.focus(true);
      editor.placeholder.refresh();

      editor.undo.saveStep();
    }

    /**
     * Get to code mode.
     */
    function _showHTML ($btn, height) {
      // Enable code mirror.
      if (!code_mirror && editor.opts.codeMirror && typeof CodeMirror != 'undefined') {
        code_mirror = CodeMirror.fromTextArea($html_area.get(0), editor.opts.codeMirrorOptions);
      }

      editor.undo.saveStep();

      // Clean white tags but ignore selection.
      editor.html.cleanWhiteTags(true);

      // Blur the element.
      editor.selection.save();
      editor.$el.find('.fr-marker[data-type="true"]:first').replaceWith('FROALA-SM');
      editor.$el.find('.fr-marker[data-type="false"]:last').replaceWith('FROALA-EM');
      editor.$el.blur();

      // Get HTML.
      var html = editor.html.get();

      // Beautify HTML.
      if (editor.opts.codeBeautifier) {
        html = beautify(html, {
          end_with_newline: true,
          indent_inner_html: true,
          extra_liners: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'pre', 'ul', 'ol', 'table'],
          brace_style: 'expand',
          indent_char: '\t',
          indent_size: 1,
          wrap_line_length: 0
        });
      }

      var s_index;
      var e_index;

      // Code mirror is enabled.
      if (code_mirror) {
        s_index = html.indexOf('FROALA-SM');
        e_index = html.indexOf('FROALA-EM');

        if (s_index > e_index) {
          s_index = e_index;
        }
        else {
          e_index = e_index - 9;
        }

        html = html.replace(/FROALA-SM/g, '').replace(/FROALA-EM/g, '')
        var s_line = html.substring(0, s_index).length - html.substring(0, s_index).replace(/\n/g, '').length;
        var e_line = html.substring(0, e_index).length - html.substring(0, e_index).replace(/\n/g, '').length;

        s_index = html.substring(0, s_index).length - html.substring(0, html.substring(0, s_index).lastIndexOf('\n') + 1).length;
        e_index = html.substring(0, e_index).length - html.substring(0, html.substring(0, e_index).lastIndexOf('\n')  + 1).length;

        code_mirror.setSize(null, height);
        code_mirror.setValue(html);
        code_mirror.focus();
        code_mirror.setSelection({ line: s_line, ch: s_index }, { line: e_line, ch: e_index })
        code_mirror.refresh();
        code_mirror.clearHistory();
      }

      // No code mirror.
      else {
        s_index = html.indexOf('FROALA-SM');
        e_index = html.indexOf('FROALA-EM') - 9;

        $html_area.css('height', height);
        $html_area.val(html.replace(/FROALA-SM/g, '').replace(/FROALA-EM/g, ''));
        $html_area.focus();
        $html_area.get(0).setSelectionRange(s_index, e_index);
      }

      // Disable buttons.
      editor.$tb.find(' > .fr-command').not($btn).addClass('fr-disabled');
      $btn.addClass('fr-active');

      if (!editor.helpers.isMobile() && editor.opts.toolbarInline) {
        editor.toolbar.hide();
      }
    }

    /**
     * Toggle the code view.
     */
    function toggle () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="html"]');

      if (_enabled()) {
        editor.$box.toggleClass('fr-code-view', false);
        _showText($btn);
      } else {
        editor.popups.hideAll();
        var height = editor.$wp.outerHeight();
        editor.$box.toggleClass('fr-code-view', true);
        _showHTML($btn, height);
      }
    }

    /**
     * Destroy.
     */
    function _destroy () {
      if (_enabled()) {
        toggle(editor.$tb.find('button[data-cmd="html"]'));
        $html_area.val('').removeData().remove();
      }

      if ($back_button) $back_button.remove();
    }

    /**
     * Initialize.
     */
    var $back_button;
    function _init () {
      if (!editor.$wp) return false;

      // Add the coding textarea to the wrapper.
      $html_area = $('<textarea class="fr-code" tabindex="-1">');
      editor.$wp.append($html_area);

      $html_area.attr('dir', editor.opts.direction);

      var cancel = function () {
        return !_enabled();
      }

      // Exit code view button for inline toolbar.
      if (editor.opts.toolbarInline) {
        $back_button = $('<a data-cmd="html" title="Code View" class="fr-command fr-btn html-switch' + (editor.helpers.isMobile() ? '' : ' fr-desktop') + '" role="button" tabindex="-1"><i class="fa fa-code"></i></button>');
        editor.$box.append($back_button);

        editor.events.bindClick(editor.$box, 'a.html-switch', function () {
          toggle(editor.$tb.find('button[data-cmd="html"]'));
        });
      }

      // Disable refresh of the buttons while enabled.
      editor.events.on('buttons.refresh', cancel);
      editor.events.on('copy', cancel, true);
      editor.events.on('cut', cancel, true);
      editor.events.on('paste', cancel, true);

      editor.events.on('destroy', _destroy, true);

      editor.events.on('form.submit', function () {
        if (_enabled()) {
          toggle(editor.$tb.find('button[data-cmd="html"]'));
        }
      });
    }

    /**
     * HTML BEAUTIFIER
     *
     * LICENSE: The MIT License (MIT)
     *
     * Written by Nochum Sossonko, (nsossonko@hotmail.com)
     *
     * Based on code initially developed by: Einar Lielmanis, <einar@jsbeautifier.org>
     * http://jsbeautifier.org/
     *
     */
    /* jshint ignore:start */
    /* jscs:disable */
    function beautify (html_source, options) {
      function ltrim(s) {
        return s.replace(/^\s+/g, '');
      }

      function rtrim(s) {
        return s.replace(/\s+$/g, '');
      }

      var multi_parser;
      var indent_inner_html;
      var indent_size;
      var indent_character;
      var wrap_line_length;
      var brace_style;
      var unformatted;
      var preserve_newlines;
      var max_preserve_newlines;
      var indent_handlebars;
      var wrap_attributes;
      var wrap_attributes_indent_size;
      var end_with_newline;
      var extra_liners;

      options = options || {};

      // backwards compatibility to 1.3.4
      if ((options.wrap_line_length === undefined || parseInt(options.wrap_line_length, 10) === 0) &&
        (options.max_char !== undefined && parseInt(options.max_char, 10) !== 0)) {
        options.wrap_line_length = options.max_char;
      }

      indent_inner_html = (options.indent_inner_html === undefined) ? false : options.indent_inner_html;
      indent_size = (options.indent_size === undefined) ? 4 : parseInt(options.indent_size, 10);
      indent_character = (options.indent_char === undefined) ? ' ' : options.indent_char;
      brace_style = (options.brace_style === undefined) ? 'collapse' : options.brace_style;
      wrap_line_length = parseInt(options.wrap_line_length, 10) === 0 ? 32786 : parseInt(options.wrap_line_length || 250, 10);
      unformatted = options.unformatted || ['a', 'span', 'img', 'bdo', 'em', 'strong', 'dfn', 'code', 'samp', 'kbd',
        'var', 'cite', 'abbr', 'acronym', 'q', 'sub', 'sup', 'tt', 'i', 'b', 'big', 'small', 'u', 's', 'strike',
        'font', 'ins', 'del', 'address', 'dt', 'pre'
      ];
      preserve_newlines = (options.preserve_newlines === undefined) ? true : options.preserve_newlines;
      max_preserve_newlines = preserve_newlines ?
        (isNaN(parseInt(options.max_preserve_newlines, 10)) ? 32786 : parseInt(options.max_preserve_newlines, 10)) : 0;
      indent_handlebars = (options.indent_handlebars === undefined) ? false : options.indent_handlebars;
      wrap_attributes = (options.wrap_attributes === undefined) ? 'auto' : options.wrap_attributes;
      wrap_attributes_indent_size = (options.wrap_attributes_indent_size === undefined) ? indent_size : parseInt(options.wrap_attributes_indent_size, 10) || indent_size;
      end_with_newline = (options.end_with_newline === undefined) ? false : options.end_with_newline;
      extra_liners = Array.isArray(options.extra_liners) ?
        options.extra_liners.concat() : (typeof options.extra_liners == 'string') ?
        options.extra_liners.split(',') : 'head,body,/html'.split(',');

      if (options.indent_with_tabs) {
        indent_character = '\t';
        indent_size = 1;
      }

      function Parser() {

        this.pos = 0; //Parser position
        this.token = '';
        this.current_mode = 'CONTENT'; //reflects the current Parser mode: TAG/CONTENT
        this.tags = { //An object to hold tags, their position, and their parent-tags, initiated with default values
          parent: 'parent1',
          parentcount: 1,
          parent1: ''
        };
        this.tag_type = '';
        this.token_text = this.last_token = this.last_text = this.token_type = '';
        this.newlines = 0;
        this.indent_content = indent_inner_html;

        this.Utils = { //Uilities made available to the various functions
          whitespace: "\n\r\t ".split(''),
          single_token: 'br,input,link,meta,source,!doctype,basefont,base,area,hr,wbr,param,img,isindex,embed'.split(','), //all the single tags for HTML
          extra_liners: extra_liners, //for tags that need a line of whitespace before them
          in_array: function(what, arr) {
            for (var i = 0; i < arr.length; i++) {
              if (what == arr[i]) {
                return true;
              }
            }
            return false;
          }
        };

        // Return true if the given text is composed entirely of whitespace.
        this.is_whitespace = function(text) {
          for (var n = 0; n < text.length; text++) {
            if (!this.Utils.in_array(text.charAt(n), this.Utils.whitespace)) {
              return false;
            }
          }
          return true;
        };

        this.traverse_whitespace = function() {
          var input_char = '';

          input_char = this.input.charAt(this.pos);
          if (this.Utils.in_array(input_char, this.Utils.whitespace)) {
            this.newlines = 0;
            while (this.Utils.in_array(input_char, this.Utils.whitespace)) {
              if (preserve_newlines && input_char == '\n' && this.newlines <= max_preserve_newlines) {
                this.newlines += 1;
              }

              this.pos++;
              input_char = this.input.charAt(this.pos);
            }
            return true;
          }
          return false;
        };

        // Append a space to the given content (string array) or, if we are
        // at the wrap_line_length, append a newline/indentation.
        this.space_or_wrap = function(content) {
          if (this.line_char_count >= this.wrap_line_length) { //insert a line when the wrap_line_length is reached
            this.print_newline(false, content);
            this.print_indentation(content);
          } else {
            this.line_char_count++;
            content.push(' ');
          }
        };

        this.get_content = function() { //function to capture regular content between tags
          var input_char = '',
              content = [];

          while (this.input.charAt(this.pos) != '<') {
            if (this.pos >= this.input.length) {
              return content.length ? content.join('') : ['', 'TK_EOF'];
            }

            if (this.traverse_whitespace()) {
              this.space_or_wrap(content);
              continue;
            }

            if (indent_handlebars) {
              // Handlebars parsing is complicated.
              // {{#foo}} and {{/foo}} are formatted tags.
              // {{something}} should get treated as content, except:
              // {{else}} specifically behaves like {{#if}} and {{/if}}
              var peek3 = this.input.substr(this.pos, 3);
              if (peek3 == '{{#' || peek3 == '{{/') {
                // These are tags and not content.
                break;
              } else if (peek3 == '{{!') {
                return [this.get_tag(), 'TK_TAG_HANDLEBARS_COMMENT'];
              } else if (this.input.substr(this.pos, 2) == '{{') {
                if (this.get_tag(true) == '{{else}}') {
                  break;
                }
              }
            }

            input_char = this.input.charAt(this.pos);
            this.pos++;
            this.line_char_count++;
            content.push(input_char); //letter at-a-time (or string) inserted to an array
          }
          return content.length ? content.join('') : '';
        };

        this.get_contents_to = function(name) { //get the full content of a script or style to pass to js_beautify
          if (this.pos == this.input.length) {
            return ['', 'TK_EOF'];
          }
          var content = '';
          var reg_match = new RegExp('</' + name + '\\s*>', 'igm');
          reg_match.lastIndex = this.pos;
          var reg_array = reg_match.exec(this.input);
          var end_script = reg_array ? reg_array.index : this.input.length; //absolute end of script
          if (this.pos < end_script) { //get everything in between the script tags
            content = this.input.substring(this.pos, end_script);
            this.pos = end_script;
          }
          return content;
        };

        this.record_tag = function(tag) { //function to record a tag and its parent in this.tags Object
          if (this.tags[tag + 'count']) { //check for the existence of this tag type
            this.tags[tag + 'count']++;
            this.tags[tag + this.tags[tag + 'count']] = this.indent_level; //and record the present indent level
          } else { //otherwise initialize this tag type
            this.tags[tag + 'count'] = 1;
            this.tags[tag + this.tags[tag + 'count']] = this.indent_level; //and record the present indent level
          }
          this.tags[tag + this.tags[tag + 'count'] + 'parent'] = this.tags.parent; //set the parent (i.e. in the case of a div this.tags.div1parent)
          this.tags.parent = tag + this.tags[tag + 'count']; //and make this the current parent (i.e. in the case of a div 'div1')
        };

        this.retrieve_tag = function(tag) { //function to retrieve the opening tag to the corresponding closer
          if (this.tags[tag + 'count']) { //if the openener is not in the Object we ignore it
            var temp_parent = this.tags.parent; //check to see if it's a closable tag.
            while (temp_parent) { //till we reach '' (the initial value);
              if (tag + this.tags[tag + 'count'] == temp_parent) { //if this is it use it
                break;
              }
              temp_parent = this.tags[temp_parent + 'parent']; //otherwise keep on climbing up the DOM Tree
            }
            if (temp_parent) { //if we caught something
              this.indent_level = this.tags[tag + this.tags[tag + 'count']]; //set the indent_level accordingly
              this.tags.parent = this.tags[temp_parent + 'parent']; //and set the current parent
            }
            delete this.tags[tag + this.tags[tag + 'count'] + 'parent']; //delete the closed tags parent reference...
            delete this.tags[tag + this.tags[tag + 'count']]; //...and the tag itself
            if (this.tags[tag + 'count'] == 1) {
              delete this.tags[tag + 'count'];
            } else {
              this.tags[tag + 'count']--;
            }
          }
        };

        this.indent_to_tag = function(tag) {
          // Match the indentation level to the last use of this tag, but don't remove it.
          if (!this.tags[tag + 'count']) {
            return;
          }
          var temp_parent = this.tags.parent;
          while (temp_parent) {
            if (tag + this.tags[tag + 'count'] == temp_parent) {
              break;
            }
            temp_parent = this.tags[temp_parent + 'parent'];
          }
          if (temp_parent) {
            this.indent_level = this.tags[tag + this.tags[tag + 'count']];
          }
        };

        this.get_tag = function(peek) { //function to get a full tag and parse its type
          var input_char = '',
            content = [],
            comment = '',
            space = false,
            first_attr = true,
            tag_start, tag_end,
            tag_start_char,
            orig_pos = this.pos,
            orig_line_char_count = this.line_char_count;

          peek = peek !== undefined ? peek : false;

          do {
            if (this.pos >= this.input.length) {
              if (peek) {
                this.pos = orig_pos;
                this.line_char_count = orig_line_char_count;
              }
              return content.length ? content.join('') : ['', 'TK_EOF'];
            }

            input_char = this.input.charAt(this.pos);
            this.pos++;

            if (this.Utils.in_array(input_char, this.Utils.whitespace)) { //don't want to insert unnecessary space
              space = true;
              continue;
            }

            if (input_char == "'" || input_char == '"') {
              input_char += this.get_unformatted(input_char);
              space = true;

            }

            if (input_char == '=') { //no space before =
              space = false;
            }

            if (content.length && content[content.length - 1] != '=' && input_char != '>' && space) {
              //no space after = or before >
              this.space_or_wrap(content);
              space = false;
              if (!first_attr && wrap_attributes == 'force' && input_char != '/') {
                this.print_newline(true, content);
                this.print_indentation(content);
                for (var count = 0; count < wrap_attributes_indent_size; count++) {
                  content.push(indent_character);
                }
              }
              for (var i = 0; i < content.length; i++) {
                if (content[i] == ' ') {
                  first_attr = false;
                  break;
                }
              }
            }

            if (indent_handlebars && tag_start_char == '<') {
              // When inside an angle-bracket tag, put spaces around
              // handlebars not inside of strings.
              if ((input_char + this.input.charAt(this.pos)) == '{{') {
                input_char += this.get_unformatted('}}');
                if (content.length && content[content.length - 1] != ' ' && content[content.length - 1] != '<') {
                  input_char = ' ' + input_char;
                }
                space = true;
              }
            }

            if (input_char == '<' && !tag_start_char) {
              tag_start = this.pos - 1;
              tag_start_char = '<';
            }

            if (indent_handlebars && !tag_start_char) {
              if (content.length >= 2 && content[content.length - 1] == '{' && content[content.length - 2] == '{') {
                if (input_char == '#' || input_char == '/' || input_char == '!') {
                  tag_start = this.pos - 3;
                } else {
                  tag_start = this.pos - 2;
                }
                tag_start_char = '{';
              }
            }

            this.line_char_count++;
            content.push(input_char); //inserts character at-a-time (or string)

            if (content[1] && (content[1] == '!' || content[1] == '?' || content[1] == '%')) { //if we're in a comment, do something special
              // We treat all comments as literals, even more than preformatted tags
              // we just look for the appropriate close tag
              content = [this.get_comment(tag_start)];
              break;
            }

            if (indent_handlebars && content[1] && content[1] == '{' && content[2] && content[2] == '!') { //if we're in a comment, do something special
              // We treat all comments as literals, even more than preformatted tags
              // we just look for the appropriate close tag
              content = [this.get_comment(tag_start)];
              break;
            }

            if (indent_handlebars && tag_start_char == '{' && content.length > 2 && content[content.length - 2] == '}' && content[content.length - 1] == '}') {
              break;
            }
          } while (input_char != '>');

          var tag_complete = content.join('');
          var tag_index;
          var tag_offset;

          if (tag_complete.indexOf(' ') != -1) { //if there's whitespace, thats where the tag name ends
            tag_index = tag_complete.indexOf(' ');
          } else if (tag_complete[0] == '{') {
            tag_index = tag_complete.indexOf('}');
          } else { //otherwise go with the tag ending
            tag_index = tag_complete.indexOf('>');
          }
          if (tag_complete[0] == '<' || !indent_handlebars) {
            tag_offset = 1;
          } else {
            tag_offset = tag_complete[2] == '#' ? 3 : 2;
          }
          var tag_check = tag_complete.substring(tag_offset, tag_index).toLowerCase();
          if (tag_complete.charAt(tag_complete.length - 2) == '/' ||
            this.Utils.in_array(tag_check, this.Utils.single_token)) { //if this tag name is a single tag type (either in the list or has a closing /)
            if (!peek) {
              this.tag_type = 'SINGLE';
            }
          } else if (indent_handlebars && tag_complete[0] == '{' && tag_check == 'else') {
            if (!peek) {
              this.indent_to_tag('if');
              this.tag_type = 'HANDLEBARS_ELSE';
              this.indent_content = true;
              this.traverse_whitespace();
            }
          } else if (this.is_unformatted(tag_check, unformatted)) { // do not reformat the "unformatted" tags
            comment = this.get_unformatted('</' + tag_check + '>', tag_complete); //...delegate to get_unformatted function
            content.push(comment);
            tag_end = this.pos - 1;
            this.tag_type = 'SINGLE';
          } else if (tag_check == 'script' &&
            (tag_complete.search('type') == -1 ||
              (tag_complete.search('type') > -1 &&
                tag_complete.search(/\b(text|application)\/(x-)?(javascript|ecmascript|jscript|livescript)/) > -1))) {
            if (!peek) {
              this.record_tag(tag_check);
              this.tag_type = 'SCRIPT';
            }
          } else if (tag_check == 'style' &&
            (tag_complete.search('type') == -1 ||
              (tag_complete.search('type') > -1 && tag_complete.search('text/css') > -1))) {
            if (!peek) {
              this.record_tag(tag_check);
              this.tag_type = 'STYLE';
            }
          } else if (tag_check.charAt(0) == '!') { //peek for <! comment
            // for comments content is already correct.
            if (!peek) {
              this.tag_type = 'SINGLE';
              this.traverse_whitespace();
            }
          } else if (!peek) {
            if (tag_check.charAt(0) == '/') { //this tag is a double tag so check for tag-ending
              this.retrieve_tag(tag_check.substring(1)); //remove it and all ancestors
              this.tag_type = 'END';
            } else { //otherwise it's a start-tag
              this.record_tag(tag_check); //push it on the tag stack
              if (tag_check.toLowerCase() != 'html') {
                this.indent_content = true;
              }
              this.tag_type = 'START';
            }

            // Allow preserving of newlines after a start or end tag
            if (this.traverse_whitespace()) {
              this.space_or_wrap(content);
            }

            if (this.Utils.in_array(tag_check, this.Utils.extra_liners)) { //check if this double needs an extra line
              this.print_newline(false, this.output);
              if (this.output.length && this.output[this.output.length - 2] != '\n') {
                this.print_newline(true, this.output);
              }
            }
          }

          if (peek) {
            this.pos = orig_pos;
            this.line_char_count = orig_line_char_count;
          }

          return content.join(''); //returns fully formatted tag
        };

        this.get_comment = function(start_pos) { //function to return comment content in its entirety
          // this is will have very poor perf, but will work for now.
          var comment = '',
            delimiter = '>',
            matched = false;

          this.pos = start_pos;
          var input_char = this.input.charAt(this.pos);
          this.pos++;

          while (this.pos <= this.input.length) {
            comment += input_char;

            // only need to check for the delimiter if the last chars match
            if (comment[comment.length - 1] == delimiter[delimiter.length - 1] &&
              comment.indexOf(delimiter) != -1) {
              break;
            }

            // only need to search for custom delimiter for the first few characters
            if (!matched && comment.length < 10) {
              if (comment.indexOf('<![if') === 0) { //peek for <![if conditional comment
                delimiter = '<![endif]>';
                matched = true;
              } else if (comment.indexOf('<![cdata[') === 0) { //if it's a <[cdata[ comment...
                delimiter = ']]>';
                matched = true;
              } else if (comment.indexOf('<![') === 0) { // some other ![ comment? ...
                delimiter = ']>';
                matched = true;
              } else if (comment.indexOf('<!--') === 0) { // <!-- comment ...
                delimiter = '-->';
                matched = true;
              } else if (comment.indexOf('{{!') === 0) { // {{! handlebars comment
                delimiter = '}}';
                matched = true;
              } else if (comment.indexOf('<?') === 0) { // {{! handlebars comment
                delimiter = '?>';
                matched = true;
              } else if (comment.indexOf('<%') === 0) { // {{! handlebars comment
                delimiter = '%>';
                matched = true;
              }
            }

            input_char = this.input.charAt(this.pos);
            this.pos++;
          }

          return comment;
        };

        this.get_unformatted = function(delimiter, orig_tag) { //function to return unformatted content in its entirety

          if (orig_tag && orig_tag.toLowerCase().indexOf(delimiter) != -1) {
            return '';
          }
          var input_char = '';
          var content = '';
          var min_index = 0;
          var space = true;
          do {

            if (this.pos >= this.input.length) {
              return content;
            }

            input_char = this.input.charAt(this.pos);
            this.pos++;

            if (this.Utils.in_array(input_char, this.Utils.whitespace)) {
              if (!space) {
                this.line_char_count--;
                continue;
              }
              if (input_char == '\n' || input_char == '\r') {
                content += '\n';
                /*  Don't change tab indention for unformatted blocks.  If using code for html editing, this will greatly affect <pre> tags if they are specified in the 'unformatted array'
              for (var i=0; i<this.indent_level; i++) {
                content += this.indent_string;
              }
              space = false; //...and make sure other indentation is erased
              */
                this.line_char_count = 0;
                continue;
              }
            }
            content += input_char;
            this.line_char_count++;
            space = true;

            if (indent_handlebars && input_char == '{' && content.length && content[content.length - 2] == '{') {
              // Handlebars expressions in strings should also be unformatted.
              content += this.get_unformatted('}}');
              // These expressions are opaque.  Ignore delimiters found in them.
              min_index = content.length;
            }
          } while (content.toLowerCase().indexOf(delimiter, min_index) == -1);
          return content;
        };

        this.get_token = function() { //initial handler for token-retrieval
          var token;

          if (this.last_token == 'TK_TAG_SCRIPT' || this.last_token == 'TK_TAG_STYLE') { //check if we need to format javascript
            var type = this.last_token.substr(7);
            token = this.get_contents_to(type);
            if (typeof token != 'string') {
              return token;
            }
            return [token, 'TK_' + type];
          }
          if (this.current_mode == 'CONTENT') {
            token = this.get_content();
            if (typeof token != 'string') {
              return token;
            } else {
              return [token, 'TK_CONTENT'];
            }
          }

          if (this.current_mode == 'TAG') {
            token = this.get_tag();
            if (typeof token != 'string') {
              return token;
            } else {
              var tag_name_type = 'TK_TAG_' + this.tag_type;
              return [token, tag_name_type];
            }
          }
        };

        this.get_full_indent = function(level) {
          level = this.indent_level + level || 0;
          if (level < 1) {
            return '';
          }

          return (new Array(level + 1)).join(this.indent_string);
        };

        this.is_unformatted = function(tag_check, unformatted) {
          //is this an HTML5 block-level link?
          if (!this.Utils.in_array(tag_check, unformatted)) {
            return false;
          }

          if (tag_check.toLowerCase() != 'a' || !this.Utils.in_array('a', unformatted)) {
            return true;
          }

          //at this point we have an  tag; is its first child something we want to remain
          //unformatted?
          var next_tag = this.get_tag(true /* peek. */ );

          // test next_tag to see if it is just html tag (no external content)
          var tag = (next_tag || '').match(/^\s*<\s*\/?([a-z]*)\s*[^>]*>\s*$/);

          // if next_tag comes back but is not an isolated tag, then
          // let's treat the 'a' tag as having content
          // and respect the unformatted option
          if (!tag || this.Utils.in_array(tag, unformatted)) {
            return true;
          } else {
            return false;
          }
        };

        this.printer = function(js_source, indent_character, indent_size, wrap_line_length, brace_style) { //handles input/output and some other printing functions

          this.input = js_source || ''; //gets the input for the Parser
          this.output = [];
          this.indent_character = indent_character;
          this.indent_string = '';
          this.indent_size = indent_size;
          this.brace_style = brace_style;
          this.indent_level = 0;
          this.wrap_line_length = wrap_line_length;
          this.line_char_count = 0; //count to see if wrap_line_length was exceeded

          for (var i = 0; i < this.indent_size; i++) {
            this.indent_string += this.indent_character;
          }

          this.print_newline = function(force, arr) {
            this.line_char_count = 0;
            if (!arr || !arr.length) {
              return;
            }
            if (force || (arr[arr.length - 1] != '\n')) { //we might want the extra line
              if ((arr[arr.length - 1] != '\n')) {
                arr[arr.length - 1] = rtrim(arr[arr.length - 1]);
              }
              arr.push('\n');
            }
          };

          this.print_indentation = function(arr) {
            for (var i = 0; i < this.indent_level; i++) {
              arr.push(this.indent_string);
              this.line_char_count += this.indent_string.length;
            }
          };

          this.print_token = function(text) {
            // Avoid printing initial whitespace.
            if (this.is_whitespace(text) && !this.output.length) {
              return;
            }
            if (text || text !== '') {
              if (this.output.length && this.output[this.output.length - 1] == '\n') {
                this.print_indentation(this.output);
                text = ltrim(text);
              }
            }
            this.print_token_raw(text);
          };

          this.print_token_raw = function(text) {
            // If we are going to print newlines, truncate trailing
            // whitespace, as the newlines will represent the space.
            if (this.newlines > 0) {
              text = rtrim(text);
            }

            if (text && text !== '') {
              if (text.length > 1 && text[text.length - 1] == '\n') {
                // unformatted tags can grab newlines as their last character
                this.output.push(text.slice(0, -1));
                this.print_newline(false, this.output);
              } else {
                this.output.push(text);
              }
            }

            for (var n = 0; n < this.newlines; n++) {
              this.print_newline(n > 0, this.output);
            }
            this.newlines = 0;
          };

          this.indent = function() {
            this.indent_level++;
          };

          this.unindent = function() {
            if (this.indent_level > 0) {
              this.indent_level--;
            }
          };
        };
        return this;
      }

      /*_____________________--------------------_____________________*/

      multi_parser = new Parser(); //wrapping functions Parser
      multi_parser.printer(html_source, indent_character, indent_size, wrap_line_length, brace_style); //initialize starting values

      while (true) {
        var t = multi_parser.get_token();
        multi_parser.token_text = t[0];
        multi_parser.token_type = t[1];

        if (multi_parser.token_type == 'TK_EOF') {
          break;
        }

        switch (multi_parser.token_type) {
          case 'TK_TAG_START':
            multi_parser.print_newline(false, multi_parser.output);
            multi_parser.print_token(multi_parser.token_text);
            if (multi_parser.indent_content) {
              multi_parser.indent();
              multi_parser.indent_content = false;
            }
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_STYLE':
          case 'TK_TAG_SCRIPT':
            multi_parser.print_newline(false, multi_parser.output);
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_END':
            // Print new line only if the tag has no content and has child
            if (multi_parser.last_token == 'TK_CONTENT' && multi_parser.last_text === '') {
              var tag_name = multi_parser.token_text.match(/\w+/)[0];
              var tag_extracted_from_last_output = null;
              if (multi_parser.output.length) {
                tag_extracted_from_last_output = multi_parser.output[multi_parser.output.length - 1].match(/(?:<|{{#)\s*(\w+)/);
              }
              if (tag_extracted_from_last_output == null ||
                (tag_extracted_from_last_output[1] != tag_name && !multi_parser.Utils.in_array(tag_extracted_from_last_output[1], unformatted))) {
                multi_parser.print_newline(false, multi_parser.output);
              }
            }
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_SINGLE':
            // Don't add a newline before elements that should remain unformatted.
            var tag_check = multi_parser.token_text.match(/^\s*<([a-z-]+)/i);
            if (!tag_check || !multi_parser.Utils.in_array(tag_check[1], unformatted)) {
              multi_parser.print_newline(false, multi_parser.output);
            }
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_HANDLEBARS_ELSE':
            multi_parser.print_token(multi_parser.token_text);
            if (multi_parser.indent_content) {
              multi_parser.indent();
              multi_parser.indent_content = false;
            }
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_HANDLEBARS_COMMENT':
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'TAG';
            break;
          case 'TK_CONTENT':
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'TAG';
            break;
          case 'TK_STYLE':
          case 'TK_SCRIPT':
            if (multi_parser.token_text !== '') {
              multi_parser.print_newline(false, multi_parser.output);
              var text = multi_parser.token_text;
              var _beautifier;
              var script_indent_level = 1;

              if (multi_parser.token_type == 'TK_SCRIPT') {
                _beautifier = typeof js_beautify == 'function' && js_beautify;
              } else if (multi_parser.token_type == 'TK_STYLE') {
                _beautifier = typeof css_beautify == 'function' && css_beautify;
              }

              if (options.indent_scripts == 'keep') {
                script_indent_level = 0;
              } else if (options.indent_scripts == 'separate') {
                script_indent_level = -multi_parser.indent_level;
              }

              var indentation = multi_parser.get_full_indent(script_indent_level);
              if (_beautifier) {
                // call the Beautifier if avaliable
                text = _beautifier(text.replace(/^\s*/, indentation), options);
              } else {
                // simply indent the string otherwise
                var white = text.match(/^\s*/)[0];
                var _level = white.match(/[^\n\r]*$/)[0].split(multi_parser.indent_string).length - 1;
                var reindent = multi_parser.get_full_indent(script_indent_level - _level);

                text = text.replace(/^\s*/, indentation)
                  .replace(/\r\n|\r|\n/g, '\n' + reindent)
                  .replace(/\s+$/, '');
              }
              if (text) {
                multi_parser.print_token_raw(text);
                multi_parser.print_newline(true, multi_parser.output);
              }
            }
            multi_parser.current_mode = 'TAG';
            break;
          default:
            // We should not be getting here but we don't want to drop input on the floor
            // Just output the text and move on
            if (multi_parser.token_text !== '') {
              multi_parser.print_token(multi_parser.token_text);
            }
            break;
        }
        multi_parser.last_token = multi_parser.token_type;
        multi_parser.last_text = multi_parser.token_text;
      }
      var sweet_code = multi_parser.output.join('').replace(/[\r\n\t ]+$/, '');
      if (end_with_newline) {
        sweet_code += '\n';
      }
      return sweet_code;
    }
    /* jshint ignore:end */
    /* jscs:enable */

    return {
      _init: _init,
      toggle: toggle,
      beautify: beautify
    }
  };

  $.FroalaEditor.RegisterCommand('html', {
    title: 'Code View',
    undo: false,
    focus: false,
    forcedRefresh: true,
    callback: function () {
      this.codeView.toggle();
    }
  })

  $.FroalaEditor.DefineIcon('html', {
    NAME: 'code'
  });

})(jQuery);
