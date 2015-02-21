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

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Traits\ControllerTrait;

class Packages extends BaseController {

    use ControllerTrait;

    protected $routeSuffix  = 'Package';
    protected $folder       = 'packages';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_012', 'name_012', ['data' => 'active_012', 'type' => 'active']];
    protected $nameM        = 'name_012';
    protected $model        = '\Syscover\Pulsar\Models\Package';
    protected $icon         = 'cut-icon-grid';
    protected $objectTrans  = 'package';

    public function storeCustomRecord($parameters)
    {
        Package::create([
            'name_012'      => Input::get('name'),
            'active_012'    => Input::get('active', 0)
        ]);
    }

    public function updateCustomRecord($parameters)
    {
        Package::where('id_012', $parameters['id'])->update([
            'name_012'      => Input::get('name'),
            'active_012'    => Input::get('active', 0)
        ]);

        // update object packages from session
        Session::put('packages', Package::getModulesForSession());
    }
}

