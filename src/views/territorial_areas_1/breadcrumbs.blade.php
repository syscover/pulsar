<!-- pulsar::territorial_areas_1.breadcrumbs -->
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li>
    <a href="{{ route('Country') }}">{{ trans_choice('pulsar::pulsar.country', 2) }}</a>
</li>
<li class="current">
    <a href="{{ route($routeSuffix, [$country->id_002]) }}">{{ trans_choice('pulsar::pulsar.' . $objectTrans, 2) }}</a>
</li>
<!-- /pulsar::territorial_areas_1.breadcrumbs -->