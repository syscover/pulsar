<!-- Forms -->
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/uniform/jquery.uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/fileinput/fileinput.js') }}"></script>

<!-- form validation -->
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.validation/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.validation/additional-methods.min.js') }}"></script>
@if(config('app.locale') != 'en')
<script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.validation/localization/messages_' . config('app.locale') . '.min.js') }}"></script>
@endif

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