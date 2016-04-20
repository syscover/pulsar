<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;

/**
 * Class DashboardController
 * @package Syscover\Pulsar\Controllers
 */

class DashboardController extends Controller
{
    protected $folder       = 'dashboard';
    protected $package      = 'pulsar';
    
    public function index()
    {
        $data['package']        = $this->package;
        $data['folder']         = $this->folder;

        return view('pulsar::dashboard.index', $data);
    }
}