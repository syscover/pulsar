@extends('pulsar::pulsar.pulsar.layouts.default')

@section('script')
    @include('pulsar::pulsar.pulsar.common.block.block_script_header_form')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);" title="">{{ucwords(Lang::get('pulsar::pulsar.administracion'))}}</a>
</li>
<li>
    <a href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/paises" title="">Países</a>
</li>
<li class="current">
    <a href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales2/{{ $pais->id_002 }}" title=""><?php echo $pais->area_territorial_2_002; ?></a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="entypo-icon-globe"></i> <?php echo $pais->area_territorial_2_002; ?></h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales2/update/{{ $pais->id_002 }}/{{ $inicio }}">
                    {{ Form::token() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID <span class="required">*</span></label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="id" value="<?php echo $areaTerritorial2->id_004; ?>" rangelength="2, 10">
                            <input name="idOld" type="hidden" value="<?php echo $areaTerritorial2->id_004; ?>">
                            <?php echo $errors->first('id',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">País</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" value="<?php echo $pais->nombre_002; ?>" readonly="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><?php echo $pais->area_territorial_1_002 ?> <span class="required">*</span></label>
                        <div class="col-md-2">
                            <select class="form-control" name="areaTerritorial1" notequal="null">
                                <option value="null">Elija un/a <?php echo $pais->area_territorial_1_002; ?></option>
                                <?php foreach ($areasTerritoriales1 as $areaTerritorial1): ?>
                                <option value="<?php echo $areaTerritorial1->id_003 ?>" <?php if($areaTerritorial2->area_territorial_1_004  == $areaTerritorial1->id_003) echo 'selected=""'; ?>><?php echo $areaTerritorial1->nombre_003 ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $errors->first('modulo',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="<?php echo $areaTerritorial2->nombre_004; ?>" rangelength="2, 50">
                            <?php echo $errors->first('nombre',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ Lang::get('pulsar::pulsar.guardar') }}</button>
                        <a class="btn btn-inverse" href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales2/{{ $pais->id_002 }}/{{ $inicio }}">{{ Lang::get('pulsar::pulsar.cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>       
@stop