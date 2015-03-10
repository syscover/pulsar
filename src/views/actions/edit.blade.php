@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::actions.edit -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_008, 'maxLength' => '25', 'rangeLength' => '2,25', 'required' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_008, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::actions.edit -->
@stop