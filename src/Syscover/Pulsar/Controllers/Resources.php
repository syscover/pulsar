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
use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Traits\ControllerTrait;

class Resources extends Controller {

    use ControllerTrait;

    protected $routeSuffix  = 'Resource';
    protected $folder       = 'resources';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_007', 'name_012', 'name_007'];
    protected $nameM        = 'name_007';
    protected $model        = '\Syscover\Pulsar\Models\Resource';
    protected $icon         = 'icomoon-icon-database';
    protected $objectTrans  = 'resource';

    public function createCustomRecord($parameters)
    {
        $parameters['packages'] = Package::get();
        return $parameters;
    }
    
    public function storeCustomRecord($parameters)
    {
        Resource::create([
            'id_007'        => Request::input('id'),
            'package_007'   => Request::input('package'),
            'name_007'      => Request::input('name')
        ]);
    }
    
    public function editCustomRecord($parameters)
    {
        $parameters['packages'] = Package::get();
        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        Resource::where('id_007', $parameters['id'])->update([
            'id_007'        => Request::input('id'),
            'package_007'   => Request::input('package'),
            'name_007'      => Request::input('name')
        ]);
    }
}