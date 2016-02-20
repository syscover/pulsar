<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Models\Action;
use Syscover\Pulsar\Traits\TraitController;

/**
 * Class ActionController
 * @package Syscover\Pulsar\Controllers
 */

class ActionController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'action';
    protected $folder       = 'action';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_008', 'name_008'];
    protected $nameM        = 'name_008';
    protected $model        = Action::class;
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