<!-- pulsar::field_value.breadcrumbs -->
<li>
    <a href="javascript:void(0)">{{ trans('pulsar::pulsar.package_name') }}</a>
</li>
<li>
    <a href="{{ route('customField', [session('baseLang')->id_001]) }}">{{ trans_choice('pulsar::pulsar.field', 2) }}</a>
</li>
<li class="current">
    <a href="{{ route($routeSuffix, ['field' => $field, 'lang' => $lang, 'offset' => $offset]) }}">{{ trans_choice($objectTrans, 2) }}</a>
</li>
<!-- ./pulsar::field_value.breadcrumbs -->