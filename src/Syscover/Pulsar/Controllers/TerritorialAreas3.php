<?php namespace Syscover\Pulsar\Controllers;

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
    Syscover\Pulsar\Models\Country,
    Syscover\Pulsar\Models\TerritorialArea1,
    Syscover\Pulsar\Models\TerritorialArea2,
    Syscover\Pulsar\Models\TerritorialArea3;
use Syscover\Pulsar\Traits\ControllerTrait;

class TerritorialAreas3 extends Controller {

    use ControllerTrait;

    protected $routeSuffix  = 'TerritorialArea3';
    protected $folder       = 'territorial_areas_3';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_005', 'name_003', 'name_004', 'name_005'];
    protected $nameM        = 'name_005';
    protected $model        = '\Syscover\Pulsar\Models\TerritorialArea3';
    protected $icon         = 'entypo-icon-globe';
    protected $customTrans  = null;

    public function indexCustom($parameters)
    {
        $parameters['country']      = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);
        $parameters['customTrans']  = $parameters['country']->territorial_area_3_002;

        return $parameters;
    }

    public function customActionUrlParameters($actionUrlParameters, $parameters)
    {
        $actionUrlParameters['country'] = $parameters['country'];

        return $actionUrlParameters;
    }

    public function createCustomRecord($parameters)
    {
        $parameters['territorialAreas1']    = TerritorialArea1::getTerritorialAreas1FromCountry($parameters['country']);
        if(Input::old('territorialArea1') && Input::old('territorialArea1') != "null")
        {
            $parameters['territorialAreas2'] = territorialArea1::find(Input::old('territorialArea1'))->territorialAreas2()->get();
        }
        else
        {
            $parameters['territorialAreas2'] = [];
        }
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);
        $parameters['customTrans']          = $parameters['country']->territorial_area_3_002;

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        TerritorialArea3::create([
            'id_005'                    => Input::get('id'),
            'country_005'               => $parameters['country'],
            'territorial_area_1_005'    => Input::get('territorialArea1'),
            'territorial_area_2_005'    => Input::get('territorialArea2'),
            'name_005'                  => Input::get('name')
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['territorialAreas1']    = TerritorialArea1::getTerritorialAreas1FromCountry($parameters['country']);
        $parameters['territorialAreas2']    = TerritorialArea2::getTerritorialAreas2FromTerritorialArea1($parameters['object']->territorial_area_1_005);
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);
        $parameters['customTrans']          = $parameters['country']->territorial_area_3_002;

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        TerritorialArea3::where('id_005', $parameters['id'])->update([
            'id_005'                    => Input::get('id'),
            'territorial_area_1_005'    => Input::get('territorialArea1'),
            'territorial_area_2_005'    => Input::get('territorialArea2'),
            'name_005'                  => Input::get('name')
        ]);
    }
    
    public function jsonTerritorialAreas3FromTerritorialArea2($country, $id)
    {
        $data['json'] = [];
        if($id!="null") $data['json'] = TerritorialArea2::find($id)->territorialAreas3()->get()->toJson();

        return view('pulsar::common.views.json_display',$data);
    }
}