<!-- pulsar::includes.html.script_header_form -->
<link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/jquery.select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/jquery.select2.custom/css/select2.css') }}">

<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/uniform/jquery.uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/fileinput/fileinput.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.select2.custom/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.select2/js/i18n/' . config('app.locale') . '.js') }}"></script>

<!-- form validation -->
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.validation/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.validation/additional-methods.min.js') }}"></script>
@if(config('app.locale') != 'en')
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.validation/localization/messages_' . config('app.locale') . '.min.js') }}"></script>
@endif
<!-- /form validation -->

<script type="text/javascript">
    $(document).ready(function() {
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
                    buttontext: '{{ trans('pulsar::pulsar.select') }}...',
                    inputsize: 'input-block-level'
            });
        }
    });
</script>
<!-- /pulsar::includes.html.script_header_form -->