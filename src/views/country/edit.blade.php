@extends('pulsar::layouts.form', ['action' => 'update'])

@section('head')
    @parent
    @include('pulsar::includes.js.delete_translation_record')
@stop

@section('rows')
    <!-- pulsar::countries.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_002, 'maxLength' => '2', 'size' => '2', 'required' => true, 'fieldSize' => 2, 'readOnly' => $lang->id_001 !=  session('baseLang')->id_001? true : false])
    @include('pulsar::includes.html.form_image_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'name' => 'lang', 'nameImage' => $lang->name_001, 'value' => $lang->id_001, 'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_002, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.sorting'), 'name' => 'sorting', 'value' => $object->sorting_002, 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.prefix'), 'name' => 'prefix', 'value' => $object->prefix_002, 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.territorial_area',1) . ' 1', 'name' => 'territorialArea1', 'value' => $object->territorial_area_1_002, 'maxLength' => '50', 'rangeLength' => '2,50'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.territorial_area',1) . ' 2', 'name' => 'territorialArea2', 'value' => $object->territorial_area_2_002, 'maxLength' => '50', 'rangeLength' => '2,50'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.territorial_area',1) . ' 3', 'name' => 'territorialArea3', 'value' => $object->territorial_area_3_002, 'maxLength' => '50', 'rangeLength' => '2,50'])
    <!-- /pulsar::countries.create -->
@stop
