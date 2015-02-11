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
                <form class="form-horizontal" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/usuarios/store/{{ $offset }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID</label>
                        <div class="col-md-2"><input class="form-control" type="text" name="id" readonly=""></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="{{ Input::old('nombre') }}" maxlength="50" rangelength="2, 50">
                            {!! $errors->first('nombre',config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Apellidos <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="apellidos" value="{{ Input::old('apellidos') }}" maxlength="50" rangelength="2, 50">
                            {!! $errors->first('apellidos',config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Email <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required email" type="email" name="email" value="{{ Input::old('email') }}" maxlength="50" rangelength="2, 50">
                            {!! $errors->first('email',config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Idioma <span class="required">*</span></label>
                        <div class="col-md-2">
                           <select class="form-control" name="idioma" notequal="null">
                                <option value="null">Elija un idioma</option>
                                <?php foreach ($idiomas as $idioma): ?>
                                <option value="<?php echo $idioma->id_001 ?>" <?php if(Input::old('idioma') == $idioma->id_001) echo 'selected=""'; ?>><?php echo $idioma->nombre_001 ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $errors->first('idioma',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Acceso</label>
                        <div class="col-md-10">
                            <input class="uniform" type="checkbox" name="acceso" value="1"  <?php if(Input::old('acceso')) echo 'checked=""'; ?>>
                        </div>
                    </div>
                    <div class="widget-sub-header"><h4><i class="icomoon-icon-users"></i> Datos de acceso</h4></div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Perfil <span class="required">*</span></label>
                        <div class="col-md-2">
                            <select class="form-control" name="perfil" notequal="null">
                                <option value="null">Elija un perfil</option>
                                <?php foreach ($perfiles as $perfil): ?>
                                <option value="<?php echo $perfil->id_006 ?>" <?php if(Input::old('perfil') == $perfil->id_006) echo 'selected=""'; ?>><?php echo $perfil->nombre_006 ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $errors->first('perfil',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Usuario <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="user" value="<?php echo Input::old('user'); ?>" maxlength="50" rangelength="2, 50">
                            <?php echo $errors->first('user',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Contraseña <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="password" name="password" value="<?php echo Input::old('password'); ?>" maxlength="50" rangelength="4, 50">
                            <?php echo $errors->first('password',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Repita la contraseña <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="password" name="password2" value="<?php echo Input::old('password2'); ?>" maxlength="50" rangelength="4, 50" equalTo="[name='password']">
                            <?php echo $errors->first('password2',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                        <a class="btn btn-inverse" href="{{ url(config('pulsar.appName')) }}/pulsar/usuarios/{{ $offset }}">{{ trans('pulsar::pulsar.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>                    
@stop