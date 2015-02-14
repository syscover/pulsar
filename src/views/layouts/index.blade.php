@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('mainContent')
    <!-- pulsar::layouts.index -->
    <div class="row">
        <div class="col-md-12">
            <a class="btn marginB10" href="{{ route('create' . $routeSuffix, $offset) }}"><i class="icomoon-icon-power"></i> {{ trans('pulsar::pulsar.' . $newTrans) . ' '. trans_choice('pulsar::pulsar.' . $object, 1) }}</a>
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> {{ trans_choice('pulsar::pulsar.' . $object, 2) }}</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                        </div>
                    </div>
                </div>
                <div class="widget-content no-padding">
                    <form id="formView" method="post" action="{{ route('destroySelect' . $routeSuffix) }}">
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