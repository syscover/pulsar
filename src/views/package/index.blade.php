@extends('pulsar::layouts.index', ['newTrans' => 'new2'])

@section('head')
    @parent
    <!-- pulsar::packages.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        {"sortable": false, "targets": [5,6]},
                        {"class": "checkbox-column", "targets": [5]},
                        {"class": "align-center", "targets": [3,6]}
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
    <!-- /.pulsar::packages.index -->
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
    <!-- /.pulsar::packages.index -->
@stop