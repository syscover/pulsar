<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Models\Package;

/**
 * Class PackageController
 * @package Syscover\Pulsar\Controllers
 */

class PackageController extends Controller
{
    protected $routeSuffix  = 'package';
    protected $folder       = 'package';
    protected $package      = 'pulsar';
    protected $indexColumns     = ['id_012', 'name_012', 'folder_012', ['data' => 'active_012', 'type' => 'active'], 'sorting_012'];
    protected $nameM        = 'name_012';
    protected $model        = Package::class;
    protected $icon         = 'cut-icon-grid';
    protected $objectTrans  = 'package';

    public function storeCustomRecord($parameters)
    {
        Package::create([
            'name_012'      => $this->request->input('name'),
            'folder_012'    => $this->request->input('folder'),
            'active_012'    => $this->request->input('active', 0),
            'sorting_012'   => $this->request->input('sorting')
        ]);
    }

    public function updateCustomRecord($parameters)
    {
        Package::where('id_012', $parameters['id'])->update([
            'name_012'      => $this->request->input('name'),
            'folder_012'    => $this->request->input('folder'),
            'active_012'    => $this->request->input('active', 0),
            'sorting_012'   => $this->request->input('sorting')
        ]);

        // update object packages from session
        session(['packages' => Package::getRecords(['active_012' => true, 'orderBy' => ['column' => 'sorting_012', 'order' => 'desc']])]);
    }
}