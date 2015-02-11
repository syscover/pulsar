@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_form')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li>
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/paises">Países</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName')) }}/pulsar/areasterritoriales1/{{ $pais->id_002 }}"><?php echo $pais->area_territorial_1_002; ?></a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="entypo-icon-globe"></i> <?php echo $pais->area_territorial_1_002; ?></h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ url(config('pulsar.appName')) }}/pulsar/areasterritoriales1/update/{{ $pais->id_002 }}/{{ $offset }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID <span class="required">*</span></label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="id" value="<?php echo $area_territorial_1->id_003; ?>" rangelength="2, 6">
                            <input name="idOld" type="hidden" value="<?php echo $area_territorial_1->id_003; ?>">
                            <?php echo $errors->first('id',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">País</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" value="<?php echo $pais->nombre_002; ?>" readonly="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="<?php echo $area_territorial_1->nombre_003; ?>" rangelength="2, 50">
                            <?php echo $errors->first('nombre',config('pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                        <a class="btn btn-inverse" href="{{ url(config('pulsar.appName')) }}/pulsar/areasterritoriales1/{{ $pais->id_002 }}/{{ $offset }}">{{ trans('pulsar::pulsar.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>       
@stop