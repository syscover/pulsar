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
use Syscover\Pulsar\Models\Action;
use Syscover\Pulsar\Traits\TraitController;

class Actions extends Controller {

    use TraitController;

    protected $routeSuffix  = 'Action';
    protected $folder       = 'actions';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_008', 'name_008'];
    protected $nameM        = 'name_008';
    protected $model        = '\Syscover\Pulsar\Models\Action';
    protected $icon         = 'icomoon-icon-power';
    protected $objectTrans  = 'action';

    public function storeCustomRecord($parameters)
    {
        Action::create([
            'id_008'    => Request::input('id'),
            'name_008'  => Request::input('name')
        ]);
    }
    
    public function updateCustomRecord($parameters)
    {
        Action::where('id_008', $parameters['id'])->update([
            'id_008'    => Request::input('id'),
            'name_008'  => Request::input('name')
        ]);
    }
}