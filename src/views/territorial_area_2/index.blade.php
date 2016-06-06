@extends('pulsar::layouts.index', ['newTrans' => 'new2'])

@section('head')
    @parent
    <!-- pulsar::territorial_areas_2.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'displayStart' : {{ $offset }},
                    'columnDefs': [
                        { 'sortable': false, 'aTargets': [3,4]},
                        { 'sClass': 'checkbox-column', 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [4]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('jsonData' . ucfirst($routeSuffix), ['country' => $country->id_002, 'parentOffset' => $parentOffset]) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /.pulsar::territorial_areas_2.index -->
@stop

@section('tHead')
    <!-- pulsar::territorial_areas_2.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-hide="phone">{{ $country->territorial_area_1_002 }}</th>
    <th data-class="expand">{{ $country->territorial_area_2_002 }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /.pulsar::territorial_areas_2.index -->
@stop