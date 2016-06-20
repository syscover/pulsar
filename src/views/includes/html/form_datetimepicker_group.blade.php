<div @if(isset($containerId)) id="{{ $containerId }}"@endif class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <div class="input-group date datetimepicker error-placement-after"@if(isset($id)) id="{{ $id }}"@endif{!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!}>
            <input type='text' class="form-control" name="{{ $name }}" data-error-placement-after='datetimepicker' value="{{ isset($value)? $value : null }}" @if(isset($placeholder)) placeholder="{{ $placeholder }}"@endif{{ isset($readOnly) && $readOnly? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
    @if(isset($inputs) && is_array($inputs))
        @foreach($inputs as $input)
            @if(isset($input['label']))
                <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $input['label'] }} @if(isset($input['required']) && $input['required']) @include('pulsar::includes.html.required') @endif</label>
            @endif
            <div class="col-md-{{ $input['fieldSize'] or 10 }}">
                <input class="form-control" type="{{ $input['type'] or 'text' }}" name="{{ $input['name'] }}" value="{{ $input['value'] or null }}"{!! isset($input['data'])? Miscellaneous::setDataAttributes($input['data']) : null !!} @if(isset($input['placeholder'])) placeholder="{{ $input['placeholder'] }}"@endif @if(isset($input['maxLength'])) maxlength="{{ $input['maxLength'] }}"@endif @if(isset($input['rangeLength']))rangelength="{{ $input['rangeLength'] }}"@endif @if(isset($input['min'])) min="{{ $input['min'] }}"@endif @if(isset($max)) max="{{ $max }}"@endif{{ isset($input['readOnly']) && $input['readOnly']? ' readonly' : null }}{{ isset($input['required']) && $input['required']? ' required' : null }}>
                {!! $errors->first($input['name'], config('pulsar.errorDelimiters')) !!}
            </div>
        @endforeach
    @endif
</div>