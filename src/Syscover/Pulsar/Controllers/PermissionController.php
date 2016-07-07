<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Illuminate\Database\Eloquent\Model;
use Syscover\Pulsar\Libraries\Miscellaneous;
use Syscover\Pulsar\Libraries\AclLibrary;
use Syscover\Pulsar\Models\Permission;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Models\Action;

/**
 * Class PermissionController
 * @package Syscover\Pulsar\Controllers
 */

class PermissionController extends Controller
{
    protected $routeSuffixProfile   = 'profile';
    protected $areDeleteRecord      = true;

    protected $routeSuffix          = 'permission';
    protected $folder               = 'permission';
    protected $package              = 'pulsar';
    protected $indexColumns             = ['id_007', 'name_012', 'name_007'];
    protected $nameM                = 'name_008';
    protected $model                = \Syscover\Pulsar\Models\Resource::class;
    protected $icon                 = 'icon-shield';
    protected $objectTrans          = 'permission';
    protected $request;
    
    public function customIndex($parameters)
    {
        $parameters['profile']                = Profile::find($parameters['profile']);
        $parameters['areDeleteRecord']        = $this->areDeleteRecord;
        $parameters['routeSuffixProfile']     = $this->routeSuffixProfile;

        return $parameters;
    }

    public function jsonData()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $actionsAcl     = Action::get();
        $acl            = AclLibrary::getProfileAcl($parameters['profile']);

        $parameters         =  Miscellaneous::dataTablePaginate($this->request, $parameters);
        $parameters         =  Miscellaneous::dataTableSorting($this->request, $parameters, $this->indexColumns);
        $parameters         =  Miscellaneous::dataTableFiltering($this->request, $parameters);

        // set columns in parameters array
        $parameters['indexColumns'] = $this->indexColumns;
        $parametersCount            = $parameters;
        $parametersCount['count']   = true;

        // get data to table
        $objects        = call_user_func($this->model . '::getIndexRecords', $this->request, $parameters);
        $iFilteredTotal = call_user_func($this->model . '::getIndexRecords', $this->request, $parametersCount);
        $iTotal         = call_user_func($this->model . '::countRecords', $this->request, $parameters);

        // get properties of model class
        $class          = new \ReflectionClass($this->model);

        $response = [
            "sEcho"                 => intval($this->request->input('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => []
        ];

        // instance model to get primary key
        $instance = new $this->model;

        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject)
        {
		    $row = [];
		    foreach ($this->indexColumns as $indexColumn)
            {
                $row[] = $aObject[$indexColumn];
            }

            $actions = '<div><select id="re' . $aObject[$instance->getKeyName()] . '" data-resource="' . $aObject[$instance->getKeyName()] . '" data-nresource="' . $aObject['name_007'] . '" multiple style="width: 100%;">';
            foreach ($actionsAcl as $actionAcl)
            {
                $selected = $acl->allows($aObject['id_007'], $actionAcl->id_008, $parameters['profile'])? ' selected' : null;
                $actions .= '<option value="' . $actionAcl->id_008 . '"' . $selected . '>' . $actionAcl->name_008 . '</option>';
            }
            $actions .= '</select></div>';

            $row[] =   $actions;

            $response['aaData'][] = $row;
            $i++;
	    }

        return response()->json($response);
    }

    public function jsonCreate($profile, $resource, $action)
    {
        Permission::create([
            'profile_id_009'    => $profile,
            'resource_id_009'   => $resource,
            'action_id_009'     => $action
        ]);
    }
    
    public function jsonDestroy($profile, $resource, $action)
    {
        Permission::deleteRecord($profile, $resource, $action);
    }
}