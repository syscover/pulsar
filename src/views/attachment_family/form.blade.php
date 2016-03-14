@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::attachment_family.form -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => isset($object)? $object->id_015 : null,
        'fieldSize' => 2,
        'readOnly' => true
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans_choice('pulsar::pulsar.resource', 1),
        'id' => 'resource', 'name' => 'resource',
        'value' => old('resource', isset($object)? $object->resource_015 : null),
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
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object)? $object->name_015 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.width'),
        'type' => 'number',
        'name' => 'width',
        'value' => old('width', isset($object)? $object->width_015 : null),
        'fieldSize' => 4,
        'inputs' => [
            [
                'label' => trans('pulsar::pulsar.height'),
                'type' => 'number',
                'name' => 'height',
                'value' => old('height', isset($object)? $object->height_015 : null),
                'fieldSize' => 4
            ]
    ]])
    <!-- ./pulsar::attachment_family.form -->
@stop