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

use Syscover\Pulsar\Models\Action;
use Syscover\Pulsar\Traits\TraitController;

class ActionController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'action';
    protected $folder       = 'action';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_008', 'name_008'];
    protected $nameM        = 'name_008';
    protected $model        = '\Syscover\Pulsar\Models\Action';
    protected $icon         = 'fa fa-bolt';
    protected $objectTrans  = 'action';

    public function storeCustomRecord($request, $parameters)
    {
        Action::create([
            'id_008'    => $request->input('id'),
            'name_008'  => $request->input('name')
        ]);
    }
    
    public function updateCustomRecord($request, $parameters)
    {
        Action::where('id_008', $parameters['id'])->update([
            'id_008'    => $request->input('id'),
            'name_008'  => $request->input('name')
        ]);
    }
}