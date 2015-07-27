@extends('pulsar::layouts.form', ['action' => 'store'])

@section('script')
    @parent
    <!-- pulsar::email_account.create -->
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/bootstrap-inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- /pulsar::email_account.create -->
@stop

@section('rows')
    <!-- pulsar::email_account.create -->
    {!! $errors->first('error', config('pulsar.globalErrorDelimiters')) !!}
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true ])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.email'), 'name' => 'email', 'value' => Input::old('email'), 'maxLength' => '100', 'rangeLength' => '2,100', 'required' => true, 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.reply_to'), 'name' => 'replyTo', 'value' => Input::old('replyTo'), 'maxLength' => '100', 'rangeLength' => '2,100', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_section_header', ['label' => trans('pulsar::pulsar.outgoing_server'), 'icon' => 'icon-upload-alt'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.outgoing_server'), 'name' => 'outgoingServer', 'value' => Input::old('outgoingServer'), 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.user', 1), 'name' => 'outgoingUser', 'value' => Input::old('outgoingUser'), 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['type' => 'password', 'label' => trans_choice('pulsar::pulsar.password', 1), 'name' => 'outgoingPass', 'value' => Input::old('outgoingPass'), 'maxLength' => '100', 'rangeLength' => '2,100', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_select_group', ['label' => trans('pulsar::pulsar.incoming_secure'), 'name' => 'outgoingSecure', 'value' => Input::old('outgoingSecure'), 'objects' => $outgoingSecures, 'idSelect' => 'id', 'nameSelect' => 'name', 'class' => 'form-control', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.port'), 'name' => 'outgoingPort', 'value' => Input::old('outgoingPort'), 'fieldSize' => 2, 'data' => ['mask' => '9?99']])
    @include('pulsar::includes.html.form_section_header', ['label' => trans('pulsar::pulsar.incoming_server'), 'icon' => 'icon-download-alt'])
    @include('pulsar::includes.html.form_select_group', ['label' => trans('pulsar::pulsar.incoming_type'), 'name' => 'incomingType', 'value' => Input::old('incomingType'), 'objects' => $incomingTypes, 'idSelect' => 'id', 'nameSelect' => 'name', 'class' => 'form-control', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.incoming_server'), 'name' => 'incomingServer', 'value' => Input::old('incomingServer'), 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.user', 1), 'name' => 'incomingUser', 'value' => Input::old('incomingUser'), 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['type' => 'password', 'label' => trans_choice('pulsar::pulsar.password', 1), 'name' => 'incomingPass', 'value' => Input::old('incomingPass'), 'maxLength' => '100', 'rangeLength' => '2,100', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_select_group', ['label' => trans('pulsar::pulsar.incoming_secure'), 'name' => 'incomingSecure', 'value' => Input::old('incomingSecure'), 'objects' => $incomingSecures, 'idSelect' => 'id', 'nameSelect' => 'name', 'class' => 'form-control', 'fieldSize' => 5])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.port'), 'name' => 'incomingPort', 'value' => Input::old('incomingPort'), 'fieldSize' => 2, 'data' => ['mask' => '9?99']])
    <!-- /pulsar::email_account.create -->
@stop