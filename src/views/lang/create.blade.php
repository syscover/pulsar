@extends('pulsar::layouts.form', ['action' => 'store', 'enctype' => true])

@section('rows')
    <!-- pulsar::langs.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => old('id'), 'maxLength' => '2', 'size' => '2', 'required' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => old('name'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.sorting'), 'name' => 'sorting', 'value' => old('sorting'), 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_checkbox_group', ['label' => trans('pulsar::pulsar.base_language'), 'name' => 'base', 'value' => 1, 'checked' => old('base')])
    @include('pulsar::includes.html.form_file_image_group', ['label' => trans_choice('pulsar::pulsar.image', 1), 'name' => 'image'])
    @include('pulsar::includes.html.form_checkbox_group', ['label' => trans('pulsar::pulsar.active'), 'name' => 'active', 'value' => 1, 'checked' => old('active')])
    <!-- /pulsar::langs.create -->
@stop