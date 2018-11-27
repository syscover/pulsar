<div class="form-group contentbuilder-container">
    <label class="col-md-{{ $labelSize ?? 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>
    <div class="col-md-{{ $fieldSize ?? 10 }}">
        <iframe src="{{ route('contentbuilder', ['input' => $name, 'package' => $package, 'theme' => $theme]) }}" class="col-xs-12 col-md-12 iframe-contentbuilder"></iframe>
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>