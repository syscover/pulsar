@extends('pulsar::layouts.form', ['enctype' => true])

@section('head')
    @parent
    @include('pulsar::includes.js.delete_image')
    <script>
        $(document).ready(function() {
            $("form").submit(function() {
                if($('input[name="base"]').is(':checked')) $('input[name="base"]').attr("disabled", false);
            });
        });
    </script>
@stop

@section('rows')
    <!-- pulsar::lang.create -->
    @include('pulsar::includes.html.form_text_group', [
        'fieldSize' => 2,
        'label' => 'ID',
        'name' => 'id',
        'value' => old('id', isset($object)? $object->id_001 : null),
        'maxLength' => '2',
        'size' => '2',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object)? $object->name_001 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.sorting'),
        'name' => 'sorting',
        'type' => 'number',
        'value' => old('sorting', isset($object)? $object->sorting_001 : null),
        'maxLength' => '3',
        'rangeLength' => '1,3',
        'min' => '0',
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_checkbox_group', [
        'label' => trans('pulsar::pulsar.base_language'),
        'name' => 'base',
        'value' => 1,
        'checked' => old('base', isset($object)? $object->base_001 : false)
    ])
    @include('pulsar::includes.html.form_file_image_group', [
        'fieldSize' => 5,
        'label' => trans_choice('pulsar::pulsar.image', 1),
        'name' => 'image',
        'value' => old('image', isset($object)? $object->image_001 : null),
        'dirname' => '/packages/syscover/pulsar/storage/langs/',
        'urlDelete' => isset($object)? route('deleteImageLang', $object->id_001) : null
    ])
    @include('pulsar::includes.html.form_checkbox_group', [
        'label' => trans('pulsar::pulsar.active'),
        'name' => 'active',
        'value' => 1,
        'checked' => old('active', isset($object)? $object->active_001 : true)
    ])
    <!-- /pulsar::lang.create -->
@stop