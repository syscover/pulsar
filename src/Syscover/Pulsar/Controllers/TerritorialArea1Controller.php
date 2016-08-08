<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Models\TerritorialArea1;

/**
 * Class TerritorialArea1Controller
 * @package Syscover\Pulsar\Controllers
 */

class TerritorialArea1Controller extends Controller
{
    protected $routeSuffix          = 'territorialArea1';
    protected $folder               = 'territorial_area_1';
    protected $package              = 'pulsar';
    protected $indexColumns             = ['id_003', 'name_003'];
    protected $nameM                = 'name_003';
    protected $model                = TerritorialArea1::class;
    protected $icon                 = 'entypo-icon-globe';
    protected $customTrans          = null;
    protected $customTransHeader    = null;

    public function customIndex($parameters)
    {
        $parameters['country']              = Country::where('id_002', $parameters['country'])->where('lang_id_002', base_lang()->id_001)->first();
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
        $parameters['country']              = Country::where('id_002', $parameters['country'])->where('lang_id_002', base_lang()->id_001)->first();
        $parameters['customTrans']          = $parameters['country']->territorial_area_1_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_1_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        TerritorialArea1::create([
            'id_003'            => $this->request->input('id'),
            'country_id_003'    => $parameters['country'],
            'name_003'          => $this->request->input('name')
        ]);
    }
    
    public function editCustomRecord($parameters)
    {
        $parameters['country']              = Country::where('id_002', $parameters['object']->country_id_003)->where('lang_id_002', base_lang()->id_001)->first();
        $parameters['customTrans']          = $parameters['country']->territorial_area_1_002;
        $parameters['customTransHeader']    = $parameters['country']->territorial_area_1_002 . ' (' . $parameters['country']->name_002 . ')';

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        TerritorialArea1::where('id_003', $parameters['id'])->update([
            'id_003'    => $this->request->input('id'),
            'name_003'  => $this->request->input('name')
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
                'data'      => Country::where('id_002', $country)->where('lang_id_002', base_lang()->id_001)->first()->getTerritorialAreas1
            ]);
    }
}