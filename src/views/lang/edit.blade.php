@extends('pulsar::layouts.form', ['action' => 'update', 'enctype' => true])

@section('script')
    @parent
    @include('pulsar::includes.js.delete_image')
    <script type="text/javascript">
        $(document).ready(function() {
            $("form").submit(function() {
                if($('input[name="base"]').is(':checked')) $('input[name="base"]').attr("disabled", false);
            });
        });
    </script>
@stop

@section('rows')
    <!-- pulsar::langs.crete -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_001, 'maxLength' => '2', 'rangeLength' => '2,2', 'required' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_001, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.sorting'), 'name' => 'sorting', 'value' => $object->sorting_001, 'maxLength' => '3', 'rangeLength' => '1,3', 'min' => '0', 'fieldSize' => 2])
    @include('pulsar::includes.html.form_checkbox_group', ['label' => trans('pulsar::pulsar.base_language'), 'name' => 'base', 'value' => 1, 'checked' => $object->base_001])
    @include('pulsar::includes.html.form_file_image_group',
    [
        'label' => trans_choice('pulsar::pulsar.image', 1),
        'name' => 'image',
        'value' =>  $object->image_001,
        'dirname' => '/packages/syscover/pulsar/storage/langs/',
        'urlDelete' => route('deleteImageLang', $object->id_001)])

    @include('pulsar::includes.html.form_checkbox_group', ['label' => trans('pulsar::pulsar.active'), 'name' => 'active', 'value' => 1, 'checked' => $object->active_001])
    <!-- /pulsar::langs.crete -->
@stop