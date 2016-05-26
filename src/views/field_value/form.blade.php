@extends('pulsar::layouts.form')

@section('head')
    @parent
    @include('pulsar::includes.js.delete_translation_record')
@stop

@section('head')
    @parent
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(){
                $('input').prop('readonly', false)
                $('input').prop('disabled', false)
                $('select').prop('disabled', false)
            })

            @if($action == 'store')
                $('[name=id]').prop('readonly', true)
                $('[name=assignId]').on('change', function(){
                    if($('[name=assignId]').is(":checked"))
                    {
                        $('[name=id]').prop('readonly', false)
                    }
                    else
                    {
                        $('[name=id]').prop('readonly', true)
                    }
                })
            @endif
        });
    </script>
@stop

@section('rows')
    <!-- pulsar::field_value.create -->
    <div class="row">
        <div class="col-md-6">
            @include('pulsar::includes.html.form_text_group', [
                'labelSize' => 4,
                'fieldSize' => 4,
                'label' => 'ID',
                'name' => 'id',
                'value' => old('id', isset($object->id_027)? $object->id_027 : null),
                'readOnly' => $action == 'update' || $action == 'store'? false : true
            ])
        </div>
        <div class="col-md-6">
            @if($action == 'store')
                @include('pulsar::includes.html.form_checkbox_group', [
                    'labelSize' => 4,
                    'fieldSize' => 4,
                    'label' => trans('pulsar::pulsar.assign_id'),
                    'name' => 'assignId',
                    'value' => 1
                ])
            @endif
        </div>
    </div>
    @include('pulsar::includes.html.form_image_group', [
        'label' => trans_choice('pulsar::pulsar.language', 1),
        'name' => 'lang',
        'nameImage' => $lang->name_001,
        'value' => $lang->id_001,
        'url' => asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001)
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.name', 1),
        'name' => 'name',
        'value' => old('name', isset($object->name_027)? $object->name_027 : null),
        'maxLength' => '255',
        'rangeLength' => '1,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.sorting'),
        'name' => 'sorting',
        'type' => 'number',
        'value' => old('sorting', isset($object->sorting_027)? $object->sorting_027 : null),
        'maxLength' => '3',
        'rangeLength' => '1,3',
        'min' => '0',
        'fieldSize' => 2,
        'readOnly' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_checkbox_group', [
        'label' => trans('pulsar::pulsar.featured'),
        'name' => 'featured',
        'value' => 1,
        'checked' => old('featured',  isset($object)? $object->featured_027 : null),
        'disabled' => $action == 'update' || $action == 'store'? false : true
    ])
    @include('pulsar::includes.html.form_hidden', [
        'name' => 'field',
        'value' => $fieldObject->id_026
    ])
    @include('pulsar::includes.html.form_hidden', [
        'name' => 'action',
        'value' => $action
    ])
    <!-- /.pulsar::field_value.create -->
@stop