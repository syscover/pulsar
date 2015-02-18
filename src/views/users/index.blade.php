@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/usuarios">Usuarios</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ url(config('pulsar.appName')) }}/pulsar/usuarios/create/{{ $offset }}"><i class="icomoon-icon-users"></i> Nuevo usuario</a>
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Usuarios</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <form id="formView" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/usuarios/delete/select/elements">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                            <tr>
                                <th data-hide="phone,tablet">ID.</th>
                                <th data-class="expand">Nombre</th>
                                <th data-hide="phone,tablet">Apellidos</th>
                                <th data-hide="phone">Email</th>
                                <th data-hide="phone">Perfil</th>
                                <th data-hide="phone">Acceso</th>
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