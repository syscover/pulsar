<!-- Forms -->
<script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/uniform/jquery.uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/fileinput/fileinput.js') }}"></script>

<!-- Form Validation -->
<script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/validation/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/validation/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/additional-rules-validate.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        if ($.validator) {
            $.extend($.validator.messages, {
                required:           "{{ trans('pulsar::js-validation.required') }}",
                remote:             "{{ trans('pulsar::js-validation.remote') }}",
                email:              "{{ trans('pulsar::js-validation.email') }}",
                url:                "{{ trans('pulsar::js-validation.url') }}",
                date:               "{{ trans('pulsar::js-validation.date') }}",
                dateISO:            "{{ trans('pulsar::js-validation.dateISO') }}",
                number:             "{{ trans('pulsar::js-validation.number') }}",
                digits:             "{{ trans('pulsar::js-validation.digits') }}",
                creditcard:         "{{ trans('pulsar::js-validation.creditcard') }}",
                equalTo:            "{{ trans('pulsar::js-validation.equalTo') }}",
                accept:             "{{ trans('pulsar::js-validation.accept') }}",
                notequal:           "{{ trans('pulsar::js-validation.notequal') }}",
                notequaltofield:    "{{ trans('pulsar::js-validation.notequal') }}",
                maxlength:          jQuery.validator.format("{{ trans('pulsar::js-validation.maxlength') }}"),
                minlength:          jQuery.validator.format("{{ trans('pulsar::js-validation.minlength') }}"),
                rangelength:        jQuery.validator.format("{{ trans('pulsar::js-validation.rangelength') }}"),
                rangelengthnoempty: jQuery.validator.format("{{ trans('pulsar::js-validation.rangelengthnoempty') }}"),
                range:              jQuery.validator.format("{{ trans('pulsar::js-validation.range') }}"),
                max:                jQuery.validator.format("{{ trans('pulsar::js-validation.max') }}"),
                min:                jQuery.validator.format("{{ trans('pulsar::js-validation.min') }}")
            });
        }
        if ($.fn.inputlimiter) {
            $.extend(true, $.fn.inputlimiter.defaults, {
                boxAttach:  false,
                remText:    "{{ trans('pulsar::js-inputlimiter.remText') }}",
                limitText:  "{{ trans('pulsar::js-inputlimiter.limitText') }}",
                zeroPlural: true
            });
        }
        if ($.fn.fileInput) {
            // Set default options
            $.extend(true, $.fn.fileInput.defaults, {
                    placeholder: '{{ trans('pulsar::pulsar.file_not_select') }}...',
                    buttontext: '{{ trans('pulsar::pulsar.select') }}...'
            });

            $('[data-style="fileinput"]').each(function () {
                    var $input = $(this);
                    $input.fileInput($input.data());
            });
        }
    });
</script>