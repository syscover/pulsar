<div class="form-group ace-editor-container">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <pre id="{{ $idEditor or 'aceEditor' }}" style="height: {{ $fieldHeight or '300' }}px">{!! isset($value)? $value : null !!}</pre>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>