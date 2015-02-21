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
use Pulsar\Pulsar\Models\Action;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Actions extends BaseController {

    use ControllerTrait;

    protected $routeSuffix  = 'Action';
    protected $folder       = 'actions';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_008', 'name_008'];
    protected $nameM        = 'name_008';
    protected $model        = '\Pulsar\Pulsar\Models\Action';
    protected $icon         = 'icomoon-icon-power';
    protected $objectTrans  = 'action';

    public function storeCustomRecord()
    {
        Action::create([
            'id_008'    => Input::get('id'),
            'name_008'  => Input::get('name')
        ]);
    }
    
    public function updateCustomRecord($id)
    {
        Action::where('id_008', $id)->update([
            'id_008'    => Input::get('id'),
            'name_008'  => Input::get('name')
        ]);
    }
}