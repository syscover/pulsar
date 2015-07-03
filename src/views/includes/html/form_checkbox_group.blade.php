<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }}</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <input class="uniform" type="checkbox" name="{{ $name }}" value="{{ $value }}"{{ isset($disabled)? ' disabled' : null }}{{ isset($isChecked) && $isChecked? ' checked' : null }}>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
    @if(isset($inputs) && is_array($inputs))
        @foreach($inputs as $input)
            @if(isset($input['label']))
                <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $input['label'] }} @if(isset($input['required']) && $input['required']) @include('pulsar::includes.html.required') @endif</label>
            @endif
            <div class="col-md-{{  $input['fieldSize'] or 10 }}">
                <input class="uniform" type="checkbox" name="{{ $input['name'] }}" value="{{ $input['value'] }}"{{ isset($input['disabled'])? ' disabled' : null }}{{ isset($input['isChecked']) && $input['isChecked']? ' checked' : null }}>
                {!! $errors->first($input['name'], config('pulsar.errorDelimiters')) !!}
            </div>
        @endforeach
    @endif
</div>