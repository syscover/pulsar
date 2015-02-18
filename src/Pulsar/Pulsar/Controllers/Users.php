<?php
namespace Pulsar\Pulsar\Controllers;

use Illuminate\Support\Facades\App,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\URL,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Lang,
    Illuminate\Support\Facades\View,
    Illuminate\Support\Facades\Redirect,
    Illuminate\Support\Facades\Hash,
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Pulsar\Pulsar\Models\Idioma,
    Pulsar\Pulsar\Models\Usuario,
    Pulsar\Pulsar\Models\Perfil;

class Usuarios extends BaseController {
    
    private $resource = 'admin-user';
    
    public function index($offset=0)
    {

        
        //Inicializa las sesiones para las búsquedas rápidas desde la vista de tablas en caso de cambio de página
        Miscellaneous::sessionParamterSetPage($this->resource);
  
        $data['recurso']        = $this->resource; // Variable para instanciar permisos
        $data['inicio']         = $offset;
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.usuarios.js.index';
        
        return view('pulsar::pulsar.pulsar.usuarios.index',$data);
    }
    
    public function jsonData()
    {

        
        //Columnas para instanciar filtos de la tabla
	    $aColumns = array('id_010','nombre_010','apellidos_010','email_010','nombre_006','acceso_010');
        $params = array();
        
        //Paginado de la tabla
        $params =  Miscellaneous::paginateDataTable($params);
	    
        //Orden de la tabla
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);
        
        //filtrados de la tabla
        $params =  Miscellaneous::filteringDataTable($params);
	        
        //Toma de datos para la tabla
        $objects        = Usuario::getUsuariosLimit($aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Usuario::getUsuariosLimit($aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'])->count();
        $iTotal         = Usuario::count();
        
        //cabecera JSON
        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => array()
        );
        
        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject)
        {
		    $row = array();
		    foreach ($aColumns as $aColumn)
            {
                if($aColumn == "email_010")
                {
                    $row[] = '<a href="mailto:'.$aObject[$aColumn].'">'.$aObject[$aColumn].'</a>';
                }
                elseif($aColumn == "acceso_010")
                {
                    if($aObject[$aColumn] == 1)
                        $row[] = '<i class="icomoon-icon-checkmark-3"></i>';
                    else
                        $row[] = '<i class="icomoon-icon-blocked"></i>';

                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
		    }

            $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_010'].'">';

            //Botones de acciones
            $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/usuarios/'.$aObject['id_010'].'/edit/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_registro').'"><i class="icon-pencil"></i></a>' : '';
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$aObject['id_010'].'\')" data-original-title="'.Lang::get('pulsar::pulsar.borrar_registro').'"><i class="icon-trash"></i></a>' : '';
		    $row[] =  $acciones;
                
            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
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

        
        $validation = Usuario::validate(Input::all(), true, true, true);
              
        if ($validation->fails())
        {
            return Redirect::route('createUsuario',array($offset))->withErrors($validation)->withInput();
        }
        else
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
            
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('usuarios', array($offset));
        }
    }
    
    public function edit($id, $offset=0)
    {

        
        $data['idiomas']    = Idioma::getIdiomasActivos();
        $data['perfiles']   = Perfil::get();
        $data['inicio']     = $offset;
        $data['usuario']    = Usuario::find($id);
        
        return view('pulsar::pulsar.pulsar.usuarios.edit', $data);
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

    public function delete($id)
    {
        i
        
        $usuario = Usuario::find($id);
        Usuario::destroy($id);

        return Redirect::route('usuarios')->with(array(
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.borrado_registro',array('nombre' => $usuario->nombre_010))
        ));
    }
    
    public function deleteSelect($offset=0)
    {
        i
        
        $nElements = Input::get('nElementsDataTable'); 
        
        $ids = array();
        for($i=0;$i<$nElements;$i++)
        {
            if(Input::has('element'.$i))
            {
                array_push($ids, Input::get('element'.$i));
            }
        }
                
        Usuario::deleteUsuarios($ids);

        return Redirect::route('usuarios')->with(array(
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.borrado_registros')
        ));
    }
}