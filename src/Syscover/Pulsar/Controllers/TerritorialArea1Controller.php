<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Models\TerritorialArea1;
use Syscover\Pulsar\Traits\TraitController;

/**
 * Class TerritorialArea1Controller
 * @package Syscover\Pulsar\Controllers
 */

class TerritorialArea1Controller extends Controller {

    use TraitController;

    protected $routeSuffix          = 'territorialArea1';
    protected $folder               = 'territorial_area_1';
    protected $package              = 'pulsar';
    protected $aColumns             = ['id_003', 'name_003'];
    protected $nameM                = 'name_003';
    protected $model                = \Syscover\Pulsar\Models\TerritorialArea1::class;
    protected $icon                 = 'entypo-icon-globe';
    protected $customTrans          = null;
    protected $customTransHeader    = null;

    public function indexCustom($parameters)
    {
        $parameters['country']              = Country::getTranslationRecord(['id' => $parameters['country'], 'lang' => session('baseLang')->id_001]);
        $parameters['customTrans']          = $parameters['country']->territorial_area_1_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_1_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function customActionUrlParameters($actionUrlParameters, $parameters)
    {
        $actionUrlParameters['country'] = $parameters['country'];

        return $actionUrlParameters;
    }
    
    public function createCustomRecord($request, $parameters)
    {
        $parameters['country']              = Country::getTranslationRecord(['id' => $parameters['country'], 'lang' => session('baseLang')->id_001]);
        $parameters['customTrans']          = $parameters['country']->territorial_area_1_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_1_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function storeCustomRecord($request, $parameters)
    {
        TerritorialArea1::create([
            'id_003'        => $request->input('id'),
            'country_003'   => $parameters['country'],
            'name_003'      => $request->input('name')
        ]);
    }
    
    public function editCustomRecord($request, $parameters)
    {
        $parameters['country']              = Country::getTranslationRecord(['id' => $parameters['object']->country_003, 'lang' => session('baseLang')->id_001]);
        $parameters['customTrans']          = $parameters['country']->territorial_area_1_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_1_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function updateCustomRecord($request, $parameters)
    {
        TerritorialArea1::where('id_003', $parameters['id'])->update([
            'id_003'    => $request->input('id'),
            'name_003'  => $request->input('name')
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
                'data'      => Country::where('id_002', $country)->where('lang_002', session('baseLang')->id_001)->first()->getTerritorialAreas1
            ]);
    }
}