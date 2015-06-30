<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }}</label>
    <div class="col-md-{{ $fieldSize or 10 }} height-field">
        <img src="{{ $url }}">
        {{ $nameImage }}
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>