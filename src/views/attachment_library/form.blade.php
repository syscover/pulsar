@extends('pulsar::layouts.form')

@section('rows')
    <!-- cms::categories.create -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => old('name', isset($object->id_352)? $object->id_352 : null),
        'readOnly' => true,
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_image_group', [
        'label' => trans_choice('pulsar::pulsar.language', 1),
        'name' => 'lang',
        'nameImage' => $lang->name_001,
        'value' => $lang->id_001,
        'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object->name_352)? $object->name_352 : null),
        'maxLength' => '50',
        'rangeLength' => '2,50',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.sorting'),
        'name' => 'sorting',
        'type' => 'number',
        'value' => old('sorting', isset($object->sorting_352)? $object->sorting_352 : null),
        'maxLength' => '3',
        'rangeLength' => '1,3',
        'min' => '0',
        'fieldSize' => 2
    ])
    <!-- /cms::categories.create -->
@stop