@extends('pulsar::pulsar.pulsar.layouts.default')

@section('script')
    @include('pulsar::pulsar.pulsar.common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);" title="">{{ucwords(Lang::get('pulsar::pulsar.administracion'))}}</a>
</li>
<li class="current">
    <a href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/paises" title="">Países</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/paises/create/{{ $inicio }}/<?php echo $idiomaBase->id_001; ?>"><i class="entypo-icon-globe"></i> Nuevo país</a>
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
                <form id="formView" method="post" action="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/paises/destroy/select/elements">
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
                                <th><?php echo Lang::get('pulsar::pulsar.acciones');?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <input type="hidden" name="nElementsDataTable" value="" />
                </form>
            </div>
        </div>
    </div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
@stop