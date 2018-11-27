<div class="form-group">
    <label class="col-md-{{ $labelSize ?? 2 }} control-label">{{ $label }}</label>
    <div class="col-md-{{ $fieldSize ?? 10 }}">
        <div class="container-overflow-div-group">
            <ul>
                @if(is_array($value))
                    @foreach($value as $element)
                        <li>{!! $element !!}</li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>