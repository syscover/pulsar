<!-- pulsar::includes.html.form_record_footer -->
    <div class="form-actions">
        @if(isset($beforeButtonFooter))
            {!! $beforeButtonFooter !!}
        @endif
        @if($action != 'show')
            <button type="submit" class="btn margin-r10">{{ trans('pulsar::pulsar.save') }}</button>
        @endif
        @if($viewParameters['cancelButton'])
            <a id="cancel" class="btn btn-inverse" href="{{ route($routeSuffix, $urlParameters) }}">{{ trans('pulsar::pulsar.cancel') }}</a>
        @endif
        @if($action == 'update' && isset($lang) && $lang->id_001 != session('baseLang')->id_001)
            <a class="btn btn-danger margin-l10 delete-lang-record" data-delete-url="{{ route('deleteTranslation' . ucfirst($routeSuffix), $urlParameters) }}">{{ trans('pulsar::pulsar.delete_translation') }}</a>
        @endif
        @if(isset($afterButtonFooter))
            {!! $afterButtonFooter !!}
        @endif
    </div>
</form>
<!-- /.pulsar::includes.html.form_record_footer -->