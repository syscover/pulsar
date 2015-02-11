@extends('pulsar::pulsar.pulsar.layouts.default')

@section('script')
    @include('pulsar::pulsar.pulsar.common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);" title="">{{ucwords(Lang::get('pulsar::pulsar.administracion'))}}</a>
</li>
<li>
    <a href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/paises" title="">Países</a>
</li>
<li class="current">
    <a href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales1/{{ $pais->id_002 }}" title="">{{ $pais->area_territorial_1_002 }}</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales1/create/{{ $pais->id_002 }}/{{ $inicio }}"><i class="entypo-icon-globe"></i> Nuevo/a <?php echo $pais->area_territorial_1_002; ?></a>
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> <?php echo $pais->area_territorial_1_002; ?> (<?php echo $pais->nombre_002; ?>)</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <form id="formView" method="post" action="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales1/destroy/select/elements/{{ $pais->id_002 }}">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                            <tr>
                                <th data-hide="phone,tablet">ID.</th>
                                <th data-class="expand">{{ Lang::get('pulsar::pulsar.nombre') }}</th>
                                <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
                                <th>{{ Lang::get('pulsar::pulsar.acciones') }}</th>
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