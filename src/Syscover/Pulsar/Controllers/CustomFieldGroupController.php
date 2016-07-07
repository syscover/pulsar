<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\CustomFieldGroup;

/**
 * Class CustomFieldGroupController
 * @package Syscover\Pulsar\Controllers
 */

class CustomFieldGroupController extends Controller
{
    protected $routeSuffix  = 'customFieldGroup';
    protected $folder       = 'field_group';
    protected $package      = 'pulsar';
    protected $indexColumns     = ['id_025', 'name_007', 'name_025'];
    protected $nameM        = 'name_025';
    protected $model        = CustomFieldGroup::class;
    protected $icon         = 'fa fa-th';
    protected $objectTrans  = 'field_group';

    public function createCustomRecord($parameters)
    {
        $parameters['resources'] = Resource::builder()
            ->where('active_012', true)
            ->whereIn('id_007', config('pulsar.resourcesCustomFields'))
            ->get();

        return $parameters;
    }
    
    public function storeCustomRecord($parameters)
    {
        CustomFieldGroup::create([
            'resource_id_025'   => $this->request->input('resource'),
            'name_025'          => $this->request->input('name')
        ]);
    }
    
    public function editCustomRecord($parameters)
    {
        $parameters['resources'] = Resource::builder()
            ->where('active_012', true)
            ->whereIn('id_007', config('pulsar.resourcesCustomFields'))
            ->get();

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        CustomFieldGroup::where('id_025', $parameters['id'])->update([
            'resource_id_025'   => $this->request->input('resource'),
            'name_025'          => $this->request->input('name')
        ]);
    }
}