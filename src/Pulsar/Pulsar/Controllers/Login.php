<?php namespace Pulsar\Pulsar\Controllers;

use Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Session,
    Pulsar\Pulsar\Libraries\PulsarAcl,
    Pulsar\Pulsar\Models\Package;

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

            if (!Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'pulsar', 'access'))
            {
                return redirect()->to(config('pulsar.appName'))->with('loginErrors', true);
            }

            return redirect()->intended(config('pulsar.appName') . '/pulsar/dashboard');
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

}