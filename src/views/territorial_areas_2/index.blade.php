@extends('pulsar::layouts.index', ['newTrans' => 'new2', 'customTrans' => $country->territorial_area_2_002])

@section('script')
    @parent
            <!-- pulsar::territorial_areas_1.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [3,4]},
                        { 'sClass': 'checkbox-column', 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [4]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . $routeSuffix, [$country->id_002]) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /pulsar::territorial_areas_1.index -->
@stop

@section('tHead')
    <!-- pulsar::actions.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th data-hide="phone">{{ $country->territorial_area_1_002 }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::actions.index -->
@stop