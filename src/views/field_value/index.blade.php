@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('script')
    @parent
    <!-- pulsar::field_value.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [3,4]},
                        { 'sClass': 'checkbox-column', 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [4]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . ucfirst($routeSuffix), ['field' => $field, 'lang' => session('baseLang')]) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- pulsar::field_value.index -->
@stop

@section('tHead')
    <!-- pulsar::field_value.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th>{{ trans_choice('pulsar::pulsar.family', 1) }}</th>
        <th data-class="expand">{{ trans_choice('pulsar::pulsar.name', 1) }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /pulsar::field_value.index -->
@stop