@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('script')
    @parent
    <!-- cms::attachment_family.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [2,3]},
                        { 'sClass': 'checkbox-column', 'aTargets': [2]},
                        { 'sClass': 'align-center', 'aTargets': [3]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . $routeSuffix) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- cms::attachment_family.index -->
@stop

@section('tHead')
    <!-- cms::attachment_family.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /cms::attachment_family.index -->
@stop