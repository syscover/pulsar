@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::cron_jobs.form -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => isset($object->id_011)? $object->id_011 : null,
        'fieldSize' => 2,
        'readOnly' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object->name_011)? $object->name_011 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_select_group', [
        'fieldSize' => 5,
        'label' => trans_choice('pulsar::pulsar.package', 1),
        'name' => 'package',
        'value' => (int) old('package', isset($object->package_id_011)? $object->package_id_011 : null),
        'required' => true,
        'objects' => $packages,
        'idSelect' => 'id_012',
        'nameSelect' => 'name_012'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.cron_expression'),
        'name' => 'cronExpression',
        'value' => old('cronExpression', isset($object->cron_expression_011)? $object->cron_expression_011 : null),
        'maxLength' => '255',
        'rangeLength' => '9,255',
        'required' => true
    ])
    @if($action == 'update')
        @include('pulsar::includes.html.form_text_group', [
            'label' => trans('pulsar::pulsar.last_run'),
            'name' => 'lastRun',
            'value' => $lastRun,
            'readOnly' => true,
            'fieldSize' => 6
        ])
        @include('pulsar::includes.html.form_text_group', [
            'label' => trans('pulsar::pulsar.next_run'),
            'name' => 'nextRun',
            'value' => $nextRun,
            'readOnly' => true,
            'fieldSize' => 6
        ])
    @endif
    @include('pulsar::includes.html.form_checkbox_group', [
        'label' => trans('pulsar::pulsar.active'),
        'name' => 'active',
        'value' => 1,
        'checked' => old('active', isset($object->active_011)? $object->active_011 : false)
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.key'),
        'name' => 'key',
        'value' => old('key', isset($object->key_011)? $object->key_011 : null),
        'maxLength' => '50',
        'rangeLength' => '2,50',
        'required' => true
    ])
    <!-- /.pulsar::cron_jobs.form -->
@stop