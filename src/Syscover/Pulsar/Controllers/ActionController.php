<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Models\Action;

/**
 * Class ActionController
 * @package Syscover\Pulsar\Controllers
 */

class ActionController extends Controller
{
    protected $routeSuffix  = 'action';
    protected $folder       = 'action';
    protected $package      = 'pulsar';
    protected $indexColumns     = ['id_008', 'name_008'];
    protected $nameM        = 'name_008';
    protected $model        = Action::class;
    protected $icon         = 'fa fa-bolt';
    protected $objectTrans  = 'action';
    
    public function storeCustomRecord($parameters)
    {
        Action::create([
            'id_008'    => $this->request->input('id'),
            'name_008'  => $this->request->input('name')
        ]);
    }
    
    public function updateCustomRecord($parameters)
    {
        Action::where('id_008', $parameters['id'])->update([
            'id_008'    => $this->request->input('id'),
            'name_008'  => $this->request->input('name')
        ]);
    }
}