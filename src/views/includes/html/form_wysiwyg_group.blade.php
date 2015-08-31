<div class="form-group wysiwyg-container">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <textarea class="form-control wysiwyg" name="{{ $name }}" {{ isset($readOnly) && $readOnly? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>{{ isset($value)? $value : null }}</textarea>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>