<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

/**
 * Class Controller
 * @package Syscover\Pulsar\Controllers
 */

abstract class Controller extends BaseController {

    protected $resource;
    protected $request;
    protected $viewParameters = [
        'newButton'             => true,    // button from index view to create record
        'checkBoxColumn'        => true,    // checkbox from index view to select various records
        'showButton'            => false,   // button from ajax response, to view record
        'editButton'            => true,    // button from ajax response, to edit record
        'deleteButton'          => true,    // button from ajax response, to delete record
        'deleteSelectButton'    => true     // button delete records when select checkbox on index view
    ];

	public function __construct(Request $request)
	{
        // set request to all controller methods
        $this->request = $request;

        $action = $request->route()->getAction();

        if(isset($action['resource']))
        {
            $this->resource = $request->route()->getAction()['resource'];
        }
	}
}