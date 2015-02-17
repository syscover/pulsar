@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
    @include('pulsar::common.js.script_success_message')
    @include('pulsar::common.js.script_datatable_config')
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
                            placeholder: '{{ trans('pulsar::pulsar.choose_a') . ' ' . trans_choice('pulsar::pulsar.action', 1) }}'
                        });

                        $("[id^='re']").on("change", function(e){
                            var element = this;

                            if(e.added){
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('jsonCreatePermission', $profile->id_006) }}/" + $(this).data('resource') + "/" + e.added.id,
                                    data: {
                                        _token : '{{ csrf_token() }}'
                                    },
                                    dataType: 'text',
                                    success: function(data) {
                                        $.pnotify({
                                            type:   'success',
                                            title:  '{{ trans('pulsar::pulsar.action_successful') }}',
                                            text:   '{!! trans('pulsar::pulsar.message_create_permission_successful', ['action' => '\' + e.added.text + \'', 'resource'=> '\' + $(element).data(\'nresource\') + \'']) !!}',
                                            icon:   'picon icon16 iconic-icon-check-alt white',
                                            opacity: 0.95,
                                            history: false,
                                            sticker: false
                                        });
                                    },
                                    error: function () {
                                        $.pnotify({
                                            type:   'error',
                                            title:  '{{ trans('pulsar::pulsar.action_error') }}',
                                            text:   '{!! trans('pulsar::pulsar.message_create_permission_error', ['action'=> '\' + e.added.text + \'', 'resource'=> '\' + $(element).data(\'nresource\') + \'']) !!}',
                                            icon:   'picon icon16 iconic-icon-check-alt white',
                                            opacity: 0.95,
                                            history: false,
                                            sticker: false
                                        });
                                    }
                                });
                            }

                            if(e.removed){
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('jsonDestroyPermission', $profile->id_006) }}/" + $(this).data('resource') + "/" + e.removed.id,
                                    data: {
                                        _token : '{{ csrf_token() }}'
                                    },
                                    dataType: 'text',
                                    success: function(data) {
                                        $.pnotify({
                                            type:   'success',
                                            title:  '{{ trans('pulsar::pulsar.action_successful') }}',
                                            text:   '{!! trans('pulsar::pulsar.message_delete_permission_successful', ['action'=> '\'+e.removed.text+\'', 'resource'=> '\'+$(element).data(\'nresource\')+\'']) !!}',
                                            icon:   'picon icon16 iconic-icon-check-alt white',
                                            opacity: 0.95,
                                            history: false,
                                            sticker: false
                                        });
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                        $.pnotify({
                                            type:   'error',
                                            title:  '{{ trans('pulsar::pulsar.action_error') }}',
                                            text:   '{!! trans('pulsar::pulsar.message_delete_permission_error', ['action'=> '\'+e.removed.text+\'', 'resource'=> '\'+$(element).data(\'nresource\')+\'']) !!}',
                                            icon:   'picon icon16 iconic-icon-check-alt white',
                                            opacity: 0.95,
                                            history: false,
                                            sticker: false
                                        });
                                    }
                                });
                            }
                        });

                        //start lineas heredadas de views/pulsar/pulsar/common/js/script_config_datatable.blade.php
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
                        search_input.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>');
                        //search_input.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>').css('width', '250px');

                        // Responsive
                        if (typeof responsiveHelper != 'undefined') {
                            responsiveHelper.respond();
                        }
                        //end lineas heredadas de views/pulsar/pulsar/common/js/script_config_datatable.blade.php
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
                    <h4><i class="icon-reorder"></i> {{ trans_choice('pulsar::pulsar.' . $objectTrans, 2) }}</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
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