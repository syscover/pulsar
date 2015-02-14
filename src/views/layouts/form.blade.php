@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_form')
@stop

@section('mainContent')
    <!-- pulsar::layouts.form -->
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header"><h4><i class="icomoon-icon-power"></i> {{ $object }}</h4></div>
                <div class="widget-content">
                    <form class="form-horizontal" method="post" action="{{ route($action . $routeSuffix, $offset) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if($action == 'update') @include('pulsar::common.block.block_put') @endif
                        @yield('rows')
                        <div class="form-actions">
                            <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
                            <a class="btn btn-inverse" href="{{ route($routeSuffix, $offset) }}">{{ trans('pulsar::pulsar.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /pulsar::layouts.form -->
@stop