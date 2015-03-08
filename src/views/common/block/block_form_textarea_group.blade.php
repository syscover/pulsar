<div class="form-group">
    <label class="col-md-{{ $sizeLabel or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::common.block.block_required') @endif</label>
    <div class="col-md-{{ $sizeField or 10 }}">
        <textarea class="form-control{{ isset($required) && $required? ' required' : null }}" name="{{ $name }}" @if(isset($rows))rows="{{ $rows }}"@endif @if(isset($cols)) cols="{{ $cols }}"@endif{{ isset($readOnly) && $readOnly? ' readonly' : null }}>{{ isset($value)? $value : null }}</textarea>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>