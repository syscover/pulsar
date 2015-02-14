@extends('pulsar::pulsar.pulsar.layouts.default')

@section('script')
    @include('pulsar::pulsar.pulsar.common.block.block_script_header_form')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);" title="">{{ucwords(Lang::get('pulsar::pulsar.administracion'))}}</a>
</li>
<li class="current">
    <a href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/cron/jobs" title="">Tareas Cron</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="icomoon-icon-stopwatch"></i> Tarea cron</h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/cron/jobs/update/{{ $inicio }}">
                    {{ Form::token() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID</label>
                        <div class="col-md-2">
                            <input class="form-control" type="text" name="id" readonly="" value="<?php echo $cronJob->id_043; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="<?php echo $cronJob->nombre_043; ?>" maxlength="100" rangelength="2, 100">
                            <?php echo $errors->first('nombre',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Módulo <span class="required">*</span></label>
                        <div class="col-md-2">
                            <select class="form-control" name="modulo" notequal="null">
                                <option value="null">Elija un módulo</option>
                                <?php foreach ($modulos as $modulo): ?>
                                <option value="<?php echo $modulo->id_012 ?>" <?php if($cronJob->modulo_043 === $modulo->id_012) echo 'selected=""'; ?>><?php echo $modulo->name_012 ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $errors->first('modulo',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Expresión Cron <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="cronExpresion" value="<?php echo $cronJob->cron_expresion_043; ?>" maxlength="255" rangelength="9, 255">
                            <?php echo $errors->first('cronExpresion',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Última ejecución</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" readonly="" value="<?php echo $ultimaEjecucion; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Siguiente ejecución</span></label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" readonly="" value="<?php echo $siguienteEjecucion; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Activa</label>
                        <div class="col-md-10">
                            <input class="uniform" type="checkbox" name="activa" value="1"  <?php if($cronJob->activa_043) echo 'checked=""'; ?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Key <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="key" value="<?php echo $cronJob->key_043; ?>" maxlength="50" rangelength="1, 50">
                            <?php echo $errors->first('key',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ Lang::get('pulsar::pulsar.guardar') }}</button>
                        <a class="btn btn-inverse" href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/cron/jobs/{{ $inicio }}">{{ Lang::get('pulsar::pulsar.cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>                    
@stop