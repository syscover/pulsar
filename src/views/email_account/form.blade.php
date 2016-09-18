@extends('pulsar::layouts.form')

@section('head')
    @parent
    <!-- pulsar::email_account.form -->
    <script src="{{ asset('packages/syscover/pulsar/plugins/bootstrap-inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- /pulsar::email_account.form -->
@stop

@section('rows')
    <!-- pulsar::email_account.form -->
    {!! $errors->first('error', config('pulsar.globalErrorDelimiters')) !!}
    @include('pulsar::includes.html.form_text_group', [
        'label' => 'ID',
        'name' => 'id',
        'value' => isset($object->id_013)? $object->id_013: null,
        'readOnly' => true,
        'fieldSize' => 2
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.name'),
        'name' => 'name',
        'value' => old('name', isset($object->name_013)? $object->name_013: null),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.email'),
        'name' => 'email',
        'value' => old('email', isset($object->email_013)? $object->email_013: null),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'required' => true,
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.reply_to'),
        'name' => 'replyTo',
        'value' => old('replyTo', isset($object->reply_to_013)? $object->reply_to_013: null),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_section_header', [
        'label' => trans('pulsar::pulsar.outgoing_server'),
        'icon' => 'fa fa-upload'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.outgoing_server'),
        'name' => 'outgoingServer',
        'value' => old('outgoingServer', isset($object->outgoing_server_013)? $object->outgoing_server_013: null),
        'maxLength' => '100',
        'rangeLength' => '2,100'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.user', 1),
        'name' => 'outgoingUser',
        'value' => old('outgoingUser', isset($object->outgoing_user_013)? $object->outgoing_user_013: null),
        'maxLength' => '100',
        'rangeLength' => '2,100'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'type' => 'password',
        'label' => trans_choice('pulsar::pulsar.password', 1),
        'name' => 'outgoingPass',
        'value' => old('outgoingPass'),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans('pulsar::pulsar.outgoing_secure'),
        'name' => 'outgoingSecure',
        'value' => old('outgoingSecure', isset($object->outgoing_secure_013)? $object->outgoing_secure_013: null),
        'objects' => $outgoingSecures,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.port'),
        'name' => 'outgoingPort',
        'value' => old('outgoingPort', isset($object->outgoing_port_013)? $object->outgoing_port_013: null),
        'fieldSize' => 2,
        'data' => [
            'mask' => '9?99'
        ]
    ])
    @include('pulsar::includes.html.form_section_header', [
        'label' => trans('pulsar::pulsar.incoming_server'),
        'icon' => 'fa fa-download'
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans('pulsar::pulsar.incoming_type'),
        'name' => 'incomingType',
        'value' => old('incomingType', isset($object->incoming_type_013)? $object->incoming_type_013: null),
        'objects' => $incomingTypes,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.incoming_server'),
        'name' => 'incomingServer',
        'value' => old('incomingServer', isset($object->incoming_server_013)? $object->incoming_server_013: null),
        'maxLength' => '100',
        'rangeLength' => '2,100'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.user', 1),
        'name' => 'incomingUser',
        'value' => old('incomingUser', isset($object->incoming_user_013)? $object->incoming_user_013: null),
        'maxLength' => '100',
        'rangeLength' => '2,100'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'type' => 'password',
        'label' => trans_choice('pulsar::pulsar.password', 1),
        'name' => 'incomingPass',
        'value' => old('incomingPass'),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans('pulsar::pulsar.incoming_secure'),
        'name' => 'incomingSecure',
        'value' => old('incomingSecure', isset($object->incoming_secure_013)? $object->incoming_secure_013: null),
        'objects' => $incomingSecures,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.port'),
        'name' => 'incomingPort',
        'value' => old('incomingPort', isset($object->incoming_port_013)? $object->incoming_port_013: null),
        'fieldSize' => 2,
        'data' => [
            'mask' => '9?99'
        ]
    ])
    <!-- /pulsar::email_account.form -->
@stop