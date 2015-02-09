<?php
namespace Pulsar\Pulsar\Controllers;

use Illuminate\Support\Facades\App,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\URL,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Lang,
    Illuminate\Support\Facades\View,
    Illuminate\Support\Facades\Redirect,
    Illuminate\Support\Facades\File,
    //Pulsar\Pulsar\Libraries\Miscellaneous,

    Pulsar\Pulsar\Models\Language;

class Languages extends BaseController
{
    protected $resource = 'admin-lang';
    protected $route    = 'languages';
    protected $package  = 'pulsar';

    public function jsonData()
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'access')) App::abort(403, 'Permission denied.');

        // columns to set table filter
        $aColumns = array('id_001','image_001','name_001','base_001','active_001','sorting_001');
        $params = array();

        // table paginated
        $params =  Miscellaneous::paginateDataTable($params);

        // table sorting
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);

        // quick search data table
        $params =  Miscellaneous::filteringDataTable($params);

        // get data to table
        $objects        = Language::getIdiomasLimit($aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Language::getIdiomasLimit($aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'], null, true);
        $iTotal         = Language::count();

        // instance json data
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
                if($aColumn == "email_010")
                {
                    $row[] = '<a href="mailto:'.$aObject[$aColumn].'">'.$aObject[$aColumn].'</a>';
                }
                elseif($aColumn == "image_001")
                {
                    if($aObject[$aColumn] != '')
                        $row[] = '<img src="' . URL::to('/packages/pulsar/pulsar/storage/languages/' . $aObject[$aColumn]) . '">';
                    else
                        $row[] = '';

                }
                elseif($aColumn == "base_001")
                {
                    if($aObject[$aColumn] == 1)
                        $row[] = '<i class="icomoon-icon-checkmark-3"></i>';
                    else
                        $row[] = '';

                }
                elseif($aColumn == "active_001")
                {
                    if($aObject[$aColumn] == 1)
                        $row[] = '<i class="icomoon-icon-checkmark-3"></i>';
                    else
                        $row[] = '<i class="icomoon-icon-blocked"></i>';

                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
		    }
            $row[] = '<input type="checkbox" class="uniform" name="element' . $i . '" value="' . $aObject['id_001'] . '">';

            $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="' . URL::to(Config::get('pulsar::pulsar.rootUri') . '/pulsar/languages/' . $aObject['id_001'] . '/edit/' . Input::get('iDisplayStart')) . '" data-original-title="' . Lang::get('pulsar::pulsar.editar_registro') . '"><i class="icon-pencil"></i></a>' : null;
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$aObject['id_001'].'\')" data-original-title="' . Lang::get('pulsar::pulsar.borrar_registro') . '"><i class="icon-trash"></i></a>' : null;
		    $row[] =  $acciones;
                
            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return View::make('pulsar::pulsar.pulsar.common.json_display', $data);
    }
    
    public function create($inicio)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
  
        return View::make('pulsar::pulsar.pulsar.languages.create',array('inicio' => $inicio));
    }
    
    public function store($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
        $validation = Language::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createIdioma', $inicio)->withErrors($validation)->withInput();
        }
        else
        {
            $filename = Miscellaneous::uploadFiles('imagen', public_path().'/packages/pulsar/pulsar/storage/languages', false, Input::get('id'));

            if(Input::get('base'))
            {
                Language::resetIdiomaBase();
            }
                       
            Language::create(array(
                'id_001'        => Input::get('id'),
                'name_001'      => Input::get('nombre'),
                'image_001'     => $filename,
                'sorting_001'   => Input::get('orden'),
                'base_001'      => Input::get('base',0),
                'active_001'    => Input::get('activo',0)
            )); 
            
            if(Input::get('base')){
                Session::put('idiomaBase', Language::getIdiomaBase());
            }

            return Redirect::route('languages', array($inicio))->with(array(
                'msg'        => 1,
                'txtMsg'     => Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre')))
            ));
        }
    }
    
    public function edit($id, $inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'access')) App::abort(403, 'Permission denied.');
        
        $data['inicio']             = $inicio;
        $data['javascriptView']     = 'pulsar::pulsar.pulsar.languages.js.edit';
        $data['idioma']             = Language::find($id);

        return View::make('pulsar::pulsar.pulsar.languages.edit',$data);
    }
    
    public function update($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        //comprobamos las reglas a cumplir dependiendo de los cambios realizados
        if(Input::hasFile('imagen')) $imageRule = true; else $imageRule = false;
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = Language::validate(Input::all(), $imageRule, $idRule);
                
        if ($validation->fails())
        {
            return Redirect::route('editIdioma',array(Input::get('idOld'), $inicio))->withErrors($validation);
        }
        else
        {
            if(Input::hasFile('imagen'))
            {
                $filename = Miscellaneous::uploadFiles('imagen', public_path().'/packages/pulsar/pulsar/storage/languages', false, Input::get('id'));
            }
            else
            {
                // we change file name to be the same name that new ID
                if($idRule)
                {
                    $filename = Input::get('id') . '.' . File::extension(Input::get('imagen'));
                    File::move(public_path() . '/packages/pulsar/pulsar/storage/languages/' . Input::get('imagen'), public_path() . '/packages/pulsar/pulsar/storage/languages/' . $filename);
                }
                else
                {
                    $filename = Input::get('imagen');
                }
            }
            
            if(Input::get('base'))
            {
                Language::resetIdiomaBase();
            }
            
            Language::where('id_001','=',Input::get('idOld'))->update(array(
                'id_001'        => Input::get('id'),
                'name_001'      => Input::get('nombre'),
                'image_001'     => $filename,
                'sorting_001'   => Input::get('orden'),
                'base_001'      => Input::get('base',0),
                'active_001'    => Input::get('activo',0)
            ));

            if(Input::get('base'))
            {
                Session::put('idiomaBase', Language::getIdiomaBase());
            }

            return Redirect::route('languages', array($inicio))->with(array(
                'msg'        => 1,
                'txtMsg'     => Lang::get('pulsar::pulsar.aviso_actualiza_registro', array('nombre' => Input::get('nombre')))
            ));
        }
    }
    
    public function destroy($id)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'access')) App::abort(403, 'Permission denied.');
        
        $idioma = Language::find($id);
        Language::destroy($id);
        File::delete(public_path() . '/packages/pulsar/pulsar/storage/languages/' . $idioma->imagen_001);

        return Redirect::route('languages')->with(array(
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.borrado_registro', array('nombre' => $idioma->name_001))
        ));
    }
    
    public function destroySelect($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'delete')) App::abort(403, 'Permission denied.');
        
        $nElements = Input::get('nElementsDataTable'); 
        
        $ids = array();
        for($i=0; $i < $nElements; $i++)
        {
            if(Input::has('element'.$i))
            {
                array_push($ids, Input::get('element'.$i));
            }
        }
        
        $languages = Language::getIdiomasId($ids);
        
        foreach ($languages as $language)
        {
            File::delete(public_path() . '/packages/pulsar/pulsar/storage/languages/' . $language->image_001);
        }

        Language::deleteIdiomas($ids);

        return Redirect::route('languages')->with(array(
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.borrado_registros', array('nombre' => $idioma->name_001))
        ));
    }

    public function deleteImagen($id)
    {
        $idioma = Language::find($id);

        File::delete(public_path() . '/packages/pulsar/pulsar/storage/languages/' . $idioma->imagen_001);

        Language::where('id_001', $id)->update(array(
            'image_001' => null,
        ));
    }
}