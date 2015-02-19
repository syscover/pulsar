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
    Illuminate\Support\Facades\Redirect,
    Pulsar\Pulsar\Models\Country,
    Pulsar\Pulsar\Models\AreaTerritorial1;
use Pulsar\Pulsar\Traits\ControllerTrait;

class TerritorialAreas1 extends BaseController {

    use ControllerTrait;

    protected $resource     = 'admin-country-at1';
    protected $routeSuffix  = 'TerritorialArea1';
    protected $folder       = 'territorial_areas_1';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_003', 'name_003'];
    protected $nameM        = 'name_003';
    protected $model        = '\Pulsar\Pulsar\Models\TerritorialArea1';
    protected $icon         = 'entypo-icon-globe';
    protected $objectTrans  = 'territorial_area';
    
    public function indexCustom($parameters)
    {
        $parameters['country'] = Country::getTranslationRecord($parameters['country'], Auth::user()->lang_010);

        return $parameters;
    }
    
    public function createCustomRecord($parameters)
    {
        $parameters['country'] = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);

        return $parameters;
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