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
use Syscover\Pulsar\Traits\ControllerTrait;

class TerritorialAreas1 extends Controller {

    use ControllerTrait;

    protected $routeSuffix          = 'TerritorialArea1';
    protected $folder               = 'territorial_areas_1';
    protected $package              = 'pulsar';
    protected $aColumns             = ['id_003', 'name_003'];
    protected $nameM                = 'name_003';
    protected $model                = '\Syscover\Pulsar\Models\TerritorialArea1';
    protected $icon                 = 'entypo-icon-globe';
    protected $customTrans          = null;
    protected $customTransHeader    = null;

    public function indexCustom($parameters)
    {
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], session('baseLang')->id_001);
        $parameters['customTrans']          = $parameters['country']->territorial_area_1_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_1_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function customActionUrlParameters($actionUrlParameters, $parameters)
    {
        $actionUrlParameters['country'] = $parameters['country'];

        return $actionUrlParameters;
    }
    
    public function createCustomRecord($parameters)
    {
        $parameters['country']              = Country::getTranslationRecord($parameters['country'], session('baseLang')->id_001);
        $parameters['customTrans']          = $parameters['country']->territorial_area_1_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_1_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        TerritorialArea1::create([
            'id_003'        => Request::input('id'),
            'country_003'   => $parameters['country'],
            'name_003'      => Request::input('name')
        ]);
    }
    
    public function editCustomRecord($parameters)
    {
        $parameters['country']              = Country::getTranslationRecord($parameters['object']->country_003, session('baseLang')->id_001);
        $parameters['customTrans']          = $parameters['country']->territorial_area_1_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_1_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        TerritorialArea1::where('id_003', $parameters['id'])->update([
            'id_003'    => Request::input('id'),
            'name_003'  => Request::input('name')
        ]);
    }

    public function jsonTerritorialAreas1FromCountry($country)
    {
        if($country == "null")
            return response()->json([
                'status'    => 'error',
                'message'   => 'We expect a correct country code'
            ], 400);
        else
            return response()->json([
                'status'    => 'success',
                'data'      => Country::getTranslationRecord($country, session('baseLang')->id_001)->territorialAreas1()->get()
            ]);
    }
}