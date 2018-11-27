<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Libraries\AclLibrary;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\Action;
use Syscover\Pulsar\Models\Permission;

/**
 * Class ProfileController
 * @package Syscover\Pulsar\Controllers
 */

class ProfileController extends Controller
{
    protected $routeSuffix  = 'profile';
    protected $folder       = 'profile';
    protected $package      = 'pulsar';
    protected $indexColumns = ['id_006', 'name_006'];
    protected $nameM        = 'name_006';
    protected $model        = Profile::class;
    protected $icon         = 'icomoon-icon-users-2';
    protected $objectTrans  = 'profile';

    private $rePermission   = 'admin-perm-perm';

    public function jsonCustomDataBeforeActions($aObject, $actionUrlParameters, $parameters)
    {
        $actions = is_allowed($this->rePermission, 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('permission', [0, $aObject['id_006'], $this->request->input('start')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_permissions').'"><i class="fa fa-shield"></i></a>' : null;
        $actions .= is_allowed($this->rePermission, 'access')? '<a class="btn btn-xs bs-tooltip all-permissions" onClick="setAllPermissions(this)" data-all-permissions-url="' . route('allPermissionsProfile', [$aObject['id_006'], $this->request->input('start')]) . '" data-original-title="' . trans('pulsar::pulsar.set_all_permissions').'"><i class="fa fa-unlock-alt"></i></a>' : null;

        return $actions;
    }
    
    public function storeCustomRecord($parameters)
    {
        Profile::create([
            'name_006'  => $this->request->input('name')
        ]);
    }
    
    public function updateCustomRecord($parameters)
    {
        Profile::where('id_006', $parameters['id'])->update([
            'name_006'  => $this->request->input('name')
        ]);
    }

    public function setAllPermissions()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $profile    = Profile::find($parameters['id']);
        $resources  = Resource::all();
        $actions    = Action::all();

        $permissions = [];
        foreach($resources as $resource)
        {
            foreach($actions as $action)
            {
                $permissions[] = ['profile_id_009' => $parameters['id'], 'resource_id_009' => $resource->id_007, 'action_id_009' => $action->id_008];
            }
        }

        Permission::deleteRecordsProfile($parameters['id']);
        Permission::insert($permissions);

        // if profile it's same that our profile, overwrite ours permissions
        if($profile->id_006 == auth()->guard('pulsar')->user()->profile_id_010)
            session(['userAcl' => AclLibrary::getProfileAcl(auth()->guard('pulsar')->user()->profile_id_010)]);

        return redirect()->route($this->routeSuffix, $parameters)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_create_all_permissions', ['profile' => $profile->name_006])
        ]);
    }
}