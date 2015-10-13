@extends('pulsar::layouts.form', ['action' => 'store'])

@section('rows')
    <!-- hotels::attachment_family.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'fieldSize' => 2, 'readOnly' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.width'), 'type' => 'number', 'name' => 'width', 'value' => Input::old('width'), 'fieldSize' => 4, 'inputs' => [
        ['label' => trans('pulsar::pulsar.height'), 'type' => 'number', 'name' => 'height', 'value' => Input::old('height'), 'fieldSize' => 4]
    ]])
    <!-- /hotels::attachment_family.create -->
@stop