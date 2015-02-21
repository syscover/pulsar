<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;

class RemindersController extends Controller
{
    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */
    public function postRemind() {
        /* variables extras a pasar a la vista del mail
        View::composer('emails.auth.reminder', function($view) use ($user) {
            $view->with([
                'logo'         => asset('/images/mail/logo.png'),
                'twitter_img'  => asset('/images/mail/btn_twitter_pressed.png'),
                'facebook_img' => asset('/images/mail/btn_fb_pressed.png')
            ]);
        });
         */
        
        //validamos el usuario existe en nuestra base de datos
        switch ($response = Password::remind(Input::only('email_010'),
                function($message, $user)
                {
                    $message->subject(Lang::get('pulsar::reminders.email_subject'));
                }
            )
        ){
            case Password::INVALID_USER:
                return view('pulsar::pulsar.pulsar.common.json_display', ['json' => json_encode(array('response' => false))]);

            case Password::REMINDER_SENT:
                return view('pulsar::pulsar.pulsar.common.json_display', ['json' => json_encode(array('response' => true))]);
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getReset($token = null)
    {
        if (is_null($token))
            App::abort(404);

        return view('pulsar::reminder.index')->with('token', $token);
    }

    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Response
     */
    public function postReset()
    {
        $credentials = Input::only(
            'email_010', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function($user, $password) {
                    $user->password_010 = Hash::make($password);
                    $user->save();
                });

        switch ($response) {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->withErrors(['email_010' => 'Email incorrecto'])->withInput();
            case Password::PASSWORD_RESET:
                return Redirect::to('/' . config('pulsar.appName'));
        }
    }

}
