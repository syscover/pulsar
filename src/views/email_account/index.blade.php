@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('script')
    @parent
    <!-- pulsar::email_accounts.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [3,4]},
                        { 'sClass': 'checkbox-column', 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [3,4]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . $routeSuffix) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /pulsar::email_accounts.index -->
@stop

@section('headButtons')

@stop

@section('tHead')
    <!-- pulsar::email_accounts.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th data-hide="expand">{{ trans('pulsar::pulsar.name') }}</th>
        <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.email') }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /pulsar::email_accounts.index -->
@stop