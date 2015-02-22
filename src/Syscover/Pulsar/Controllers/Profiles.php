<?php namespace Syscover\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Traits\ControllerTrait;

class Profiles extends Controller {

    use ControllerTrait;

    protected $routeSuffix  = 'Profile';
    protected $folder       = 'profiles';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_006', 'name_006'];
    protected $nameM        = 'name_006';
    protected $model        = '\Syscover\Pulsar\Models\Profile';
    protected $icon         = 'icomoon-icon-users-2';
    protected $objectTrans  = 'profile';

    private $rePermission   = 'admin-perm-perm';

    public function jsonCustomDataBeforeActions($aObject)
    {
        return Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->rePermission, 'access')? '<a class="btn btn-xs bs-tooltip" title="" href="' . route('Permission', [0, $aObject['id_006'], Input::get('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_permissions').'"><i class="icon-shield"></i></a>' : null;
    }
    
    public function storeCustomRecord($parameters)
    {
        Profile::create([
            'name_006'  => Input::get('name')
        ]);
    }
    
    public function updateCustomRecord($parameters)
    {
        Profile::where('id_006', $parameters['id'])->update([
            'name_006'  => Input::get('name')
        ]);
    }
}