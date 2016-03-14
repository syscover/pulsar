@extends('pulsar::layouts.form'])

@section('rows')
    <!-- pulsar::users.create -->
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'readOnly' => true,
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name'),
        'maxLength' => '50',
        'rangeLength' => '2,50',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.surname'), 'name' => 'surname', 'value' => old('surname'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.email'), 'name' => 'email', 'value' => old('email'), 'maxLength' => '50', 'rangeLength' => '2,50', 'fieldSize' => 6, 'required' => true, 'type' => 'email'])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'fileSize' => 5, 'name' => 'lang', 'value' => old('lang'), 'required' => true, 'objects' => $langs, 'idSelect' => 'id_001', 'nameSelect' => 'name_001'])
    @include('pulsar::includes.html.form_checkbox_group', ['label' => trans('pulsar::pulsar.access'), 'name' => 'access', 'value' => 1, 'checked' => old('access', 1)])
    @include('pulsar::includes.html.form_section_header', ['label' => trans('pulsar::pulsar.data_access'), 'icon' => 'icomoon-icon-users'])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.profile', 1), 'fileSize' => 5,'name' => 'profile', 'value' => old('profile'), 'required' => true, 'objects' => $profiles, 'idSelect' => 'id_006', 'nameSelect' => 'name_006'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.user', 1), 'name' => 'user', 'value' => old('user'), 'maxLength' => '50', 'rangeLength' => '2,50', 'fieldSize' => 6, 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.password'), 'type' => 'password', 'name' => 'password', 'value' => old('password'), 'maxLength' => '50', 'rangeLength' => '4,50', 'fieldSize' => 6, 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.repeat_password'), 'type' => 'password', 'name' => 'repassword', 'value' => old('repassword'), 'maxLength' => '50', 'rangeLength' => '4,50', 'fieldSize' => 6, 'required' => true])
    <!-- ./pulsar::users.create -->
@stop