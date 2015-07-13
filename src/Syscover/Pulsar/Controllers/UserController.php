<?php
namespace Syscover\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Hash;
use Syscover\Pulsar\Models\Lang;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Models\User;
use Syscover\Pulsar\Traits\TraitController;

class UserController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'User';
    protected $folder       = 'user';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_010', 'name_010', 'surname_010', ['data' => 'email_010', 'type' => 'email'], 'name_006', ['data' => 'access_010', 'type' => 'active']];
    protected $nameM        = 'name_010';
    protected $model        = '\Syscover\Pulsar\Models\User';
    protected $icon         = 'icomoon-icon-users';
    protected $objectTrans  = 'user';

    public function createCustomRecord($parameters)
    {
        $parameters['langs']    = Lang::getActivesLangs();
        $parameters['profiles'] = Profile::all();

        return $parameters;
    }
    
    public function storeCustomRecord()
    {
        User::create([
            'name_010'      => Request::input('name'),
            'surname_010'   => Request::input('surname'),
            'email_010'     => Request::input('email'),
            'lang_010'      => Request::input('lang'),
            'access_010'    => Request::input('access',0),
            'profile_010'   => Request::input('profile'),
            'user_010'      => Request::input('user'),
            'password_010'  => Hash::make(Request::input('password'))
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['langs']    = Lang::getActivesLangs();
        $parameters['profiles'] = Profile::all();

        return $parameters;
    }

    public function checkSpecialRulesToUpdate($parameters)
    {
        $user = User::find($parameters['id']);

        $parameters['specialRules']['emailRule']    = Request::input('email') == $user->email_010? true : false;
        $parameters['specialRules']['userRule']     = Request::input('user') == $user->user_010? true : false;
        $parameters['specialRules']['passRule']     = Request::input('password') == ""? true : false;

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        $user = [
            'name_010'      => Request::input('name'),
            'surname_010'   => Request::input('surname'),
            'email_010'     => Request::input('email'),
            'lang_010'      => Request::input('lang'),
            'access_010'    => Request::input('access',0),
            'profile_010'   => Request::input('profile'),
            'user_010'      => Request::input('user'),
        ];

        if($parameters['specialRules']['emailRule'])  $user['email_010']      = Request::input('email');
        if($parameters['specialRules']['userRule'])   $user['user_010']       = Request::input('user');
        if(!$parameters['specialRules']['passRule'])  $user['password_010']   = Hash::make(Request::input('password'));

        User::where('id_010', $parameters['id'])->update($user);
    }
}