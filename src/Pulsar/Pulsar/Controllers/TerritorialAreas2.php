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

use Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Input,
    Pulsar\Pulsar\Models\Country,
    Pulsar\Pulsar\Models\TerritorialArea1,
    Pulsar\Pulsar\Models\TerritorialArea2;
use Pulsar\Pulsar\Traits\ControllerTrait;

class TerritorialAreas2 extends BaseController {

    use ControllerTrait;

    protected $resource     = 'admin-country-at2';
    protected $routeSuffix  = 'TerritorialArea2';
    protected $folder       = 'territorial_areas_2';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_004', 'name_003', 'name_004'];
    protected $nameM        = 'name_004';
    protected $model        = '\Pulsar\Pulsar\Models\TerritorialArea2';
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

    public function storeCustomRecord($parameters)
    {
        TerritorialArea2::create([
            'id_004'                    => Input::get('id'),
            'country_004'               => $parameters['country'],
            'area_territorial_1_004'    => Input::get('TerritorialArea1'),
            'name_004'                  => Input::get('name')
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['TerritorialesAreas1']  = TerritorialArea1::getTerritorialAreas1Country($parameters['country']);
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);

        return $parameters;
    }
    
    public function updateCustomRecord($id)
    {
        TerritorialArea2::where('id_004', $id)->update([
            'id_004'                    => Input::get('id'),
            'territorial_area_1_004'    => Input::get('territorialArea1'),
            'name_004'                  => Input::get('name')
        ]);
    }


    public function jsonGetAreasTerritoriales2FromAreaTerritorial1($id)
    {
        $data['json'] = null;
        if($id!="null") $data['json'] = TerritorialArea2::find($id)->areasTerritoriales2()->get()->toJson();

        return view('pulsar::common.json_display',$data);
    }
}