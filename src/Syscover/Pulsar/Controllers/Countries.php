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
use Syscover\Pulsar\Traits\TraitController;

class Countries extends Controller {

    use TraitController;

    protected $routeSuffix  = 'Country';
    protected $folder       = 'countries';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_002', 'name_001', 'name_002', 'sorting_002', 'prefix_002', ['data' => 'territorial_area_1_002', 'route' => 'TerritorialArea1', 'type' => 'territorialArea'], ['data' => 'territorial_area_2_002', 'route' => 'TerritorialArea2', 'type' => 'territorialArea'], ['data' => 'territorial_area_3_002', 'route' => 'TerritorialArea3', 'type' => 'territorialArea']];
    protected $nameM        = 'name_002';
    protected $model        = '\Syscover\Pulsar\Models\Country';
    protected $icon         = 'entypo-icon-globe';
    protected $objectTrans  = 'country';

    protected $reArea1      = 'admin-country-at1';
    protected $reArea2      = 'admin-country-at2';
    protected $reArea3      = 'admin-country-at3';

    
    public function indexCustom($parameters)
    {
        $parameters['urlParameters']['lang']    = session('baseLang');

        return $parameters;
    }

    public function customColumnType($row, $aColumn, $aObject, $request)
    {
        switch ($aColumn['type'])
        {
            case 'territorialArea':
                $row[] = '<a href="' . route($aColumn['route'], ['country' => $aObject['id_002'], 'parentOffset' => $request->input('iDisplayStart')]) . '">' . $aObject[$aColumn['data']] . '</a>';
                break;
        }
        return $row;
    }

    public function checkSpecialRulesToStore($parameters)
    {
        // check special rule to objects with multiple language if is new object translation or new object
        if(Request::has('lang') && Request::input('lang') != session('baseLang')->id_001)
        {
            $parameters['specialRules']['idRule'] = true;
        }

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        Country::create([
            'id_002'                    => Request::input('id'),
            'lang_002'                  => Request::input('lang'),
            'name_002'                  => Request::input('name'),
            'orden_002'                 => Request::input('sorting', 0),
            'prefijo_002'               => Request::input('prefix'),
            'territorial_area_1_002'    => Request::input('territorialArea1'),
            'territorial_area_2_002'    => Request::input('territorialArea2'),
            'territorial_area_3_002'    => Request::input('territorialArea3'),
            'data_002'                  => Country::addLangDataRecord(Request::input('id'), Request::input('lang'))
        ]);
    }
    
    public function updateCustomRecord($parameters)
    {
        Country::where('id_002', $parameters['id'])->where('lang_002', Request::input('lang'))->update([
            'name_002'                  => Request::input('name'),
            'sorting_002'               => Request::input('sorting', 0),
            'territorial_area_1_002'    => Request::input('territorialArea1'),
            'territorial_area_2_002'    => Request::input('territorialArea2'),
            'territorial_area_3_002'    => Request::input('territorialArea3')
        ]);

        // common data
        Country::where('id_002', $parameters['id'])->update([
            'prefix_002' => Request::input('prefix')
        ]);
    }

    public function jsonCountry($country)
    {
        if($country == "null")
            return response()->json([
                'status'    => 'error',
                'message'   => 'We expect a correct country code'
            ], 400);
        else
            return response()->json([
                'status'    => 'success',
                'data'      => Country::getTranslationRecord($id, session('baseLang')->id_001)
            ]);
    }

    public function jsonCountries($lang = null)
    {
        if($lang == null)
            return response()->json([
                'status'    => 'error',
                'message'   => 'We expect a correct lang code'
            ], 400);
        else
            return response()->json([
                'status'    => 'success',
                'data'      => Country::where('lang_002', $lang)->get()
            ]);
    }
}