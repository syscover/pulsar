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
    Pulsar\Pulsar\Models\Modulo,
    Pulsar\Pulsar\Models\Resource;
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
    
    public function jsonData()
    {
        $params =  Miscellaneous::paginateDataTable();
        $params =  Miscellaneous::dataTableSorting($params, $this->aColumns);
        $params =  Miscellaneous::filteringDataTable($params);

        $objects       = Resource::getRecordsLimit($this->aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Resource::getRecordsLimit($this->aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'], null, true);
        $iTotal         = Resource::count();

        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => array()
        );
        
        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject){
		$row = array();
		foreach ($this->aColumns as $aColumn){
                    $row[] = $aObject[$aColumn]; 
		}
                $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_007'].'">';
                //Botones de acciones
                $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to('/').'/'.Config::get('pulsar::pulsar.rootUri').'/pulsar/recursos/'.$aObject['id_007'].'/edit/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_registro').'"><i class="icon-pencil"></i></a>' : '';
                $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$aObject['id_007'].'\')" data-original-title="'.Lang::get('pulsar::pulsar.borrar_registro').'"><i class="icon-trash"></i></a>' : '';
		$row[] =  $acciones;
                
                $output['aaData'][] = $row;
                $i++;
	}
                
        $data['json'] = json_encode($output);
        
        return View::make('pulsar::common.json_display', $data);
    }
    
    public function create($inicio=0){
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
       
        return View::make('pulsar::pulsar.pulsar.recursos.create',array('inicio' => $inicio, 'modulos' =>  Package::get()));
    }
    
    public function store($inicio=0){
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
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
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'access')) App::abort(403, 'Permission denied.');
        
        $data['inicio'] = $inicio;
        $data['modulos'] = Package::get();
        $data['recurso'] = Resource::find($id);
                
        return View::make('pulsar::pulsar.pulsar.recursos.edit',$data);
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