<div class="form-group ace-editor-container">
    <label class="col-md-{{ $labelSize ?? 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize ?? 10 }}">
        <pre id="{{ $idEditor ?? 'aceEditor' }}" style="height: {{ $fieldHeight ?? '300' }}px">{!! isset($value)? $value : null !!}</pre>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>