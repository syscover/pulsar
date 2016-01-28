<?php namespace Syscover\Pulsar\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Pulsar\Support\Facades\Config;
use Syscover\Pulsar\Libraries\AclLibrary;
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

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Here you can customize your guard, this guar has to set in auth.php config
     *
     * @var string
     */
    protected $guard;


    //protected  $loginPath;
    //protected  $redirectPath;

	/**
	 * Create a new authentication controller instance.
	 */
	public function __construct()
	{
        $this->redirectTo       = route('dashboard');
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
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'user_010' => 'required', 'password' => 'required',
        ]);

        $credentials = $request->only('user_010', 'password');

        if(Auth::guard('pulsar')->attempt($credentials, $request->has('remember')))
        {
            // check if user has access
            if(!Auth::guard('pulsar')->user()->access_010)
            {
                Auth::logout();
                return redirect($this->loginPath())
                    ->withInput($request->only('user_010', 'remember'))
                    ->withErrors([
                        'loginErrors' => 3
                    ]);
            }

            // set user access control list
            session(['userAcl' => AclLibrary::getProfileAcl(Auth::guard('pulsar')->user()->profile_010)]);

            // check if user has permission to access
            if (!session('userAcl')->allows('pulsar', 'access'))
            {
                Auth::logout();
                return redirect($this->loginPath())
                    ->withInput($request->only('user_010', 'remember'))
                    ->withErrors([
                        'loginErrors' => 2
                    ]);
            }

            session(['packages' => Package::getRecords(['active_012' => true, 'orderBy' => ['column' => 'sorting_012', 'order' => 'desc']])]);
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
        Auth::logout();
        session()->flush();
        return redirect(config('pulsar.appName'));
    }
}