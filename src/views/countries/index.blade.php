@extends('pulsar::layouts.index', ['newTrans' => 'new'])


@section('mainContentXX')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ url(config('pulsar.appName')) }}/pulsar/paises/create/{{ $offset }}/<?php //echo $idiomaBase->id_001; ?>"><i class="entypo-icon-globe"></i> Nuevo país</a>
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Países</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <form id="formView" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/paises/destroy/select/elements">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                            <tr>
                                <th data-hide="phone,tablet">ID.</th>
                                <th data-hide="phone,tablet">Idioma</th>
                                <th data-class="expand">País</th>
                                <th data-hide="phone,tablet">Orden</th>
                                <th data-hide="phone,tablet">Prefijo</th>
                                <th data-hide="phone">Área territorial 1</th>
                                <th data-hide="phone">Área territorial 2</th>
                                <th data-hide="phone">Área territorial 3</th>
                                <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
                                <th><?php echo trans('pulsar::pulsar.acciones');?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <input type="hidden" name="nElementsDataTable">
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
    @parent
    <!-- pulsar::countries.index -->
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.dataTable) {
                $('.datatable-pulsar').dataTable({
                    'iDisplayStart' : {{ $offset }},
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [8,9]},
                        { 'sClass': 'checkbox-column', 'aTargets': [8]},
                        { 'sClass': 'align-center', 'aTargets': [9]}
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "{{ route('jsonData' . $routeSuffix) }}"
                }).fnSetFilteringDelay();

            }
        });
    </script>
    <!-- /pulsar::countries.index -->
@stop

@section('tHead')
    <!-- pulsar::countries.index -->
    <th data-hide="phone,tablet">ID.</th>
    <th data-hide="phone,tablet">{{ trans_choice('pulsar::pulsar.language', 1) }}</th>
    <th data-class="expand">{{ trans_choice('pulsar::pulsar.country', 1) }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.sorting') }}</th>
    <th data-hide="phone,tablet">{{ trans('pulsar::pulsar.prefix') }}</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.territorial_area', 1) }} 1</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.territorial_area', 1) }} 2</th>
    <th data-hide="phone">{{ trans_choice('pulsar::pulsar.territorial_area', 1) }} 3</th>
    <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
    <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
    <!-- /pulsar::countries.index -->
@stop