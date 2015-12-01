<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

abstract class Controller extends BaseController {

    protected $resource;

	public function __construct(Request $request)
	{
        $action = $request->route()->getAction();

        if(isset($action['resource']))
        {
            $this->resource = $request->route()->getAction()['resource'];
        }
	}
}