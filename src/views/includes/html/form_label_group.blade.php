<div @if(isset($containerId)) id="{{ $containerId }}"@endif class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">{{ $value or null }}</div>
    @if(isset($inputs) && is_array($inputs))
        @foreach($inputs as $input)
            @if(isset($input['label']))
            <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $input['label'] }} @if(isset($input['required']) && $input['required']) @include('pulsar::elements.required') @endif</label>
            @endif
            <div class="col-md-{{ $input['fieldSize'] or 10 }}">{{ $input['value'] or null }}</div>
        @endforeach
    @endif
</div>