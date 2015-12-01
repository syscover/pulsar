<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Http\Request;
use Syscover\Pulsar\Libraries\PulsarAcl;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\Action;
use Syscover\Pulsar\Models\Permission;
use Syscover\Pulsar\Traits\TraitController;

/**
 * Class ProfileController
 *
 * @package     Syscover\Pulsar\Controllers
 */

class ProfileController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'profile';
    protected $folder       = 'profile';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_006', 'name_006'];
    protected $nameM        = 'name_006';
    protected $model        = '\Syscover\Pulsar\Models\Profile';
    protected $icon         = 'icomoon-icon-users-2';
    protected $objectTrans  = 'profile';

    private $rePermission   = 'admin-perm-perm';

    public function jsonCustomDataBeforeActions($request, $aObject)
    {
        $actions = session('userAcl')->isAllowed($request->user()->profile_010, $this->rePermission, 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('permission', [0, $aObject['id_006'], $request->input('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_permissions').'"><i class="fa fa-shield"></i></a>' : null;
        $actions .= session('userAcl')->isAllowed($request->user()->profile_010, $this->rePermission, 'access')? '<a class="btn btn-xs bs-tooltip all-permissions" onClick="setAllPermissions(this)" data-all-permissions-url="' . route('allPermissionsProfile', [$aObject['id_006'], $request->input('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.set_all_permissions').'"><i class="fa fa-unlock-alt"></i></a>' : null;

        return $actions;
    }
    
    public function storeCustomRecord($request, $parameters)
    {
        Profile::create([
            'name_006'  => $request->input('name')
        ]);
    }
    
    public function updateCustomRecord($request, $parameters)
    {
        Profile::where('id_006', $parameters['id'])->update([
            'name_006'  => $request->input('name')
        ]);
    }

    public function setAllPermissions(Request $request)
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

        // if profile it's same that our profile, overwrite ours permissions
        if($profile->id_006 == $request->user()->profile_010)
            session(['userAcl' => PulsarAcl::getProfileAcl($request->user()->profile_010)]);

        return redirect()->route($this->routeSuffix, $parameters)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_create_all_permissions', ['profile' => $profile->name_006])
        ]);
    }
}