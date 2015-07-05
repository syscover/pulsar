<div @if(isset($containerId)) id="{{ $containerId }}"@endif class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <input class="form-control{{ isset($class)? ' ' . $class : null }}" type="{{ isset($type)? $type : 'text' }}" name="{{ $name }}" value="{{ isset($value)? $value : null }}"{!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!} @if(isset($placeholder)) placeholder="{{ $placeholder }}"@endif @if(isset($maxLength)) maxlength="{{ $maxLength }}"@endif @if(isset($rangeLength))rangelength="{{ $rangeLength }}"@endif @if(isset($min)) min="{{ $min }}"@endif{{ isset($readOnly) && $readOnly? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
    @if(isset($inputs) && is_array($inputs))
        @foreach($inputs as $input)
            @if(isset($input['label']))
            <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $input['label'] }} @if(isset($input['required']) && $input['required']) @include('pulsar::includes.html.required') @endif</label>
            @endif
            <div class="col-md-{{  $input['fieldSize'] or 10 }}">
                <input class="form-control" type="{{ isset($input['type'])? $input['type'] : 'text' }}" name="{{ $input['name'] }}" value="{{ isset($input['value'])? $input['value'] : null }}"{!! isset($input['data'])? Miscellaneous::setDataAttributes($input['data']) : null !!} @if(isset($input['placeholder'])) placeholder="{{ $input['placeholder'] }}"@endif @if(isset($input['maxLength'])) maxlength="{{ $input['maxLength'] }}"@endif @if(isset($input['rangeLength']))rangelength="{{ $input['rangeLength'] }}"@endif @if(isset($input['min'])) min="{{ $input['min'] }}"@endif{{ isset($input['readOnly']) && $input['readOnly']? ' readonly' : null }}{{ isset($input['required']) && $input['required']? ' required' : null }}>
                {!! $errors->first($input['name'], config('pulsar.errorDelimiters')) !!}
            </div>
        @endforeach
    @endif
</div>