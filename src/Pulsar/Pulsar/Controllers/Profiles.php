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
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Pulsar\Pulsar\Models\Perfil;

class Perfiles extends BaseController
{
    private $resource    = 'admin-pass-profile';
    private $rePermisos = 'admin-pass-pass';
    
    public function index($inicio=0)
    {

        
        //Inicializa las sesiones para las búsquedas rápidas desde la vista de tablas en caso de cambio de página
        Miscellaneous::sessionParamterSetPage($this->resource);
               
        //instanciamos la variable de inicio pasra sabel el punto de inicio en caso de borrado o edición, volver al mismo punto de la lista
        $data['recurso']        = $this->resource;
        $data['inicio']         = $inicio; 
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.perfiles.js.index';
        
        //$data['re_permisos'] = $this->re_permisos;
        return View::make('pulsar::pulsar.pulsar.perfiles.index',$data);
    }
    
    public function jsonData()
    {

        
        //Columnas para instanciar filtos de la tabla
	    $aColumns = array('id_006','nombre_006');
        $params = array();
        
        //Paginado de la tabla
        $params =  Miscellaneous::paginateDataTable($params);
	    
        //Orden de la tabla
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);
        
        //filtrados de la tabla
        $params =  Miscellaneous::filteringDataTable($params);
	        
        //Toma de datos para la tabla
        $objects       = Perfil::getPerfilesLimit($aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Perfil::getPerfilesLimit($aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'])->count();
        $iTotal         = Perfil::count();
        
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
                $row[] = $aObject[$aColumn];
		    }

            $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_006'].'">';
            //Botones de acciones
            $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->rePermisos,'access')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/permisos/'.$aObject['id_006'].'/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_permisos').'"><i class="icon-shield"></i></a>' : '';
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/perfiles/'.$aObject['id_006'].'/edit/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_registro').'"><i class="icon-pencil"></i></a>' : '';
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$aObject['id_006'].'\')" data-original-title="'.Lang::get('pulsar::pulsar.borrar_registro').'"><i class="icon-trash"></i></a>' : '';
		    $row[] =  $acciones;
                
            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return View::make('pulsar::pulsar.pulsar.common.json_display',$data);
    }
    
    public function create($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
        $data['inicio'] = $inicio;
        return View::make('pulsar::pulsar.pulsar.perfiles.create',$data);
    }
    
    public function store($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
        $validation = Perfil::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createPerfil',array($inicio))->withErrors($validation)->withInput();
        }
        else
        {
            Perfil::create(array(
                'nombre_006'  => Input::get('nombre')
            )); 
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('perfiles',array($inicio));
        }
    }
    
    public function edit($id, $inicio=0)
    {

        
        $data['inicio'] = $inicio;
        $data['perfil'] = Perfil::find($id);
        
        return View::make('pulsar::pulsar.pulsar.perfiles.edit',$data);
    }
    
    public function update($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        $validation = Perfil::validate(Input::all());
        
        if ($validation->fails())
        {
            return Redirect::route('editPerfil',array(Input::get('id'), $inicio))->withErrors($validation);
        }
        else
        {
            Perfil::where('id_006','=',Input::get('id'))->update(array(
                'nombre_006'  => Input::get('nombre')
            ));
                        
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('perfiles',array($inicio));
        }
    }

    public function destroy($id)
    {

        
        $perfil = Perfil::find($id);
        Perfil::destroy($id);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registro',array('nombre' => $perfil->nombre_006)));
        
        return Redirect::route('perfiles');
    }
    
    public function destroySelect($inicio=0)
    {

        
        $nElements = Input::get('nElementsDataTable'); 
        $ids = array();
        for($i=0;$i<$nElements;$i++){
            if(Input::get('element'.$i) != false){
                array_push($ids, Input::get('element'.$i));
            }
        }
                
        Perfil::deletePerfiles($ids);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición        
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registros'));
        
        return Redirect::route('perfiles');
    }
}