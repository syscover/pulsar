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
        'show'      => false,
        'edit'      => true,
        'delete'    => true
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