@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::resources.create -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => old('id', isset($object)? $object->id_007 : null),
        'maxLength' => '30',
        'rangeLength' => '2,30',
        'required' => true,
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans_choice('pulsar::pulsar.package', 1),
        'name' => 'package',
        'value' => old('package', isset($object)? $object->package_007 : null),
        'required' => true,
        'objects' => $packages,
        'idSelect' => 'id_012',
        'nameSelect' => 'name_012',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object)? $object->name_007 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    <!-- /.pulsar::resources.create -->
@stop