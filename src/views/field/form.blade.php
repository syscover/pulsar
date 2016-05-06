@extends('pulsar::layouts.form')

@section('head')
    @parent
    @include('pulsar::includes.js.delete_translation_record')
@stop

@section('rows')
    <!-- pulsar::field.form -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => old('id', isset($object->id_026)? $object->id_026 : null),
        'readOnly' => true,
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans_choice('pulsar::pulsar.group', 1),
        'id' => 'group',
        'name' => 'group',
        'value' => old('group', isset($object->group_026)? $object->group_026 : null),
        'required' => true,
        'objects' => $groups,
        'idSelect' => 'id_025',
        'nameSelect' => 'name_025',
        'class' => 'select2',
        'fieldSize' => 5,
        'data' => [
            'language' => config('app.locale'),
            'width' => '100%',
            'error-placement' => 'select2-group-outer-container',
            'disabled' => $action == 'update' || $action == 'store'? false : true
        ]
    ])
    @include('pulsar::includes.html.form_image_group', [
        'label' => trans_choice('pulsar::pulsar.language', 1),
        'name' => 'lang',
        'nameImage' => $lang->name_001,
        'value' => $lang->id_001,
        'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.label', 1),
        'name' => 'label',
        'value' => old('name', isset($object->label_026)? $object->label_026 : null),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object->name_026)? $object->name_026 : null),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'required' => true,
        'readOnly' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans_choice('pulsar::pulsar.field_type', 1),
        'name' => 'fieldType',
        'value' => old('fieldType', isset($object->field_type_id_026)? $object->field_type_id_026 : null),
        'required' => true,
        'objects' => $fieldTypes,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 5,
        'disabled' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans_choice('pulsar::pulsar.data_type', 1),
        'name' => 'dataType',
        'value' => old('dataType', isset($object->data_type_id_026)? $object->data_type_id_026 : null),
        'required' => true,
        'objects' => $dataTypes,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 5,
        'disabled' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_checkbox_group', [
        'label' => trans('pulsar::pulsar.required'),
        'name' => 'required',
        'value' => 1,
        'checked' => old('required',  isset($object)? $object->required_026 : null),
        'disabled' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.sorting'),
        'name' => 'sorting',
        'type' => 'number',
        'value' => old('sorting', isset($object->sorting_026)? $object->sorting_026 : null),
        'maxLength' => '3',
        'rangeLength' => '1,3',
        'min' => '0',
        'fieldSize' => 2,
        'readOnly' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.maximum_length'),
        'name' => 'maxLength',
        'type' => 'number',
        'value' => old('maxLength', isset($object->max_length_026)? $object->max_length_026 : null),
        'maxLength' => '3',
        'rangeLength' => '1,3',
        'min' => '0',
        'fieldSize' => 2,
        'readOnly' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.pattern', 1),
        'name' => 'pattern',
        'value' => old('pattern', isset($object->pattern_026)? $object->pattern_026 : null),
        'maxLength' => '50',
        'rangeLength' => '2,50',
        'fieldSize' => 5,
        'readOnly' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.label_size'),
        'name' => 'labelSize',
        'type' => 'number',
        'value' => old('labelSize', isset($object->label_size_026)? $object->label_size_026 : null),
        'maxLength' => '1',
        'min' => '0',
        'fieldSize' => 2,
        'readOnly' => $action == 'update' || $action == 'store'? false : true,
        'inputs' =>[
            [
                'label' => trans('pulsar::pulsar.field_size'),
                'name' => 'fieldSize',
                'type' => 'number',
                'value' => old('fieldSize', isset($object->field_size_026)? $object->field_size_026 : null),
                'maxLength' => '1',
                'min' => '0', 'fieldSize' => 2,
                'readOnly' => $action == 'update' || $action == 'store'? false : true
            ]
    ]])
    <!-- /.pulsar::field.form -->
@stop