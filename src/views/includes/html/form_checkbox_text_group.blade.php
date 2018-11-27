<div class="form-group">
    <label class="col-md-{{ $labelSize ?? 2 }} control-label">{{ $label }}</label>
    <div class="col-md-{{ $fieldSize ?? 1 }}">
        <input class="uniform" type="checkbox" name="{{ $name }}" value="{{ $value }}"{{ isset($disabled)? ' disabled' : null }}{{ isset($checked) && $checked? ' checked' : null }}>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
    <div class="col-md-{{ $inputText['size'] ?? 9 }}">
        <input class="form-control{{ $inputText['class'] ?? null }}" type="{{ $inputText['type'] ?? 'text' }}" name="{{ $inputText['name'] }}" value="{{ $inputText['value'] ?? null }}"{!! isset($inputText['data'])? Miscellaneous::setDataAttributes($inputText['data']) : null !!} placeholder="{{ $inputText['placeholder'] ?? null }}" @if(isset($inputText['maxlength'])) maxlength="{{ $inputText['maxlength'] }}"@endif @if(isset($inputText['rangelength']))rangelength="{{ $inputText['rangelength'] }}"@endif @if(isset($inputText['min'])) min="{{ $inputText['min'] }}"@endif @if(isset($inputText['max'])) max="{{ $inputText['max'] }}"@endif{{ isset($inputText['readOnly']) && $inputText['readOnly']? ' readonly' : null }}{{ isset($inputText['required']) && $inputText['required']? ' required' : null }}>
        {!! $errors->first($inputText['name'], config('pulsar.errorDelimiters')) !!}
    </div>
</div>