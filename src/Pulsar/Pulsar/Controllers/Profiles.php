<?php
namespace Pulsar\Pulsar\Controllers;

use Illuminate\Support\Facades\App,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\URL,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Lang,
    Illuminate\Support\Facades\Redirect,
    Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Profiles extends BaseController {

    use ControllerTrait;

    protected $resource     = 'admin-perm-profile';
    protected $routeSuffix  = 'Profile';
    protected $folder       = 'profiles';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_006','name_006'];
    protected $nameM        = 'name_006';
    protected $model        = '\Pulsar\Pulsar\Models\Profile';

    private $rePermission   = 'admin-perm-perm';

    public function jsonCustomDataBeforeActions($aObject)
    {
        return Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->rePermission, 'access')? '<a class="btn btn-xs bs-tooltip" title="" href="' . route('permissions', [$aObject['id_006'], Input::get('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_permissions').'"><i class="icon-shield"></i></a>' : null;
    }
    
    public function store($offset = 0)
    {
        $validation = call_user_func($this->model . '::validate', Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('create' . $this->routeSuffix, $offset)->withErrors($validation)->withInput();
        }
        else
        {
            Perfil::create(array(
                'name_006'  => Input::get('name')
            ));

            return Redirect::route($this->routeSuffix, $offset)->with([
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_log_recorded', ['name' => Input::get('name')])
            ]);
        }
    }
    
    public function update($offset=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        $validation = Perfil::validate(Input::all());
        
        if ($validation->fails())
        {
            return Redirect::route('editPerfil',array(Input::get('id'), $offset))->withErrors($validation);
        }
        else
        {
            Perfil::where('id_006','=',Input::get('id'))->update(array(
                'nombre_006'  => Input::get('nombre')
            ));
                        
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('perfiles',array($offset));
        }
    }
}