@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::field_group.create -->
    @include('pulsar::includes.html.form_text_group', [
        'fieldSize' => 2,
        'label' => 'ID',
        'name' => 'id',
        'value' => isset($object->id_025)? $object->id_025 : null,
        'readOnly' => true
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans_choice('pulsar::pulsar.resource', 1),
        'id' => 'resource',
        'name' => 'resource',
        'value' => old('resource', isset($object->resource_id_025)? $object->resource_id_025 : null),
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
        'value' => old('name', isset($object->name_025)? $object->name_025 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    <!-- /.pulsar::field_group.create -->
@stop