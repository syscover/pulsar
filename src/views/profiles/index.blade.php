@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('breadcrumbs')
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ route('Profile') }}">{{ trans_choice('pulsar::pulsar.profile', 2) }}</a>
</li>
@stop

@section('mainContent')
<div class="row">
    <div class="col-md-12">
        <a class="btn marginB10" href="{{ route('storeProfile', $offset) }}"><i class="icomoon-icon-users-2"></i> {{ trans('pulsar::pulsar.new') . ' '. trans_choice('pulsar::pulsar.profile', 1) }}</a>
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> {{ trans_choice('pulsar::pulsar.profile', 2) }}</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <form id="formView" method="post" action="{{ route('destroySelectProfile') }}">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                            <tr>
                                <th data-hide="phone,tablet">ID.</th>
                                <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
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
    </div>
</div>
@stop