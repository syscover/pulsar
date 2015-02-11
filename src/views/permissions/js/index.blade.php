<script type="text/javascript">    
    $(document).ready(function() {
        @include('pulsar::pulsar/pulsar/common/js/script_success_mensaje')
        @include('pulsar::pulsar.pulsar.common.js.script_config_datatable')
        if ($.fn.dataTable) {
            $('.datatable-pulsar').dataTable({
                'iDisplayStart' : {{ $offset }},
                'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [3]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ url(config('pulsar.appName')) }}/pulsar/permisos/json/data/perfil/<?php echo $perfil->id_006; ?>",
                fnDrawCallback: function() {
                    $("[id^='re']").select2({
                        placeholder: "Seleccione un permiso",
                        formatNoMatches: function () { return "Resultados no encontrados"; },
                    });
                    
                    $("[id^='re']").on("change", function(e){
                        var element = this; 
                        if(e.added){   
                            $.ajax({
                                type: "POST",
                                url: "<?php echo url('/');?>/<?php echo config('pulsar.appName'); ?>/pulsar/permisos/json/create/<?php echo $perfil->id_006; ?>/"+$(this).attr('data-idrecurso')+"/"+e.added.id,
                                dataType: 'text',
                                success: function(data) {
                                    $.pnotify({
                                        type:   'success',
                                        title:  '<?php echo trans('pulsar::pulsar.accion_realizada');?>',
                                        text:   '<?php echo trans('pulsar::pulsar.crear_permiso',array('accion'=> '\'+e.added.text+\'', 'recurso'=> '\'+$(element).attr(\'data-nrecurso\')+\''));?>',
                                        icon:   'picon icon16 iconic-icon-check-alt white',
                                        opacity: 0.95,
                                        history: false,
                                        sticker: false
                                    });
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    $.pnotify({
                                        type:   'error',
                                        title:  '<?php echo trans('pulsar::pulsar.accion_no_realizada');?>',
                                        text:   '<?php echo trans('pulsar::pulsar.crear_permiso_error',array('accion'=> '\'+e.added.text+\'', 'recurso'=> '\'+$(element).attr("data-nrecurso")+\''));?>',
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
                                url: "<?php echo url('/');?>/<?php echo config('pulsar.appName'); ?>/pulsar/permisos/json/destroy/<?php echo $perfil->id_006; ?>/"+$(this).attr('data-idrecurso')+"/"+e.removed.id,
                                dataType: 'text',
                                success: function(data) {
                                    $.pnotify({
                                        type:   'success',
                                        title:  '<?php echo trans('pulsar::pulsar.accion_realizada');?>',
                                        text:   '<?php echo trans('pulsar::pulsar.borrar_permiso',array('accion'=> '\'+e.removed.text+\'', 'recurso'=> '\'+$(element).attr(\'data-nrecurso\')+\''));?>',
                                        icon:   'picon icon16 iconic-icon-check-alt white',
                                        opacity: 0.95,
                                        history: false,
                                        sticker: false
                                    });
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    $.pnotify({
                                        type:   'error',
                                        title:  '<?php echo trans('pulsar::pulsar.accion_no_realizada');?>',
                                        text:   '<?php echo trans('pulsar::pulsar.borrar_permiso_error',array('accion'=> '\'+e.removed.text+\'', 'recurso'=> '\'+$(element).attr(\'data-nrecurso\')+\''));?>',
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
                    $('input[name="nElementsDataTable"]').attr('value',this.fnGetData().length);
               
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

                    search_input.attr('placeholder', '<?php echo trans('pulsar::datatable.bSearch'); ?>')
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
            @include('pulsar::pulsar.pulsar.common.js.script_button_delete')
        }
        
        
    });
</script>