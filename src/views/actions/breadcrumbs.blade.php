<!-- pulsar::actions.breadcrumbs -->
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li class="current">
    <a href="{{ route($routeSuffix) }}">{{ trans_choice('pulsar::pulsar.' . $objectTrans, 2) }}</a>
</li>
<!-- /pulsar::actions.breadcrumbs -->