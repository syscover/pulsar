@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_form')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/recursos">Recursos</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="icomoon-icon-database"></i> Recurso</h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/recursos/store/{{ $offset }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID <span class="required">*</span></label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="id" value="<?php echo Input::old('id'); ?>" maxlength="30" rangelength="2, 30">
                            <?php echo $errors->first('id',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Módulo <span class="required">*</span></label>
                        <div class="col-md-2">
                            <select class="form-control" name="modulo" notequal="null">
                                <option value="null">Elija un módulo</option>
                                <?php foreach ($modulos as $modulo): ?>
                                <option value="<?php echo $modulo->id_012 ?>" <?php if(Input::old('modulo') === $modulo->id_012) echo 'selected=""'; ?>><?php echo $modulo->name_012 ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $errors->first('modulo',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="{{ Input::old('nombre') }}" maxlength="50" rangelength="2, 50">
                            <?php echo $errors->first('nombre',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                        <a class="btn btn-inverse" href="{{ url(config('pulsar.appName')) }}/pulsar/recursos/{{ $offset }}">{{ trans('pulsar::pulsar.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>                    
@stop