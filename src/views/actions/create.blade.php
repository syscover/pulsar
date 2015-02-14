@extends('pulsar::layouts.form', ['object' => trans_choice('pulsar::pulsar.action', 1), 'action' => 'store'])

@section('rows')
    <!-- pulsar::actions.crete -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'maxlength' => '25', 'rangelength' => '2,25', 'required' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxlength' => '50', 'rangelength' => '2,50', 'required' => true])
    <!-- /pulsar::actions.crete -->
@stop