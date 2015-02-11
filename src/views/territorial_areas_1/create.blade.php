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
    <a href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales1/{{ $pais->id_002 }}" title="">{{ $pais->area_territorial_1_002 }}</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="entypo-icon-globe"></i> {{ $pais->area_territorial_1_002 }}</h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales1/store/{{ $pais->id_002 }}/{{ $inicio }}">
                    {{ Form::token() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID <span class="required">*</span></label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="id" value="{{ Input::old('id') }}" rangelength="2, 6">
                            <?php echo $errors->first('id',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">País</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" value="{{ $pais->nombre_002 }}" readonly="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="nombre" value="{{ Input::old('nombre') }}" rangelength="2, 50">
                            <?php echo $errors->first('nombre',Config::get('pulsar::pulsar.errorDelimiters')); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ Lang::get('pulsar::pulsar.guardar') }}</button>
                        <a class="btn btn-inverse" href="{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales1/{{ $pais->id_002 }}/{{ $inicio }}">{{ Lang::get('pulsar::pulsar.cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>       
@stop