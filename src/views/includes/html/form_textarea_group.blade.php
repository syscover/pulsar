<div class="form-group textarea-container">
    <label class="col-md-{{ $labelSize ?? 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize ?? 10 }}">
        <textarea class="form-control" name="{{ $name }}" @if(isset($rows))rows="{{ $rows }}"@endif @if(isset($cols)) cols="{{ $cols }}"@endif{{ isset($readOnly) && $readOnly? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>{{ isset($value)? $value : null }}</textarea>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>