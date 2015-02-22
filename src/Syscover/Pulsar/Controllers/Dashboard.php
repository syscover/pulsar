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

class Dashboard extends Controller
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