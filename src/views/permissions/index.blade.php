@extends('pulsar::layouts.default')

@section('script')
    @include('pulsar::common.block.block_script_header_list')
@stop

@section('mainContent')
    <!-- pulsar::permissions.index -->
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> {{ trans_choice('pulsar::pulsar.permission', 2) }}</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                        </div>
                    </div>
                </div>
                <div class="widget-content no-padding">
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                        <thead>
                        <tr>
                            <th data-hide="phone,tablet">ID.</th>
                            <th data-hide="phone,tablet">{{ trans_choice('pulsar::pulsar.package', 1) }}</th>
                            <th data-class="expand">{{ trans('pulsar::pulsar.name') }}</th>
                            <th>{{ trans_choice('pulsar::pulsar.permission', 1) }}</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <input type="hidden" name="nElementsDataTable">
                </div>
            </div>
        </div>
    </div>
    <!-- /pulsar::permissions.index -->
@stop