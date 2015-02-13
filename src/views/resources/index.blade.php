@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ route('create' . $routeSuffix, $offset) }}"><i class="icomoon-icon-database"></i> {{ trans('pulsar::pulsar.new') . ' '. trans_choice('pulsar::pulsar.resource', 1) }}</a>
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> {{ trans_choice('pulsar::pulsar.resource', 2) }}</h4>
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
                                <th data-hide="phone,tablet">ID.</th>
                                <th data-hide="phone">{{ trans_choice('pulsar::pulsar.package', 1) }}</th>
                                <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
                                <th class="checkbox-column"><input type="checkbox" class="uniform"></th>
                                <th>{{ trans_choice('pulsar::pulsar.action', 1) }}</th>
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
@stop