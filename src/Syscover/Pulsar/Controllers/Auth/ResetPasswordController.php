<?php namespace Syscover\Pulsar\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;


class ResetPasswordController extends Controller
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
     * The view to reset email.
     *
     * @var string
     */
    protected $resetView = 'pulsar::auth.reset_password';

    /**
     * Create a new password controller instance.
     */
    public function __construct()
    {
        // redirect after reset password
        $this->redirectTo = route('pulsarGetLogin');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  string|null                  $token
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
            'email'         => 'required|email',
            'password'      => 'required|confirmed|min:6',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

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
        return redirect($this->redirectPath())
            ->with('status', trans($response)); // set message to show
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
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
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

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Support\Facades\Password;
     */
    public function broker()
    {
        return Password::broker('pulsarPasswordBroker');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth('pulsar');
    }
}
