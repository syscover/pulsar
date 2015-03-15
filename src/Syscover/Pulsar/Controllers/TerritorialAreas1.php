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

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Models\TerritorialArea1;
use Syscover\Pulsar\Traits\ControllerTrait;

class TerritorialAreas1 extends Controller {

    use ControllerTrait;

    protected $routeSuffix  = 'TerritorialArea1';
    protected $folder       = 'territorial_areas_1';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_003', 'name_003'];
    protected $nameM        = 'name_003';
    protected $model        = '\Syscover\Pulsar\Models\TerritorialArea1';
    protected $icon         = 'entypo-icon-globe';
    protected $customTrans  = null;

    public function indexCustom($parameters)
    {
        $parameters['country']      = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);
        $parameters['customTrans']  = $parameters['country']->territorial_area_1_002;

        return $parameters;
    }

    public function customActionUrlParameters($actionUrlParameters, $parameters)
    {
        $actionUrlParameters['country'] = $parameters['country'];

        return $actionUrlParameters;
    }
    
    public function createCustomRecord($parameters)
    {
        $parameters['country']      = Country::getTranslationRecord($parameters['country'], Session::get('baseLang')->id_001);
        $parameters['customTrans']  = $parameters['country']->territorial_area_1_002;

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
        $parameters['country']      = Country::getTranslationRecord($parameters['object']->country_003, Session::get('baseLang')->id_001);
        $parameters['customTrans']  = $parameters['country']->territorial_area_1_002;

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        TerritorialArea1::where('id_003', $parameters['id'])->update([
            'id_003'    => Request::input('id'),
            'name_003'  => Request::input('name')
        ]);
    }

    public function jsonTerritorialAreas1FromCountry($id)
    {
        $data['json'] = [];
        if($id!="null") $data['json'] = Country::getTranslationRecord($id, Session::get('baseLang')->id_001)->territorialAreas1()->get()->toJson();

        return view('pulsar::common.views.json_display', $data);
    }
}