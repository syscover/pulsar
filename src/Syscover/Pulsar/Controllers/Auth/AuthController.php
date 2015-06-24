<?php namespace Syscover\Pulsar\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Pulsar\Support\Facades\Config;
use Syscover\Pulsar\Libraries\PulsarAcl;
use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\Lang;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;


    private  $loginPath;
    private  $redirectPath;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth             = $auth;
		$this->registrar        = $registrar;
        $this->loginPath        = route('getLogin');
        $this->redirectPath     = route('dashboard');
	}

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('pulsar::auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'user_010' => 'required', 'password' => 'required',
        ]);

        $credentials = $request->only('user_010', 'password');

        if ($this->auth->attempt($credentials, $request->has('remember')))
        {
            // check if user has access
            if (!$this->auth->user()->access_010)
            {
                $this->auth->logout();
                return redirect($this->loginPath())
                    ->withInput($request->only('user_010', 'remember'))
                    ->withErrors([
                        'loginErrors' => 3
                    ]);
            }

            session(['userAcl' => PulsarAcl::getProfileAcl($this->auth->user()->profile_010)]);

            // check if user has permission to access
            if (!session('userAcl')->isAllowed($this->auth->user()->profile_010, 'pulsar', 'access'))
            {
                $this->auth->logout();
                return redirect($this->loginPath())
                    ->withInput($request->only('user_010', 'remember'))
                    ->withErrors([
                        'loginErrors' => 2
                    ]);
            }

            session(['packages' => Package::getModulesForSession()]);
            session(['baseLang' => Lang::getBaseLang()]);

            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath())
            ->withInput($request->only('user_010', 'remember'))
            ->withErrors([
                'loginErrors' => 1
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();
        session()->flush();
        return redirect(config('pulsar.appName'));
    }
}