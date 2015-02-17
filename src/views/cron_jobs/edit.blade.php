@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::cron_jobs.edit -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_043, 'sizeField' => 2, 'readOnly' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_043, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true])
    @include('pulsar::common.block.block_form_select_group', ['label' => trans_choice('pulsar::pulsar.package', 1), 'name' => 'package', 'value' => $object->package_043, 'required' => true, 'objects' => $packages, 'idSelect' => 'id_012', 'nameSelect' => 'name_012'])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.cron_expression'), 'name' => 'cronExpression', 'value' => $object->cron_expression_043, 'maxLength' => '255', 'rangeLength' => '9,255', 'required' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.last_run'), 'name' => 'lastRun', 'value' => $lastRun, 'readOnly' => true, 'sizeField' => 6])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.next_run'), 'name' => 'nextRun', 'value' => $nextRun, 'readOnly' => true, 'sizeField' => 6])
    @include('pulsar::common.block.block_form_checkbox_group', ['label' => trans('pulsar::pulsar.active'), 'name' => 'active', 'value' => 1, 'isChecked' => $object->active_043])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.key'), 'name' => 'key', 'value' => $object->key_043, 'sizeField' => 2, 'required' => true])
    <!-- /pulsar::cron_jobs.edit -->
@stop