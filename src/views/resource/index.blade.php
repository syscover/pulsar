@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('head')
    @parent
    <!-- pulsar::resources.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'displayStart' : {{ $offset }},
                    'columnDefs': [
                        { 'sortable': false, 'aTargets': [3,4]},
                        { 'sClass': 'checkbox-column', 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [4]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('jsonData' . ucfirst($routeSuffix)) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /.pulsar::resources.index -->
@stop

@section('tHead')
    <!-- pulsar::resources.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.package', 1) }}</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 1) }}</th>
    <!-- /.pulsar::resources.index -->
@stop