<?php namespace Pulsar\Pulsar\Controllers;

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
use Illuminate\Support\Facades\Input;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Models\Lang;
use Pulsar\Pulsar\Models\Country;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Countries extends BaseController {

    use ControllerTrait;

    protected $resource     = 'admin-country';
    protected $routeSuffix  = 'Country';
    protected $folder       = 'countries';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_002', 'name_001', 'name_002', 'sorting_002', 'prefix_002', 'territorial_area_1_002', 'territorial_area_2_002', 'territorial_area_3_002'];
    protected $nameM        = 'name_002';
    protected $model        = '\Pulsar\Pulsar\Models\Country';
    protected $icon         = 'entypo-icon-globe';
    protected $objectTrans  = 'country';

    protected $reArea1      = 'admin-country-at1';
    protected $reArea2      = 'admin-country-at2';
    protected $reArea3      = 'admin-country-at3';

    
    public function indexCustom($parameters)
    {
        $parameters['baseLang']     = Session::get('baseLang');

        return $parameters;
    }
    
    public function jsonData()
    {
        $baseLang   = Session::get('baseLang');
        $langs      = Lang::getActiveLangs();

        $args =  Miscellaneous::paginateDataTable();
        $args =  Miscellaneous::dataTableSorting($args, $this->aColumns);
        $args =  Miscellaneous::filteringDataTable($args);

        $args['aColumns']   = $this->aColumns;
        $args['lang']       = $baseLang->id_001;
        $argsCount = $args;
        $argsCount['count'] = true;

        $objects        = call_user_func($this->model . '::getRecordsLimit', $args);
        $iFilteredTotal = call_user_func($this->model . '::getRecordsLimit', $argsCount);
        $iTotal         = Country::getRecordsLimit(['lang' => $args['lang']])->count();

        $ids            = Miscellaneous::getIdsCollection($objects, 'id_002');

        // get properties of model class
        $class          = new \ReflectionClass($this->model);

        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => array()
        );

        $instance = new $this->model;
        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject)
        {
		    $row = array();
		    foreach ($this->aColumns as $aColumn)
            {
                if($aColumn == "name_001")
                {
                    if($aObject[$aColumn] != '')
                        $row[] = '<img src="' . asset('/packages/pulsar/pulsar/storage/langs/' . $aObject["image_001"]) .'"> ' . $aObject["name_001"];
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

            $actions = '<div class="btn-group">';
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" href="' . route('edit'. $class->getShortName(), [$aObject[$instance->getKeyName()], $baseLang->id_001, Input::get('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip delete-record" data-id="' . $aObject[$instance->getKeyName()] .'" data-original-title="' . trans('pulsar::pulsar.delete_record') . '"><i class="icon-trash"></i></a>' : null;

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
                        $actions .= route('editCountry', [$aObject['id_002'], $lang->id_001, Input::get('iDisplayStart')]);
                    }
                    else
                    {
                        $actions .= route('createCountry', [Input::get('iDisplayStart'), $lang->id_001, $aObject['id_002']]);
                    }

                    $actions .= '" data-original-title="' . $lang->name_001 . '"><img src="' . asset('/packages/pulsar/pulsar/storage/langs/' . $lang->image_001) . '"> ';

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
        
        return view('pulsar::common.json_display',$data);
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


    public function storeCustomRecord()
    {
        Country::create([
            'id_002'                    => Input::get('id'),
            'lang_002'                  => Input::get('lang'),
            'name_002'                  => Input::get('name'),
            'orden_002'                 => Input::get('sorting', 0),
            'prefijo_002'               => Input::get('prefix'),
            'territorial_area_1_002'    => Input::get('territorialArea1'),
            'territorial_area_2_002'    => Input::get('territorialArea2'),
            'territorial_area_3_002'    => Input::get('territorialArea3'),
            'data_002'                  => Country::addLangDataRecord(Input::get('id'), Input::get('lang'))
        ]);
    }
    
    public function editCustomRecord($parameters)
    {
        $parameters['object']   = Country::getTranslationRecord($parameters['id'], $parameters['lang']);
        $parameters['lang']     = $parameters['object']->lang;

        return $parameters;
    }
    
    public function updateCustomRecord($id)
    {
        Country::where('id_002', $id)->where('lang_002', Input::get('lang'))->update([
            'name_002'                  => Input::get('name'),
            'sorting_002'               => Input::get('sorting', 0),
            'territorial_area_1_002'    => Input::get('territorialArea1'),
            'territorial_area_2_002'    => Input::get('territorialArea2'),
            'territorial_area_3_002'    => Input::get('territorialArea3')
        ]);

        // common data
        Country::where('id_002', $id)->update([
            'prefix_002' => Input::get('prefix')
        ]);
    }

    public function jsonCountry($id)
    {
        $parameters['json'] = [];

        if($id!="null") $parameters['json'] = Country::getTranslationRecord($id, Session::get('baseLang')->id_001)->toJson();

        return view('pulsar::common.json_display', $parameters);
    }
}