<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Syscover\Pulsar\Libraries\PulsarAcl;
use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\Lang;

class Login extends Controller
{
    public function login()
    {
        // in the array must indicate the user db field but not the password because it grabs Laravel thanks to the interface method implemented in the model, getAuthPassword()
        $userData = ['user_010' => Input::get('user'), 'password' => Input::get('password')];

        if (Auth::attempt($userData))
        {
            Session::put('userAcl',     PulsarAcl::getProfileAcl(Auth::user()->profile_010));
            Session::put('packages',    Package::getModulesForSession());
            Session::put('baseLang',    Lang::getBaseLang());

            if (!Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'pulsar', 'access'))
            {
                return redirect()->to(config('pulsar.appName'))->withErrors(['loginErrors' => 2]);
            }

            return redirect()->intended(route('dashboard'));
        }
        else
        {
            return redirect()->route('login')->withErrors(['loginErrors' => 2]);
        }
    }


}