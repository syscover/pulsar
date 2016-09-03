@extends('pulsar::layouts.login')

@section('head')
    <script src="{{ asset('packages/syscover/pulsar/js/login.js') }}"></script>
    @include('pulsar::includes.js.header_form')
@stop

@section('mainContent')
<form class="form-vertical login-form" action="{{ route('postResetPassword', ['token' => $token]) }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="token" value="{{ $token }}">

    <h3 class="form-title">{{ trans('pulsar::pulsar.reset_password') }}</h3>

    <!-- Error Message -->
    <div class="alert fade in alert-danger" style="display: none;">
        <i class="icon-remove close" data-dismiss="alert"></i>
        Enter your email and new password
    </div>
    <!-- /Error Message -->
    <div class="form-group">
        <div class="input-icon">
            <i class="icon-envelope"></i>
            <input type="email" name="email" class="form-control required" placeholder="{{ trans('pulsar::pulsar.email') }}" autofocus="autofocus">
            {!! $errors->first('email', config('pulsar.errorDelimiters')) !!}
            {!! $errors->first('token', config('pulsar.errorDelimiters')) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="input-icon">
            <i class="icon-lock"></i>
            <input type="password" name="password" class="form-control required" placeholder="{{ trans('pulsar::pulsar.password') }}">
            {!! $errors->first('password', config('pulsar.errorDelimiters')) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="input-icon">
            <i class="icon-lock"></i>
            <input type="password" name="password_confirmation" class="form-control required" placeholder="{{ trans('pulsar::pulsar.repeat_password') }}" equalTo="[name='password']">
            {!! $errors->first('password_confirmation', config('pulsar.errorDelimiters')) !!}
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="submit btn btn-primary pull-right">
            {{ trans('pulsar::pulsar.reset_password') }} <i class="icon-angle-right"></i>
        </button>
    </div>
</form>
@stop