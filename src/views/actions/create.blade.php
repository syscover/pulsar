@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_form')
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="icomoon-icon-power"></i> {{ trans_choice('pulsar::pulsar.action', 1) }}</h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ route('store' . $routeSuffix, $offset) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID</label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="id" maxlength="25" rangelength="2, 25">
                            {!! $errors->first('id', config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('pulsar::pulsar.name') }} <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="name" value="{{ Input::old('name') }}" maxlength="50" rangelength="2, 50">
                            {!! $errors->first('name', config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                        <a class="btn btn-inverse" href="{{ route($routeSuffix, $offset) }}">{{ trans('pulsar::pulsar.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>                    
@stop