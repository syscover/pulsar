@extends('pulsar::layouts.form', ['action' => 'update'])

@section('head')
    @parent
    @include('pulsar::includes.js.delete_translation_record')
@stop

@section('rows')
    <!-- pulsar::field_value.edit -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id',  'value' => old('id', isset($object->id_027)? $object->id_027 : null), 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_image_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'name' => 'lang', 'nameImage' => $lang->name_001, 'value' => $lang->id_001, 'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.value', 1), 'name' => 'value', 'value' => $object->value_027, 'maxLength' => '255', 'rangeLength' => '1,255', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.sorting'), 'name' => 'sorting', 'type' => 'number', 'value' => $object->sorting_027, 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_checkbox_group', ['label' => trans('pulsar::pulsar.featured'), 'name' => 'featured', 'value' => 1, 'checked' => $object->featured_027])
    @include('pulsar::includes.html.form_hidden', ['name' => 'field', 'value' => $field->id_026])
    <!-- ./pulsar::field_value.edit -->
@stop