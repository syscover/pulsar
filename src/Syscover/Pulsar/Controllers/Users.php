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

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Syscover\Pulsar\Models\Lang;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Models\User;
use Syscover\Pulsar\Traits\ControllerTrait;

class Users extends BaseController {

    use ControllerTrait;

    protected $routeSuffix  = 'User';
    protected $folder       = 'users';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_010', 'name_010', 'surname_010', ['data' => 'email_010', 'type' => 'email'], 'name_006', 'access_010'];
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
            'name_010'      => Input::get('name'),
            'surname_010'   => Input::get('surname'),
            'email_010'     => Input::get('email'),
            'lang_010'      => Input::get('lang'),
            'access_010'    => Input::get('access',0),
            'profile_010'   => Input::get('profile'),
            'user_010'      => Input::get('user'),
            'password_010'  => Hash::make(Input::get('password'))
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

        $parameters['specialRules']['emailRule']    = Input::get('email') == $user->email_010? true : false;
        $parameters['specialRules']['userRule']     = Input::get('user') == $user->user_010? true : false;
        $parameters['specialRules']['passRule']     = Input::get('password') == ""? true : false;

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        $user = [
            'name_010'      => Input::get('name'),
            'surname_010'   => Input::get('surname'),
            'email_010'     => Input::get('email'),
            'lang_010'      => Input::get('lang'),
            'access_010'    => Input::get('access',0),
            'profile_010'   => Input::get('profile'),
            'user_010'      => Input::get('user'),
        ];

        //if($emailRule)  $user['email_010']      = Input::get('email');
        //if($userRule)   $user['user_010']       = Input::get('user');
       // if($passRule)   $user['password_010']   = Hash::make(Input::get('password'));

        User::where('id_010', $parameters['id'])->update($user);

    }
}