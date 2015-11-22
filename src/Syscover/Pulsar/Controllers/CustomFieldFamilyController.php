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
use Syscover\Pulsar\Models\CustomFieldFamily;

class CustomFieldFamilyController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'customFieldFamily';
    protected $folder       = 'field_family';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_025', 'name_007', 'name_025'];
    protected $nameM        = 'name_025';
    protected $model        = '\Syscover\Pulsar\Models\CustomFieldFamily';
    protected $icon         = 'fa fa-th';
    protected $objectTrans  = 'field_family';

    public function createCustomRecord($request, $parameters)
    {
        $parameters['resources'] = Resource::getResources(['active_012' => true]);

        return $parameters;
    }
    
    public function storeCustomRecord($request, $parameters)
    {
        CustomFieldFamily::create([
            'resource_025'  => $request->input('resource'),
            'name_025'      => $request->input('name')
        ]);
    }
    
    public function editCustomRecord($request, $parameters)
    {
        $parameters['resources'] = Resource::getResources(['active_012' => true]);

        return $parameters;
    }
    
    public function updateCustomRecord($request, $parameters)
    {
        CustomFieldFamily::where('id_025', $parameters['id'])->update([
            'resource_025'  => $request->input('resource'),
            'name_025'      => $request->input('name')
        ]);
    }
}