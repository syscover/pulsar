@extends('pulsar::layouts.form', ['action' => 'store'])

@section('rows')
    <!-- pulsar::countries.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => Input::old('id', isset($object->id_002)? $object->id_002 : null), 'maxLength' => '2', 'size' => '2', 'required' => true, 'fieldSize' => 2, 'readOnly' => $lang->id_001 == Session::get('baseLang')->id_001? false : true])
    @include('pulsar::includes.html.form_image_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'name' => 'lang', 'nameImage' => $lang->name_001, 'value' => $lang->id_001, 'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name', isset($object->name_002)? $object->name_002 : null), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.sorting'), 'name' => 'sorting', 'value' => Input::old('sorting', isset($object->sorting_002)? $object->sorting_002 : null), 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.prefix'), 'name' => 'prefix', 'value' => Input::old('prefix', isset($object->prefix_002)? $object->prefix_002 : null), 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.territorial_area',1) . ' 1', 'name' => 'territorialArea1', 'value' => Input::old('territorialArea1', isset($object->territorial_area_1_002)? $object->territorial_area_1_002 : null), 'maxLength' => '50', 'rangeLength' => '2,50'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.territorial_area',1) . ' 2', 'name' => 'territorialArea2', 'value' => Input::old('territorialArea2', isset($object->territorial_area_2_002)? $object->territorial_area_2_002 : null), 'maxLength' => '50', 'rangeLength' => '2,50'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.territorial_area',1) . ' 3', 'name' => 'territorialArea3', 'value' => Input::old('territorialArea3', isset($object->territorial_area_3_002)? $object->territorial_area_3_002 : null), 'maxLength' => '50', 'rangeLength' => '2,50'])
    <!-- /pulsar::countries.create -->
@stop