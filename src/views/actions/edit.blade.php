@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::actions.edit -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'oldName' => 'idOld', 'value' => $object->id_008, 'maxLength' => '25', 'rangeLength' => '2,25', 'required' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_008, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::actions.edit -->
@stop