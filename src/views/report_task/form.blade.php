@extends('pulsar::layouts.form')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/tokenfield/css/bootstrap-tokenfield.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/tokenfield/css/tokenfield-typeahead.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">

    <script src="{{ asset('packages/syscover/pulsar/vendor/ace/src-noconflict/ace.js') }}"></script>
    <script src="{{ asset('packages/syscover/pulsar/vendor/ace/src-noconflict/ext-language_tools.js') }}"></script>
    <script src="{{ asset('packages/syscover/pulsar/vendor/tokenfield/bootstrap-tokenfield.js') }}"></script>
    <script src="{{ asset('packages/syscover/pulsar/vendor/datetimepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('packages/syscover/pulsar/vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var editor = ace.edit('aceEditor');
            editor.setTheme('ace/theme/sqlserver');
            editor.getSession().setMode('ace/mode/mysql');
            editor.setShowPrintMargin(false);
            editor.setHighlightActiveLine(true);
            editor.getSession().setUseWrapMode(true);
            editor.setOptions({
                enableBasicAutocompletion: true
            });

            // save sql data to submit value
            $('#recordForm').on('submit', function () {
                $('[name=sql]').val(editor.getValue());
                $("[name=jsonCcEmails]").val(JSON.stringify($('[name=ccEmails]').tokenfield('getTokens')));
            });

            // tags element, on edit we load values across javascript
            $('[name=ccEmails]').tokenfield({
                autocomplete: {
                    source: {!! json_encode($ccEmails) !!},
                    delay: 100
                },
                showAutocompleteOnFocus: true
            })@if(isset($ccEmails)).tokenfield('setTokens', {!! json_encode($ccEmails) !!});@else; @endif

            // rutine to avoid introduce a repeat token
            $('[name=ccEmails]').on('tokenfield:createtoken', function (event) {
                var existingTokens  = $(this).tokenfield('getTokens');
                var autocomplete    = $(this).tokenfield('getAutocomplete');

                // search if there is a object with the same label
                if(event.attrs.value === 'null')
                {
                    $.each(autocomplete.source, function (index, object) {
                        if(object.label === event.attrs.label)
                        {
                            event.preventDefault();
                            $('[name=ccEmails]').tokenfield('createToken', object);
                        }
                    });
                }

                $.each(existingTokens, function(index, token)
                {
                    if (event.attrs.value === 'null' && token.label === event.attrs.label)
                    {
                        event.preventDefault();
                    }
                    else if(event.attrs.value !== 'null' && token.value === event.attrs.value)
                    {
                        event.preventDefault();
                    }
                });
            });

            $('[name=frequency]').on('change', function() {

                if($(this).val() == 1)
                {
                    $('#dates').slideToggle("slow");
                }
                else
                {
                    if($('#dates').is(':visible'))
                        $('#dates').slideToggle("slow");
                }
            });

            @if(! isset($object) || (isset($object) && $object->frequency_023 != 1))
                // hide elements
                $('#dates').hide();
            @endif
        });
    </script>
@stop

@section('rows')
    <!-- pulsar::reports.create -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => old('id', isset($object)? $object->id_023 : null),
        'readOnly' => true,
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.email'),
        'name' => 'email',
        'value' => old('email', isset($object)? $object->email_023 : auth()->guard('pulsar')->user()->email_010),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.cc'),
        'name' => 'ccEmails',
        'placeholder' => trans('pulsar::pulsar.write_emails')
    ])
    @include('pulsar::includes.html.form_hidden', [
        'name' => 'jsonCcEmails'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.subject'),
        'name' => 'subject',
        'value' => old('subject', isset($object)? $object->subject_023 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    <div class="row">
        <div class="col-md-6">
            @include('pulsar::includes.html.form_text_group', [
                'label' => trans('pulsar::pulsar.filename'),
                'name' => 'filename',
                'value' => old('filename', isset($object)? $object->filename_023 : null),
                'maxLength' => '255',
                'rangeLength' => '2,255',
                'required' => true,
                'labelSize' => 4,
                'fieldSize' => 8
            ])
        </div>
        <div class="col-md-6">
            @include('pulsar::includes.html.form_select_group', [
                'label' => trans('pulsar::pulsar.extension_file'),
                'name' => 'extensionFile',
                'value' => old('extensionFile', isset($object)? $object->extension_file_023 : null),
                'required' => true,
                'objects' => $extensionsExportFile,
                'idSelect' => 'id',
                'nameSelect' => 'name',
                'labelSize' => 4,
                'fieldSize' => 5
            ])
        </div>
    </div>

    @include('pulsar::includes.html.form_select_group', [
        'label' => trans('pulsar::pulsar.frequency'),
        'name' => 'frequency',
        'value' => old('frequency', isset($object)? $object->frequency_023 : null),
        'required' => true,
        'objects' => $frequencies,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 3
    ])

    <div id="dates" class="row">
        <div class="col-md-6">
            @include('pulsar::includes.html.form_datetimepicker_group', [
                'labelSize' => 4,
                'fieldSize' => 8,
                'label' => trans('pulsar::pulsar.from'),
                'name' => 'from',
                'id' => 'idFrom',
                'value' => old('from', isset($object->from_023)? date(config('pulsar.datePattern') . ' H:i', $object->from_023) : null),
                'data' => [
                    'format' => Miscellaneous::convertFormatDate(config('pulsar.datePattern')) . ' HH:mm',
                    'locale' => config('app.locale')
                ]
            ])
        </div>
        <div class="col-md-6">
            @include('pulsar::includes.html.form_datetimepicker_group', [
                'labelSize' => 4,
                'fieldSize' => 8,
                'label' => trans('pulsar::pulsar.until'),
                'name' => 'until',
                'id' => 'idUntil',
                'value' => old('until', isset($object->until_023)? date(config('pulsar.datePattern') . ' H:i', $object->until_023) : null),
                'data' => [
                    'format' => Miscellaneous::convertFormatDate(config('pulsar.datePattern')) . ' HH:mm',
                    'locale' => config('app.locale')
                ]
            ])
        </div>
    </div>
    @include('pulsar::includes.html.form_ace_editor_group', [
        'fieldHeight' => 300,
        'label' => trans('pulsar::pulsar.sql'),
        'name' => 'sql',
        'value' => old('sql', isset($object->sql_023)? $object->sql_023 : null),
        'required' => true
    ])
    @include('pulsar::includes.html.form_hidden', [
        'name' => 'sql',
        'required' => true
    ])
    <!-- /pulsar::reports.create -->
@stop