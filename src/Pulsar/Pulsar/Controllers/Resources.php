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
use Pulsar\Pulsar\Models\Package;
use Pulsar\Pulsar\Models\Resource;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Resources extends BaseController {

    use ControllerTrait;

    protected $resource     = 'admin-perm-resource';
    protected $routeSuffix  = 'Resource';
    protected $folder       = 'resources';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_007', 'name_012', 'name_007'];
    protected $nameM        = 'name_007';
    protected $model        = '\Pulsar\Pulsar\Models\Resource';

    public function createCustomRecord()
    {
        $data['packages'] = Package::get();
        return $data;
    }
    
    public function storeCustomRecord()
    {
        Resource::create([
            'id_007'        => Input::get('id'),
            'package_007'   => Input::get('package'),
            'name_007'      => Input::get('name')
        ]);
    }
    
    public function editCustomRecord($data)
    {
        $data['packages'] = Package::get();
        return $data;
    }
    
    public function updateCustomRecord($id)
    {
        Resource::where('id_007', $id)->update([
            'id_007'        => Input::get('id'),
            'package_007'   => Input::get('package'),
            'name_007'      => Input::get('name')
        ]);
    }
}