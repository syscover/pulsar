@extends('pulsar::layouts.index')

@section('head')
    @parent
    <!-- pulsar::lang.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        { "sortable": false, "targets": [6,7]},
                        { "class": "checkbox-column", "targets": [6]},
                        { "class": "align-center", "targets": [3,4,7]}
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
    <!-- /pulsar::lang.index -->
@stop

@section('tHead')
    <!-- pulsar::lang.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th>{{ trans_choice('pulsar::pulsar.image', 2) }}</th>
    <th data-class="expand">{{ trans_choice('pulsar::pulsar.language', 2) }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.base_language') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.active') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.sorting') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::lang.index -->
@stop