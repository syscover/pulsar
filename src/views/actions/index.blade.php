@extends('pulsar::layouts.index', ['newTrans' => 'new2', 'object' => 'action'])

@section('tHead')
    <!-- pulsar::actions.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::actions.index -->
@stop