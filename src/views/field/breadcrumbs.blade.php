<!-- pulsar::field.breadcrumbs -->
<li>
    <a href="javascript:void(0)">{{ trans('pulsar::pulsar.package_name') }}</a>
</li>
<li class="current">
    <a href="{{ route($routeSuffix, [session('baseLang')]) }}">{{ trans_choice($objectTrans, 2) }}</a>
</li>
<!-- ./pulsar::field.breadcrumbs -->