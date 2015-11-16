@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('script')
    @parent
    <!-- pulsar::langs.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [6,7]},
                        { 'sClass': 'checkbox-column', 'aTargets': [6]},
                        { 'sClass': 'align-center', 'aTargets': [3,4,7]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . ucfirst($routeSuffix)) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- pulsar::langs.index -->
@stop

@section('tHead')
    <!-- pulsar::langs.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th>{{ trans_choice('pulsar::pulsar.image', 2) }}</th>
    <th data-class="expand">{{ trans_choice('pulsar::pulsar.language', 2) }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.base_language') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.active') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.sorting') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::langs.index -->
@stop