@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::profiles.create -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_006, 'readOnly' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_006, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::profiles.create -->
@stop