<div @if(isset($containerId)) id="{{ $containerId }}"@endif class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <div class="input-group date datetimepicker error-placement-after"@if(isset($id)) id="{{ $id }}"@endif{!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!}>
            <input type='text' class="form-control" name="{{ $name }}" data-error-placement-after='datetimepicker' value="{{ isset($value)? $value : null }}" @if(isset($placeholder)) placeholder="{{ $placeholder }}"@endif{{ isset($readOnly) && $readOnly? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>