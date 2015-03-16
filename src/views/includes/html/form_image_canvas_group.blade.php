<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }}</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <div class="dropzone" {!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!}>
            <input type="file" name="{{ $name }}"{{ isset($required) && $required? ' required' : null }}>
            <span class="html5-label">Pulsa o suelta aquí la imagen que desear cargar</span>
        </div>
    </div>
</div>