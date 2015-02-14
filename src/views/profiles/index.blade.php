@extends('pulsar::layouts.index', ['newTrans' => 'new', 'object' => 'profile'])

@section('tHead')
    <!-- pulsar::profiles.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /pulsar::profiles.index -->
@stop