@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::cron_jobs.edit -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_011, 'fieldSize' => 2, 'readOnly' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_011, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.package', 1), 'name' => 'package', 'value' => $object->package_011, 'required' => true, 'objects' => $packages, 'idSelect' => 'id_012', 'nameSelect' => 'name_012', 'class' => 'form-control', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.cron_expression'), 'name' => 'cronExpression', 'value' => $object->cron_expression_011, 'maxLength' => '255', 'rangeLength' => '9,255', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.last_run'), 'name' => 'lastRun', 'value' => $lastRun, 'readOnly' => true, 'fieldSize' => 6])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.next_run'), 'name' => 'nextRun', 'value' => $nextRun, 'readOnly' => true, 'fieldSize' => 6])
    @include('pulsar::includes.html.form_checkbox_group', ['label' => trans('pulsar::pulsar.active'), 'name' => 'active', 'value' => 1, 'checked' => $object->active_011])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.key'), 'name' => 'key', 'value' => $object->key_011, 'fieldSize' => 2, 'required' => true])
    <!-- /pulsar::cron_jobs.edit -->
@stop