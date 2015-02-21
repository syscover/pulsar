@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('script')
    @parent
    <!-- pulsar::users.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [6,7]},
                        { 'sClass': 'checkbox-column', 'aTargets': [6]},
                        { 'sClass': 'align-center', 'aTargets': [5,7]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . $routeSuffix) }}"
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /pulsar::users.index -->
@stop

@section('tHead')
    <!-- pulsar::users.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.surname') }}</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.email') }}</th>
    <th data-class="expand">{{ trans_choice('pulsar::pulsar.profile', 1) }}</th>
    <th data-class="expand">{{ trans('pulsar::pulsar.access') }}</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::users.index -->
@stop