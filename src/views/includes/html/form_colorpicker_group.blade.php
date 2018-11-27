<div class="form-group">
    <label class="col-md-{{ $labelSize ?? 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize ?? 10 }}">
        <div class="input-group color-picker">
            <input type="text" class="form-control" name="{{ $name }}" value="{{ isset($value)? $value : null }}"{!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!} @if(isset($placeholder)) placeholder="{{ $placeholder }}"@endif {{ isset($readOnly) && $readOnly? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>
            <span class="input-group-addon"><i></i></span>
        </div>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>