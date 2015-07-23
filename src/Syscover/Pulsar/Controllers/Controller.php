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

		//if (session('configPulsar') == null)
		//{
		//	$data = array();
		//	$configs = ConfigPulsar::all();
		//	foreach ($configs as $config)
		//	{
		//		$data[$config->id_018] = $config->value_018;
		//	}
		//	Session::put('configPulsar', $data);
		//}
	}
}