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
    Pulsar\Pulsar\Models\AreaTerritorial2,
    Pulsar\Pulsar\Models\AreaTerritorial3;

class AreasTerritoriales3 extends BaseController {
    
    private $resource = 'admin-country-at3';
    
    public function index($pais, $inicio=0){

        
        //Inicializa las sesiones para las búsquedas rápidas desde la vista de tablas en caso de cambio de página
        Miscellaneous::sessionParamterSetPage($this->resource);
        
        //instanciamos la variable de inicio pasra sabel el punto de inicio en caso de borrado o edición, volver al mismo punto de la lista
        $data['recurso']        = $this->resource;
        $data['inicio']         = $inicio; 
        $data['pais']           = Pais::getPais($pais, Auth::user()->idioma_010);
        $data['javascriptView'] = 'pulsar::pulsar/Pulsar/areas_territoriales_3/js/index';
        
        return view('pulsar::pulsar.pulsar.areas_territoriales_3.index',$data);
    }
    
    public function jsonData($pais){

        
        //Columnas para instanciar filtos de la tabla
	$aColumns = array('id_005','nombre_003','nombre_004','nombre_005');
        $params = array();
        
        //Paginado de la tabla
        $params =  Miscellaneous::paginateDataTable($params);
	    
        //Orden de la tabla
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);
        
        //filtrados de la tabla
        $params =  Miscellaneous::filteringDataTable($params);
	        
        //Toma de datos para la tabla
        $objects        = AreaTerritorial3::getAreasTerritorialesLimit3($pais, $aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = AreaTerritorial3::getAreasTerritorialesLimit3($pais, $aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'])->count();
        $iTotal         = AreaTerritorial3::count();
        
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
                $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_005'].'">';
                //Botones de acciones
                $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/areasterritoriales3/'.$aObject['id_005'].'/edit/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_registro').'"><i class="icon-pencil"></i></a>' : '';
                $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$pais.'\',\''.$aObject['id_005'].'\')" data-original-title="'.Lang::get('pulsar::pulsar.borrar_registro').'"><i class="icon-trash"></i></a>' : '';
		$row[] =  $acciones;
                
                $output['aaData'][] = $row;
                $i++;
	}
                
        $data['json'] = json_encode($output);
        
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
    
    public function create($pais, $inicio=0){

        
        $data['inicio']                     = $inicio;
        $data['pais']                       = Pais::getPais($pais, Auth::user()->idioma_010);
        $data['areasTerritoriales1']        = AreaTerritorial1::getAllAreasTerritoriales1($pais);
        if(Input::old('areaTerritorial1') && Input::old('areaTerritorial1') != "null"){
            $data['areasTerritoriales2']    = AreaTerritorial1::find(Input::old('areaTerritorial1'))->areasTerritoriales2()->get();
        }else{
            $data['areasTerritoriales2']    = array();
        }
        $data['javascriptView']             = 'pulsar::pulsar/Pulsar/areas_territoriales_3/js/create';
        return view('pulsar::pulsar.pulsar.areas_territoriales_3.create',$data);
    }
    
    public function store($pais, $inicio=0){

        
        $validation = AreaTerritorial3::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createAreasTerritoriales3',array($pais, $inicio))->withErrors($validation)->withInput();
        }
        else
        {
            AreaTerritorial3::create(array(
                'id_005'                    => Input::get('id'),
                'pais_005'                  => $pais,
                'area_territorial_1_005'    => Input::get('areaTerritorial1'),
                'area_territorial_2_005'    => Input::get('areaTerritorial2'),
                'nombre_005'                => Input::get('nombre')
            )); 
            
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('areasTerritoriales3',array($pais, $inicio));
        }
    }
    
    public function edit($id, $inicio=0){

        
        $data['inicio']                 = $inicio;
        $data['areaTerritorial3']       = AreaTerritorial3::find($id);
        $data['areasTerritoriales1']    = AreaTerritorial1::getAllAreasTerritoriales1($data['areaTerritorial3']->pais_005);
        $data['areasTerritoriales2']    = AreaTerritorial1::find($data['areaTerritorial3']->area_territorial_1_005)->areasTerritoriales2()->get();
        $data['pais']                   = Pais::getPais($data['areaTerritorial3']->pais_005, Auth::user()->idioma_010);
        $data['javascriptView']         = 'pulsar::pulsar/Pulsar/areas_territoriales_3/js/edit';
        
        return view('pulsar::pulsar.pulsar.areas_territoriales_3.edit',$data);
    }

    public function update($pais, $inicio=0){
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = AreaTerritorial3::validate(Input::all(), $idRule);
        
        if ($validation->fails())
        {
            return Redirect::route('editAreasTerritoriales3',array(Input::get('idOld'), $inicio))->withErrors($validation);
        }
        else
        {
            AreaTerritorial3::where('id_005','=',Input::get('idOld'))->update(array(
                'id_005'                    => Input::get('id'),
                'area_territorial_1_005'    => Input::get('areaTerritorial1'),
                'area_territorial_2_005'    => Input::get('areaTerritorial2'),
                'nombre_005'                => Input::get('nombre')
            ));
                        
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('areasTerritoriales3',array($pais, $inicio));
        }
    }
    
    public function destroy($pais, $id){
        i
        
        $areaTerrirorial3 = AreaTerritorial3::find($id);
        AreaTerritorial3::destroy($id);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registro',array('nombre' => $areaTerrirorial3->nombre_005)));
        
        return Redirect::route('areasTerritoriales3',array($areaTerrirorial3->pais_005));
    }
    
    public function destroySelect($pais, $inicio=0){
        i
        
        $nElements = Input::get('nElementsDataTable'); 
        $ids = array();
        for($i=0;$i<$nElements;$i++){
            if(Input::get('element'.$i) != false){
                array_push($ids, Input::get('element'.$i));
            }
        }
                
        AreaTerritorial3::deleteAreasTerritoriales3($ids);
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición        
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registros'));
        
        return Redirect::route('areasTerritoriales3',array($pais));
    }
    
    public function jsonGetAreasTerritoriales3FromAreaTerritorial2($id){
        $data['json'] = array();
        if($id!="null") $data['json'] = AreaTerritorial2::find($id)->areasTerritoriales3()->get()->toJson();
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
}