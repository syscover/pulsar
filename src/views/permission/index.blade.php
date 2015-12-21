@extends('pulsar::layouts.default')

@section('css')
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/jquery.select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/jquery.select2.custom/css/select2.css') }}">
@stop

@section('script')
    @include('pulsar::includes.js.header_list')
    @include('pulsar::includes.js.success_message')
    @include('pulsar::includes.js.datatable_config')

    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.select2.custom/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.select2/js/i18n/' . config('app.locale') . '.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery.cookie/js.cookie.js') }}"></script>
    <!-- pulsar::permissions.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable)
            {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [3]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonDataPermission', $profile->id_006) }}",
                    fnDrawCallback: function() {

                        $("[id^='re']").select2({
                            placeholder: '{{ trans('pulsar::pulsar.select_a') . ' ' . trans_choice('pulsar::pulsar.action', 1) }}'
                        });

                        $("[id^='re']").on("select2:select", function(e){
                            var element     = this;
                            var dataEvent   = e.params.data;

                            $.ajax({
                                type: "POST",
                                url: "{{ route('jsonCreatePermission', $profile->id_006) }}/" + $(this).data('resource') + "/" + dataEvent.id,
                                data: {
                                    _token : '{{ csrf_token() }}'
                                },
                                dataType: 'text',
                                success: function(data) {
                                    new PNotify({
                                        type:   'success',
                                        title:  '{{ trans('pulsar::pulsar.action_successful') }}',
                                        text:   '{!! trans('pulsar::pulsar.message_create_permission_successful', ['action' => '\' + dataEvent.text + \'', 'resource'=> '\' + $(element).data(\'nresource\') + \'']) !!}',
                                        opacity: .9,
                                        styling: 'fontawesome'
                                    });
                                },
                                error: function () {
                                    new PNotify({
                                        type:   'error',
                                        title:  '{{ trans('pulsar::pulsar.action_error') }}',
                                        text:   '{!! trans('pulsar::pulsar.message_create_permission_error', ['action'=> '\' + dataEvent.text + \'', 'resource'=> '\' + $(element).data(\'nresource\') + \'']) !!}',
                                        opacity: .9,
                                        styling: 'fontawesome'
                                    });
                                }
                            });

                        });

                        $("[id^='re']").on("select2:unselect", function(e) {
                            var element     = this;
                            var dataEvent   = e.params.data;

                            $.ajax({
                                type: "POST",
                                url: "{{ route('jsonDestroyPermission', $profile->id_006) }}/" + $(this).data('resource') + "/" + dataEvent.id,
                                data: {
                                    _token : '{{ csrf_token() }}'
                                },
                                dataType: 'text',
                                success: function(data) {
                                    new PNotify({
                                        type:   'success',
                                        title:  '{{ trans('pulsar::pulsar.action_successful') }}',
                                        text:   '{!! trans('pulsar::pulsar.message_delete_permission_successful', ['action'=> '\' + dataEvent.text + \'', 'resource'=> '\' + $(element).data(\'nresource\') + \'']) !!}',
                                        opacity: .9,
                                        styling: 'fontawesome'
                                    });
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    new PNotify({
                                        type:   'error',
                                        title:  '{{ trans('pulsar::pulsar.action_error') }}',
                                        text:   '{!! trans('pulsar::pulsar.message_delete_permission_error', ['action'=> '\' + dataEvent.text + \'', 'resource'=> '\' + $(element).data(\'nresource\') + \'']) !!}',
                                        opacity: .9,
                                        styling: 'fontawesome'
                                    });
                                }
                            });
                        });

                        //start lineas heredadas de views/pulsar/pulsar/includes/js/script_config_datatable.blade.php
                        $('input[name="nElementsDataTable"]').attr('value', this.fnGetData().length);

                        //activacion de los tooltips en la datatables
                        if ($.fn.tooltip) {
                            $('.bs-tooltip').tooltip({container: 'body'});
                        }

                        if ($.fn.uniform) {
                            $(':radio.uniform, :checkbox.uniform').uniform();
                        }

                        if ($.fn.select2) {
                            $('.dataTables_length select').select2({
                                minimumResultsForSearch: "-1"
                            });
                        }

                        // SEARCH - Add the placeholder for Search and Turn this into in-line formcontrol
                        var search_input = $(this).closest('.dataTables_wrapper').find('div[id$=_filter] input');

                        // Only apply settings once
                        if (search_input.parent().hasClass('input-group'))
                            return;

                        search_input.attr('placeholder', '{{ trans('pulsar::datatable.bSearch') }}')
                        search_input.addClass('form-control')
                        search_input.wrap('<div class="input-group"></div>');
                        search_input.parent().prepend('<span class="input-group-addon"><i class="fa fa-search"></i></span>');
                        //search_input.parent().prepend('<span class="input-group-addon"><i class="fa fa-search"></i></span>').css('width', '250px');

                        // Responsive
                        if (typeof responsiveHelper != 'undefined') {
                            responsiveHelper.respond();
                        }
                        //end lineas heredadas de views/pulsar/pulsar/includes/js/script_config_datatable.blade.php
                    }
                }).fnSetFilteringDelay();
            }
        });
    </script>
    <!-- /pulsar::permissions.index -->
@stop

@section('mainContent')
    <!-- pulsar::permissions.index -->
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="fa fa-reorder"></i> {{ trans_choice($objectTrans, 2) }}</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse"><i class="fa fa-angle-down"></i></span>
                        </div>
                    </div>
                </div>
                <div class="widget-content no-padding">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                        <tr>
                            <th data-hide="phone,tablet">ID.</th>
                            <th data-hide="phone,tablet">{{ trans_choice('pulsar::pulsar.package', 1) }}</th>
                            <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
                            <th>{{ trans_choice('pulsar::pulsar.permission', 1) }}</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <input type="hidden" name="nElementsDataTable">
                </div>
            </div>
        </div>
    </div>
    <!-- /pulsar::permissions.index -->
@stop