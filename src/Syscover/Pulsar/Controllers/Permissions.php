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

use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Syscover\Pulsar\Libraries\Miscellaneous;
use Syscover\Pulsar\Libraries\PulsarAcl;
use Syscover\Pulsar\Models\Permission;
use Syscover\Pulsar\Models\Profile;
use Syscover\Pulsar\Models\Action;
use Syscover\Pulsar\Traits\TraitController;


class Permissions extends Controller
{
    use TraitController;

    protected $routeSuffixProfile   = 'Profile';
    protected $areDeleteRecord      = true;

    protected $routeSuffix          = 'Permission';
    protected $folder               = 'permissions';
    protected $package              = 'pulsar';
    protected $aColumns             = ['id_007', 'name_012', 'name_007'];
    protected $nameM                = 'name_008';
    protected $model                = '\Syscover\Pulsar\Models\Resource';
    protected $icon                 = 'icon-shield';
    protected $objectTrans          = 'permission';
    
    public function indexCustom($parameters)
    {
        $parameters['profile']                = Profile::find($parameters['profile']);
        $parameters['areDeleteRecord']        = $this->areDeleteRecord;
        $parameters['routeSuffixProfile']     = $this->routeSuffixProfile;

        return $parameters;
    }

    public function jsonData(HttpRequest $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $actionsAcl     = Action::get();
        $acl            = PulsarAcl::getProfileAcl($parameters['profile']);

        $parameters         =  Miscellaneous::paginateDataTable($parameters);
        $parameters         =  Miscellaneous::dataTableSorting($parameters, $this->aColumns);
        $parameters         =  Miscellaneous::filteringDataTable($parameters);

        // set columns in parameters array
        $parameters['aColumns']     = $this->aColumns;
        $parametersCount            = $parameters;
        $parametersCount['count']   = true;

        // get data to table
        $objects        = call_user_func($this->model . '::getRecordsLimit', $parameters);
        $iFilteredTotal = call_user_func($this->model . '::getRecordsLimit', $parametersCount);
        $iTotal         = call_user_func($this->model . '::countRecords', $parameters);

        // get properties of model class
        $class          = new \ReflectionClass($this->model);

        $response = [
            "sEcho"                 => intval(Request::input('sEcho')),
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
		    foreach ($this->aColumns as $aColumn)
            {
                $row[] = $aObject[$aColumn];
            }

            $actions = '<div><select id="re' . $aObject[$instance->getKeyName()] . '" data-resource="' . $aObject[$instance->getKeyName()] . '" data-nresource="' . $aObject['name_007'] . '" multiple style="width: 100%;">';
            foreach ($actionsAcl as $actionAcl)
            {
                $selected = $acl->isAllowed($parameters['profile'], $aObject['id_007'], $actionAcl->id_008)? ' selected' : null;
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
            'profile_009'       => $profile,
            'resource_009'      => $resource,
            'action_009'        => $action
        ]);
    }
    
    public function jsonDestroy($profile, $resource, $action)
    {
        Permission::deleteRecord($profile, $resource, $action);
    }
}