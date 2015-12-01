<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Models\TerritorialArea1;
use Syscover\Pulsar\Models\TerritorialArea2;
use Syscover\Pulsar\Traits\TraitController;

/**
 * Class TerritorialArea2Controller
 * @package Syscover\Pulsar\Controllers
 */

class TerritorialArea2Controller extends Controller {

    use TraitController;

    protected $routeSuffix          = 'territorialArea2';
    protected $folder               = 'territorial_area_2';
    protected $package              = 'pulsar';
    protected $aColumns             = ['id_004', 'name_003', 'name_004'];
    protected $nameM                = 'name_004';
    protected $model                = \Syscover\Pulsar\Models\TerritorialArea2::class;
    protected $icon                 = 'entypo-icon-globe';
    protected $customTrans          = null;
    protected $customTransHeader    = null;

    public function indexCustom($parameters)
    {
        $parameters['country']              = Country::getTranslationRecord(['id' => $parameters['country'], 'lang' => session('baseLang')->id_001]);
        $parameters['customTrans']          = $parameters['country']->territorial_area_2_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_2_002 . ' (' . $parameters['country']->name_002 . ')';

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
        $parameters['country']              = Country::getTranslationRecord(['id' => $parameters['country'], 'lang' => session('baseLang')->id_001]);
        $parameters['customTrans']          = $parameters['country']->territorial_area_2_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_2_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function storeCustomRecord($request, $parameters)
    {
        TerritorialArea2::create([
            'id_004'                    => $request->input('id'),
            'country_004'               => $parameters['country'],
            'territorial_area_1_004'    => $request->input('territorialArea1'),
            'name_004'                  => $request->input('name')
        ]);
    }

    public function editCustomRecord($request, $parameters)
    {
        $parameters['territorialAreas1']    = TerritorialArea1::getTerritorialAreas1FromCountry($parameters['country']);
        $parameters['country']              = Country::getTranslationRecord(['id' => $parameters['country'], 'lang' => session('baseLang')->id_001]);
        $parameters['customTrans']          = $parameters['country']->territorial_area_2_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_2_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }
    
    public function updateCustomRecord($request, $parameters)
    {
        TerritorialArea2::where('id_004', $parameters['id'])->update([
            'id_004'                    => $request->input('id'),
            'territorial_area_1_004'    => $request->input('territorialArea1'),
            'name_004'                  => $request->input('name')
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