@extends('pulsar::layouts.index', ['newTrans' => 'new2', 'object' => 'cronjob'])

@section('tHead')
    <!-- pulsar::cron_jobs.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th data-hide="phone,tablet">Módulo</th>
    <th data-hide="phone,tablet">Key</th>
    <th data-hide="phone,tablet">Expresión</th>
    <th data-hide="phone,tablet">Activa</th>
    <th data-hide="phone,tablet">Última ejecución</th>
    <th data-hide="phone,tablet">Siguiente ejecución</th>
    <th data-hide="phone"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::cron_jobs.index -->
@stop