@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('head')
    @parent
    <!-- pulsar::attachment_library.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'displayStart' : {{ $offset }},
                    'columnDefs': [
                        { 'sortable': false, 'targets': [6,7]},
                        { 'class': 'checkbox-column', 'targets': [6]},
                        { 'class': 'align-center', 'targets': [1,7]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('jsonData' . ucfirst($routeSuffix)) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /.pulsar::attachment_library.index -->
@stop

@section('tHead')
    <!-- pulsar::attachment_library.index -->
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
    <!-- /.pulsar::attachment_library.index -->
@stop