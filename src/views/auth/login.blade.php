@extends('pulsar::layouts.login')

@section('script')
    <!-- App -->
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/login.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if($errors->has('loginErrors') && $errors->first('loginErrors') == 1)
            new PNotify({
                type:   'error',
                title:  '{{ trans('pulsar::pulsar.message_error_login') }}',
                text:   '{{ trans('pulsar::pulsar.message_error_login_msg_1') }}',
                opacity: .9,
                styling: 'fontawesome'
            });
            @endif

            @if($errors->has('loginErrors') && $errors->first('loginErrors') == 2)
            new PNotify({
                type:   'error',
                title:  '{{ trans('pulsar::pulsar.message_error_login') }}',
                text:   '{{ trans('pulsar::pulsar.message_error_login_msg_2') }}',
                opacity: .9,
                styling: 'fontawesome'
            });
            @endif

            // user don
            @if($errors->has('loginErrors') && $errors->first('loginErrors') == 3)
            new PNotify({
                type:   'error',
                title:  '{{ trans('pulsar::pulsar.message_error_login') }}',
                text:   '{{ trans('pulsar::pulsar.message_error_login_msg_3') }}',
                opacity: .9,
                styling: 'fontawesome'
            });
            @endif
        });
    </script>
@stop

@section('mainContent')
<!-- Login Formular -->
<form class="form-vertical login-form" action="{{ route('postLogin') }}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <h3 class="form-title">{{ trans('pulsar::pulsar.access_your_account') }}</h3>

    <!-- Error Message -->
    <div class="alert fade in alert-danger" style="display: none;">
        <i class="icon-remove close" data-dismiss="alert"></i>
        {{ trans('pulsar::pulsar.message_user_pass') }}
    </div>

    <div class="form-group">
        <div class="input-icon">
            <i class="fa fa-user"></i>
            <input type="text" name="user_010" value="{{ Input::old('user_010') }}" class="form-control" placeholder="{{ trans_choice('pulsar::pulsar.user', 1) }}" autofocus="autofocus" data-rule-required="true" data-msg-required="{{ trans('pulsar::pulsar.message_user') }}">
        </div>
    </div>
    <div class="form-group">
        <div class="input-icon">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="{{ trans('pulsar::pulsar.password') }}" data-rule-required="true" data-msg-required="{{ trans('pulsar::pulsar.message_pass') }}">
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="submit btn btn-primary pull-right">
            {{ trans('pulsar::pulsar.login') }} <i class="fa fa-angle-right"></i>
        </button>
    </div>
</form>
<!-- /Login Form -->
@stop

@section('reminder')
<div class="inner-box">
    <div class="content">
        <!-- Close Button -->
        <i class="fa fa-remove close hide-default"></i>

        <!-- Link as Toggle Button -->
        <a href="#" class="forgot-password-link">{{ trans('pulsar::pulsar.remember_password') }}</a>

        <!-- Forgot Password Formular -->
        <form id="forgot-password" class="form-vertical forgot-password-form hide-default" action="{{ route('emailResetPassword') }}" method="post" onsubmit="return false">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <div class="input-icon">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email_010" class="form-control" placeholder="{{ trans('pulsar::pulsar.enter_email') }}" data-rule-required="true" data-rule-email="true" data-msg-required="{{ trans('pulsar::pulsar.error_reset_password') }}">
                </div>
            </div>

            <button type="submit" class="submit btn btn-default btn-block">
                {{ trans('pulsar::pulsar.reset_password') }}
            </button>
        </form>
        <!-- /Forgot Password Formular -->

        <!-- Shows up if reset-button was clicked -->
        <div class="forgot-password-done hide-default">
            <div class="forgot-password-ok">
                <i class="fa fa-ok success-icon"></i>
                <span>{{ trans('pulsar::pulsar.reset_password_successful') }}</span>
            </div>
            <div class="forgot-password-nook">
                <i class="fa fa-remove danger-icon"></i>
                <span>{{ trans('pulsar::pulsar.error2_reset_password') }}</span>
            </div>
        </div>
    </div>
</div>
@stop