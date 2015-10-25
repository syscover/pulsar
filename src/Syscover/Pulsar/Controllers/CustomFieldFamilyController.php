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
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\CustomFieldFamily;

class CustomFieldFamilyController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'CustomFieldFamily';
    protected $folder       = 'field_family';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_025', 'name_025', 'name_025', 'data_025'];
    protected $nameM        = 'name_025';
    protected $model        = '\Syscover\Pulsar\Models\CustomFieldFamily';
    protected $icon         = 'fa fa-th';
    protected $objectTrans  = 'field_family';

    public function createCustomRecord($parameters)
    {
        $parameters['resources'] = Resource::getResources(['active_012' => true]);

        return $parameters;
    }
    
    public function storeCustomRecord($parameters)
    {
        CustomFieldFamily::create([
            'resource_025'  => Request::input('resource'),
            'name_025'      => Request::input('name')
        ]);
    }
    
    public function editCustomRecord($parameters)
    {
        $parameters['resources'] = Resource::getResources(['active_012' => true]);

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        CustomFieldFamily::where('id_025', $parameters['id'])->update([
            'resource_025'  => Request::input('resource'),
            'name_025'      => Request::input('name')
        ]);
    }
}