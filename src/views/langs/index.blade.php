@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ url(config('pulsar.appName') . '/pulsar/languages') }}">{{ trans_choice('pulsar::pulsar.language', 2) }}</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ url(config('pulsar.appName') . '/pulsar/langs/create/'. $offset) }}"><i class="brocco-icon-flag"></i> {{ trans('pulsar::pulsar.new') . ' '. trans_choice('pulsar::pulsar.language', 1) }}</a>
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> {{ trans_choice('pulsar::pulsar.language', 2) }}</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <form id="formView" method="post" action="{{ url(config('pulsar.appName') . '/pulsar/langs/destroy/select/elements') }}">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                            <tr>
                                <th data-hide="phone,tablet">ID.</th>
                                <th>{{ trans_choice('pulsar::pulsar.image', 2) }}</th>
                                <th data-class="expand">{{ trans_choice('pulsar::pulsar.language', 2) }}</th>
                                <th data-hide="phone">{{ trans('pulsar::pulsar.base_language') }}</th>
                                <th data-hide="phone">{{ trans('pulsar::pulsar.active') }}</th>
                                <th data-hide="phone">{{ trans('pulsar::pulsar.sorting') }}</th>
                                <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
                                <th>{{ trans_choice('pulsar::pulsar.action', 2) }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <input type="hidden" name="nElementsDataTable">
                </form>
            </div>
        </div>
    </div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
@stop