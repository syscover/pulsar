@extends('pulsar::layouts.index', ['newTrans' => 'new2'])

@section('script')
    @parent
    <!-- pulsar::cron_jobs.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [8,9]},
                        { 'sClass': 'checkbox-column', 'aTargets': [8]},
                        { 'sClass': 'align-center', 'aTargets': [5,9]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . ucfirst($routeSuffix)) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- pulsar::cron_jobs.index -->
@stop

@section('tHead')
    <!-- pulsar::cron_jobs.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th data-hide="phone,tablet">{{ trans_choice('pulsar::pulsar.package', 1) }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.key') }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.cron_expression') }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.active') }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.last_run') }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.next_run') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::cron_jobs.index -->
@stop