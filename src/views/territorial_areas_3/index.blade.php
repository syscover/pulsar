@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li>
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/paises">Países</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/areasterritoriales3/{{ $pais->id_002 }}"><?php echo $pais->area_territorial_3_002; ?></a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ url(config('pulsar.appName')) }}/pulsar/areasterritoriales3/create/{{ $pais->id_002 }}/{{ $offset }}"><i class="entypo-icon-globe"></i> Nuevo/a <?php echo $pais->area_territorial_3_002; ?></a>
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> <?php echo $pais->area_territorial_3_002; ?> (<?php echo $pais->nombre_002; ?>)</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <form id="formView" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/areasterritoriales3/destroy/select/elements/{{ $pais->id_002 }}">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                            <tr>
                                <th data-hide="phone,tablet">ID.</th>
                                <th data-hide="phone"><?php echo $pais->area_territorial_1_002 ?></th>
                                <th data-hide="phone"><?php echo $pais->area_territorial_2_002 ?></th>
                                <th data-class="expand">{{ trans('pulsar::pulsar.nombre') }}</th>
                                <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
                                <th>{{ trans('pulsar::pulsar.acciones') }}</th>
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