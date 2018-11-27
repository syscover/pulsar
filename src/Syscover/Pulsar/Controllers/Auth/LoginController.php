<?php namespace Syscover\Pulsar\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Syscover\Pulsar\Libraries\AclLibrary;
use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\Lang;

class LoginController extends Controller
{
	use ThrottlesLogins;

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
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'user'      => 'required',
            'password'  => 'required',
        ]);

        $credentials = $request->only('user', 'password');

        if(auth()->guard('pulsar')->attempt($credentials, $request->has('remember')))
        {
            // check if user has access
            if(!auth()->guard('pulsar')->user()->access_010)
            {
                auth()->guard('pulsar')->logout();
                return redirect($this->loginPath)
                    ->withInput($request->only('user', 'remember'))
                    ->withErrors([
                        'loginErrors' => 3
                    ]);
            }

            // set user access control list
            session(['userAcl' => AclLibrary::getProfileAcl(auth()->guard('pulsar')->user()->profile_id_010)]);

            // check if user has permission to access
            if (!is_allowed('pulsar', 'access'))
            {
                auth()->guard('pulsar')->logout();
                return redirect($this->loginPath)
                    ->withInput($request->only('user', 'remember'))
                    ->withErrors([
                        'loginErrors' => 2
                    ]);
            }

            session(['packages' => Package::getRecords(['active_012' => true, 'orderBy' => ['column' => 'sorting_012', 'order' => 'desc']])]);
            session(['baseLang' => Lang::getBaseLang()]);

            return redirect()->intended($this->redirectTo);
        }

        return redirect($this->loginPath)
            ->withInput($request->only('user', 'remember'))
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
        auth()->guard('pulsar')->logout();
        session()->flush();

        return redirect(config('pulsar.name'));
    }
}