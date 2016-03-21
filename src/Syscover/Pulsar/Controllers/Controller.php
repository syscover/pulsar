<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

/**
 * Class Controller
 * @package Syscover\Pulsar\Controllers
 */

abstract class Controller extends BaseController {

    protected $resource;
    protected $viewParameters = [
        'checkBoxColumn'        => true,
        'showButton'            => false,
        'editButton'            => true,
        'deleteButton'          => true,
        'deleteSelectButton'    => true     // button delete records when select checkbox on index view
    ];

	public function __construct(Request $request)
	{
        $action = $request->route()->getAction();

        if(isset($action['resource']))
        {
            $this->resource = $request->route()->getAction()['resource'];
        }
	}
}