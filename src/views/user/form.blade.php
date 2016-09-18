@extends('pulsar::layouts.form')

@section('rows')
    <!-- pulsar::users.create -->
    @include('pulsar::includes.html.form_text_group', [
        'fieldSize' => 2,
        'label' => 'ID',
        'name' => 'id',
        'value' => old('id', isset($object)? $object->id_010 : null),
        'readOnly' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object)? $object->name_010 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.surname'),
        'name' => 'surname',
        'value' => old('surname', isset($object)? $object->surname_010 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'fieldSize' => 6,
        'label' => trans('pulsar::pulsar.email'),
        'name' => 'email',
        'value' => old('email', isset($object)? $object->email_010 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true,
        'type' => 'email'
    ])
    @include('pulsar::includes.html.form_select_group', [
        'fieldSize' => 4,
        'label' => trans_choice('pulsar::pulsar.language', 1),
        'name' => 'lang',
        'value' => old('lang', isset($object)? $object->lang_id_010 : null),
        'required' => true,
        'objects' => $langs,
        'idSelect' => 'id_001',
        'nameSelect' => 'name_001'
    ])
    @include('pulsar::includes.html.form_checkbox_group', [
        'label' => trans('pulsar::pulsar.access'),
        'name' => 'access',
        'value' => 1,
        'checked' => old('access', isset($object)? $object->access_010 : true)
    ])
    @include('pulsar::includes.html.form_section_header', [
        'label' => trans('pulsar::pulsar.data_access'),
        'icon' => 'icomoon-icon-users'
    ])
    @include('pulsar::includes.html.form_select_group', [
        'fieldSize' => 4,
        'label' => trans_choice('pulsar::pulsar.profile', 1),
        'name' => 'profile',
        'value' => old('profile', isset($object)? $object->profile_id_010 : null),
        'required' => true,
        'objects' => $profiles,
        'idSelect' => 'id_006',
        'nameSelect' => 'name_006'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'fieldSize' => 6,
        'label' => trans_choice('pulsar::pulsar.user', 1),
        'name' => 'user',
        'value' => old('user', isset($object)? $object->user_010 : null),
        'maxLength' => '255',
        'rangeLength' => '2,255',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'fieldSize' => 6,
        'label' => trans('pulsar::pulsar.password'),
        'type' => 'password',
        'name' => 'password',
        'value' => old('password'),
        'maxLength' => '50',
        'rangeLength' => '4,50',
        'required' =>  ! isset($object)
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.repeat_password'),
        'type' => 'password',
        'name' => 'repassword',
        'value' => old('repassword'),
        'maxLength' => '50',
        'rangeLength' => '4,50',
        'fieldSize' => 6,
        'required' => ! isset($object)
    ])
    <!-- /pulsar::users.create -->
@stop