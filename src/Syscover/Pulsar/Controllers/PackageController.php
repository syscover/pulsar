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
use Syscover\Pulsar\Traits\TraitController;

class PackageController extends Controller {

    use TraitController;

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
            'name_012'      => Request::input('name'),
            'active_012'    => Request::input('active', 0)
        ]);
    }

    public function updateCustomRecord($parameters)
    {
        Package::where('id_012', $parameters['id'])->update([
            'name_012'      => Request::input('name'),
            'active_012'    => Request::input('active', 0)
        ]);

        // update object packages from session
        session(['packages' => Package::getModulesForSession()]);
    }
}

