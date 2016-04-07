@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::attachment_mime.form -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => isset($object)? $object->id_019 : null,
        'fieldSize' => 2,
        'readOnly' => true
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans_choice('pulsar::pulsar.resource', 1),
        'id' => 'resource', 'name' => 'resource',
        'value' => old('resource', isset($object)? $object->resource_id_019 : null),
        'objects' => $resources,
        'idSelect' => 'id_007',
        'nameSelect' => 'name_007',
        'class' => 'select2',
        'fieldSize' => 5,
        'required' => true,
        'data' => [
            'language' => config('app.locale'),
            'width' => '100%',
            'error-placement' => 'select2-resource-outer-container'
        ]
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.mime', 1),
        'name' => 'mime',
        'value' => old('mime', isset($object)? $object->mime_019 : null),
        'maxLength' => '100',
        'rangeLength' => '2,255',
        'required' => true
    ])
    <!-- /.pulsar::attachment_mime.form -->
@stop