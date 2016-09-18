@extends('pulsar::layouts.index')

@section('head')
    @parent
    <!-- pulsar::field.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        { "sortable": false, "targets": [8,9]},
                        { "class": "checkbox-column", "targets": [8]},
                        { "class": "align-center", "targets": [5,6,7,9]}
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
    <!-- /pulsar::field.index -->
@stop

@section('tHead')
    <!-- pulsar::field.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th>{{ trans_choice('pulsar::pulsar.field_group', 1) }}</th>
        <th data-class="expand">{{ trans_choice('pulsar::pulsar.name', 1) }}</th>
        <th data-hide="phone">{{ trans_choice('pulsar::pulsar.field_type', 1) }}</th>
        <th data-hide="phone">{{ trans_choice('pulsar::pulsar.data_type', 1) }}</th>
        <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.required') }}</th>
        <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.sorting') }}</th>
        <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.maximum_length') }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /pulsar::field.index -->
@stop