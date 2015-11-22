@extends('pulsar::layouts.form', ['action' => 'store'])

@section('rows')
    <!-- pulsar::field.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id',  'value' => Input::old('name', isset($object->id_026)? $object->id_026 : null), 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.family', 1), 'name' => 'family', 'value' => Input::old('famlily'), 'required' => true, 'objects' => $families, 'idSelect' => 'id_025', 'nameSelect' => 'name_025', 'class' => 'form-control select2', 'data' => ['language' => config('app.locale'), 'width' => '100%', 'error-placement' => 'select2-section-outer-container', 'disabled' => isset($object->id_025)? true : null]])
    @include('pulsar::includes.html.form_image_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'name' => 'lang', 'nameImage' => $lang->name_001, 'value' => $lang->id_001, 'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.label', 1), 'name' => 'label', 'value' => Input::old('name', isset($object->name_026)? $object->name_026 : null), 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name', isset($object->name_152)? $object->name_152 : null), 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    <!-- /pulsar::field.create -->
@stop