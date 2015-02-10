<?php namespace Pulsar\Pulsar\Controllers;

use Illuminate\Routing\Controller;

abstract class BaseController extends Controller {

	public function __construct()
	{
		//if (Session::get('baseLang') == null)
		//{
		//	Session::put('baseLang', Language::getIdiomaBase());
		//}

		//Config::set('pulsar::application.language', Session::get('baseLang')->id_001);

		/**
		 *
		 * Modelo de tabla de parametros de configuración propios de la aplicación.
		 * Nos aseguramos que esté creado en la session
		 *
		 */
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