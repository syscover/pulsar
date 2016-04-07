@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::cron_jobs.form -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'fieldSize' => 2,
        'readOnly' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name'),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'required' => true
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans_choice('pulsar::pulsar.package', 1),
        'name' => 'package',
        'value' => old('package'),
        'required' => true,
        'objects' => $packages,
        'idSelect' => 'id_012',
        'nameSelect' => 'name_012',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.cron_expression'),
        'name' => 'cronExpression',
        'value' => old('cronExpresion'),
        'maxLength' => '255',
        'rangeLength' => '9,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_checkbox_group', [
        'label' => trans('pulsar::pulsar.active'),
        'name' => 'active',
        'value' => 1,
        'checked' => old('active')
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.key'),
        'name' => 'key',
        'value' => old('key'),
        'maxLength' => '50',
        'rangeLength' => '2,50',
        'required' => true
    ])
    <!-- /.pulsar::cron_jobs.form -->
@stop