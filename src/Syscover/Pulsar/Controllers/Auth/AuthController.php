<?php namespace Syscover\Pulsar\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Pulsar\Support\Facades\Config;
use Syscover\Pulsar\Libraries\AclLibrary;
use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\Lang;

class AuthController extends Controller
{

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

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Route to get login form
     *
     * @var string
     */
    protected $loginPath;

    /**
     * Here you can customize your guard, this guar has to set in auth.php config
     *
     * @var string
     */
    protected $guard;


	/**
	 * Create a new authentication controller instance.
	 */
	public function __construct()
	{
        $this->redirectTo   = route('dashboard');
        $this->loginPath    = route('pulsarGetLogin');
	}

    /**
     * Return view with login form.
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
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'user_010' => 'required', 'password' => 'required',
        ]);

        $credentials = $request->only('user_010', 'password');

        if(auth('pulsar')->attempt($credentials, $request->has('remember')))
        {
            // check if user has access
            if(!auth('pulsar')->user()->access_010)
            {
                auth('pulsar')->logout();
                return redirect($this->loginPath)
                    ->withInput($request->only('user_010', 'remember'))
                    ->withErrors([
                        'loginErrors' => 3
                    ]);
            }

            // set user access control list
            session(['userAcl' => AclLibrary::getProfileAcl(auth('pulsar')->user()->profile_id_010)]);

            // check if user has permission to access
            if (!is_allowed('pulsar', 'access'))
            {
                auth('pulsar')->logout();
                return redirect($this->loginPath)
                    ->withInput($request->only('user_010', 'remember'))
                    ->withErrors([
                        'loginErrors' => 2
                    ]);
            }

            session(['packages' => Package::getRecords(['active_012' => true, 'orderBy' => ['column' => 'sorting_012', 'order' => 'desc']])]);
            session(['baseLang' => Lang::getBaseLang()]);

            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath)
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
        auth('pulsar')->logout();
        session()->flush();
        return redirect(config('pulsar.appName'));
    }
}