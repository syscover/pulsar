<!-- pulsar::permissions.breadcrumbs -->
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li>
    <a href="{{ route($routeSuffixProfile, $offsetProfile) }}">{{ trans_choice('pulsar::pulsar.profile', 2) }}</a>
</li>
<li class="current">
    <a href="{{ route($routeSuffix, [$offset, $profile, $offsetProfile]) }}">{{ trans_choice('pulsar::pulsar.permission', 2) }}</a>
</li>
<!-- /pulsar::permissions.breadcrumbs -->