@extends('pulsar::layouts.form')

@section('head')
    @parent
    <!-- pulsar::email_account.form -->
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/bootstrap-inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- /pulsar::email_account.form -->
@stop

@section('rows')
    <!-- pulsar::email_account.form -->
    {!! $errors->first('error', config('pulsar.globalErrorDelimiters')) !!}
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
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'required' => true
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.email'),
        'name' => 'email',
        'value' => old('email'),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'required' => true,
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.reply_to'),
        'name' => 'replyTo',
        'value' => old('replyTo'),
        'maxLength' => '100',
        'rangeLength' => '2,100',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_section_header', [
        'label' => trans('pulsar::pulsar.outgoing_server'),
        'icon' => 'icon-upload-alt'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.outgoing_server'),
        'name' => 'outgoingServer',
        'value' => old('outgoingServer'),
        'maxLength' => '100',
        'rangeLength' => '2,100'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.user', 1),
        'name' => 'outgoingUser',
        'value' => old('outgoingUser'),
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
        'label' => trans('pulsar::pulsar.incoming_secure'),
        'name' => 'outgoingSecure',
        'value' => old('outgoingSecure'),
        'objects' => $outgoingSecures,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.port'),
        'name' => 'outgoingPort',
        'value' => old('outgoingPort'),
        'fieldSize' => 2,
        'data' => [
            'mask' => '9?99'
        ]
    ])
    @include('pulsar::includes.html.form_section_header', [
        'label' => trans('pulsar::pulsar.incoming_server'),
        'icon' => 'icon-download-alt'
    ])
    @include('pulsar::includes.html.form_select_group', [
        'label' => trans('pulsar::pulsar.incoming_type'),
        'name' => 'incomingType',
        'value' => old('incomingType'),
        'objects' => $incomingTypes,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.incoming_server'),
        'name' => 'incomingServer',
        'value' => old('incomingServer'),
        'maxLength' => '100',
        'rangeLength' => '2,100'
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans_choice('pulsar::pulsar.user', 1),
        'name' => 'incomingUser',
        'value' => old('incomingUser'),
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
        'value' => old('incomingSecure'),
        'objects' => $incomingSecures,
        'idSelect' => 'id',
        'nameSelect' => 'name',
        'fieldSize' => 5
    ])
    @include('pulsar::includes.html.form_text_group', [
        'label' => trans('pulsar::pulsar.port'),
        'name' => 'incomingPort',
        'value' => old('incomingPort'),
        'fieldSize' => 2,
        'data' => [
            'mask' => '9?99'
        ]
    ])
    <!-- /pulsar::email_account.form -->
@stop