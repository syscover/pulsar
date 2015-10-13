@extends('pulsar::layouts.form', ['action' => 'store'])

@section('rows')
    <!-- pulsar::attachment_family.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'fieldSize' => 2, 'readOnly' => true])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.resource', 1), 'id' => 'resource', 'name' => 'resource', 'value' => Input::old('resource'), 'objects' => $resources, 'idSelect' => 'id_007', 'nameSelect' => 'name_007', 'class' => 'form-control select2', 'fieldSize' => 5, 'required' => true, 'data' => ['language' => config('app.locale'), 'width' => '100%', 'error-placement' => 'select2-resource-outer-container']])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.width'), 'type' => 'number', 'name' => 'width', 'value' => Input::old('width'), 'fieldSize' => 4, 'inputs' => [
        ['label' => trans('pulsar::pulsar.height'), 'type' => 'number', 'name' => 'height', 'value' => Input::old('height'), 'fieldSize' => 4]
    ]])
    <!-- /pulsar::attachment_family.create -->
@stop