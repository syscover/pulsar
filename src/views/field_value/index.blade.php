@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('head')
    @parent
    <!-- pulsar::field_value.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'displayStart' : {{ $offset }},
                    'columnDefs': [
                        { 'sortable': false, 'targets': [5,6]},
                        { 'class': 'checkbox-column', 'targets': [5]},
                        { 'class': 'align-center', 'targets': [4,6]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('jsonData' . ucfirst($routeSuffix), ['field' => $field, 'lang' => session('baseLang')->id_001]) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /.pulsar::field_value.index -->
@stop

@section('tHead')
    <!-- pulsar::field_value.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th>{{ trans_choice('pulsar::pulsar.field', 1) }}</th>
        <th>{{ trans_choice('pulsar::pulsar.language', 1) }}</th>
        <th data-class="expand">{{ trans_choice('pulsar::pulsar.name', 1) }}</th>
        <th>{{ trans('pulsar::pulsar.featured') }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /.pulsar::field_value.index -->
@stop