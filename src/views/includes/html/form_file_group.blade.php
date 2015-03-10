<!-- pulsar::includes.html.form_file_group -->
<div class="form-group">
    <label class="col-md-2 control-label">{{ $label }} @if(isset($required)) @include('pulsar::includes.html.required') @endif</label>
    <div class="col-md-10">
        <!-- If the file has logo field is hidden from JavasScript -->
        <div id="inputFile"@if(isset($value)) style="display: none"@endif>
            <input{{ isset($required)? ' class="required"' : null }} type="file" data-style="fileinput" name="image" accept="image/*">
            <label for="{{ $name }}" class="has-error help-block" generated="true" style="display:none;"></label>
        </div>
        @if(isset($value))
        <div id="inputImage">
            <a href="{{ asset($dirname . $value) }}" title="{{ $value }}" class="lightbox"><span title="{{ trans('pulsar::pulsar.show_file') }}" class="icon24 brocco-icon-picture tip ico-image"></span></a>
            <a href="javascript:deleteImage('{{ $urlDelete }}')"><span title="{{ trans('pulsar::pulsar.borrar_imagen') }}" class="icon16 icomoon-icon-remove tip"></span></a>
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        </div>
        @endif
        {!! $errors->first($name, config('pulsar.errorDelimiters')) !!}
    </div>
</div>
<!-- /pulsar::includes.html.form_file_group -->