<div @if(isset($containerId)) id="{{ $containerId }}"@endif class="form-group">
    <label{!! isset($labelId)? ' id="' . $labelId . '"' : null !!} class="col-md-{{ $labelSize or 2 }} control-label">{{ isset($label)? $label : null }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <select{!! isset($id)? ' id="' . $id . '" ' : null !!} class="form-control {{ $class or null }}" {!! isset($style)? ' style="' . $style . '" ' : null !!}name="{{ $name }}"{{ isset($multiple)? ' multiple' : null }}{{ isset($required) && $required? ' required' : null }}{{ isset($disabled) && $disabled? ' disabled' : null }}{!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!}>
            @if(!isset($multiple))<option value="">{{ trans('pulsar::pulsar.select_a') }} {{ isset($label)? $label : null }}</option>@endif
            @if(isset($objects))
                @foreach ($objects as $object)
                    @if(is_array($object))
                        <option value="{{ $object[$idSelect] }}"{{ Miscellaneous::isSelected($value, $object[$idSelect])? ' selected' : null }}{!! isset($dataOption)? Miscellaneous::setDataOptionAttributes($dataOption, $object) : null !!}>{{ $object[$nameSelect] }}</option>
                    @else
                        <option value="{{ $object->{$idSelect} }}"{{ Miscellaneous::isSelected($value, $object->{$idSelect})? ' selected' : null }}{!! isset($dataOption)? Miscellaneous::setDataOptionAttributes($dataOption, $object) : null !!}>{{ $object->{$nameSelect} }}</option>
                    @endif
                @endforeach
            @endif
        </select>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>