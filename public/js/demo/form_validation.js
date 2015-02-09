/*
 * form_validation.js
 *
 * Demo JavaScript used on Validation-pages.
 */

"use strict";

$(document).ready(function(){

	//===== Validation =====//
	// @see: for default options, see assets/js/plugins.form-components.js (initValidation())

	$.extend( $.validator.defaults, {
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
				? 'You missed 1 field. It has been highlighted.'
				: 'You missed ' + errors + ' fields. They have been highlighted.';
				noty({
					text: message,
					type: 'error',
					timeout: 2000
				});
			}
		},
		errorPlacement: function(error, element) {
			 if (element.attr('type') === "file" && element.data('style') === "fileinput"){
				 error.appendTo(element.closest("div.fileinput-holder").parent('div'));
			 } else {
				 error.insertAfter(element)
			 }
		}
	});

	$("#validate-1").validate();
	$("#validate-2").validate();
	$("#validate-3").validate();
	$("#validate-4").validate();

});