@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('script')
    @parent
    <!-- pulsar::field.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [4,5]},
                        { 'sClass': 'checkbox-column', 'aTargets': [4]},
                        { 'sClass': 'align-center', 'aTargets': [5]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . ucfirst($routeSuffix), [session('baseLang')]) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- pulsar::field.index -->
@stop

@section('tHead')
    <!-- pulsar::field.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th data-hide="phone,tablet">{{ trans_choice('pulsar::pulsar.language', 1) }}</th>
        <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
        <th>{{ trans_choice('pulsar::pulsar.label', 1) }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /pulsar::field.index -->
@stop