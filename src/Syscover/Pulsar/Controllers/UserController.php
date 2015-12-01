<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Support\Facades\Hash;
use Syscover\Pulsar\Models\Lang;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Models\User;
use Syscover\Pulsar\Traits\TraitController;

/**
 * Class UserController
 * @package Syscover\Pulsar\Controllers
 */

class UserController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'user';
    protected $folder       = 'user';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_010', 'name_010', 'surname_010', ['data' => 'email_010', 'type' => 'email'], 'name_006', ['data' => 'access_010', 'type' => 'active']];
    protected $nameM        = 'name_010';
    protected $model        = \Syscover\Pulsar\Models\User::class;
    protected $icon         = 'fa fa-users';
    protected $objectTrans  = 'user';

    public function createCustomRecord($request, $parameters)
    {
        $parameters['langs']    = Lang::getActivesLangs();
        $parameters['profiles'] = Profile::all();

        return $parameters;
    }
    
    public function storeCustomRecord($request, $parameters)
    {
        User::create([
            'name_010'      => $request->input('name'),
            'surname_010'   => $request->input('surname'),
            'email_010'     => $request->input('email'),
            'lang_010'      => $request->input('lang'),
            'access_010'    => $request->input('access',0),
            'profile_010'   => $request->input('profile'),
            'user_010'      => $request->input('user'),
            'password_010'  => Hash::make($request->input('password')),
            //'test'          => 'Esto es una prueba'
        ]);
    }

    public function editCustomRecord($request, $parameters)
    {
        $parameters['langs']    = Lang::getActivesLangs();
        $parameters['profiles'] = Profile::all();

        return $parameters;
    }

    public function checkSpecialRulesToUpdate($request, $parameters)
    {
        $user = User::find($parameters['id']);

        $parameters['specialRules']['emailRule']    = $request->input('email') == $user->email_010? true : false;
        $parameters['specialRules']['userRule']     = $request->input('user') == $user->user_010? true : false;
        $parameters['specialRules']['passRule']     = $request->input('password') == ""? true : false;

        return $parameters;
    }

    public function updateCustomRecord($request, $parameters)
    {
        $user = [
            'name_010'      => $request->input('name'),
            'surname_010'   => $request->input('surname'),
            'email_010'     => $request->input('email'),
            'lang_010'      => $request->input('lang'),
            'access_010'    => $request->input('access',0),
            'profile_010'   => $request->input('profile'),
            'user_010'      => $request->input('user'),
        ];

        if($parameters['specialRules']['emailRule'])  $user['email_010']      = $request->input('email');
        if($parameters['specialRules']['userRule'])   $user['user_010']       = $request->input('user');
        if(!$parameters['specialRules']['passRule'])  $user['password_010']   = Hash::make($request->input('password'));

        User::where('id_010', $parameters['id'])->update($user);
    }
}