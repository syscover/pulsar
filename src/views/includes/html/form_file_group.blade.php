<!-- pulsar::includes.html.form_file_group -->
<div class="form-group">
    <label class="col-md-{{ $labelSize ?? 2 }} control-label">{{ $label }} @if(isset($required)) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize ?? 10 }} file-container">
        @if(! isset($disabled) ||  isset($disabled) && ! $disabled)
            <div class="fileinput fileinput-new input-group" @if(isset($value)) style="display: none" @endif data-provides="fileinput">
                <div class="form-control" data-trigger="fileinput">
                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                    <span class="fileinput-filename"></span>
                </div>
                <span class="input-group-addon btn btn-default btn-file">
                    <span class="fileinput-new">Select file</span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="{{ $name }}"{{ isset($required)? ' required' : null }}>
                </span>
                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
            </div>
        @endif
        @if(isset($value))
            <div class="filelink">
                <a href="{{ asset($dirName . '/' . $value) }}" title="{{ $value }}" target="_blank"><i class="black-text fa-2x fa fa-arrow-circle-o-down"></i></a>
                @if(! isset($disabled) ||  isset($disabled) && ! $disabled)
                    <a class="link-delete-file margin-l30" href="javascript:void(0)" data-href="{{ isset($urlDelete)? $urlDelete : null }}" data-file="{{ $value }}" data-id="{{ $objectId }}" data-name="{{ $name }}"><i class="black-text fa-2x fa fa-trash"></i></a>
                @endif
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            </div>
        @endif
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>
<!-- /pulsar::includes.html.form_file_group -->