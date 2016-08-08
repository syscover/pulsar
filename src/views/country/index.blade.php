@extends('pulsar::layouts.index')

@section('head')
    @parent
    <!-- pulsar::countries.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        { "sortable": false, "targets": [8,9]},
                        { "class": "checkbox-column", "targets": [8]},
                        { "class": "align-center", "targets": [9]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('jsonData' . ucfirst($routeSuffix), [base_lang()->id_001]) }}",
                        "type": "POST",
                        "headers": {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    }
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /.pulsar::countries.index -->
@stop

@section('tHead')
    <!-- pulsar::countries.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-hide="phone,tablet">{{ trans_choice('pulsar::pulsar.language', 1) }}</th>
    <th data-class="expand">{{ trans_choice('pulsar::pulsar.country', 1) }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.sorting') }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.prefix') }}</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.territorial_area', 1) }} 1</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.territorial_area', 1) }} 2</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.territorial_area', 1) }} 3</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /.pulsar::countries.index -->
@stop