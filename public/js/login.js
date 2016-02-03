/*
 * Core script to handle all login specific things
 */

var Login = function() {

	"use strict";

	/* * * * * * * * * * * *
	 * Uniform
	 * * * * * * * * * * * */
	var initUniform = function() {
		if ($.fn.uniform) {
			$(':radio.uniform, :checkbox.uniform').uniform();
		}
	}

	/* * * * * * * * * * * *
	 * Sign In / Up Switcher
	 * * * * * * * * * * * */
	var initSignInUpSwitcher = function() {
		// Click on "Don't have an account yet? Sign Up"-text
		$('.sign-up').click(function (e) {
			e.preventDefault(); // Prevent redirect to #

			// Hide login form
			$('.login-form').slideUp(350, function() {
				// Finished, so show register form
				$('.register-form').slideDown(350);
				$('.sign-up').hide();
			});
		});

		// Click on "Back"-button
		$('.back').click(function (e) {
			e.preventDefault(); // Prevent redirect to #

			// Hide register form
			$('.register-form').slideUp(350, function() {
				// Finished, so show login form
				$('.login-form').slideDown(350);
				$('.sign-up').show();
			});
		});
	}

	/* * * * * * * * * * * *
	 * Forgot Password
	 * * * * * * * * * * * */
	var initForgotPassword = function() {
		// Click on "Forgot Password?" link
		$('.forgot-password-link').click(function(e) {
			e.preventDefault(); // Prevent redirect to #

			$('.forgot-password-form').slideToggle(200);
			$('.inner-box .close').fadeToggle(200);
		});

		// Click on close-button
		$('.inner-box .close').click(function() {
			// Emulate click on forgot password link
			// to reduce redundancy
			$('.forgot-password-link').click();
		});
	}

	/* * * * * * * * * * * *
	 * Validation Defaults
	 * * * * * * * * * * * */
	var initValidationDefaults = function() {
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
					$(element).closest(".form-group").addClass(errorClass).removeClass(validClass);
				},
				unhighlight: function(element, errorClass, validClass) {
					if (element.type === 'radio') {
						this.findByName(element.name).removeClass(errorClass).addClass(validClass);
					} else {
						$(element).removeClass(errorClass).addClass(validClass);
					}
					$(element).closest(".form-group").removeClass(errorClass).addClass(validClass);

					// Fix for not removing label in BS3
					$(element).closest('.form-group').find('label[generated="true"]').html('');
				}
			});

			var _base_resetForm = $.validator.prototype.resetForm;
			$.extend( $.validator.prototype, {
				resetForm: function() {
					_base_resetForm.call( this );
					this.elements().closest('.form-group')
						.removeClass(this.settings.errorClass + ' ' + this.settings.validClass);
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

	/* * * * * * * * * * * *
	 * Validation for Login
	 * * * * * * * * * * * */
	var initLoginValidation = function() {
		if ($.validator) {
			$('.login-form').validate({
				invalidHandler: function (event, validator) { // display error alert on form submit
					NProgress.start(); // Demo Purpose Only!
					$('.login-form .alert-danger').show();
					NProgress.done(); // Demo Purpose Only!
				},

				submitHandler: function (form) {
					// This is just example code for the live preview
					//
					// In reality, you want to remove this submitHandler
					// to use the native browser submitting
					form.submit();
				}
			});
		}
	}

    /* * * * * * * * * * * *
     * Validation for Forgot Password
     * * * * * * * * * * * */
    var initForgotPasswordValidation = function() {
        if ($.validator)
        {
            $('.forgot-password-form').validate({
                submitHandler: function (form) {

                    $.cssLoader.show({
                        useLayer: false
                    });

                    $.ajax({
                        type: "post",
                        url: $(form).attr('action'),
                        dataType: 'json',
						headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                        data: {
                            'email_010': $('input[name=email_010]').val()
                        },
                        success: function(data)
                        {
                            if(data.success)
                            {
                                $('.inner-box').slideUp(350, function() {
                                    $('.forgot-password-ok').show();
                                    $('.forgot-password-nook').hide();
                                    $('.forgot-password-form').hide();
                                    $('.forgot-password-link').hide();
                                    $('.inner-box .close').hide();
                                    $('.forgot-password-done').show();
                                    $('.inner-box').slideDown(350);
                                });
                            }
                            else
                            {
                                $('.inner-box').slideUp(350, function() {
                                    $('.forgot-password-ok').hide();
                                    $('.forgot-password-nook').show();
                                    $('.forgot-password-form').hide();
                                    $('.forgot-password-link').hide();
                                    $('.inner-box .close').hide();
                                    $('.forgot-password-done').show();
                                    $('.inner-box').slideDown(350);
                                });
                            }
                            $.cssLoader.hide();
                        },
                        error: function(e)
                        {
                            console.log(e);
                            $.cssLoader.hide();
                        }
                    });
                    return false;
                }
            });
        }
    }

	return {

		// main function to initiate all plugins
		init: function () {
			initUniform(); // Styled checkboxes
			initSignInUpSwitcher(); // Handle sign in and sign up specific things
			initForgotPassword(); // Handle forgot password specific things

			// Validations
			initValidationDefaults(); // Extending jQuery Validation defaults
			initLoginValidation(); // Validation for Login (Sign In)
			initForgotPasswordValidation(); // Validation for the Password-Forgotten-Widget
		},

	};

}();