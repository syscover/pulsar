<div class="form-group">
    <label class="col-md-{{ $sizeLabel or 2 }} control-label">{{ $label }}</label>
    <div class="col-md-{{ $sizeField or 10 }}">
        <input class="uniform" type="checkbox" name="{{ $name }}" value="{{ $value }}"{{ isset($readOnly)? ' readonly' : null }}{{ isset($isChecked)? ' checked' : null }}>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>