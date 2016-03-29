<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 8 }}">
        <input class="form-control" type="{{ isset($type)? $type : 'text' }}" name="{{ $name }}" value="{{ isset($value)? $value : null }}"{!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!} @if(isset($placeholder)) placeholder="{{ $placeholder }}"@endif @if(isset($maxLength)) maxlength="{{ $maxLength }}"@endif @if(isset($rangeLength))rangelength="{{ $rangeLength }}"@endif @if(isset($min)) min="{{ $min }}"@endif{{ isset($readOnly) && $readOnly? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>
        {!! $errors->first($name.'id', config('pulsar.errorDelimiters')) !!}
    </div>
    <div class="col-md-{{ $iconSize or 2 }}">
        <a href="{{ $modalUrl }}" class="magnific-popup"><span class="fa fa-plus"></span></a>
        <input type="hidden" name="{{ $name.'id' }}" value="{{ isset($valueId)? $valueId : null }}"{{ isset($required) && $required? ' required' : null }}>
    </div>
</div>