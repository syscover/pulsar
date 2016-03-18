@extends('pulsar::layouts.form', ['action' => 'update'])

@section('head')
    @parent
    <!-- pulsar::email_account.eidt -->
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/bootstrap-inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- pulsar::email_account.create -->
@stop

@section('rows')
    <!-- pulsar::email_account.create -->
    {!! $errors->first('error', config('pulsar.globalErrorDelimiters')) !!}
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'value' => $object->id_013, 'name' => 'id', 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_013, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true ])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.email'), 'name' => 'email', 'value' => $object->email_013, 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true, 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.reply_to'), 'name' => 'replyTo', 'value' => $object->reply_to_013, 'maxLength' => '100', 'rangeLength' => '2,100', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_section_header', ['label' => trans('pulsar::pulsar.outgoing_server'), 'icon' => 'icon-upload-alt'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.outgoing_server'), 'name' => 'outgoingServer', 'value' => $object->outgoing_server_013, 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.user', 1), 'name' => 'outgoingUser', 'value' => $object->outgoing_user_013, 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['type' => 'password', 'label' => trans_choice('pulsar::pulsar.password', 1), 'name' => 'outgoingPass', 'value' => old('outgoingPass'), 'maxLength' => '100', 'rangeLength' => '2,100', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_select_group', ['label' => trans('pulsar::pulsar.outgoing_secure'), 'name' => 'outgoingSecure', 'value' => $object->outgoing_secure_013, 'objects' => $outgoingSecures, 'idSelect' => 'id', 'nameSelect' => 'name', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.port'), 'name' => 'outgoingPort', 'value' => $object->outgoing_port_013, 'fieldSize' => 2, 'data' => ['mask' => '9?99']])
    @include('pulsar::includes.html.form_section_header', ['label' => trans('pulsar::pulsar.incoming_server'), 'icon' => 'icon-download-alt'])
    @include('pulsar::includes.html.form_select_group', ['label' => trans('pulsar::pulsar.incoming_type'), 'name' => 'incomingType', 'value' => $object->incoming_type_013, 'objects' => $incomingTypes, 'idSelect' => 'id', 'nameSelect' => 'name', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.incoming_server'), 'name' => 'incomingServer', 'value' => $object->incoming_server_013, 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.user', 1), 'name' => 'incomingUser', 'value' => $object->incoming_user_013, 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['type' => 'password', 'label' => trans_choice('pulsar::pulsar.password', 1), 'name' => 'incomingPass', 'value' => old('incomingPass'), 'maxLength' => '100', 'rangeLength' => '2,100', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_select_group', ['label' => trans('pulsar::pulsar.incoming_secure'), 'name' => 'incomingSecure', 'value' => $object->incoming_secure_013, 'objects' => $incomingSecures, 'idSelect' => 'id', 'nameSelect' => 'name', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.port'), 'name' => 'incomingPort', 'value' => $object->incoming_port_013, 'fieldSize' => 2, 'data' => ['mask' => '9?99']])
    <!-- /pulsar::email_account.create -->
@stop