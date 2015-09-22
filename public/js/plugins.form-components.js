/*
 * Core script to handle all form specific plugins
 */

var FormComponents = function() {

	"use strict";

	/**************************
	 * Elastic textareas      *
	 **************************/
	var initAutosize = function() {
		if ($.fn.autosize) {
			$('textarea.auto').autosize();
		}
	}

	/**************************
	 * Input limiter          *
	 **************************/
	var initInputlimiter = function() {
		if ($.fn.inputlimiter) {
			// Set default options
			$.extend(true, $.fn.inputlimiter.defaults, {
				boxAttach: false,
				boxId: 'limit-text',
				remText: '%n character%s remaining.',
				limitText: 'Field limited to %n character%s.',
				zeroPlural: true
			});

			// Initialize limiter
			$('textarea.limited').each(function(index, value) {
				var limitText = $.fn.inputlimiter.defaults.limitText;
				var data_limit = $(this).data('limit');
				limitText = limitText.replace(/\%n/g, data_limit);
				limitText = limitText.replace(/\%s/g, (data_limit <= 1 ? '' : 's'));

				$(this).parent().find('#limit-text').html(limitText);
				$(this).inputlimiter({
					limit: data_limit
				});
			});
		}
	}

	/**************************
	 * Uniform                *
	 **************************/
	var initUniform = function() {
		if ($.fn.uniform) {
			$(':radio.uniform, :checkbox.uniform').uniform();
		}
	}

	/**************************
	 * Tags                   *
	 **************************/
/*
	var initTags = function() {
		if ($.fn.tagsInput) {
			// Initialize and set default options
			$('.tags').tagsinput({
				width: '100%',
			 	height: 'auto',
			 	defaultText: 'add a tag'
			});
		}
	}
	*/
	/*
	var initTags = function() {
		if ($.fn.tagsInput) {
			// Initialize and set default options
			$('.tags').tagsInput({
				width: '100%',
				height: 'auto',
				defaultText: 'add a tag'
			});
		}
	}
*/
	/**************************
	 * Spinner                *
	 **************************/
	var initSpinner = function() {
		if ($.fn.spinner) {
			$('.spinner').each(function() {
				$(this).spinner();
			});
		}
	}

	/**************************
	 * Dual Select Boxes      *
	 **************************/
	var initDualListBox = function() {
		if ($.configureBoxes) {
			$.configureBoxes({
                            textShowing: 'Mostrando',
                            textOf: 'de'
                        });
		}
	}

	/**************************
	 * Validation             *
	 **************************/
	var initValidation = function() {
		if ($.validator) {
			// Set default options
			$.extend( $.validator.defaults, {
				errorClass: "has-error",
				validClass: "has-success",
				highlight: function(element, errorClass, validClass) {
					if (element.type === 'radio') {
						this.findByName(element.name).addClass(errorClass).removeClass(validClass);
					} else {
						$(element).addClass(errorClass).removeClass(validClass);
					}

					// @see http://support.stammtec.de/discussion/412/form-vertical-validation
					if ($(element).closest("form").hasClass('form-vertical')) {
						var class_selector = "*[class^=col-]";
					} else {
						var class_selector = ".form-group";
					}

					$(element).closest(class_selector).addClass(errorClass).removeClass(validClass);
				},
				unhighlight: function(element, errorClass, validClass) {
					if (element.type === 'radio') {
						this.findByName(element.name).removeClass(errorClass).addClass(validClass);
					} else {
						$(element).removeClass(errorClass).addClass(validClass);
					}

					// @see http://support.stammtec.de/discussion/412/form-vertical-validation
					if ($(element).closest("form").hasClass('form-vertical')) {
						var class_selector = "*[class^=col-]";
					} else {
						var class_selector = ".form-group";
					}

					$(element).closest(class_selector).removeClass(errorClass).addClass(validClass);

					// Fix for not removing label in BS3
					$(element).closest(class_selector).find('label[generated="true"]').html('');
				}
			});

			var _base_resetForm = $.validator.prototype.resetForm;
			$.extend( $.validator.prototype, {
				resetForm: function() {
					var resetForm_this = this;
					_base_resetForm.call( this );

					$(this.currentForm).find('.form-group').each(function () {
						$(this).removeClass(resetForm_this.settings.errorClass + ' ' + resetForm_this.settings.validClass);
					});

					// Removing states from select2-boxes
					$(this.currentForm).find('.select2-container').removeClass(resetForm_this.settings.errorClass + ' ' + resetForm_this.settings.validClass);

					$(this.currentForm).find('label[generated="true"]').html('');
				},
				showLabel: function(element, message) {
					var label = this.errorsFor( element );
					if ( label.length ) {
						// refresh error/success class
						label.removeClass( this.settings.validClass ).addClass( this.settings.errorClass );

						// check if we have a generated label, replace the message then
						if ( label.attr("generated") ) {
							label.html(message);
						}
					} else {
						// create label
						label = $("<" + this.settings.errorElement + "/>")
							.attr({"for":  this.idOrName(element), generated: true})
							.addClass(this.settings.errorClass)
							.addClass('help-block')
							.html(message || "");
						if ( this.settings.wrapper ) {
							// make sure the element is visible, even in IE
							// actually showing the wrapped element is handled elsewhere
							label = label.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
						}
						if ( !this.labelContainer.append(label).length ) {
							if ( this.settings.errorPlacement ) {
								this.settings.errorPlacement(label, $(element) );
							} else {
							label.insertAfter(element);
							}
						}
					}
					if ( !message && this.settings.success ) {
						label.text("");
						if ( typeof this.settings.success === "string" ) {
							label.addClass( this.settings.success );
						} else {
							this.settings.success( label, element );
						}
					}
					this.toShow = this.toShow.add(label);
				}
			});
		}
	}

	/**************************
	 * WYSIWYG                *
	 **************************/
	var initWysiwyg = function() {
		if ($.fn.wysihtml5) {
			// Set default options
			$.extend(true, $.fn.wysihtml5.defaultOptions, {
				stylesheets: ["./assets/css/plugins/bootstrap-wysihtml5.css"]
			});

			$('.wysiwyg').each(function () {
				$(this).wysihtml5();
			});
		}
	}

	/**************************
	 * Multiselect            *
	 **************************/
	var initMultiselect = function() {
		if ($.fn.multiselect) {
			$('.multiselect').each(function () {
				$(this).multiselect();
			});
		}
	}

	return {

		// main function to initiate all plugins
		init: function () {
			initAutosize(); // Elastic textareas
			initInputlimiter(); // Input limiter
			initUniform(); // Uniform (styled radio- and checkboxes)
			//initTags(); // TagsInput
			initSpinner(); // Spinner
			initDualListBox(); // Dual Select Boxes
			initValidation(); // Validation
			initWysiwyg(); // wysihtml5
			initMultiselect(); // Multiselect
		}

	};

}();