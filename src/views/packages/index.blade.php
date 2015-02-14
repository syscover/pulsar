@extends('pulsar::layouts.index', ['newTrans' => 'new2', 'object' => 'package'])

@section('tHead')
    <!-- pulsar::packages.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.active') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::packages.index -->
@stop