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
    Illuminate\Support\Facades\Event,
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Pulsar\Pulsar\Models\Pais,
    Pulsar\Pulsar\Models\AreaTerritorial1,
    Pulsar\Pulsar\Models\AreaTerritorial2;

class AreasTerritoriales2 extends BaseController {
     
    private $resource = 'admin-country-at2';
    
    public function index($pais, $offset=0){

        
        //Inicializa las sesiones para las búsquedas rápidas desde la vista de tablas en caso de cambio de página
        Miscellaneous::sessionParamterSetPage($this->resource);
                
        //instanciamos la variable de inicio pasra sabel el punto de inicio en caso de borrado o edición, volver al mismo punto de la lista
        $data['recurso']        = $this->resource;
        $data['inicio']         = $offset;
        $data['pais']           = Pais::getPais($pais, Auth::user()->idioma_010);
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.areas_territoriales_2.js.index';
       
        return view('pulsar::pulsar.pulsar.areas_territoriales_2.index',$data);
    }
    
    public function jsonData($pais)
    {

        
        //Columnas para instanciar filtos de la tabla
	    $aColumns = array('id_004','nombre_003','nombre_004');
        $params = array();
        
        //Paginado de la tabla
        $params =  Miscellaneous::paginateDataTable($params);
	    
        //Orden de la tabla
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);
        
        //filtrados de la tabla
        $params =  Miscellaneous::filteringDataTable($params);
	        
        //Toma de datos para la tabla
        $objects        = AreaTerritorial2::getAreasTerritorialesLimit2($pais, $aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = AreaTerritorial2::getAreasTerritorialesLimit2($pais, $aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'])->count();
        $iTotal         = AreaTerritorial2::count();
        
        //cabecera JSON
        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => array()
        );
        
        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject){
		$row = array();
		foreach ($aColumns as $aColumn){
                    $row[] = $aObject[$aColumn];   
		}
                $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_004'].'">';
                //Botones de acciones
                $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/areasterritoriales2/'.$aObject['id_004'].'/edit/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_registro').'"><i class="icon-pencil"></i></a>' : '';
                $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$pais.'\',\''.$aObject['id_004'].'\')" data-original-title="'.Lang::get('pulsar::pulsar.borrar_registro').'"><i class="icon-trash"></i></a>' : '';
		$row[] =  $acciones;
                
                $output['aaData'][] = $row;
                $i++;
	}
                
        $data['json'] = json_encode($output);
        
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
    
    public function create($pais, $offset=0){

        
        $data['inicio']                 = $offset;
        $data['pais']                   = Pais::getPais($pais, Auth::user()->idioma_010);
        $data['areasTerritoriales1']    = AreaTerritorial1::getAllAreasTerritoriales1($pais);
        
        return view('pulsar::pulsar.pulsar.areas_territoriales_2.create',$data);
    }
       
    public function store($pais, $offset=0){

        
        $validation = AreaTerritorial2::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createAreasTerritoriales2',array($pais, $offset))->withErrors($validation)->withInput();
        }
        else
        {
            AreaTerritorial2::create(array(
                'id_004'                    => Input::get('id'),
                'pais_004'                  => $pais,
                'area_territorial_1_004'    => Input::get('areaTerritorial1'),
                'nombre_004'                => Input::get('nombre')
            )); 
            
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('areasTerritoriales2',array($pais, $offset));
        }
    }
    
    public function edit($id, $offset=0){

        
        $data['inicio']                 = $offset;
        $data['areaTerritorial2']       = AreaTerritorial2::find($id);
        $data['pais']                   = Pais::getPais($data['areaTerritorial2']->pais_004, Auth::user()->idioma_010);
        $data['areasTerritoriales1']    = AreaTerritorial1::getAllAreasTerritoriales1($data['pais']->id_002);
        
        return view('pulsar::pulsar.pulsar.areas_territoriales_2.edit',$data);
    }
    
    public function update($pais, $offset=0){
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = AreaTerritorial2::validate(Input::all(), $idRule);
        
        if ($validation->fails())
        {
            return Redirect::route('editAreasTerritoriales2',array(Input::get('idOld'), $offset))->withErrors($validation);
        }
        else
        {
            AreaTerritorial2::where('id_004','=',Input::get('idOld'))->update(array(
                'id_004'                    => Input::get('id'),
                'area_territorial_1_004'    => Input::get('areaTerritorial1'),
                'nombre_004'                => Input::get('nombre')
            ));
                        
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('areasTerritoriales2',array($pais, $offset));
        }
    }

    public function delete($pais, $id){
        i
        
        $areaTerrirorial2 = AreaTerritorial2::find($id);
        AreaTerritorial2::delete($id);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registro',array('nombre' => $areaTerrirorial2->nombre_004)));
        
        return Redirect::route('areasTerritoriales2',array($pais));
    }
    
    public function deleteSelect($pais, $offset=0){
        i
        
        $nElements = Input::get('nElementsDataTable'); 
        $ids = array();
        for($i=0;$i<$nElements;$i++){
            if(Input::get('element'.$i) != false){
                array_push($ids, Input::get('element'.$i));
            }
        }
                
        AreaTerritorial2::deleteAreasTerritoriales2($ids);
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición        
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registros'));
        
        return Redirect::route('areasTerritoriales2',array($pais));
    }
    public function jsonGetAreasTerritoriales2FromAreaTerritorial1($id){
        $data['json'] = null;
        if($id!="null") $data['json'] = AreaTerritorial1::find($id)->areasTerritoriales2()->get()->toJson();
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
}