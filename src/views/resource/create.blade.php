@extends('pulsar::layouts.form', ['action' => 'store'])

@section('rows')
    <!-- pulsar::resources.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => Input::old('id'), 'maxLength' => '30', 'rangeLength' => '2,30', 'required' => true, 'fieldSize' => 5])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.package', 1), 'name' => 'package', 'value' => Input::old('package'), 'required' => true, 'objects' => $packages, 'idSelect' => 'id_012', 'nameSelect' => 'name_012', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::resources.create -->
@stop