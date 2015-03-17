<!-- pulsar::includes.html.form_record_footer -->
    <div class="form-actions">
        <button type="submit" class="btn marginR10">{{ trans('pulsar::pulsar.save') }}</button>
        @if(!isset($cancelButton) || isset($cancelButton) && $cancelButton)
        <a id="cancel" class="btn btn-inverse" href="{{ route($routeSuffix, $urlParameters) }}">{{ trans('pulsar::pulsar.cancel') }}</a>
        @endif
        @if($action != 'store' && isset($lang) && $lang->id_001 != Session::get('baseLang')->id_001)
            <a class="btn btn-danger marginL10 delete-lang-record" data-delete-url="{{ route('deleteTranslation' . $routeSuffix, $urlParameters) }}">{{ trans('pulsar::pulsar.delete_translation') }}</a>
        @endif
    </div>
</form>
<!-- /pulsar::includes.html.form_record_footer -->