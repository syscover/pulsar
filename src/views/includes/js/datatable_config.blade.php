<!-- pulsar::includes.js.datatable_config -->
<script src="{{ asset('packages/syscover/pulsar/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('packages/syscover/pulsar/vendor/datatables/custom/DT_bootstrap.js') }}"></script>
<script src="{{ asset('packages/syscover/pulsar/vendor/datatables/custom/responsive/datatables.responsive.js') }}"></script>
<script>
    $(document).ready(function() {
        if ($.fn.dataTable) {
            //--------------- Data tables ------------------//
            var responsiveHelper;
            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };

            // Preserve old function from $.extend above to extend a function
            //var old_fnDrawCallback = $.fn.dataTable.defaults.fnDrawCallback

            jQuery.extend(true, $.fn.dataTable.defaults, {
                "language": {
                    "learch": "_INPUT_",
                    "lengthMenu": "{{ trans('pulsar::datatable.sLengthMenu') }}",
                    "info": "{{ trans('pulsar::datatable.sInfo01') }}",
                    "infoEmpty": "{{ trans('pulsar::datatable.sInfoEmpty') }}",
                    "emptyTable": "{{ trans('pulsar::datatable.sEmptyTable') }}",
                    "zeroRecords" : "{{ trans('pulsar::datatable.sZeroRecords') }}",
                    "infoFiltered": "{{ trans('pulsar::datatable.sInfoFiltered') }}",
                    "loadingRecords": "{{ trans('pulsar::datatable.sLoadingRecords') }}",
                    "processing": "{{ trans('pulsar::datatable.sProcessing') }}",
                    "paginate": {"sFirst": "{{ trans('pulsar::datatable.sFirst') }}",
                    "previous": "{{ trans('pulsar::datatable.sPrevious') }}",
                    "next": "{{ trans('pulsar::datatable.sNext') }}",
                    "last": "{{ trans('pulsar::datatable.sLast') }}"}
                },
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "search": {
                    "search": Cookies.get('dtSearch')
                },
                "pagingType": "bootstrap",
                "dom": "<'row'<'dataTables_header clearfix'<'col-md-6'f><'col-md-6'<'buttonsDataTables'>>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'l><'col-md-6'<'row'p><'row'i>>>>",
                // set the initial value
                "displayLength": 10,
                bAutoWidth: false,
                fnPreDrawCallback: function() {
                    // Initialize the responsive datatables helper once.
                    if (! responsiveHelper)
                        responsiveHelper = new ResponsiveDatatablesHelper(this, breakpointDefinition);
                },
                fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    responsiveHelper.createExpandIcon(nRow);
                },
                fnDrawCallback: function(oSettings) {
                    $('input[name="nElementsDataTable"]').val(this.fnPagingInfo().iTotal);

                    //activacion de los tooltips en la datatables
                    if ($.fn.tooltip)
                        $('.bs-tooltip').tooltip({container: 'body'});

                    if ($.fn.uniform)
                        $(':radio.uniform, :checkbox.uniform').uniform();

                    $('.dataTables_length select').addClass('form-control');

                    // function call parent function to send every data on json format
                    @if(isset($modal) && $modal)
                        $('.related-record').on('click', function() {

                            // we contemplate jquery function and javascript function
                            if($.isFunction(parent.{{ isset($callback)? $callback : 'relatedRecord' }} ))
                            {
                                parent.{{ isset($callback)? $callback : 'relatedRecord' }}($(this).data('json'));
                            }
                            else if($.isFunction(parent.$.{{ isset($callback)? $callback : 'relatedRecord' }} ))
                            {
                                parent.$.{{ isset($callback)? $callback : 'relatedRecord' }}($(this).data('json'));
                            }
                        });
                    @endif

                    if($.fn.magnificPopup)
                    {
                        $('.magnific-popup').magnificPopup({
                            type: 'iframe',
                            removalDelay: 300,
                            mainClass: 'mfp-fade'
                        });

                        $('.ajax-magnific-popup').magnificPopup({
                            type: 'ajax',
                            alignTop: true,
                            overflowY: 'scroll'
                        });
                    }

                    @if(! isset($areDeleteRecord))
                        $('.delete-record').bind('click', function() {
                            var that = this;
                            $.msgbox('{!! trans('pulsar::pulsar.message_delete_record') !!}',
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
                                        $(location).attr('href', $(that).data('delete-url'))
                                    }
                                }
                            )
                        });
                    @endif

                    // SEARCH - Add the placeholder for Search and Turn this into in-line formcontrol
                    var search_input = $(this).closest('.dataTables_wrapper').find('div[id$=_filter] input');

                    // Only apply settings once
                    if (search_input.parent().hasClass('input-group'))
                        return;

                    search_input.attr('placeholder', '{{ trans('pulsar::datatable.bSearch') }}');
                    search_input.addClass('form-control');
                    search_input.wrap('<div class="input-group"></div>');
                    search_input.parent().prepend('<span class="input-group-addon"><i class="fa fa-search"></i></span>');

                    // Responsive
                    if (typeof responsiveHelper != 'undefined')
                        responsiveHelper.respond();

                    @if($viewParameters['deleteSelectButton'] && $viewParameters['deleteSelectButton'] == 'true' && is_allowed($resource, 'delete'))
                        $.addDeleteButton();
                    @endif
                }
            });

            //función para controlar el dalay del filtrado de datatable
            jQuery.fn.dataTableExt.oApi.fnSetFilteringDelay = function(oSettings, iDelay) {
                var _that = this;

                if (iDelay === undefined)
                    iDelay = 300;

                this.each(function(i) {
                    $.fn.dataTableExt.iApiIndex = i;
                    var oTimerId = null,
                        sPreviousSearch = null,
                        anControl = $('input', _that.fnSettings().aanFeatures.f);

                    //
                    anControl.unbind('keyup').bind('keyup', function() {

                        if (sPreviousSearch === null || sPreviousSearch != anControl.val())
                        {
                            window.clearTimeout(oTimerId);
                            sPreviousSearch = anControl.val();
                            oTimerId = window.setTimeout(function() {
                                $.fn.dataTableExt.iApiIndex = i;
                                _that.fnFilter(anControl.val());

                                Cookies.set('dtSearch', anControl.val(), { expires: 7, path: '/{{ isset($path)? $path : null }}' })

                            }, iDelay)
                        }
                    });
                    return this
                });
                return this
            }
        }
    });

    $.addDeleteButton = function(){
        // button to delete multiple records
        $("div.buttonsDataTables").html('<a class="btn" href="javascript:deleteRecords()"><i class="fa fa-trash"></i> {{ trans('pulsar::pulsar.delete') }}</a>')
    };

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
                    $('#formView').submit()
                }
            });
        }
        else
        {
            $.msgbox('{{ trans('pulsar::pulsar.message_record_no_select') }}',
            {
                type:'info',
                buttons: [
                    {type: 'cancel', value: '{{ trans('pulsar::pulsar.accept') }}'}
                ]
            });
        }
    }
</script>
<!-- /pulsar::includes.js.datatable_config -->