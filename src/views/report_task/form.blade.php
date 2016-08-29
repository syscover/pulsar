@extends('pulsar::layouts.form')

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
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.filename'),
        'name' => 'filename',
        'value' => old('filename', isset($object)? $object->filename_023 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true,
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans('pulsar::pulsar.extension_file'),
        'name' => 'extensionFile',
        'value' => old('extensionFile', isset($object)? $object->extension_file_023 : null),
        'required' => true,
        'objects' => $extensionsExportFile,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 3
    ])
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
    @include('pulsar::includes.html.form_textarea_group', [
        'label' => trans('pulsar::pulsar.sql'),
        'name' => 'sql',
        'value' => old('sql', isset($object->sql_023)? $object->sql_023 : null),
        'required' => true
    ])
    <!-- /pulsar::reports.create -->
@stop