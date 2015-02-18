@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_form')
@stop

@section('mainContent')
    <!-- pulsar::layouts.form -->
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header"><h4><i class="{{ isset($icon)? $icon : 'icomoon-icon-power' }}"></i> {{ trans_choice('pulsar::pulsar.' . $objectTrans, 1) }}</h4></div>
                <div class="widget-content">
                    <form class="form-horizontal" method="post" action="{{ route($action . $routeSuffix, $offset) }}" @if(isset($enctype) && $enctype)enctype="multipart/form-data"@endif>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if($action == 'update') @include('pulsar::common.block.block_put') @endif
                        @yield('rows')
                        <div class="form-actions">
                            <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                            <a class="btn btn-inverse" href="{{ route($routeSuffix, $offset) }}">{{ trans('pulsar::pulsar.cancel') }}</a>
                            @if(isset($lang) && $lang->id_001 != Session::get('baseLang')->id_001)
                            <a class="btn btn-danger marginL10 delete-lang-record">{{ trans('pulsar::pulsar.delete_translation') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /pulsar::layouts.form -->
@stop