<?php namespace Pulsar\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Models\Package;
use Pulsar\Pulsar\Models\Resource;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Resources extends BaseController {

    use ControllerTrait;

    protected $resource = 'admin-pass-resource';
    protected $route    = 'resources';
    protected $folder   = 'resources';
    protected $package  = 'pulsar';
    protected $aColumns = ['id_007', 'name_012', 'name_007'];
    protected $nameM    = 'name_007';
    protected $model    = '\Pulsar\Pulsar\Models\Resource';

    public function create($inicio=0){

       
        return view('pulsar::pulsar.pulsar.recursos.create',array('inicio' => $inicio, 'modulos' =>  Package::get()));
    }
    
    public function store($inicio=0){

        
        $validation = Resource::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createResource',array($inicio))->withErrors($validation)->withInput();
        }
        else
        {
            Resource::create(array(
                'id_007'        => Input::get('id'),
                'modulo_007'    => Input::get('modulo'),
                'nombre_007'    => Input::get('nombre')
            )); 
            
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('recursos',array($inicio));
        }
    }
    
    public function edit($id, $inicio=0){

        
        $data['inicio'] = $inicio;
        $data['modulos'] = Package::get();
        $data['recurso'] = Resource::find($id);
                
        return view('pulsar::pulsar.pulsar.recursos.edit',$data);
    }
    
    public function update($inicio=0){
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = Resource::validate(Input::all(), $idRule);
        
        if ($validation->fails())
        {
            return Redirect::route('editResource',array(Input::get('idOld'), $inicio))->withErrors($validation);
        }
        else
        {
            Resource::where('id_007','=',Input::get('idOld'))->update(array(
                'id_007'        => Input::get('id'),
                'modulo_007'    => Input::get('modulo'),
                'nombre_007'    => Input::get('nombre')
            ));
                        
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('recursos',array($inicio));
        }
    }
}