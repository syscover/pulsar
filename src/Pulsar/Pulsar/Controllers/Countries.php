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

use Illuminate\Support\Facades\App,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\URL,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Lang,
    Illuminate\Support\Facades\Redirect,
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Pulsar\Pulsar\Models\Lang as Language,
    Pulsar\Pulsar\Models\Pais;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Countries extends BaseController {

    use ControllerTrait;


    protected $resource = 'admin-country';
    protected $route    = 'countries';
    protected $folder   = 'countries';
    protected $package  = 'pulsar';
    protected $aColumns = ['id_002', 'name_001', 'name_002', 'sorting_002', 'prefix_002', 'territorial_area_1_002', 'territorial_area_2_002', 'territorial_area_3_002'];
    protected $nameM    = 'name_001';
    protected $model    = '\Pulsar\Pulsar\Models\Lang';

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
        $idiomaBase     = Session::get('idiomaBase');
        $idiomas        = Language::getIdiomasActivos();

        //Columnas para instanciar filtos de la tabla
	    $aColumns = array('id_002', 'name_001', 'nombre_002', 'orden_002', 'prefijo_002', 'area_territorial_1_002', 'area_territorial_2_002', 'area_territorial_3_002');
        $params = array();
        
        //Paginado de la tabla
        $params =  Miscellaneous::paginateDataTable($params);
	    
        //Orden de la tabla
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);
        
        //filtrados de la tabla
        $params =  Miscellaneous::filteringDataTable($params);
	        
        //Toma de datos para la tabla
        $objects        = Pais::getPaisesLimit($idiomaBase->id_001, $aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Pais::getPaisesLimit($idiomaBase->id_001, $aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'], null, true);
        $iTotal         = Pais::getPaisesLimit($idiomaBase->id_001, $aColumns)->count();
        $ids            = Miscellaneous::getIdsCollection($objects, 'id_002');
        $paisesAllLang  = Pais::getPaisesFromIds($ids);

        //cabecera JSON
        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => array()
        );

        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject)
        {
		    $row = array();
		    foreach ($aColumns as $aColumn)
            {
                if($aColumn == "name_001")
                {
                    if($aObject[$aColumn] != '')
                        $row[] = '<img src="' . URL::asset('/packages/pulsar/pulsar/storage/languages/' . $aObject["image_001"]) .'"> ' . $aObject["name_001"];
                    else
                        $row[] = '';
                }
                elseif($aColumn == "area_territorial_1_002" && Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->reArea1, 'access'))
                {
                    $row[] = '<a href="' . URL::to(Config::get('pulsar::pulsar.rootUri') . '/pulsar/areasterritoriales1/' . $aObject['id_002']) . '">' . $aObject[$aColumn] . '</a>';
                }
                elseif($aColumn == "area_territorial_2_002" && Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->reArea2, 'access'))
                {
                    $row[] = '<a href="' . URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/areasterritoriales2/'.$aObject['id_002'].'">' . $aObject[$aColumn].'</a>';
                }
                elseif($aColumn == "area_territorial_3_002" && Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->reArea3, 'access'))
                {
                    $row[] = '<a href="' . URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/areasterritoriales3/'.$aObject['id_002'].'">' . $aObject[$aColumn].'</a>';
                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
		    }

            $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_002'].'">';

            $acciones = '<div class="btn-group">';
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/paises/'.$aObject['id_002'].'/edit/'.$idiomaBase->id_001.'/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_registro').'"><i class="icon-pencil"></i></a>' : '';
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$aObject['id_002'].'\')" data-original-title="'.Lang::get('pulsar::pulsar.borrar_registro').'"><i class="icon-trash"></i></a>' : '';

            $colorFl="MY_green";

            foreach ($idiomas as $idioma)
            {
                $isNew = Miscellaneous::isCreateLanguage($aObject['id_002'], $idioma->id_001, $paisesAllLang, 'idioma_002', 'id_002');

                if($colorFl=="MY_green" && $isNew)
                {
                    $colorFl="MY_red";
                    break;
                }
            }

            $acciones .= '<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                            <i class="brocco-icon-flag '.$colorFl.'"></i> <i class="icon-angle-down"></i>
                        </span>
                        <ul class="dropdown-menu pull-right">';

            $nIdiomas = count($idiomas); $j=0;

            foreach ($idiomas as $idioma)
            {
                $is_new = Miscellaneous::isCreateLanguage($aObject['id_002'], $idioma->id_001, $paisesAllLang, 'idioma_002', 'id_002');

                if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit') && Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create'))
                {
                     $acciones .= '<li><a class="bs-tooltip" title="" href="'.URL::to(Config::get('pulsar::pulsar.rootUri')).'/pulsar/paises/';
                     if($is_new)
                     {
                         $acciones .= 'create/' . Input::get('iDisplayStart') . '/' . $idioma->id_001 . '/'.$aObject['id_002'];
                     }
                     else
                     {
                         $acciones .= $aObject['id_002'].'/edit/'.$idioma->id_001.'/'.Input::get('iDisplayStart');
                     }
                     $acciones .= '" data-original-title="' . $idioma->name_001 . '"><img src="' . URL::asset('/packages/pulsar/pulsar/storage/languages/' . $idioma->image_001) . '"> ';
                     if($is_new)
                     {
                         $acciones .= Lang::get('pulsar::pulsar.crear');
                     }
                     else
                     {
                         $acciones .= Lang::get('pulsar::pulsar.editar');
                     }
                     $acciones .= '</a></li>';
                }

                $j++;
                if($j < $nIdiomas) $acciones .= '<li class="divider"></li>';
            }

            $acciones .= '</ul>';
            $acciones .= '</div>';

		    $row[] =  $acciones;
                
            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
    
    public function create($inicio=0, $idioma, $id=null)
    {
        if($id != null)
        {
            $data['pais'] = Pais::getPais($id, Session::get('idiomaBase')->id_001);
        }
        $data['inicio'] = $inicio;
        $data['idioma'] = Language::find($idioma);
        return view('pulsar::pulsar.pulsar.paises.create',$data);
    }
    
    public function store($inicio=0)
    {

        
        //comprobamos si es un nuevo idioma o no para velidar el ID
        if(Input::get('idioma') != Session::get('idiomaBase')->id_001) $idRule = false; else $idRule = true;
        
        $validation = Pais::validate(Input::all(), $idRule);
              
        if ($validation->fails())
        {
            return Redirect::route('createPais',array($inicio,Input::get('idioma')))->withErrors($validation)->withInput();
        }
        else
        {
            $pais = array(
                'id_002'                    => Input::get('id'),
                'idioma_002'                => Input::get('idioma'),
                'nombre_002'                => Input::get('nombre'),
                'orden_002'                 => Input::get('orden',0),
                'prefijo_002'               => Input::get('prefijo'),
                'area_territorial_1_002'    => Input::get('areaTerritorial1'),
                'area_territorial_2_002'    => Input::get('areaTerritorial2'),
                'area_territorial_3_002'    => Input::get('areaTerritorial3')
            );
            
            Pais::create($pais); 
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('paises',array($inicio));  
        }
    }
    
    public function edit($id, $idioma, $inicio=0)
    {

        
        $data['inicio']         = $inicio;
        $data['pais']           = Pais::getPais($id, $idioma);
        $data['idioma']         = $data['pais']->idioma;
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.paises.js.edit';
        return view('pulsar::pulsar.pulsar.paises.edit',$data);
    }
    
    public function update($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        $validation = Pais::validate(Input::all(), false);
        
        if ($validation->fails())
        {
            return Redirect::route('editPais',array(Input::get('id'), Input::get('idioma'), $inicio))->withErrors($validation);
        }
        else
        {
            Pais::where('id_002','=',Input::get('id'))->where('idioma_002','=',Input::get('idioma'))->update(array(
                'nombre_002'                => Input::get('nombre'),
                'orden_002'                 => Input::get('orden',0),
                'area_territorial_1_002'    => Input::get('areaTerritorial1'),
                'area_territorial_2_002'    => Input::get('areaTerritorial2'),
                'area_territorial_3_002'    => Input::get('areaTerritorial3')
            ));
            
            //Datos comunes
            Pais::where('id_002','=',Input::get('id'))->update(array(
                'prefijo_002'               => Input::get('prefijo')
            ));

            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('paises',array($inicio));
        }
    }
    
    public function destroy($id)
    {

        
        $pais = Pais::getPais($id, Session::get('idiomaBase')->id_001);
        Pais::deletePais($id);

        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registro',array('nombre' => $pais->nombre_002)));
        
        return Redirect::route('paises');
    }
        
    public function destroySelect($inicio=0)
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
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registros'));
        
        return Redirect::route('paises');
    }
    
    public function destroyLang($id, $idioma, $inicio=0)
    {

        
        $pais = Pais::getPais($id, $idioma);
        Pais::deleteLangPais($id, $idioma);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registro',array('nombre' => $pais->nombre_002)));
        return Redirect::route('paises', array($inicio));
    }
    
    public function jsonGetPais($id)
    {
        $data['json'] = array();
        if($id!="null") $data['json'] = Pais::getPais($id, Session::get('idiomaBase')->id_001)->toJson();
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
}