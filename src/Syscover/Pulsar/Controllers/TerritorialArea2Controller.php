<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Models\TerritorialArea1;
use Syscover\Pulsar\Models\TerritorialArea2;

/**
 * Class TerritorialArea2Controller
 * @package Syscover\Pulsar\Controllers
 */

class TerritorialArea2Controller extends Controller
{
    protected $routeSuffix          = 'territorialArea2';
    protected $folder               = 'territorial_area_2';
    protected $package              = 'pulsar';
    protected $indexColumns             = ['id_004', 'name_003', 'name_004'];
    protected $nameM                = 'name_004';
    protected $model                = TerritorialArea2::class;
    protected $icon                 = 'entypo-icon-globe';
    protected $customTrans          = null;
    protected $customTransHeader    = null;

    public function customIndex($parameters)
    {
        $parameters['country']              = Country::where('id_002', $parameters['country'])->where('lang_id_002', session('baseLang')->id_001)->first();
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
        $parameters['country']              = Country::where('id_002', $parameters['country'])->where('lang_id_002', session('baseLang')->id_001)->first();
        $parameters['customTrans']          = $parameters['country']->territorial_area_2_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_2_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        TerritorialArea2::create([
            'id_004'                    => $this->request->input('id'),
            'country_id_004'            => $parameters['country'],
            'territorial_area_1_id_004' => $this->request->input('territorialArea1'),
            'name_004'                  => $this->request->input('name')
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['territorialAreas1']    = TerritorialArea1::getTerritorialAreas1FromCountry($parameters['country']);
        $parameters['country']              = Country::where('id_002', $parameters['country'])->where('lang_id_002', session('baseLang')->id_001)->first();
        $parameters['customTrans']          = $parameters['country']->territorial_area_2_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_2_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        TerritorialArea2::where('id_004', $parameters['id'])->update([
            'id_004'                    => $this->request->input('id'),
            'territorial_area_1_id_004' => $this->request->input('territorialArea1'),
            'name_004'                  => $this->request->input('name')
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
                'data'      => TerritorialArea1::find($territorialArea1)->getTerritorialAreas2
            ]);
    }
}