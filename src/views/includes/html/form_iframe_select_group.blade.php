<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 8 }}">
        <input class="form-control" type="{{ isset($type)? $type : 'text' }}" name="{{ $name }}" value="{{ isset($value)? $value : null }}"{!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!} @if(isset($placeholder)) placeholder="{{ $placeholder }}"@endif @if(isset($maxLength)) maxlength="{{ $maxLength }}"@endif @if(isset($rangeLength))rangelength="{{ $rangeLength }}"@endif @if(isset($min)) min="{{ $min }}"@endif{{ isset($readOnly) && $readOnly || ! isset($readOnly)? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>
        {!! $errors->first($name . 'Id', config('pulsar.errorDelimiters')) !!}
    </div>
    <div class="col-md-{{ $iconSize or 2 }}">
        @if(! isset($disabled) ||  isset($disabled) && ! $disabled)
            <a href="{{ $modalUrl }}" class="magnific-popup"><span class="black-text fa-2x fa fa-external-link"></span></a>
        @endif
        <input type="hidden" name="{{ $name . 'Id' }}" value="{{ isset($valueId)? $valueId : null }}"{{ isset($required) && $required? ' required' : null }}>
    </div>
</div>