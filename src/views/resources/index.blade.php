@extends('pulsar::layouts.index', ['newTrans' => 'new', 'object' => 'resource'])

@section('tHead')
    <!-- pulsar::resources.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.package', 1) }}</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 1) }}</th>
    <!-- /pulsar::resources.index -->
@stop