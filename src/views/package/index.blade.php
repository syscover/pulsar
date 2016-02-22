@extends('pulsar::layouts.index', ['newTrans' => 'new2'])

@section('head')
    @parent
    <!-- pulsar::packages.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        {'bSortable': false, 'aTargets': [5,6]},
                        {'sClass': 'checkbox-column', 'aTargets': [5]},
                        {'sClass': 'align-center', 'aTargets': [3,6]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . ucfirst($routeSuffix)) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- pulsar::packages.index -->
@stop

@section('tHead')
    <!-- pulsar::packages.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th>{{ trans_choice('pulsar::pulsar.folder', 1) }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.active') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.sorting') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::packages.index -->
@stop