@extends('pulsar::layouts.default')

@section('head')
    @parent
    <!-- pulsar::layouts.index -->
    @include('pulsar::includes.js.header_list')
    @include('pulsar::includes.js.messages')
    @include('pulsar::includes.js.datatable_config')
    <!-- /.pulsar::layouts.index -->
@stop

@section('mainContent')
    <!-- pulsar::layouts.index -->
    <div class="row">
        <div class="col-md-12">
            @if($viewParameters['newButton'])
                <a class="btn margin-b10" href="{{ route('create' . ucfirst($routeSuffix), $urlParameters) }}"><i class="{{ isset($icon)? $icon : 'icomoon-icon-power' }}"></i> {{ trans('pulsar::pulsar.' . (isset($newTrans)? $newTrans : 'new')) }} {{ isset($customTrans)? $customTrans : trans_choice($objectTrans, 1) }}</a>
            @endif

            @yield('headButtons')

                <div class="row">
                <div class="col-md-{{ $firstColSize or 12 }}">
                    <div class="widget box">
                        <div class="widget-header">
                            <h4><i class="fa fa-reorder"></i> {{ isset($customTransHeader)? $customTransHeader : trans_choice($objectTrans, 2) }}</h4>
                            <div class="toolbar no-padding">
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="fa fa-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content no-padding">
                            <form id="formView" method="post" action="{{ $viewParameters['deleteSelectButton'] && $viewParameters['deleteSelectButton'] == 'true' && is_allowed($resource, 'delete')? route('deleteSelect' . ucfirst($routeSuffix), $urlParameters) : null }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable-pulsar">
                                    <thead>
                                    <tr>
                                        @yield('tHead')
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @yield('tBody')
                                    </tbody>
                                </table>
                                <input type="hidden" name="nElementsDataTable">
                            </form>
                        </div>
                    </div>
                </div>
                @if(isset($secondColSize))
                    <div class="col-md-{{ $secondColSize }}">
                        @yield('sideContent')
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- /.pulsar::layouts.index -->
@stop