@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- hotels::attachment_family.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_155, 'fieldSize' => 2, 'readOnly' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_155, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.width'), 'type' => 'number', 'name' => 'width', 'value' => $object->width_155, 'fieldSize' => 4, 'inputs' => [
        ['label' => trans('pulsar::pulsar.height'), 'type' => 'number', 'name' => 'height', 'value' => $object->height_155, 'fieldSize' => 4]
    ]])
    <!-- /hotels::attachment_family.create -->
@stop