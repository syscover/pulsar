@extends('pulsar::layouts.form', ['action' => 'store', 'customTrans' => $country->territorial_area_1_002])

@section('rows')
    <!-- pulsar::territorial_areas_1.create -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => Input::old('id'), 'maxLength' => '6', 'rangeLength' => '2,6', 'required' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans_choice('pulsar::pulsar.country',1), 'name' => 'country', 'value' => $country->name_002, 'sizeField' => 2, 'readOnly' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::territorial_areas_1.create -->
@stop