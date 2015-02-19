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
    Illuminate\Support\Facades\Lang,
    Illuminate\Support\Facades\Redirect,
    Pulsar\Pulsar\Models\Country,
    Pulsar\Pulsar\Models\TerritorialArea1,
    Pulsar\Pulsar\Models\TerritorialArea2,
    Pulsar\Pulsar\Models\TerritorialArea3;
use Pulsar\Pulsar\Traits\ControllerTrait;

class TerritorialAreas3 extends BaseController {

    use ControllerTrait;

    protected $resource     = 'admin-country-at3';
    protected $routeSuffix  = 'TerritorialArea3';
    protected $folder       = 'territorial_areas_3';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_005','name_003','name_004','name_005'];
    protected $nameM        = 'name_005';
    protected $model        = '\Pulsar\Pulsar\Models\TerritorialArea3';
    protected $icon         = 'entypo-icon-globe';
    protected $objectTrans  = 'territorial_area';

    public function indexCustom($parameters)
    {
        $parameters['country'] = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);

        return $parameters;
    }

    public function createCustomRecord($parameters)
    {
        $parameters['TerritorialesAreas1']  = TerritorialArea1::getTerritorialAreas1Country($parameters['country']);
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);

        return $parameters;
    }
    
    public function create($pais, $offset=0){

        
        $data['inicio']                     = $offset;
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
    
    public function store($pais, $offset=0){

        
        $validation = AreaTerritorial3::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createAreasTerritoriales3',array($pais, $offset))->withErrors($validation)->withInput();
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
            
            return Redirect::route('areasTerritoriales3',array($pais, $offset));
        }
    }
    
    public function edit($id, $offset=0){

        
        $data['inicio']                 = $offset;
        $data['areaTerritorial3']       = AreaTerritorial3::find($id);
        $data['areasTerritoriales1']    = AreaTerritorial1::getAllAreasTerritoriales1($data['areaTerritorial3']->pais_005);
        $data['areasTerritoriales2']    = AreaTerritorial1::find($data['areaTerritorial3']->area_territorial_1_005)->areasTerritoriales2()->get();
        $data['pais']                   = Pais::getPais($data['areaTerritorial3']->pais_005, Auth::user()->idioma_010);
        $data['javascriptView']         = 'pulsar::pulsar/Pulsar/areas_territoriales_3/js/edit';
        
        return view('pulsar::pulsar.pulsar.areas_territoriales_3.edit',$data);
    }

    public function update($pais, $offset=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = AreaTerritorial3::validate(Input::all(), $idRule);
        
        if ($validation->fails())
        {
            return Redirect::route('editAreasTerritoriales3',array(Input::get('idOld'), $offset))->withErrors($validation);
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
            
            return Redirect::route('areasTerritoriales3',array($pais, $offset));
        }
    }
    
    public function jsonGetAreasTerritoriales3FromAreaTerritorial2($id)
    {
        $data['json'] = array();
        if($id!="null") $data['json'] = AreaTerritorial2::find($id)->areasTerritoriales3()->get()->toJson();

        return view('pulsar::common.json_display',$data);
    }
}