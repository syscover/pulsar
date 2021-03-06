<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Models\Country;

/**
 * Class CountryController
 * @package Syscover\Pulsar\Controllers
 */

class CountryController extends Controller
{
    protected $routeSuffix  = 'country';
    protected $folder       = 'country';
    protected $package      = 'pulsar';
    protected $indexColumns = ['id_002', 'name_001', 'name_002', 'sorting_002', 'prefix_002', ['data' => 'territorial_area_1_002', 'route' => 'territorialArea1', 'type' => 'territorialArea'], ['data' => 'territorial_area_2_002', 'route' => 'territorialArea2', 'type' => 'territorialArea'], ['data' => 'territorial_area_3_002', 'route' => 'territorialArea3', 'type' => 'territorialArea']];
    protected $nameM        = 'name_002';
    protected $model        = Country::class;
    protected $icon         = 'fa fa-globe';
    protected $objectTrans  = 'country';

    protected $reArea1      = 'admin-country-at1';
    protected $reArea2      = 'admin-country-at2';
    protected $reArea3      = 'admin-country-at3';

    
    public function customIndex($parameters)
    {
        $parameters['urlParameters']['lang'] = base_lang2()->id_001;

        return $parameters;
    }

    public function customColumnType($row, $indexColumn, $aObject)
    {
        switch ($indexColumn['type'])
        {
            case 'territorialArea':
                $row[] = '<a href="' . route($indexColumn['route'], ['country' => $aObject['id_002'], 'parentOffset' => $this->request->input('start')]) . '">' . $aObject[$indexColumn['data']] . '</a>';
                break;
        }
        return $row;
    }

    public function checkSpecialRulesToStore($parameters)
    {
        // check special rule to objects with multiple language if is new object translation or new object
        if($this->request->has('lang') && $this->request->input('lang') != base_lang2()->id_001)
        {
            $parameters['specialRules']['idRule'] = true;
        }

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        Country::create([
            'id_002'                    => $this->request->input('id'),
            'lang_id_002'                  => $this->request->input('lang'),
            'name_002'                  => $this->request->input('name'),
            'sorting_002'               => $this->request->input('sorting', 0),
            'prefix_002'                => $this->request->input('prefix'),
            'territorial_area_1_002'    => $this->request->input('territorialArea1'),
            'territorial_area_2_002'    => $this->request->input('territorialArea2'),
            'territorial_area_3_002'    => $this->request->input('territorialArea3'),
            'data_lang_002'             => Country::addLangDataRecord($this->request->input('lang'), $this->request->input('id'))
        ]);
    }
    
    public function updateCustomRecord($parameters)
    {
        Country::where('id_002', $parameters['id'])->where('lang_id_002', $this->request->input('lang'))->update([
            'name_002'                  => $this->request->input('name'),
            'sorting_002'               => $this->request->input('sorting', 0),
            'territorial_area_1_002'    => $this->request->input('territorialArea1'),
            'territorial_area_2_002'    => $this->request->input('territorialArea2'),
            'territorial_area_3_002'    => $this->request->input('territorialArea3')
        ]);

        // common data
        Country::where('id_002', $parameters['id'])->update([
            'prefix_002' => $this->request->input('prefix')
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
                'data'      => Country::where('id_002', $country)->where('lang_id_002', base_lang2()->id_001)->first()
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
                'data'      => Country::where('lang_id_002', $lang)->get()
            ]);
    }
}