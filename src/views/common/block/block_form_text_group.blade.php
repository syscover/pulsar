<div class="form-group">
    <label class="col-md-{{ $sizeLabel or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::common.block.block_required') @endif</label>
    <div class="col-md-{{ $sizeField or 10 }}">
        <input class="form-control{{ isset($required) && $required? ' required' : null }}" type="text" name="{{ $name }}" value="{{ isset($value)? $value : null }}" @if(isset($maxLength)) maxlength="{{ $maxLength }}"@endif @if(isset($rangeLength))rangelength="{{ $rangeLength }}"@endif @if(isset($min)) min="{{ $min }}"@endif{{ isset($readOnly) && $readOnly? ' readonly' : null }}>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>