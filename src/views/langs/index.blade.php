@extends('pulsar::layouts.index', ['newTrans' => 'new', 'object' => 'language'])

@section('tHead')
    <!-- pulsar::langs.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th>{{ trans_choice('pulsar::pulsar.image', 2) }}</th>
    <th data-class="expand">{{ trans_choice('pulsar::pulsar.language', 2) }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.base_language') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.active') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.sorting') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::actlangsions.index -->
@stop