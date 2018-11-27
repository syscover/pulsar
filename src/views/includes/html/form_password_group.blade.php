<div class="form-group">
    <label class="col-md-{{ $labelSize ?? 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize ?? 10 }}">
        <input class="form-control" type="password" name="{{ $name }}" value="{{ isset($value)? $value : null }}" @if(isset($maxLength)) maxlength="{{ $maxLength }}"@endif @if(isset($rangeLength))rangelength="{{ $rangeLength }}"@endif @if(isset($min)) min="{{ $min }}"@endif{{ isset($readOnly) && $readOnly? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>