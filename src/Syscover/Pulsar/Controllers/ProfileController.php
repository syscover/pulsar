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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\Action;
use Syscover\Pulsar\Models\Permission;
use Syscover\Pulsar\Traits\TraitController;

class ProfileController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'Profile';
    protected $folder       = 'profile';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_006', 'name_006'];
    protected $nameM        = 'name_006';
    protected $model        = '\Syscover\Pulsar\Models\Profile';
    protected $icon         = 'icomoon-icon-users-2';
    protected $objectTrans  = 'profile';

    private $rePermission   = 'admin-perm-perm';

    public function jsonCustomDataBeforeActions($aObject)
    {
        $actions = session('userAcl')->isAllowed(Auth::user()->profile_010, $this->rePermission, 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('Permission', [0, $aObject['id_006'], Request::input('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_permissions').'"><i class="icon-shield"></i></a>' : null;
        $actions .= session('userAcl')->isAllowed(Auth::user()->profile_010, $this->rePermission, 'access')? '<a class="btn btn-xs bs-tooltip all-permissions" onClick="setAllPermissions(this)" data-all-permissions-url="' . route('allPermissionsProfile', [$aObject['id_006'], Request::input('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.set_all_permissions').'"><i class="icon-unlock-alt"></i></a>' : null;

        return $actions;
    }
    
    public function storeCustomRecord()
    {
        Profile::create([
            'name_006'  => Request::input('name')
        ]);
    }
    
    public function updateCustomRecord($parameters)
    {
        Profile::where('id_006', $parameters['id'])->update([
            'name_006'  => Request::input('name')
        ]);
    }

    public function setAllPermissions(HttpRequest $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $profile    = Profile::find($parameters['id']);
        $resources  = Resource::all();
        $actions    = Action::all();

        $permissions = [];
        foreach($resources as $resource)
        {
            foreach($actions as $action)
            {
                $permissions[] = ['profile_009' => $parameters['id'], 'resource_009' => $resource->id_007, 'action_009' => $action->id_008];
            }
        }

        Permission::deleteRecordsProfile($parameters['id']);
        Permission::insert($permissions);

        return redirect()->route($this->routeSuffix, $parameters)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_create_all_permissions', ['profile' => $profile->name_006])
        ]);
    }
}