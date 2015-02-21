@extends('pulsar::layouts.form', ['action' => 'update'])

@section('rows')
    <!-- pulsar::users.edit -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_010, 'readOnly' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_010, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.surname'), 'name' => 'surname', 'value' => $object->surname_010, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.email'), 'name' => 'email', 'value' => $object->email_010, 'maxLength' => '50', 'rangeLength' => '2,50', 'sizeField' => 6, 'required' => true])
    @include('pulsar::common.block.block_form_select_group', ['label' => trans_choice('pulsar::pulsar.language', 1), 'name' => 'lang', 'value' => $object->lang_010, 'required' => true, 'objects' => $langs, 'idSelect' => 'id_001', 'nameSelect' => 'name_001'])
    @include('pulsar::common.block.block_form_checkbox_group', ['label' => trans('pulsar::pulsar.access'), 'name' => 'access', 'value' => 1, 'isChecked' => $object->access_010])
    @include('pulsar::common.block.block_form_section_header', ['label' => trans('pulsar::pulsar.data_access'), 'icon' => 'icomoon-icon-users'])
    @include('pulsar::common.block.block_form_select_group', ['label' => trans_choice('pulsar::pulsar.profile', 1), 'name' => 'profile', 'value' => $object->profile_010, 'required' => true, 'objects' => $profiles, 'idSelect' => 'id_006', 'nameSelect' => 'name_006'])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans_choice('pulsar::pulsar.user', 1), 'name' => 'user', 'value' => $object->user_010, 'maxLength' => '50', 'rangeLength' => '2,50', 'sizeField' => 6, 'required' => true])
    @include('pulsar::common.block.block_form_password_group', ['label' => trans('pulsar::pulsar.password'), 'name' => 'password', 'maxLength' => '50', 'rangeLength' => '4,50', 'sizeField' => 6, 'required' => false])
    @include('pulsar::common.block.block_form_password_group', ['label' => trans('pulsar::pulsar.repeat_password'), 'name' => 'repassword', 'maxLength' => '50', 'rangeLength' => '4,50', 'sizeField' => 6, 'required' => false])
    <!-- /pulsar::users.edit -->
@stop