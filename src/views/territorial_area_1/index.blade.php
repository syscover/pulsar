@extends('pulsar::layouts.index', ['newTrans' => 'new2'])

@section('head')
    @parent
    <!-- pulsar::territorial_areas_1.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [2,3]},
                        { 'sClass': 'checkbox-column', 'aTargets': [2]},
                        { 'sClass': 'align-center', 'aTargets': [3]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . ucfirst($routeSuffix), ['country' => $country->id_002, 'parentOffset' => $parentOffset]) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /pulsar::territorial_areas_1.index -->
@stop

@section('tHead')
    <!-- pulsar::actions.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ $country->territorial_area_1_002 }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::actions.index -->
@stop