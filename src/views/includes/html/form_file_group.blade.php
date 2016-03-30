<!-- pulsar::includes.html.form_file_group -->
<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required)) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
            <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
            <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="{{ $name }}"{{ isset($required)? ' required' : null }}></span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
        </div>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>
<!-- /pulsar::includes.html.form_file_group -->