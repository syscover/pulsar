<?php namespace Pulsar\Pulsar\Controllers;

use Illuminate\Routing\Controller;

abstract class BaseController extends Controller {

	public function __construct()
	{

		//if (Session::get('configPulsar') == null)
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