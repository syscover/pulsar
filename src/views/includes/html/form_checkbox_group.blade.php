<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }}</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <input class="uniform" type="checkbox" name="{{ $name }}" value="{{ $value }}"{{ isset($disabled)? ' disabled' : null }}{{ isset($isChecked) && $isChecked? ' checked' : null }}>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>