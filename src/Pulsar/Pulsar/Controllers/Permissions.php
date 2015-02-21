<?php namespace Pulsar\Pulsar\Controllers;

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
use Illuminate\Http\Request;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Libraries\PulsarAcl;
use Pulsar\Pulsar\Models\Permission;
use Pulsar\Pulsar\Models\Profile;
use Pulsar\Pulsar\Models\Action;
use Pulsar\Pulsar\Traits\ControllerTrait;


class Permissions extends BaseController
{
    use ControllerTrait;

    protected $routeSuffixProfile   = 'Profile';
    protected $areDeleteRecord      = true;

    protected $routeSuffix          = 'Permission';
    protected $folder               = 'permissions';
    protected $package              = 'pulsar';
    protected $aColumns             = ['id_007', 'name_012', 'name_007'];
    protected $nameM                = 'name_008';
    protected $model                = '\Pulsar\Pulsar\Models\Resource';
    protected $icon                 = 'icon-shield';
    protected $objectTrans          = 'permission';
    
    public function indexCustom($parameters)
    {
        $parameters['profile']                = Profile::find($parameters['profile']);
        $parameters['areDeleteRecord']        = $this->areDeleteRecord;
        $parameters['routeSuffixProfile']     = $this->routeSuffixProfile;

        return $parameters;
    }

    public function jsonData(Request $request)
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

        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => []
        );

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

            $output['aaData'][] = $row;
            $i++;
	    }

        $data['json'] = json_encode($output);

        return view('pulsar::common.json_display', $data);
    }

    public function jsonCreate($profile, $resource, $action)
    {
        Permission::create(array(
            'profile_009'       => $profile,
            'resource_009'      => $resource,
            'action_009'        => $action
        ));
    }
    
    public function jsonDestroy($profile, $resource, $action)
    {
        Permission::deleteRecord($profile, $resource, $action);
    }
}