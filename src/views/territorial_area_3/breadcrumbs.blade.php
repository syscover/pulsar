<!-- pulsar::territorial_areas_3.breadcrumbs -->
<li>
    <a href="javascript:void(0);">{{ trans('pulsar::pulsar.administration') }}</a>
</li>
<li>
    <a href="{{ route('Country', ['lang' => session('baseLang'), 'offset' => $parentOffset]) }}">{{ trans_choice('pulsar::pulsar.country', 2) }}</a>
</li>
<li class="current">
    <a href="{{ route($routeSuffix, $urlParameters) }}">{{ $country->territorial_area_3_002 }}</a>
</li>
<!-- /pulsar::territorial_areas_3.breadcrumbs -->