<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Traits\TraitController;

/**
 * Class ResourceController
 * @package Syscover\Pulsar\Controllers
 */

class ResourceController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'resource';
    protected $folder       = 'resource';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_007', 'name_012', 'name_007'];
    protected $nameM        = 'name_007';
    protected $model        = Resource::class;
    protected $icon         = 'icomoon-icon-database';
    protected $objectTrans  = 'resource';

    public function createCustomRecord($parameters)
    {
        $parameters['packages'] = Package::all();

        return $parameters;
    }
    
    public function storeCustomRecord($parameters)
    {
        Resource::create([
            'id_007'        => $this->request->input('id'),
            'package_007'   => $this->request->input('package'),
            'name_007'      => $this->request->input('name')
        ]);
    }
    
    public function editCustomRecord($parameters)
    {
        $parameters['packages'] = Package::all();

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        Resource::where('id_007', $parameters['id'])->update([
            'id_007'        => $this->request->input('id'),
            'package_007'   => $this->request->input('package'),
            'name_007'      => $this->request->input('name')
        ]);
    }
}