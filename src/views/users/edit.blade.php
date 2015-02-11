@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_form')
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
        <div class="widget box">
            <div class="widget-header"><h4><i class="icomoon-icon-users"></i> Usuario</h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/usuarios/update/{{ $offset }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID</label>
                        <div class="col-md-2"><input class="form-control" type="text" readonly="" name="id" value="<?php echo $usuario->id_010; ?>"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="<?php echo $usuario->nombre_010; ?>" maxlength="50" rangelength="2, 50">
                            <?php echo $errors->first('nombre',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Apellidos <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="apellidos" value="<?php echo $usuario->apellidos_010; ?>" maxlength="50" rangelength="2, 50">
                            <?php echo $errors->first('apellidos',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Email <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required email" type="email" name="email" value="<?php echo $usuario->email_010; ?>" maxlength="50" rangelength="2, 50">
                            <?php echo $errors->first('email',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Idioma <span class="required">*</span></label>
                        <div class="col-md-2">
                           <select class="form-control required" name="idioma" notequal="null">
                                <option value="null">Elija un idioma</option>
                                <?php foreach ($idiomas as $idioma): ?>
                                <option value="<?php echo $idioma->id_001 ?>" <?php if($usuario->idioma_010 == $idioma->id_001) echo 'selected=""'; ?>><?php echo $idioma->nombre_001 ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $errors->first('idioma',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Acceso</label>
                        <div class="col-md-10">
                            <input class="uniform" type="checkbox" name="acceso" value="1"  <?php if($usuario->acceso_010) echo 'checked=""'; ?>>
                        </div>
                    </div>
                    <div class="widget-sub-header"><h4><i class="icomoon-icon-users"></i> Datos de acceso</h4></div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Perfil <span class="required">*</span></label>
                        <div class="col-md-2">
                            <select class="form-control required" name="perfil" notequal="null">
                                <option value="null">Elija un perfil</option>
                                <?php foreach ($perfiles as $perfil): ?>
                                <option value="<?php echo $perfil->id_006 ?>" <?php if($usuario->profile_010 == $perfil->id_006) echo 'selected="selected"'; ?>><?php echo $perfil->nombre_006 ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $errors->first('perfil',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Usuario <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="user" value="<?php echo $usuario->user_010; ?>" maxlength="50" rangelength="2, 50">
                            <?php echo $errors->first('user',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Contraseña</label>
                        <div class="col-md-10">
                            <input class="form-control" type="password" name="password" value="" maxlength="50" rangelength="4,50">
                            <?php echo $errors->first('password',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Repita la contraseña</label>
                        <div class="col-md-10">
                            <input class="form-control" type="password" name="password2" value="" maxlength="50" equalTo="[name='password']">
                            <?php echo $errors->first('password2',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button id="envio" type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                        <a class="btn btn-inverse" href="{{ url(config('pulsar.appName')) }}/pulsar/usuarios/{{ $offset }}">{{ trans('pulsar::pulsar.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>                    
@stop