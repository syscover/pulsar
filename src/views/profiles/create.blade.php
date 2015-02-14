@extends('pulsar::layouts.form', ['object' => trans_choice('pulsar::pulsar.profile', 1), 'action' => 'store'])

@section('rows')
    <!-- pulsar::profiles.create -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'readonly' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxlength' => '50', 'rangelength' => '2,50', 'required' => true])
    <!-- /pulsar::profiles.create -->
@stop