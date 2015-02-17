@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('script')
    @parent
    <!-- pulsar::countries.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable) {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [8,9]},
                        { 'sClass': 'checkbox-column', 'aTargets': [8]},
                        { 'sClass': 'align-center', 'aTargets': [9]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . $routeSuffix) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /pulsar::countries.index -->
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
    <!-- /pulsar::countries.index -->
@stop