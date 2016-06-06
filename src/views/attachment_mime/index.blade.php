@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('head')
    @parent
    <!-- pulsar::attachment_mime.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'displayStart' : {{ $offset }},
                    'columnDefs': [
                        { 'sortable': false, 'aTargets': [4,5]},
                        { 'sClass': 'checkbox-column', 'aTargets': [4]},
                        { 'sClass': 'align-center', 'aTargets': [5]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('jsonData' . ucfirst($routeSuffix)) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /.pulsar::attachment_mime.index -->
@stop

@section('tHead')
    <!-- pulsar::attachment_mime.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.resource', 1) }}</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.resource', 1) }}</th>
    <th data-class="expand">{{ trans_choice('pulsar::pulsar.mime', 1) }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 1) }}</th>
    <!-- /.pulsar::attachment_mime.index -->
@stop