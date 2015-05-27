@extends('pulsar::layouts.form', ['action' => 'store'])

@section('script')
    @parent
    <!-- pulsar::email_accounts.create -->
    <link href="{{ asset('packages/syscover/pulsar/css/custom/select2/select2.css') }}" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/bootstrap-inputmask/jquery.inputmask.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/select2/select2_locale_' . config('app.locale') . '.js') }}"></script>
    <!-- /pulsar::email_accounts.create -->
@stop

@section('rows')
    <!-- pulsar::email_accounts.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '50', 'rangeLength' => '2,50'])

    @include('pulsar::includes.html.form_section_header', ['label' => trans('pulsar::pulsar.outgoing_server'), 'icon' => 'icon-upload-alt'])

    @include('pulsar::includes.html.form_section_header', ['label' => trans('pulsar::pulsar.incoming_server'), 'icon' => 'icon-download-alt'])
    <!-- /pulsar::email_accounts.create -->
@stop