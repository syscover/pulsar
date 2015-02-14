<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.dataTable) {
            //--------------- Data tables ------------------//
            var responsiveHelper;
            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };

            // Preserve old function from $.extend above
            // to extend a function
            //var old_fnDrawCallback = $.fn.dataTable.defaults.fnDrawCallback;

            jQuery.extend(true, $.fn.dataTable.defaults, {
                "oLanguage": {
                    "sSearch": "_INPUT_",
                    "sLengthMenu": "{{ trans('pulsar::datatable.sLengthMenu') }}",
                    "sInfo": "{{ trans('pulsar::datatable.sInfo01') }}",
                    "sInfoEmpty": "{{ trans('pulsar::datatable.sInfoEmpty') }}",
                    "sEmptyTable": "{{ trans('pulsar::datatable.sEmptyTable') }}",
                    "sZeroRecords" : "{{ trans('pulsar::datatable.sZeroRecords') }}",
                    "sInfoFiltered": "{{ trans('pulsar::datatable.sInfoFiltered') }}",
                    "sLoadingRecords": "{{ trans('pulsar::datatable.sLoadingRecords') }}",
                    "sProcessing": "{{ trans('pulsar::datatable.sProcessing') }}",
                    "oPaginate": {"sFirst": "{{ trans('pulsar::datatable.sFirst') }}", "sPrevious": "{{ trans('pulsar::datatable.sPrevious') }}", "sNext": "{{ trans('pulsar::datatable.sNext') }}", "sLast": "{{ trans('pulsar::datatable.sLast') }}"}
                },
                "sPaginationType": "bootstrap",
                "sDom": "<'row'<'dataTables_header clearfix'<'col-md-6'f><'col-md-6'<'buttonsDataTables'>>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'l><'col-md-6'<'row'p><'row'i>>>>",
                // set the initial value
                "iDisplayLength": 10,
                bAutoWidth: false,
                fnPreDrawCallback: function() {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper) {
                        responsiveHelper = new ResponsiveDatatablesHelper(this, breakpointDefinition);
                    }
                },
                fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    responsiveHelper.createExpandIcon(nRow);
                },
                fnDrawCallback: function(oSettings) {
                    $('input[name="nElementsDataTable"]').attr('value', this.fnPagingInfo().iTotal);

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

                    @if(!isset($hideDeleteDataTable))
                    $('.delete-record').bind('click', function() {
                        var idRecord = $(this).data('id')
                        var url = "{{ route('destroy' . $routeSuffix) }}/" + idRecord;
                        $.msgbox('{!! trans('pulsar::pulsar.message_delete_record') !!}',
                        {
                            type:'confirm',
                            buttons: [
                                {type: 'submit', value: '{{ trans('pulsar::pulsar.accept') }}'},
                                {type: 'cancel', value: '{{ trans('pulsar::pulsar.cancel') }}'}
                            ]
                        },
                                function(buttonPressed) {
                                    if(buttonPressed=='{{ trans('pulsar::pulsar.accept') }}')
                                    {
                                        $(location).attr('href',url);
                                    }
                                }
                        );
                    });
                    @endif

                    // SEARCH - Add the placeholder for Search and Turn this into in-line formcontrol
                    var search_input = $(this).closest('.dataTables_wrapper').find('div[id$=_filter] input');

                    // Only apply settings once
                    if (search_input.parent().hasClass('input-group'))
                        return;

                    search_input.attr('placeholder', '{{ trans('pulsar::datatable.bSearch') }}')
                    search_input.addClass('form-control')
                    search_input.wrap('<div class="input-group"></div>');
                    search_input.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>');
                    // search_input.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>').css('width', '250px');

                    // Responsive
                    if (typeof responsiveHelper != 'undefined') {
                        responsiveHelper.respond();
                    }

                    // button to delete multiple records
                    $("div.buttonsDataTables").html('<a class="btn" href="javascript:deleteRecords()">{{ trans('pulsar::pulsar.delete') }}</a>');
                }
            });

            //Número de elementos por página
            $.fn.dataTable.defaults.aLengthMenu = [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]];

            //función para controlar el dalay del filtrado de datatable
            jQuery.fn.dataTableExt.oApi.fnSetFilteringDelay = function(oSettings, iDelay) {
                var _that = this;

                if (iDelay === undefined) {
                    iDelay = 300;
                }

                this.each(function(i) {
                    $.fn.dataTableExt.iApiIndex = i;
                    var
                            $this = this,
                            oTimerId = null,
                            sPreviousSearch = null,
                            anControl = $('input', _that.fnSettings().aanFeatures.f);

                    anControl.unbind('keyup').bind('keyup', function() {
                        var $$this = $this;

                        if (sPreviousSearch === null || sPreviousSearch != anControl.val()) {
                            window.clearTimeout(oTimerId);
                            sPreviousSearch = anControl.val();
                            oTimerId = window.setTimeout(function() {
                                $.fn.dataTableExt.iApiIndex = i;
                                _that.fnFilter(anControl.val());
                            }, iDelay);
                        }
                    });

                    return this;
                });
                return this;
            };
        }
    });

    // function to delete multiple records from datatable
    function deleteRecords()
    {
        if($('input[name^="element"]').is(':checked'))
        {
            $.msgbox('{{ trans('pulsar::pulsar.message_delete_records') }}',
            {
                type:'confirm',
                buttons: [
                    {type: 'submit', value: '{{ trans('pulsar::pulsar.accept') }}'},
                    {type: 'cancel', value: '{{ trans('pulsar::pulsar.cancel') }}'}
                ]
            },
            function(buttonPressed) {
                if(buttonPressed == '{{ trans('pulsar::pulsar.accept') }}'){
                    $('#formView').submit();
                }
            });
        }
        else
        {
            $.msgbox('{{ trans('pulsar::pulsar.message_record_no_select') }}',
            {
                type:'info',
                buttons: [
                    {type: 'cancel', value: {{ trans('pulsar::pulsar.accept') }}}
                ]
            });
        }
    }
</script>