<!-- pulsar::includes.html.form_record_header -->
<form class="form-horizontal" method="post" action="{{ route($action . $routeSuffix, $urlParameters) }}" @if(isset($enctype) && $enctype)enctype="multipart/form-data"@endif>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @if($action == 'update') @include('pulsar::includes.html.put') @endif
<!-- /pulsar::includes.html.form_record_header -->