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
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Libraries\PulsarAcl;
use Pulsar\Pulsar\Models\Permission;
use Pulsar\Pulsar\Models\Profile;
use Pulsar\Pulsar\Models\Action;
use Pulsar\Pulsar\Models\Resource;
use Pulsar\Pulsar\Traits\ControllerTrait;


class Permissions extends BaseController
{
    use ControllerTrait;

    protected $routeSuffixProfile   = 'Profile';
    protected $hideDeleteDataTable = true;

    protected $resource             = 'admin-perm-perm';
    protected $routeSuffix          = 'Permission';
    protected $folder               = 'permissions';
    protected $package              = 'pulsar';
    protected $aColumns             = ['id_007', 'name_012', 'name_007'];
    protected $nameM                = 'name_008';
    protected $model                = '\Pulsar\Pulsar\Models\Action';
    
    public function indexCustom($data,  $args)
    {
        $data['profile']                = Profile::find($args[1]);
        $data['offsetProfile']          = $args[2];
        $data['hideDeleteDataTable']    = $this->hideDeleteDataTable;
        $data['routeSuffixProfile']     = $this->routeSuffixProfile;

        return $data;
    }

    public function jsonData($profile)
    {
        $actionsAcl     = Action::get();
        $acl            = PulsarAcl::getProfileAcl($profile);

        $params         =  Miscellaneous::paginateDataTable();
        $params         =  Miscellaneous::dataTableSorting($params, $this->aColumns);
        $params         =  Miscellaneous::filteringDataTable($params);


        $objects        = Resource::getRecordsLimit($this->aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Resource::getRecordsLimit($this->aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'], null, true);
        $iTotal         = Resource::count();

        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => []
        );

        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject)
        {
		    $row = [];
		    foreach ($this->aColumns as $aColumn)
            {
                $row[] = $aObject[$aColumn];
            }

            $actions = '<div><select id="re' . $aObject['id_007'] . '" data-resource="' . $aObject['id_007'] . '" data-nresource="' . $aObject['name_007'] . '" multiple style="width: 100%;">';
            foreach ($actionsAcl as $actionAcl)
            {
                $selected = $acl->isAllowed($profile, $aObject['id_007'], $actionAcl->id_008)? ' selected' : null;
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