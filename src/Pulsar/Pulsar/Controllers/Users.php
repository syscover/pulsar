<?php
namespace Pulsar\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\App,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\Lang,
    Illuminate\Support\Facades\Redirect,
    Illuminate\Support\Facades\Hash,
    Pulsar\Pulsar\Models\User,
    Pulsar\Pulsar\Models\Profile;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Users extends BaseController {

    use ControllerTrait;

    protected $routeSuffix  = 'User';
    protected $folder       = 'users';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_010', 'name_010', 'surname_010', 'email_010', 'name_006', 'access_010'];
    protected $nameM        = 'name_010';
    protected $model        = '\Pulsar\Pulsar\Models\User';
    protected $icon         = 'icomoon-icon-users';
    protected $objectTrans  = 'user';
    

    public function jsonDataXXX()
    {
        if($aColumn == "email_010")
        {
            $row[] = '<a href="mailto:'.$aObject[$aColumn].'">'.$aObject[$aColumn].'</a>';
        }
    }
    
    public function create($offset=0)
    {
        $data['idiomas']    = Idioma::getIdiomasActivos();
        $data['perfiles']   = Perfil::get();
        
        $data['inicio']     = $offset;
        return view('pulsar::pulsar.pulsar.usuarios.create',$data);
    }
    
    public function store($offset=0)
    {

        

            Usuario::create(array(
                'idioma_010'    => Input::get('idioma'),
                'profile_010'    => Input::get('perfil'),
                'acceso_010'    => Input::get('acceso',0),
                'user_010'      => Input::get('user'),
                'password_010'  => Hash::make(Input::get('password')),
                'email_010'     => Input::get('email'),
                'nombre_010'    => Input::get('nombre'),
                'apellidos_010' => Input::get('apellidos'),
            )); 
            

    }
    
    public function edit($id, $offset=0)
    {

        
        $data['idiomas']    = Idioma::getIdiomasActivos();
        $data['perfiles']   = Perfil::get();
        $data['inicio']     = $offset;
        $data['usuario']    = Usuario::find($id);
        

    }
    
    public function update($offset=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        $usuario = Usuario::find(Input::get('id'));

        if(Input::get('email') == $usuario->email_010)  $emailRule = false; else $emailRule = true;
        if(Input::get('user') == $usuario->user_010)    $userRule = false; else $userRule = true;
        if(Input::get('password'))                      $passRule = true; else $passRule = false;
        
        $validation = Usuario::validate(Input::all(), $passRule, $userRule, $emailRule);
        
        if ($validation->fails())
        {
            return Redirect::route('editUsuario',array(Input::get('id'), $offset))->withErrors($validation);
        }
        else
        {
            $usuarioData = array(
                'idioma_010'    => Input::get('idioma'),
                'profile_010'    => Input::get('perfil'),
                'acceso_010'    => Input::get('acceso',0),
                'nombre_010'    => Input::get('nombre'),
                'apellidos_010' => Input::get('apellidos'),
            );
            if($emailRule)  $usuarioData['email_010'] = Input::get('email');
            if($userRule)   $usuarioData['user_010'] = Input::get('user');
            if($passRule)   $usuarioData['password_010'] = Hash::make(Input::get('password'));
                
            Usuario::where('id_010','=',Input::get('id'))->update($usuarioData);

            return Redirect::route('usuarios', array($offset))->with(array(
                'msg'        => 1,
                'txtMsg'     => Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre')))
            ));
        }
    }
}