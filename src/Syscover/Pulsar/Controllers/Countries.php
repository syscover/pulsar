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
use Syscover\Pulsar\Models\Lang;
use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Traits\ControllerTrait;

class Countries extends Controller {

    use ControllerTrait;

    protected $routeSuffix  = 'Country';
    protected $folder       = 'countries';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_002', 'name_001', 'name_002', 'sorting_002', 'prefix_002', ['data' => 'territorial_area_1_002', 'route' => 'TerritorialArea1', 'type' => 'AA'], 'territorial_area_2_002', 'territorial_area_3_002'];
    protected $nameM        = 'name_002';
    protected $model        = '\Syscover\Pulsar\Models\Country';
    protected $icon         = 'entypo-icon-globe';
    protected $objectTrans  = 'country';

    protected $reArea1      = 'admin-country-at1';
    protected $reArea2      = 'admin-country-at2';
    protected $reArea3      = 'admin-country-at3';

    
    public function indexCustom($parameters)
    {
        $parameters['urlParameters']['lang']    = Session::get('baseLang');

        return $parameters;
    }

    public function createCustomRecord($parameters)
    {
        if(isset($parameters['id']))
        {
            $parameters['object'] = Country::getTranslationRecord($parameters['id'], Session::get('baseLang')->id_001);
        }

        $parameters['lang'] = Lang::find($parameters['lang']);

        return $parameters;
    }

    public function checkSpecialRulesToStore($parameters)
    {
        // check special rule to objects with multiple language if is new object translation or new object
        if(Request::has('lang') && Request::input('lang') != Session::get('baseLang')->id_001)
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
    
    public function editCustomRecord($parameters)
    {
        $parameters['object']   = Country::getTranslationRecord($parameters['id'], $parameters['lang']);
        $parameters['lang']     = $parameters['object']->lang;

        return $parameters;
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

    public function jsonCountry($id = null)
    {
        $parameters['json'] = [];

        if($id != null) $parameters['json'] = Country::getTranslationRecord($id, Session::get('baseLang')->id_001)->toJson();

        return view('pulsar::common.views.json_display', $parameters);
    }

    public function jsonCountries($lang = null)
    {
        $parameters['json'] = [];

        if($lang != null) $parameters['json'] = Country::where('lang_002', $lang)->get()->toJson();

        return view('pulsar::common.views.json_display', $parameters);
    }
}