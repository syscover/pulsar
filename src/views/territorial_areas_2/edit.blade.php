@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::territorial_areas_2.create -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_004, 'maxLength' => '10', 'rangeLength' => '2,10', 'required' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans_choice('pulsar::pulsar.country',1), 'name' => 'country', 'value' => $country->name_002, 'sizeField' => 2, 'readOnly' => true])
    @include('pulsar::common.block.block_form_select_group', ['label' => $country->territorial_area_1_002, 'name' => 'territorialArea1', 'value' => $object->territorial_area_1_004, 'sizeField' => 6, 'objects' => $territorialAreas1, 'idSelect' => 'id_003', 'nameSelect' => 'name_003', 'required' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_004, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::territorial_areas_2.create -->
@stop