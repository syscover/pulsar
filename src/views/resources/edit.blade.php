@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::resources.edit -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_007, 'maxLength' => '30', 'rangeLength' => '2,30', 'required' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_select_group', ['label' => trans_choice('pulsar::pulsar.package', 1), 'name' => 'package', 'value' => $object->package_007, 'required' => true, 'objects' => $packages, 'idSelect' => 'id_012', 'nameSelect' => 'name_012', 'class' => 'form-control', 'sizeField' => 5])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_007, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::resources.edit -->
@stop