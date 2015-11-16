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

    protected $routeSuffix  = 'package';
    protected $folder       = 'package';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_012', 'name_012', 'folder_012', ['data' => 'active_012', 'type' => 'active'], 'sorting_012'];
    protected $nameM        = 'name_012';
    protected $model        = '\Syscover\Pulsar\Models\Package';
    protected $icon         = 'cut-icon-grid';
    protected $objectTrans  = 'package';

    public function storeCustomRecord($request, $parameters)
    {
        Package::create([
            'name_012'      => $request->input('name'),
            'folder_012'    => $request->input('folder'),
            'active_012'    => $request->input('active', 0),
            'sorting_012'    => $request->input('sorting')
        ]);
    }

    public function updateCustomRecord($request, $parameters)
    {
        Package::where('id_012', $parameters['id'])->update([
            'name_012'      => $request->input('name'),
            'folder_012'    => $request->input('folder'),
            'active_012'    => $request->input('active', 0),
            'sorting_012'    => $request->input('sorting')
        ]);

        // update object packages from session
        session(['packages' => Package::getRecords(['active_012' => true, 'orderBy' => ['column' => 'sorting_012', 'order' => 'desc']])]);
    }
}

