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
    Pulsar\Pulsar\Models\Pais,
    Pulsar\Pulsar\Models\AreaTerritorial1;

class TerritorialAreas1 extends BaseController {
        
    private $resource = 'admin-country-at1';
    
    public function index($pais, $offset=0){

        
        //Inicializa las sesiones para las búsquedas rápidas desde la vista de tablas en caso de cambio de página
        Miscellaneous::sessionParamterSetPage($this->resource);
                
        //instanciamos la variable de inicio pasra sabel el punto de inicio en caso de borrado o edición, volver al mismo punto de la lista
        $data['recurso']        = $this->resource;
        $data['inicio']         = $offset;
        $data['pais']           = Pais::getPais($pais, Auth::user()->idioma_010); 
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.areas_territoriales_1.js.index';
       
        return view('pulsar::pulsar.pulsar.areas_territoriales_1.index',$data);
    }
    
    public function jsonData($pais){

        
        //Columnas para instanciar filtos de la tabla
	    $aColumns = array('id_003','nombre_003');
        $params = array();
        
        //Paginado de la tabla
        $params =  Miscellaneous::paginateDataTable($params);
	    
        //Orden de la tabla
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);
        
        //filtrados de la tabla
        $params =  Miscellaneous::filteringDataTable($params);
	        
        //Toma de datos para la tabla
        $objects        = AreaTerritorial1::getAreasTerritorialesLimit1($pais, $aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = AreaTerritorial1::getAreasTerritorialesLimit1($pais, $aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'])->count();
        $iTotal         = AreaTerritorial1::count();
        
        //cabecera JSON
        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => array()
        );
        
        $aOjects = $objects->toArray(); $i=0;
        foreach($aOjects as $aObject){
		$row = array();
		foreach ($aColumns as $aColumn){
            $row[] = $aObject[$aColumn];
		}
                $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_003'].'">';
                //Botones de acciones
                $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/areasterritoriales1/'.$aObject['id_003'].'/edit/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_registro').'"><i class="icon-pencil"></i></a>' : '';
                $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$pais.'\',\''.$aObject['id_003'].'\')" data-original-title="'.Lang::get('pulsar::pulsar.borrar_registro').'"><i class="icon-trash"></i></a>' : '';
		    $row[] =  $acciones;
                
            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
    
    public function create($pais, $offset=0){

        
        $data['inicio']     = $offset;
        $data['pais']       = Pais::getPais($pais, Session::get('idiomaBase')->id_001);
        return view('pulsar::pulsar.pulsar.areas_territoriales_1.create',$data);
    }
    
    public function store($pais, $offset=0){

        
        $validation = AreaTerritorial1::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createAreasTerritoriales1',array($pais, $offset))->withErrors($validation)->withInput();
        }
        else
        {
            AreaTerritorial1::create(array(
                'id_003'        => Input::get('id'),
                'pais_003'      => $pais,
                'nombre_003'    => Input::get('nombre')
            )); 
            
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('areasTerritoriales1',array($pais, $offset));
        }
    }
    
    public function edit($id, $offset=0){

        
        $data['inicio']             = $offset;
        $data['area_territorial_1'] = AreaTerritorial1::find($id);
        $data['pais']               = Pais::getPais($data['area_territorial_1']->pais_003, Session::get('idiomaBase')->id_001);
        
        return view('pulsar::pulsar.pulsar.areas_territoriales_1.edit',$data);
    }
    
    public function update($pais, $offset=0){
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = AreaTerritorial1::validate(Input::all(), $idRule);
        
        if ($validation->fails())
        {
            return Redirect::route('editAreasTerritoriales1',array(Input::get('idOld'), $offset))->withErrors($validation);
        }
        else
        {
            AreaTerritorial1::where('id_003','=',Input::get('idOld'))->update(array(
                'id_003'        => Input::get('id'),
                'nombre_003'  => Input::get('nombre')
            ));
                        
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('areasTerritoriales1',array($pais, $offset));
        }
    }

    public function delete($pais, $id){

        
        $areaTerrirorial1 = AreaTerritorial1::find($id);
        AreaTerritorial1::delete($id);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registro',array('nombre' => $areaTerrirorial1->nombre_003)));
        
        return Redirect::route('areasTerritoriales1',array($pais));
    }
    
    public function deleteSelect($pais){

        
        $nElements = Input::get('nElementsDataTable'); 
        $ids = array();
        for($i=0;$i<$nElements;$i++){
            if(Input::get('element'.$i) != false){
                array_push($ids, Input::get('element'.$i));
            }
        }
                
        AreaTerritorial1::deleteAreasTerritoriales1($ids);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición        
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registros'));
        
        return Redirect::route('areasTerritoriales1',array($pais));
    }
    
    public function jsonGetAreasTerritoriales1FromPais($id){
        $data['json'] = array();
        if($id!="null") $data['json'] = Pais::getPais($id, Session::get('idiomaBase')->id_001)->areasTerritoriales1()->get()->toJson();
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
}