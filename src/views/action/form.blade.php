@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::actions.common -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => old('id', isset($object)? $object->id_008 : null),
        'maxLength' => '25',
        'rangeLength' => '2,25',
        'required' => true,
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object)? $object->name_008 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    <!-- /pulsar::actions.common -->
@stop