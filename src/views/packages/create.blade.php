@extends('pulsar::pulsar.pulsar.layouts.default')

@section('script')
    @include('pulsar::pulsar.pulsar.common.block.block_script_header_form')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);" title="">{{ucwords(Lang::get('pulsar::pulsar.administracion'))}}</a>
</li>
<li class="current">
    <a href="{{ URL::to(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages') }}" title="">Paquetes</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="cut-icon-grid"></i> Paqeute</h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ URL::to(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/store/' . $inicio) }}">
                    {{ Form::token() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID</label>
                        <div class="col-md-2"><input class="form-control" type="text" readonly="" name="id"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="{{ Input::old('nombre') }}" rangelength="2, 50">
                            <?php echo $errors->first('nombre',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Activo</label>
                        <div class="col-md-10">
                            <input class="uniform" type="checkbox" name="activo" value="1"  <?php if(Input::old('activo')) echo 'checked=""'; ?>>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ Lang::get('pulsar::pulsar.guardar') }}</button>
                        <a class="btn btn-inverse" href="{{ URL::to(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/' . $inicio) }}">{{ Lang::get('pulsar::pulsar.cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>       
@stop