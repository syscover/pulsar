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

use Illuminate\Support\Facades\Request;
use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Models\TerritorialArea1;
use Syscover\Pulsar\Models\TerritorialArea2;
use Syscover\Pulsar\Traits\TraitController;

class TerritorialAreas2 extends Controller {

    use TraitController;

    protected $routeSuffix          = 'TerritorialArea2';
    protected $folder               = 'territorial_areas_2';
    protected $package              = 'pulsar';
    protected $aColumns             = ['id_004', 'name_003', 'name_004'];
    protected $nameM                = 'name_004';
    protected $model                = '\Syscover\Pulsar\Models\TerritorialArea2';
    protected $icon                 = 'entypo-icon-globe';
    protected $customTrans          = null;
    protected $customTransHeader    = null;

    public function indexCustom($parameters)
    {
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], session('baseLang')->id_001);
        $parameters['customTrans']          = $parameters['country']->territorial_area_2_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_2_002 . ' (' . $parameters['country']->name_002 . ')';

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
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], session('baseLang')->id_001);
        $parameters['customTrans']          = $parameters['country']->territorial_area_2_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_2_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        TerritorialArea2::create([
            'id_004'                    => Request::input('id'),
            'country_004'               => $parameters['country'],
            'territorial_area_1_004'    => Request::input('territorialArea1'),
            'name_004'                  => Request::input('name')
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['territorialAreas1']    = TerritorialArea1::getTerritorialAreas1FromCountry($parameters['country']);
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], session('baseLang')->id_001);
        $parameters['customTrans']          = $parameters['country']->territorial_area_2_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_2_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        TerritorialArea2::where('id_004', $parameters['id'])->update([
            'id_004'                    => Request::input('id'),
            'territorial_area_1_004'    => Request::input('territorialArea1'),
            'name_004'                  => Request::input('name')
        ]);
    }

    public function jsonTerritorialAreas2FromTerritorialArea1($country, $territorialArea1)
    {
        if($territorialArea1 == "null")
            return response()->json([
                'status'    => 'error',
                'message'   => 'We expect a correct territorial area 1 code'
            ], 400);
        else
            return response()->json([
                'status'    => 'success',
                'data'      => TerritorialArea1::find($territorialArea1)->territorialAreas2()->get()
            ]);
    }
}