@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::packages.edit -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_012, 'readOnly' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_012, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::common.block.block_form_checkbox_group', ['label' => trans('pulsar::pulsar.active'), 'name' => 'active', 'value' => 1, 'isChecked' => $object->active_012])
    <!-- /pulsar::packages.edit -->
@stop