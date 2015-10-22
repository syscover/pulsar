<?php namespace Syscover\Pulsar\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class PasswordController extends Controller {

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
     * The password broker implementation.
     *
     * @var PasswordBroker
     */
    private $subject;

	/**
	 * Create a new password controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\PasswordBroker  $passwords
	 */
	public function __construct(Guard $auth, PasswordBroker $passwords)
	{
		$this->auth         = $auth;
		$this->passwords    = $passwords;
        $this->subject      = trans('pulsar::pulsar.subject_password_reset');
        $this->redirectPath = route('dashboard');
	}

    /**
     * Send a reset link to the given user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postEmail(Request $request)
    {
        $this->validate($request, ['email_010' => 'required']);

        $response = $this->passwords->sendResetLink($request->only('email_010'), function($m)
        {
            $m->subject($this->getEmailSubject());
        });

        switch ($response)
        {
            case PasswordBroker::RESET_LINK_SENT:
                return response()->json([
                    'success' => true
                ]);

            case PasswordBroker::INVALID_USER:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user'
                ], 400);
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string $token
     * @return Response
     * @throws NotFoundHttpException
     */
    public function getReset($token = null)
    {
        if (is_null($token))
        {
            throw new NotFoundHttpException;
        }

        return view('pulsar::auth.reset')->with('token', $token);
    }

    /**
     * Reset the given user's password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token'     => 'required',
            'email_010' => 'required|email',
            'password'  => 'required|confirmed',
        ]);

        $credentials = $request->only(
            'email_010', 'password', 'password_confirmation', 'token'
        );

        $response = $this->passwords->reset($credentials, function($user, $password)
        {
            $user->password_010 = bcrypt($password);

            $user->save();
        });

        switch ($response)
        {
            case PasswordBroker::PASSWORD_RESET:
                return redirect($this->redirectPath());

            default:
                return redirect()->back()
                    ->withInput($request->only('email_010'))
                    ->withErrors(['email_010' => trans($response)]);
        }
    }
}
