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

use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\CustomFieldGroup;

class CustomFieldGroupController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'customFieldGroup';
    protected $folder       = 'field_group';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_025', 'name_007', 'name_025'];
    protected $nameM        = 'name_025';
    protected $model        = '\Syscover\Pulsar\Models\CustomFieldGroup';
    protected $icon         = 'fa fa-th';
    protected $objectTrans  = 'field_group';

    public function createCustomRecord($request, $parameters)
    {
        $parameters['resources'] = Resource::getRecords(['active_012' => true, 'whereIn' => ['column' => 'id_007', 'ids' => config('pulsar.resourcesCustomFields')]]);

        return $parameters;
    }
    
    public function storeCustomRecord($request, $parameters)
    {
        CustomFieldGroup::create([
            'resource_025'  => $request->input('resource'),
            'name_025'      => $request->input('name')
        ]);
    }
    
    public function editCustomRecord($request, $parameters)
    {
        $parameters['resources'] = Resource::getRecords(['active_012' => true]);

        return $parameters;
    }
    
    public function updateCustomRecord($request, $parameters)
    {
        CustomFieldGroup::where('id_025', $parameters['id'])->update([
            'resource_025'  => $request->input('resource'),
            'name_025'      => $request->input('name')
        ]);
    }
}