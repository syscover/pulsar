<!-- pulsar::includes.html.form_file_image_group -->
<div class="form-group">
    <label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required)) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-{{ $fieldSize or 10 }}">
        <!-- If the file has logo field is hidden from JavasScript -->
        <div id="inputFile"@if(isset($value)) style="display: none"@endif>
            <input type="file" data-style="fileinput" name="image" accept="image/*"{{ isset($required)? ' required' : null }}>
            <label for="{{ $name }}" class="has-error help-block" generated="true" style="display:none;"></label>
        </div>
        @if(isset($value))
            <div id="inputImage">
                <!-- implement magic popup -->
                <a href="{{ asset($dirname . $value) }}" title="{{ $value }}" target="_blank"><span title="{{ trans('pulsar::pulsar.show_file') }}" class="icon24 brocco-icon-picture tip ico-image"></span></a>
                <a href="javascript:deleteImage('{{ $urlDelete }}')"><span title="{{ trans('pulsar::pulsar.delete_image') }}" class="icon16 icomoon-icon-remove tip"></span></a>
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            </div>
        @endif
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>
<!-- /.pulsar::includes.html.form_file_image_group -->