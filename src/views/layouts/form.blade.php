@extends('pulsar::layouts.record')

@section('content')
    <!-- pulsar::layouts.form -->
    <form class="form-horizontal" method="post" action="{{ route($action . $routeSuffix, $urlParameters) }}" @if(isset($enctype) && $enctype)enctype="multipart/form-data"@endif>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if($action == 'update') @include('pulsar::common.block.block_put') @endif
        @yield('rows')
        <div class="form-actions">
            <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
            <a class="btn btn-inverse" href="{{ route($routeSuffix, $urlParameters) }}">{{ trans('pulsar::pulsar.cancel') }}</a>
            @if($action != 'store' && isset($lang) && $lang->id_001 != Session::get('baseLang')->id_001)
            <a class="btn btn-danger marginL10 delete-lang-record" data-delete-url="{{ route('deleteTranslation' . $routeSuffix, $urlParameters) }}">{{ trans('pulsar::pulsar.delete_translation') }}</a>
            @endif
        </div>
    </form>
    <!-- /pulsar::layouts.form -->
@stop