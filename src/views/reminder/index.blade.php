@extends('pulsar::layouts.login')

@section('script')
    <!-- App -->
    <script type="text/javascript" src="{{ asset('packages/pulsar/vinipadsalesforcefrontend/js/login.js') }}"></script>
    @include('pulsar::common.block.block_script_header_form')
@stop

@section('mainContent')
<!-- Login Formular -->
<form class="form-vertical login-form" action="{{ action('Pulsar\Pulsar\Controllers\RemindersController@postReset') }}" method="post">
    <input type="hidden" name="token" value="{{ $token }}">
    <!-- Title -->
    <h3 class="form-title">Reset su password</h3>

    <!-- Error Message -->
    <div class="alert fade in alert-danger" style="display: none;">
        <i class="icon-remove close" data-dismiss="alert"></i>
        Indique su email y nueva contraseña.
    </div>

    <!-- Input Fields -->
    <div class="form-group">
        <!--<label for="username">Username:</label>-->
        <div class="input-icon">
            <i class="icon-envelope"></i>
            <input type="email" name="email_010" class="form-control" placeholder="{{ trans('pulsar::pulsar.su_email') }}" autofocus="autofocus" data-rule-required="true" data-msg-required="Por favor indique su email." />
            {!! $errors->first('email_010',config('pulsar.errorDelimiters')) !!}
        </div>
    </div>
    <div class="form-group">
        <!--<label for="password">Password:</label>-->
        <div class="input-icon">
            <i class="icon-lock"></i>
            <input type="password" name="password" class="form-control required" placeholder="{{ trans('pulsar::pulsar.contrasena') }}">
            <?php echo $errors->first('password',config('pulsar.errorDelimiters')); ?>
        </div>
    </div>
    <div class="form-group">
        <!--<label for="password">Password:</label>-->
        <div class="input-icon">
            <i class="icon-lock"></i>
            <input type="password" name="password_confirmation" class="form-control required" placeholder="{{trans('pulsar::pulsar.repita_contrasena')}}" equalTo="[name='password']" />
            <?php echo $errors->first('password_confirmation',config('pulsar.errorDelimiters')); ?>
        </div>
    </div>
    <!-- /Input Fields -->

    <!-- Form Actions -->
    <div class="form-actions">
        <button type="submit" class="submit btn btn-primary pull-right">
            {{trans('pulsar::pulsar.reset_password')}} <i class="icon-angle-right"></i>
        </button>
    </div>
</form>
<!-- /Login Formular -->
@stop