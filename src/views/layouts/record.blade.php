@extends(isset($extends) && is_array($extends) && isset($extends['record'])? $extends['record'] : 'pulsar::layouts.default')

@section('head')
    @parent
    @include('pulsar::includes.js.header_form')
@stop

@section('mainContent')
    <!-- pulsar::layouts.record -->
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header"><h4><i class="{{ isset($icon)? $icon : 'icomoon-icon-power' }}"></i> {{ isset($customTransHeader)? $customTransHeader : trans_choice($objectTrans, 1) }}</h4></div>
                <div class="widget-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <!-- /pulsar::layouts.record -->
@stop