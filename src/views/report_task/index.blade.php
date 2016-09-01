@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('head')
    @parent
    <!-- pulsar::reports.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        {"sortable": false, "targets": [3,4]},
                        {"class": "checkbox-column", "targets": [3]},
                        {"class": "align-center", "targets": [4]}
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
    <!-- /pulsar::reports.index -->
@stop

@section('tHead')
    <!-- pulsar::reports.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.email') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.subject') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::reports.index -->
@stop