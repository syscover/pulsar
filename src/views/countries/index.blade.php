@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/paises">Países</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ url(config('pulsar.appName')) }}/pulsar/paises/create/{{ $offset }}/<?php echo $idiomaBase->id_001; ?>"><i class="entypo-icon-globe"></i> Nuevo país</a>
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