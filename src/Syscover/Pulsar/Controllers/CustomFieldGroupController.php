<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\CustomFieldGroup;

/**
 * Class CustomFieldGroupController
 * @package Syscover\Pulsar\Controllers
 */

class CustomFieldGroupController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'customFieldGroup';
    protected $folder       = 'field_group';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_025', 'name_007', 'name_025'];
    protected $nameM        = 'name_025';
    protected $model        = \Syscover\Pulsar\Models\CustomFieldGroup::class;
    protected $icon         = 'fa fa-th';
    protected $objectTrans  = 'field_group';

    public function createCustomRecord($request, $parameters)
    {
        $parameters['resources'] = Resource::builder()
            ->where('active_012', true)
            ->whereIn('id_007', config('pulsar.resourcesAttachments'))
            ->get();

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
        $parameters['resources'] = Resource::builder()
            ->where('active_012', true)
            ->whereIn('id_007', config('pulsar.resourcesAttachments'))
            ->get();

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