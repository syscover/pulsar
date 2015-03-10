@extends('pulsar::layouts.form', ['action' => 'store'])

@section('rows')
    <!-- pulsar::users.create -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.surname'), 'name' => 'surname', 'value' => Input::old('surname'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.email'), 'name' => 'email', 'value' => Input::old('email'), 'maxLength' => '50', 'rangeLength' => '2,50', 'fieldSize' => 6, 'required' => true])
    @include('pulsar::common.block.block_form_select_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'name' => 'lang', 'value' => Input::old('lang'), 'required' => true, 'objects' => $langs, 'idSelect' => 'id_001', 'nameSelect' => 'name_001'])
    @include('pulsar::common.block.block_form_checkbox_group', ['label' => trans('pulsar::pulsar.access'), 'name' => 'access', 'value' => 1, 'isChecked' => Input::old('access', 1)])
    @include('pulsar::common.block.block_form_section_header', ['label' => trans('pulsar::pulsar.data_access'), 'icon' => 'icomoon-icon-users'])
    @include('pulsar::common.block.block_form_select_group', ['label' => trans_choice('pulsar::pulsar.profile', 1), 'name' => 'profile', 'value' => Input::old('profile'), 'required' => true, 'objects' => $profiles, 'idSelect' => 'id_006', 'nameSelect' => 'name_006'])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans_choice('pulsar::pulsar.user', 1), 'name' => 'user', 'value' => Input::old('user'), 'maxLength' => '50', 'rangeLength' => '2,50', 'fieldSize' => 6, 'required' => true])
    @include('pulsar::common.block.block_form_password_group', ['label' => trans('pulsar::pulsar.password'), 'name' => 'password', 'value' => Input::old('password'), 'maxLength' => '50', 'rangeLength' => '4,50', 'fieldSize' => 6, 'required' => true])
    @include('pulsar::common.block.block_form_password_group', ['label' => trans('pulsar::pulsar.repeat_password'), 'name' => 'repassword', 'value' => Input::old('repassword'), 'maxLength' => '50', 'rangeLength' => '4,50', 'fieldSize' => 6, 'required' => true])
    <!-- /pulsar::users.create -->
@stop