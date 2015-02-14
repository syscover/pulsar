@extends('pulsar::layouts.form', ['object' => trans_choice('pulsar::pulsar.action', 1), 'action' => 'update'])

@section('rows')
    <!-- pulsar::actions.edit -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'oldName' => 'idOld', 'value' => $object->id_008, 'maxlength' => '25', 'rangelength' => '2,25', 'required' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_008, 'maxlength' => '50', 'rangelength' => '2,50', 'required' => true])
    <!-- /pulsar::actions.edit -->
@stop