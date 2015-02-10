@extends('pulsar::layouts.login')

@section('script')
    <!-- App -->
    <script type="text/javascript" src="{{ asset('packages/pulsar/pulsar/js/login.js') }}"></script>      
    @include('pulsar::login.js.index')
@stop

@section('mainContent')
<!-- Login Formular -->
<form class="form-vertical login-form" action="{{ route('login') }}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <!-- Title -->
    <h3 class="form-title">{{ trans('pulsar::pulsar.access_your_account') }}</h3>

    <!-- Error Message -->
    <div class="alert fade in alert-danger" style="display: none;">
        <i class="icon-remove close" data-dismiss="alert"></i>
        Indique su usuario y contraseña.
    </div>

    <!-- Input Fields -->
    <div class="form-group">
        <!--<label for="username">Username:</label>-->
        <div class="input-icon">
            <i class="icon-user"></i>
            <input type="text" name="user" class="form-control" placeholder="{{ trans('pulsar::pulsar.user') }}" autofocus="autofocus" data-rule-required="true" data-msg-required="Please enter your username.">
        </div>
    </div>
    <div class="form-group">
        <!--<label for="password">Password:</label>-->
        <div class="input-icon">
            <i class="icon-lock"></i>
            <input type="password" name="pass" class="form-control" placeholder="{{ trans('pulsar::pulsar.password') }}" data-rule-required="true" data-msg-required="Please enter your password.">
        </div>
    </div>
    <!-- /Input Fields -->

    <!-- Form Actions -->
    <div class="form-actions">
        <button type="submit" class="submit btn btn-primary pull-right">
            {{ trans('pulsar::pulsar.login') }} <i class="icon-angle-right"></i>
        </button>
    </div>
</form>
<!-- /Login Formular -->
@stop

@section('reminder')
<div class="inner-box">
    <div class="content">
        <!-- Close Button -->
        <i class="icon-remove close hide-default"></i>

        <!-- Link as Toggle Button -->
        <a href="#" class="forgot-password-link">{{ trans('pulsar::pulsar.remember_password') }}</a>

        <!-- Forgot Password Formular -->
        <form id="forgot-password" class="form-vertical forgot-password-form hide-default" action="{{ route('postRemindPassword') }}" method="post" onsubmit="return false">
            <!-- Input Fields -->
            <div class="form-group">
                <!--<label for="email">Email:</label>-->
                <div class="input-icon">
                    <i class="icon-envelope"></i>
                    <input type="text" name="email_010" class="form-control" placeholder="{{ trans('pulsar::pulsar.enter_email') }}" data-rule-required="true" data-rule-email="true" data-msg-required="{{ trans('pulsar::pulsar.error_change_password') }}">
                </div>
            </div>
            <!-- /Input Fields -->

            <button type="submit" class="submit btn btn-default btn-block">
                {{ trans('pulsar::pulsar.change_password') }}
            </button>
        </form>
        <!-- /Forgot Password Formular -->

        <!-- Shows up if reset-button was clicked -->
        <div class="forgot-password-done hide-default">
            <div class="forgot-password-ok">
                <i class="icon-ok success-icon"></i>
                <span>Estupendo, Le hemos enviado un email.</span>
            </div>
            <div class="forgot-password-nook">
                <i class="icon-remove danger-icon"></i>
                <span>{{ trans('pulsar::pulsar.error2_change_password') }}</span>
            </div>
        </div>
    </div> <!-- /.content -->
</div>
@stop