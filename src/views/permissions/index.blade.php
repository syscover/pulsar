@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li>
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/perfiles">Perfiles</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/recursos">Permisos</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Permisos del perfil - <?php echo $perfil->nombre_006; ?></h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <form id="formView" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/recursos/destroy/select/elements">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                            <tr>
                                <th data-hide="phone,tablet">ID.</th>
                                <th data-hide="phone,tablet">Módulo</th>
                                <th data-class="expand">{{ trans('pulsar::pulsar.nombre') }}</th>
                                <th>{{ trans('pulsar::pulsar.permisos') }}</th>
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