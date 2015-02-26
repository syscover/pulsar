@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
    @include('pulsar::common.js.script_success_message')
    @include('pulsar::common.js.script_datatable_config')
@stop

@section('mainContent')
    <!-- pulsar::layouts.index -->
    <div class="row">
        <div class="col-md-12">
            <a class="btn marginB10" href="{{ route('create' . $routeSuffix, $urlParameters) }}"><i class="{{ isset($icon)? $icon : 'icomoon-icon-power' }}"></i> {{ trans('pulsar::pulsar.' . $newTrans) }} {{ isset($customTrans)? $customTrans : trans_choice($objectTrans, 1) }}</a>
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> {{ isset($customTrans)? $customTrans : trans_choice($objectTrans, 2) }}</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                        </div>
                    </div>
                </div>
                <div class="widget-content no-padding">
                    <form id="formView" method="post" action="{{ route('deleteSelect' . $routeSuffix, $urlParameters) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                            <thead>
                            <tr>
                                @yield('tHead')
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" name="nElementsDataTable">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /pulsar::layouts.index -->
@stop