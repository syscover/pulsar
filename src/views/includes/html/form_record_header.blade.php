<!-- pulsar::includes.html.form_record_header -->
<form id="recordForm" class="form-horizontal" method="post" action="{{ $action != 'show'? route(($action == "store" || $action == "storeLang"? "store" : $action) . ucfirst($routeSuffix), $urlParameters) : null }}" @if(isset($enctype) && $enctype)enctype="multipart/form-data"@endif>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @if($action == 'update') @include('pulsar::includes.html.put') @endif
<!-- /.pulsar::includes.html.form_record_header -->