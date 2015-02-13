<?php namespace Pulsar\Pulsar\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Pulsar\Pulsar\Libraries\PulsarAcl;
use Pulsar\Pulsar\Models\Package;
use Pulsar\Pulsar\Models\Lang;

class Login extends BaseController
{
    public function login()
    {
        // in the array must indicate the user db field but not the password because it grabs Laravel thanks to the interface method implemented in the model, getAuthPassword()
        $userData = array('user_010' => Input::get('user'), 'password' => Input::get('pass'));

        if (Auth::attempt($userData))
        {
            Session::put('userAcl',     PulsarAcl::getProfileAcl(Auth::user()->profile_010));
            Session::put('packages',    Package::getModulesForSession());
            Session::put('baseLang',    Lang::getBaseLang());

            if (!Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'pulsar', 'access'))
            {
                return redirect()->to(config('pulsar.appName'))->with('loginErrors', true);
            }

            return redirect()->intended(route('dashboard'));
        }
        else
        {
            return redirect()->to(config('pulsar.appName'))->with('loginErrors', true);
        }
    }

    public function loginView()
    {
        return view('pulsar::login.index');
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return Redirect::to(config('pulsar.appName'));
    }

}