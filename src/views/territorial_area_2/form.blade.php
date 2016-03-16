@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::territorial_areas_2.create -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => old('id', isset($object)? $object->id_004 : null),
        'maxLength' => '10',
        'rangeLength' => '2,10',
        'required' => true,
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.country',1),
        'name' => 'country',
        'value' => $country->name_002,
        'fieldSize' => 2,
        'readOnly' => true
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => $country->territorial_area_1_002,
        'name' => 'territorialArea1',
        'value' => old('territorialArea1', isset($object)? $object->territorial_area_1_004 : null),
        'fieldSize' => 6,
        'objects' => $territorialAreas1,
        'idSelect' => 'id_003',
        'nameSelect' => 'name_003',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object)? $object->name_004 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    <!-- ./pulsar::territorial_areas_2.create -->
@stop