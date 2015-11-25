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
                        { 'bSortable': false, 'aTargets': [6,7]},
                        { 'sClass': 'checkbox-column', 'aTargets': [6]},
                        { 'sClass': 'align-center', 'aTargets': [3,4,5,7]}
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
        <th>{{ trans_choice('pulsar::pulsar.family', 1) }}</th>
        <th data-class="expand">{{ trans_choice('pulsar::pulsar.name', 1) }}</th>
        <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.required') }}</th>
        <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.sorting') }}</th>
        <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.maximum_length') }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /pulsar::field.index -->
@stop