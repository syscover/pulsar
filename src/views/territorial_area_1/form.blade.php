@extends('pulsar::layouts.form', ['action' => 'store'])

@section('rows')
    <!-- pulsar::territorial_areas_1.create -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => old('id'),
        'maxLength' => '6',
        'rangeLength' => '2,6',
        'required' => true,
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.country',1), 'name' => 'country', 'value' => $country->name_002, 'fieldSize' => 2, 'readOnly' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => old('name'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::territorial_areas_1.create -->
@stop