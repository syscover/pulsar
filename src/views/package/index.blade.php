@extends('pulsar::layouts.index', ['newTrans' => 'new2'])

@section('script')
    @parent
    <!-- pulsar::packages.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        {'bSortable': false, 'aTargets': [3,4]},
                        {'sClass': 'checkbox-column', 'aTargets': [3]},
                        {'sClass': 'align-center', 'aTargets': [2,4]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . $routeSuffix) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- pulsar::langs.index -->
@stop

@section('tHead')
    <!-- pulsar::packages.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th data-hide="phone">{{ trans('pulsar::pulsar.active') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::packages.index -->
@stop