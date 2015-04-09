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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Syscover\Pulsar\Libraries\Miscellaneous;
use Syscover\Pulsar\Models\Lang;
use Syscover\Pulsar\Models\Country;
use Syscover\Pulsar\Traits\ControllerTrait;

class Countries extends Controller {

    use ControllerTrait;

    protected $routeSuffix  = 'Country';
    protected $folder       = 'countries';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_002', 'name_001', 'name_002', 'sorting_002', 'prefix_002', 'territorial_area_1_002', 'territorial_area_2_002', 'territorial_area_3_002'];
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

    /**
     * @access      public
     * @param       \Illuminate\Http\Request            $request
     * @return      json
     */
    public function jsonData(HttpRequest $request)
    {
        $langs      = Lang::getActivesLangs();

        // get parameters from url route
        $parameters = $request->route()->parameters();

        $parameters =  Miscellaneous::paginateDataTable($parameters);
        $parameters =  Miscellaneous::dataTableSorting($parameters, $this->aColumns);
        $parameters =  Miscellaneous::filteringDataTable($parameters);

        $parameters['aColumns']     = $this->aColumns;
        $parametersCount            = $parameters;
        $parametersCount['count']   = true;


        $objects        = call_user_func($this->model . '::getRecordsLimit', $parameters);
        $iFilteredTotal = call_user_func($this->model . '::getRecordsLimit', $parametersCount);
        $iTotal         = call_user_func($this->model . '::countRecords', $parameters);

        // get properties of model class
        $class          = new \ReflectionClass($this->model);

        $output = [
            "sEcho"                 => $request->input('sEcho'),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => []
        ];

        $instance = new $this->model;
        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject)
        {
		    $row = [];
		    foreach ($this->aColumns as $aColumn)
            {
                if($aColumn == "name_001")
                {
                    if($aObject[$aColumn] != '')
                        $row[] = '<img src="' . asset('/packages/syscover/pulsar/storage/langs/' . $aObject["image_001"]) .'"> ' . $aObject["name_001"];
                    else
                        $row[] = '';
                }
                elseif($aColumn == "territorial_area_1_002" && Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->reArea1, 'access'))
                {
                    $row[] = '<a href="' . route('TerritorialArea1', $aObject['id_002']) . '">' . $aObject[$aColumn] . '</a>';
                }
                elseif($aColumn == "territorial_area_2_002" && Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->reArea2, 'access'))
                {
                    $row[] = '<a href="' . route('TerritorialArea2', $aObject['id_002']) . '">' . $aObject[$aColumn].'</a>';
                }
                elseif($aColumn == "territorial_area_3_002" && Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->reArea3, 'access'))
                {
                    $row[] = '<a href="' . route('TerritorialArea3', $aObject['id_002']) . '">' . $aObject[$aColumn].'</a>';
                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
		    }

            $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_002'].'">';

            $actionUrlParameters['id']        = $aObject[$instance->getKeyName()];
            $actionUrlParameters['offset']    = Request::input('iDisplayStart');
            $actionUrlParameters['lang']      = $parameters['lang'];

            $actions = '<div class="btn-group">';
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" href="' . route('edit'. $class->getShortName(), $actionUrlParameters) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip delete-record" data-id="' . $aObject[$instance->getKeyName()] .'" data-original-title="' . trans('pulsar::pulsar.delete_record') . '" data-delete-url="' . route('delete' . $class->getShortName(), $actionUrlParameters) . '"><i class="icon-trash"></i></a>' : null;

            // set language to object
            $jsonObject = json_decode($aObject['data_002']);
            $colorFlag = "MY_green";

            foreach ($langs as $lang)
            {
                $isCreated = in_array($lang->id_001, $jsonObject->langs);

                if(!$isCreated)
                {
                    $colorFlag="MY_red";
                    break;
                }
            }

            $actions .= '<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                            <i class="brocco-icon-flag '.$colorFlag.'"></i> <i class="icon-angle-down"></i>
                        </span>
                        <ul class="dropdown-menu pull-right">';

            $nLangs = count($langs); $j=0;

            foreach ($langs as $lang)
            {
                $isCreated = in_array($lang->id_001, $jsonObject->langs);

                if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit') && Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'create'))
                {
                    $actions .= '<li><a class="bs-tooltip" href="';
                    if($isCreated)
                    {
                        $actions .= route('editCountry', ["id" => $aObject['id_002'], "lang" => $lang->id_001, "offset" => Request::input('iDisplayStart')]);
                    }
                    else
                    {
                        $actions .= route('createCountry', ["id" => $aObject['id_002'], "lang" => $lang->id_001, "offset" => Request::input('iDisplayStart')]);
                    }

                    $actions .= '" data-original-title="' . $lang->name_001 . '"><img src="' . asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001) . '"> ';

                    if($isCreated)
                    {
                        $actions .= trans('pulsar::pulsar.edit');
                    }
                    else
                    {
                        $actions .= trans('pulsar::pulsar.create');
                    }
                    $actions .= '</a></li>';
                }

                $j++;
                if($j < $nLangs) $actions .= '<li class="divider"></li>';
            }

            $actions .= '</ul>';
            $actions .= '</div>';

		    $row[] =  $actions;
                
            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return view('pulsar::common.views.json_display', $data);
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