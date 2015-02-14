<div class="form-group">
    <label class="col-md-2 control-label">{{ $label }} @if(isset($required)) @include('pulsar::common.block.block_required') @endif</label>
    <div class="col-md-2">
        <select class="form-control{{ isset($required)? ' required' : null }}" name="{{ $name }}"{{ isset($required)? ' notequal="null"' : null }}>
            <option value="null">{{ trans('pulsar::pulsar.choose_a') . ' ' .  $label }}</option>
            @foreach ($objects as $object)
                <option value="{{ $object->{$idSelect} }}"{{ $value === $object->{$idSelect}? ' selected' : null }}>{{ $object->{$nameSelect} }}</option>
            @endforeach
        </select>
        {{ $errors->first($name, config('pulsar.errorDelimiters')) }}
    </div>
</div>