//en caso de no ser igual a un valor
jQuery.validator.addMethod("notequal", function(value, element, param) {
  return this.optional(element) || value != param;
}, "Please specify a different (non-default) value");