@extends('pulsar::layouts.form', ['action' => 'store', 'enctype' => true])

@section('rows')
    <!-- pulsar::langs.create -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => Input::old('id'), 'maxLength' => '2', 'size' => '2', 'required' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.sorting'), 'name' => 'sorting', 'value' => Input::old('sorting'), 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'sizeField' => 2])
    @include('pulsar::common.block.block_form_checkbox_group', ['label' => trans('pulsar::pulsar.base_language'), 'name' => 'base', 'value' => 1, 'isChecked' => Input::old('base')])
    @include('pulsar::common.block.block_form_file_group', ['label' => trans_choice('pulsar::pulsar.image', 1), 'name' => 'image'])
    @include('pulsar::common.block.block_form_checkbox_group', ['label' => trans('pulsar::pulsar.active'), 'name' => 'active', 'value' => 1, 'isChecked' => Input::old('active')])
    <!-- /pulsar::langs.create -->
@stop