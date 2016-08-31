@extends('pulsar::layouts.form')

@section('head')
    @parent

    <script src="{{ asset('packages/syscover/pulsar/vendor/ace/src-noconflict/ace.js') }}"></script>
    <script src="{{ asset('packages/syscover/pulsar/vendor/ace/src-noconflict/ext-language_tools.js') }}"></script>
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
            $('#recordForm').on('submit', function ($e) {
                $('[name=sql]').val(editor.getValue());
            });
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
        'value' => old('email', isset($object)? $object->email_023 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
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