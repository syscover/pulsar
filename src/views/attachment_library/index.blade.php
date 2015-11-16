@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('script')
    @parent
    <!-- cms::library.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [6,7]},
                        { 'sClass': 'checkbox-column', 'aTargets': [6]},
                        { 'sClass': 'align-center', 'aTargets': [1,7]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . ucfirst($routeSuffix)) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- cms::evironments.index -->
@stop

@section('tHead')
    <!-- cms::library.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th data-hide="phone,tablet">{{ trans_choice('pulsar::pulsar.file', 1) }}</th>
        <th>{{ trans('pulsar::pulsar.name') }}</th>
        <th data-class="expand">{{ trans_choice('pulsar::pulsar.size', 1) }}</th>
        <th data-hide="phone,tablet">MIME</th>
        <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.type') }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /cms::library.index -->
@stop