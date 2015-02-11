<?php namespace Pulsar\Pulsar\Controllers;

use Illuminate\Support\Facades\App,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\URL,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Lang,
    Illuminate\Support\Facades\View,
    Illuminate\Support\Facades\Redirect,
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Pulsar\Pulsar\Models\Modulo;

class Dashboard extends BaseController
{
    //Variable sin uso
    //private $resource = 'admin-dashboard';
    
    public function index($inicio=0)
    {
        try
        {
            //

            return view('pulsar::dashboard.index');

            // $view =  view('pulsar::dashboard.index');
            
            //renderizamos la vista que para que compruebe que no varibles de recursos por dar de alta en la base de datos
            //$view->render();
            
            //return $view;
            
        }
        catch (\ErrorException $e)
        {
            {{ 'Error Falta dar de alta un recurso: <br>' . $e->getMessage(); }}
            
        } catch (\Zend\Permissions\Acl\Exception\InvalidArgumentException $e){
            
            {{ 'Error no tiene permisos para acceder al escritorio'; }}
        }
    }
}

