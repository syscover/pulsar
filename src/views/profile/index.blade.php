@extends('pulsar::layouts.index', ['newTrans' => 'new'])

@section('head')
    @parent
    <!-- pulsar::profiles.index -->
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    "displayStart": {{ $offset }},
                    "columnDefs": [
                        { "sortable": false, "targets": [2,3]},
                        { "class": "checkbox-column", "targets": [2]},
                        { "class": "align-center", "targets": [3]}
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('jsonData' . ucfirst($routeSuffix)) }}",
                        "type": "POST",
                        "headers": {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    }
                }).fnSetFilteringDelay();
            }
        });

        function setAllPermissions(that)
        {
            $.msgbox('{!! trans('pulsar::pulsar.message_set_all_permissions') !!}',
            {
                type:'confirm',
                buttons: [
                    {type: 'submit', value: '{{ trans('pulsar::pulsar.accept') }}'},
                    {type: 'cancel', value: '{{ trans('pulsar::pulsar.cancel') }}'}
                ]
            },
                function(buttonPressed)
                {
                    if(buttonPressed=='{{ trans('pulsar::pulsar.accept') }}')
                    {
                        $(location).attr('href', $(that).data('all-permissions-url'));
                    }
                }
            );
        }
    </script>
    <!-- /.pulsar::profiles.index -->
@stop

@section('tHead')
    <!-- pulsar::profiles.index -->
    <tr>
        <th data-hide="phone,tablet">ID.</th>
        <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
        <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
        <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    </tr>
    <!-- /.pulsar::profiles.index -->
@stop