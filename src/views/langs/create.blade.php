@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_form')
    <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/plugins/fileinput/fileinput.js') }}"></script>
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName') . '/pulsar/langs') }}">{{ trans_choice('pulsar::pulsar.language', 2) }}</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="brocco-icon-flag"></i> {{ trans_choice('pulsar::pulsar.language', 1) }}</h4></div>
            <div class="widget-content">
                <form class="form-horizontal" method="post" action="{{ url(config('pulsar.appName') . '/pulsar/langs/store/' . $offset) }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID <span class="required">*</span></label>
                        <div class="col-md-2">
                            <input class="form-control required" type="text" name="id" value="{{ Input::old('id') }}" rangelength="2,2">
                           {!! $errors->first('id', config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('pulsar::pulsar.name') }} <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required" type="text" name="name" value="{{ Input::old('name') }}" rangelength="2, 50">
                            {!! $errors->first('nombre', config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('pulsar::pulsar.sorting') }} <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="form-control required number" type="text" name="sorting" value="{{ Input::old('sorting') }}" min="0">
                            {!! $errors->first('orden', config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('pulsar::pulsar.base_language') }}</label>
                        <div class="col-md-10">
                            <input class="uniform" type="checkbox" name="base" value="1"{{ Input::old('base')? ' checked'  :null }}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans_choice('pulsar::pulsar.image', 1) }} <span class="required">*</span></label>
                        <div class="col-md-10">
                            <input class="required" type="file" data-style="fileinput" name="image" accept="image/*">
                            <label for="image" class="has-error help-block" generated="true" style="display:none;"></label>
                            {!! $errors->first('image', config('pulsar.errorDelimiters')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('pulsar::pulsar.active') }}</label>
                        <div class="col-md-10">
                            <input class="uniform" type="checkbox" name="active" value="1"{{ Input::old('active')? ' checked'  :null }}>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                        <a class="btn btn-inverse" href="{{ url(config('pulsar.appName') . '/pulsar/langs/' . $offset) }}">{{ trans('pulsar::pulsar.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>                    
@stop