@extends('pulsar::layouts.index', ['newTrans' => 'new2'])

@section('head')
    @parent
    <!-- pulsar::cron_job.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        { "sortable": false, "targets": [8,9]},
                        { "class": "checkbox-column", "targets": [8]},
                        { "class": "align-center", "targets": [5,9]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('jsonData' . ucfirst($routeSuffix)) }}",
                        "type": "POST",
                        "headers": {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    }
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /pulsar::cron_job.index -->
@stop

@section('tHead')
    <!-- pulsar::cron_job.index -->
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
    <!-- /pulsar::cron_job.index -->
@stop