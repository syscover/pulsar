<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Models\TerritorialArea1;
use Syscover\Pulsar\Models\TerritorialArea2;
use Syscover\Pulsar\Models\TerritorialArea3;
use Syscover\Pulsar\Traits\TraitController;

/**
 * Class TerritorialArea3Controller
 * @package Syscover\Pulsar\Controllers
 */

class TerritorialArea3Controller extends Controller {

    use TraitController;

    protected $routeSuffix          = 'territorialArea3';
    protected $folder               = 'territorial_area_3';
    protected $package              = 'pulsar';
    protected $aColumns             = ['id_005', 'name_003', 'name_004', 'name_005'];
    protected $nameM                = 'name_005';
    protected $model                = \Syscover\Pulsar\Models\TerritorialArea3::class;
    protected $icon                 = 'entypo-icon-globe';
    protected $customTrans          = null;
    protected $customTransHeader    = null;

    public function indexCustom($parameters)
    {
        $parameters['country']              = Country::where('id_002', $parameters['country'])->where('lang_002', session('baseLang')->id_001)->first();
        $parameters['customTrans']          = $parameters['country']->territorial_area_3_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_3_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function customActionUrlParameters($actionUrlParameters, $parameters)
    {
        $actionUrlParameters['country'] = $parameters['country'];

        return $actionUrlParameters;
    }

    public function createCustomRecord($request, $parameters)
    {
        $parameters['territorialAreas1']    = TerritorialArea1::getTerritorialAreas1FromCountry($parameters['country']);
        if($request->old('territorialArea1') && $request->old('territorialArea1') != "null")
        {
            $parameters['territorialAreas2'] = territorialArea1::find(Input::old('territorialArea1'))->getTerritorialAreas2;
        }
        else
        {
            $parameters['territorialAreas2'] = [];
        }
        $parameters['country']              = Country::where('id_002', $parameters['country'])->where('lang_002', session('baseLang')->id_001)->first();
        $parameters['customTrans']          = $parameters['country']->territorial_area_3_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_3_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function storeCustomRecord($request, $parameters)
    {
        TerritorialArea3::create([
            'id_005'                    => $request->input('id'),
            'country_005'               => $parameters['country'],
            'territorial_area_1_005'    => $request->input('territorialArea1'),
            'territorial_area_2_005'    => $request->input('territorialArea2'),
            'name_005'                  => $request->input('name')
        ]);
    }

    public function editCustomRecord($request, $parameters)
    {
        $parameters['territorialAreas1']    = TerritorialArea1::getTerritorialAreas1FromCountry($parameters['country']);
        $parameters['territorialAreas2']    = TerritorialArea2::getTerritorialAreas2FromTerritorialArea1($parameters['object']->territorial_area_1_005);
        $parameters['country']              = Country::where('id_002', $parameters['country'])->where('lang_002', session('baseLang')->id_001)->first();
        $parameters['customTrans']          = $parameters['country']->territorial_area_3_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_3_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function updateCustomRecord($request, $parameters)
    {
        TerritorialArea3::where('id_005', $parameters['id'])->update([
            'id_005'                    => $request->input('id'),
            'territorial_area_1_005'    => $request->input('territorialArea1'),
            'territorial_area_2_005'    => $request->input('territorialArea2'),
            'name_005'                  => $request->input('name')
        ]);
    }

    public function jsonTerritorialAreas3FromTerritorialArea2($country, $territorialArea2)
    {
        if($territorialArea2 == "null")
            return response()->json([
                'status'    => 'error',
                'message'   => 'We expect a correct territorial area 2 code'
            ], 400);
        else
            return response()->json([
                'status'    => 'success',
                'data'      => TerritorialArea2::find($territorialArea2)->getTerritorialAreas3
            ]);
    }
}