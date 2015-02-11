@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_form')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/paises">Países</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="entypo-icon-globe"></i> País</h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/paises/update/{{ $offset }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID</label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="id" <?php if($idioma->id_001 !=  Session::get('idiomaBase')->id_001) echo 'readonly=""'; ?> value="{{ $pais->id_002 }}" maxlength="2">
                            {!! $errors->first('id',config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Idioma <span class="required">*</span></label>
                        <div class="col-md-10">
                            <img src="<?php echo url('/');?>/packages/pulsar/pulsar/storage/languages/<?php echo $idioma->image_001; ?>">
                            <?php echo $idioma->nombre_001; ?>
                            <input type="hidden" name="idioma" value="<?php echo $idioma->id_001; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="<?php echo $pais->nombre_002; ?>" rangelength="2, 100">
                            <?php echo $errors->first('nombre',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Orden <span class="required">*</span></label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="orden" value="<?php echo $pais->orden_002; ?>">
                            <?php echo $errors->first('orden',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Prefijo <span class="required">*</span></label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="prefijo" value="<?php echo $pais->prefijo_002; ?>">
                            <?php echo $errors->first('prefijo',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Área territorial 1</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="areaTerritorial1" value="<?php echo $pais->area_territorial_1_002; ?>" rangelength="0, 50">
                            <?php echo $errors->first('areaTerritorial1',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Área territorial 2</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="areaTerritorial2" value="<?php echo $pais->area_territorial_2_002; ?>" rangelength="0, 50">
                            <?php echo $errors->first('areaTerritorial2',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Área territorial 3</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="areaTerritorial3" value="<?php echo $pais->area_territorial_3_002; ?>" rangelength="0, 50">
                            <?php echo $errors->first('areaTerritorial3',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                        <a class="btn btn-inverse" href="{{ url(config('pulsar.appName')) }}/pulsar/paises/{{ $offset }}">Cancelar</a>
                        <?php if($idioma->id_001 != Session::get('idiomaBase')->id_001):?>     
                        <a class="btn btn-danger marginL10" href="javascript:deleteLangElement('<?php echo $pais->id_002;?>','<?php echo $idioma->id_001;?>','<?php echo $offset;?>')"><?php echo trans('pulsar::pulsar.eliminar_traduccion');?></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>                    
@stop
