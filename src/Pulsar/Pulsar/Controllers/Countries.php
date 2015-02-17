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

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
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

    protected $reArea1  = 'admin-country-at1';
    protected $reArea2  = 'admin-country-at2';
    protected $reArea3  = 'admin-country-at3';

    
    public function indexCustom($data)
    {
        $data['baseLang']     = Session::get('baseLang');

        return $data;
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

        $ids                = Miscellaneous::getIdsCollection($objects, 'id_002');
        $countriesAllLang   = Country::getContriesFromIds($ids);

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
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" href="' . route('edit'. $class->getShortName(), [$aObject[$instance->getKeyName()], Input::get('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip delete-record" data-id="' . $aObject[$instance->getKeyName()] .'" data-original-title="' . trans('pulsar::pulsar.delete_record') . '"><i class="icon-trash"></i></a>' : null;

            // set language to object
            $jsonObject = json_decode($aObject['data_002']);
            $colorFlag = "MY_red";
            
            foreach ($langs as $lang)
            {
                $isCreated = in_array($lang->id_001, $jsonObject->langs);

                if($isCreated)
                {
                    $colorFlag="MY_green";
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
                        $actions .= route('editCountry', $aObject['id_002'], $lang->id_001, Input::get('iDisplayStart'));
                    }
                    else
                    {
                        $actions .= route('createCountry', Input::get('iDisplayStart'), $lang->id_001, $aObject['id_002']);
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






    
    public function create($offset = 0, $lang, $id = null)
    {
        if($id != null)
        {
            $data['pais'] = Pais::getPais($id, Session::get('idiomaBase')->id_001);
        }
        $data['inicio'] = $offset;
        $data['idioma'] = Lang::find($lang);
        return view('pulsar::pulsar.pulsar.paises.create',$data);
    }
    
    public function store($offset=0)
    {

        
        //comprobamos si es un nuevo idioma o no para velidar el ID
        if(Input::get('idioma') != Session::get('idiomaBase')->id_001) $idRule = false; else $idRule = true;
        
        $validation = Pais::validate(Input::all(), $idRule);
              
        if ($validation->fails())
        {
            return Redirect::route('createPais',array($offset,Input::get('idioma')))->withErrors($validation)->withInput();
        }
        else
        {
            $pais = array(
                'id_002'                    => Input::get('id'),
                'lang_002'                => Input::get('idioma'),
                'nombre_002'                => Input::get('nombre'),
                'orden_002'                 => Input::get('orden',0),
                'prefijo_002'               => Input::get('prefijo'),
                'territorial_area_1_002'    => Input::get('areaTerritorial1'),
                'territorial_area_2_002'    => Input::get('areaTerritorial2'),
                'territorial_area_3_002'    => Input::get('areaTerritorial3')
            );
            
            Pais::create($pais); 
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',trans('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('paises',array($offset));
        }
    }
    
    public function edit($id, $lang, $offset=0)
    {

        
        $data['inicio']         = $offset;
        $data['pais']           = Pais::getPais($id, $lang);
        $data['idioma']         = $data['pais']->idioma;
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.paises.js.edit';
        return view('pulsar::pulsar.pulsar.paises.edit',$data);
    }
    
    public function update($offset=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        $validation = Pais::validate(Input::all(), false);
        
        if ($validation->fails())
        {
            return Redirect::route('editPais',array(Input::get('id'), Input::get('idioma'), $offset))->withErrors($validation);
        }
        else
        {
            Pais::where('id_002','=',Input::get('id'))->where('lang_002','=',Input::get('idioma'))->update(array(
                'nombre_002'                => Input::get('nombre'),
                'orden_002'                 => Input::get('orden',0),
                'territorial_area_1_002'    => Input::get('areaTerritorial1'),
                'territorial_area_2_002'    => Input::get('areaTerritorial2'),
                'territorial_area_3_002'    => Input::get('areaTerritorial3')
            ));
            
            //Datos comunes
            Pais::where('id_002','=',Input::get('id'))->update(array(
                'prefijo_002'               => Input::get('prefijo')
            ));

            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',trans('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('paises',array($offset));
        }
    }
    
    public function destroy($id)
    {

        
        $pais = Pais::getPais($id, Session::get('idiomaBase')->id_001);
        Pais::deletePais($id);

        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', trans('pulsar::pulsar.borrado_registro',array('nombre' => $pais->nombre_002)));
        
        return Redirect::route('paises');
    }
        
    public function destroySelect($offset=0)
    {

        
        $nElements = Input::get('nElementsDataTable'); 
        $ids = array();
        for($i=0;$i<$nElements;$i++){
            if(Input::get('element'.$i) != false){
                array_push($ids, Input::get('element'.$i));
            }
        }
        
        Pais::deletePaises($ids);
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición        
        Session::flash('msg',1);
        Session::flash('txtMsg', trans('pulsar::pulsar.borrado_registros'));
        
        return Redirect::route('paises');
    }
    
    public function destroyLang($id, $lang, $offset=0)
    {
        $pais = Pais::getPais($id, $lang);
        Pais::deleteLangPais($id, $lang);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', trans('pulsar::pulsar.borrado_registro',array('nombre' => $pais->nombre_002)));
        return Redirect::route('paises', array($offset));
    }
    
    public function jsonCountry($id)
    {
        $data['json'] = array();
        if($id!="null") $data['json'] = Pais::getPais($id, Session::get('idiomaBase')->id_001)->toJson();
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
}