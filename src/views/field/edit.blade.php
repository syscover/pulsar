@extends('pulsar::layouts.form', ['action' => 'update'])

@section('script')
    @parent
    @include('pulsar::includes.js.delete_translation_record')
@stop

@section('rows')
    <!-- pulsar::field.edit -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id',  'value' => $object->id_026, 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.group', 1), 'name' => 'family', 'value' => $object->group_026, 'required' => true, 'objects' => $groups, 'idSelect' => 'id_025', 'nameSelect' => 'name_025', 'class' => 'select2', 'fieldSize' => 5 , 'data' => ['language' => config('app.locale'), 'width' => '100%', 'error-placement' => 'select2-section-outer-container']])
    @include('pulsar::includes.html.form_image_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'name' => 'lang', 'nameImage' => $lang->name_001, 'value' => $lang->id_001, 'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.label', 1), 'name' => 'label', 'value' => $object->label_026, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_026, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.field_type', 1), 'name' => 'fieldType', 'value' => $object->field_type_026, 'required' => true, 'objects' => $fieldTypes, 'idSelect' => 'id', 'nameSelect' => 'name', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.data_type', 1), 'name' => 'dataType', 'value' => $object->data_type_026, 'required' => true, 'objects' => $dataTypes, 'idSelect' => 'id', 'nameSelect' => 'name', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_checkbox_group', ['label' => trans('pulsar::pulsar.required'), 'name' => 'required', 'value' => 1, 'checked' => $object->required_026])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.sorting'), 'name' => 'sorting', 'type' => 'number', 'value' => $object->sorting_026, 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.maximum_length'), 'name' => 'maxLength', 'type' => 'number', 'value' => $object->max_length_026, 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.pattern'), 'name' => 'pattern', 'value' => $object->pattern_026, 'maxLength' => '50', 'rangeLength' => '2,50', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.label_size'), 'name' => 'labelSize', 'type' => 'number', 'value' => $object->label_size_026, 'maxLength' => '1', 'min' => '0', 'fieldSize' => 2, 'inputs' =>[
        ['label' => trans('pulsar::pulsar.field_size'), 'name' => 'fieldSize', 'type' => 'number', 'value' => $object->field_size_026, 'maxLength' => '1', 'min' => '0', 'fieldSize' => 2]
    ]])
    <!-- /pulsar::field.edit -->
@stop