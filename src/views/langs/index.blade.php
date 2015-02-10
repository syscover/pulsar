@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);" title="">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar::pulsar.rootUri') . '/pulsar/languages') }}">Idiomas</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ url(config('pulsar::pulsar.rootUri') . '/pulsar/languages/create/') . $offset }}"><i class="brocco-icon-flag"></i> Nuevo idioma</a>
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Idiomas</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <form id="formView" method="post" action="{{ url(config('pulsar::pulsar.rootUri') . '/pulsar/languages/destroy/select/elements') }}">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                            <tr>
                                <th data-hide="phone,tablet">ID.</th>
                                <th>Imagen</th>
                                <th data-class="expand">Idioma</th>
                                <th data-hide="phone">Idioma base</th>
                                <th data-hide="phone">Activo</th>
                                <th data-hide="phone">Orden</th>
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
    </div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
@stop