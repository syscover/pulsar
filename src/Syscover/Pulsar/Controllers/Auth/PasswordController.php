<?php namespace Syscover\Pulsar\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Session;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * The authentication guard that should be used.
     *
     * @var string
     */
    protected $guard = 'pulsar';

    /**
     * The password broker that should be used.
     *
     * @var string
     */
    protected $broker = 'pulsarPasswordBroker';

    /**
     * The view to reset email.
     *
     * @var string
     */
    protected $resetView = 'pulsar::auth.reset';

    /**
     * Subject of reset email.
     *
     * @var string
     */
    protected $subject;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->subject      = trans('pulsar::pulsar.subject_password_reset');
        $this->redirectPath = route('getLogin');
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $this->validate($request, ['email_010' => 'required|email']);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->sendResetLink($request->only('email_010'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return response()->json([
                    'success' => true
                ]);

            case Password::INVALID_USER:
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user'
                ], 400);
        }
    }


    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset(Request $request, $token = null)
    {
        if (is_null($token))
            return $this->getEmail();

        $email = $request->input('email_010');

        if (property_exists($this, 'resetView'))
            return view($this->resetView)->with(compact('token', 'email'));

        if (view()->exists('auth.passwords.reset'))
            return view('auth.passwords.reset')->with(compact('token', 'email'));

        return view('auth.reset')->with(compact('token', 'email'));
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token'         => 'required',
            'email_010'     => 'required|email',
            'password'      => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only(
            'email_010', 'password', 'password_confirmation', 'token'
        );

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->getResetSuccessResponse('pulsar::passwords.reset');

            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        Session::flash('status', trans($response));

        return redirect($this->redirectPath());
    }

    /**
     * Get the response for after a failing password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetFailureResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email_010'))
            ->withErrors(['email_010' => trans($response)]);
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password_010 = bcrypt($password);

        $user->save();

        // if redirect to user zone, we set new user on Auth
        //auth($this->getGuard())->login($user);
    }
}
