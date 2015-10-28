<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }}</label>
    <div class="col-md-{{ $fieldSize or 1 }}">
        <input class="uniform" type="checkbox" name="{{ $name }}" value="{{ $value }}"{{ isset($disabled)? ' disabled' : null }}{{ isset($isChecked) && $isChecked? ' checked' : null }}>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
    <div class="col-md-{{ $inputText['size'] or 9 }}">
        <input class="form-control{{ $inputText['class'] or null }}" type="{{ $inputText['type'] or 'text' }}" name="{{ $inputText['name'] }}" value="{{ $inputText['value'] or null }}"{!! isset($inputText['data'])? Miscellaneous::setDataAttributes($inputText['data']) : null !!} placeholder="{{ $inputText['placeholder'] or null }}" @if(isset($inputText['maxlength'])) maxlength="{{ $inputText['maxlength'] }}"@endif @if(isset($inputText['rangelength']))rangelength="{{ $inputText['rangelength'] }}"@endif @if(isset($inputText['min'])) min="{{ $inputText['min'] }}"@endif @if(isset($inputText['max'])) max="{{ $inputText['max'] }}"@endif{{ isset($inputText['readOnly']) && $inputText['readOnly']? ' readonly' : null }}{{ isset($inputText['required']) && $inputText['required']? ' required' : null }}>
        {!! $errors->first($inputText['name'], config('pulsar.errorDelimiters')) !!}
    </div>
</div>