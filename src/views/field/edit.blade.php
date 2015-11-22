@extends('pulsar::layouts.form', ['action' => 'update'])

@section('script')
    @parent
    @include('pulsar::includes.js.delete_translation_record')
@stop

@section('rows')
    <!-- pulsar::field.edit -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id',  'value' => $object->id_026, 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.family', 1), 'name' => 'family', 'value' => $object->family_026, 'required' => true, 'objects' => $families, 'idSelect' => 'id_025', 'nameSelect' => 'name_025', 'class' => 'form-control select2', 'fieldSize' => 5 , 'data' => ['language' => config('app.locale'), 'width' => '100%', 'error-placement' => 'select2-section-outer-container']])
    @include('pulsar::includes.html.form_image_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'name' => 'lang', 'nameImage' => $lang->name_001, 'value' => $lang->id_001, 'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.label', 1), 'name' => 'label', 'value' => $object->label_026, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_026, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    <!-- /pulsar::field.edit -->
@stop