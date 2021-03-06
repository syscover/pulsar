@extends('pulsar::layouts.index', ['newTrans' => 'new2'])

@section('head')
    @parent
    <!-- pulsar::territorial_areas_2.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        { "sortable": false, "targets": [3,4]},
                        { "class": "checkbox-column", "targets": [3]},
                        { "class": "align-center", "targets": [4]}
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
    <!-- /pulsar::territorial_areas_2.index -->
@stop

@section('tHead')
    <!-- pulsar::territorial_areas_2.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-hide="phone">{{ $country->territorial_area_1_002 }}</th>
    <th data-class="expand">{{ $country->territorial_area_2_002 }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::territorial_areas_2.index -->
@stop