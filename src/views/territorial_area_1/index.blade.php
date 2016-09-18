@extends('pulsar::layouts.index', [
    'newTrans' => 'new2'
])

@section('head')
    @parent
    <!-- pulsar::territorial_areas_1.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        { "sortable": false, "targets": [2,3]},
                        { "class": "checkbox-column", "targets": [2]},
                        { "class": "align-center", "targets": [3]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('jsonData' . ucfirst($routeSuffix), ['country' => $country->id_002, 'parentOffset' => $parentOffset]) }}",
                        "type": "POST",
                        "headers": {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    }
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