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

use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Traits\TraitController;

class ResourceController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'resource';
    protected $folder       = 'resource';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_007', 'name_012', 'name_007'];
    protected $nameM        = 'name_007';
    protected $model        = '\Syscover\Pulsar\Models\Resource';
    protected $icon         = 'icomoon-icon-database';
    protected $objectTrans  = 'resource';

    public function createCustomRecord($request, $parameters)
    {
        $parameters['packages'] = Package::get();
        return $parameters;
    }
    
    public function storeCustomRecord($request, $parameters)
    {
        Resource::create([
            'id_007'        => $request->input('id'),
            'package_007'   => $request->input('package'),
            'name_007'      => $request->input('name')
        ]);
    }
    
    public function editCustomRecord($request, $parameters)
    {
        $parameters['packages'] = Package::get();

        return $parameters;
    }
    
    public function updateCustomRecord($request, $parameters)
    {
        Resource::where('id_007', $parameters['id'])->update([
            'id_007'        => $request->input('id'),
            'package_007'   => $request->input('package'),
            'name_007'      => $request->input('name')
        ]);
    }
}