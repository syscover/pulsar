@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- hotels::attachment_family.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_015, 'fieldSize' => 2, 'readOnly' => true])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.resource', 1), 'id' => 'resource', 'name' => 'resource', 'value' => $object->resource_015, 'objects' => $resources, 'idSelect' => 'id_007', 'nameSelect' => 'name_007', 'class' => 'select2', 'fieldSize' => 5, 'required' => true, 'data' => ['language' => config('app.locale'), 'width' => '100%', 'error-placement' => 'select2-resource-outer-container']])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_015, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.width'), 'type' => 'number', 'name' => 'width', 'value' => $object->width_015, 'fieldSize' => 4, 'inputs' => [
        ['label' => trans('pulsar::pulsar.height'), 'type' => 'number', 'name' => 'height', 'value' => $object->height_015, 'fieldSize' => 4]
    ]])
    <!-- /hotels::attachment_family.create -->
@stop