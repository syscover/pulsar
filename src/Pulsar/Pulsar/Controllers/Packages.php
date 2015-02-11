<?php namespace Pulsar\Pulsar\Controllers;

use Illuminate\Support\Facades\App,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\URL,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Lang,
    Illuminate\Support\Facades\View,
    Illuminate\Support\Facades\Redirect,
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Pulsar\Pulsar\Models\Package;

class Packages extends BaseController {

    protected $resource   = 'admin-package';
    protected $route      = 'packages';
    protected $folder       = 'packages';
    protected $package    = 'pulsar';
    
    public function index($inicio=0)
    {


        Miscellaneous::sessionParamterSetPage($this->resource);
        
        $data['recurso']        = $this->resource;
        $data['inicio']         = $inicio; 
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.packages.js.index';
        
        return View::make('pulsar::pulsar.pulsar.packages.index',$data);
    }
    
    public function jsonData()
    {


	    $aColumns = array('id_012', 'name_012', 'active_012');
        $params = array();

        $params =  Miscellaneous::paginateDataTable($params);

        $params =  Miscellaneous::dataTableSorting($params, $aColumns);

        $params =  Miscellaneous::filteringDataTable($params);

        $objects        = Package::getModulosLimit($aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Package::getModulosLimit($aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'], null, true);
        $iTotal         = Package::count();

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
                if($aColumn == "active_012")
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

            $row[] = '<input type="checkbox" class="uniform" name="element' . $i . '" value="' . $aObject['id_012'] . '">';

            $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="' . URL::to(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/' . $aObject['id_012'] . '/edit/' . Input::get('iDisplayStart')) . '" data-original-title="' . Lang::get('pulsar::pulsar.editar_registro') . '"><i class="icon-pencil"></i></a>' : null;
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\'' . $aObject['id_012'] . '\')" data-original-title="' . Lang::get('pulsar::pulsar.borrar_registro') . '"><i class="icon-trash"></i></a>' : null;
            $row[] =  $acciones;

            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return View::make('pulsar::pulsar.pulsar.common.json_display',$data);
    }
    
    public function create($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
        return View::make('pulsar::pulsar.pulsar.packages.create',array('inicio' => $inicio));
    }
    
    public function store($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
        $validation = Package::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createModulo',array($inicio))->withErrors($validation)->withInput();
        }
        else
        {
            Package::create(array(
                'name_012'  => Input::get('nombre'),
                'active_012'  => Input::get('activo',0)
                    
            )); 

            return Redirect::route('packages', array($inicio))->with(array(
                'msg'        => 1,
                'txtMsg'     => Lang::get('pulsar::pulsar.aviso_alta_registro', array('nombre' => Input::get('nombre')))
            ));
        }
    }
    
    public function edit($id, $inicio=0)
    {

        
        $data['inicio'] = $inicio;
        $data['modulo'] = Package::find($id);
        
        return View::make('pulsar::pulsar.pulsar.packages.edit',$data);
    }
    
    public function update($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        $validation = Package::validate(Input::all());
        
        if ($validation->fails())
        {
            return Redirect::route('editModulo',array(Input::get('id'), $inicio))->withErrors($validation);
        }
        else
        {
            Package::where('id_012','=',Input::get('id'))->update(array(
                'name_012'  => Input::get('nombre'),
                'active_012'  => Input::get('activo',0)
            ));
            
            // update object packages from session
            Session::put('modulos', Package::getModulosForSession());

            return Redirect::route('packages', array($inicio))->with(array(
                'msg'        => 1,
                'txtMsg'     => Lang::get('pulsar::pulsar.aviso_actualiza_registro', array('nombre' => Input::get('nombre')))
            ));
        }
    }

    public function destroy($id)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'delete')) App::abort(403, 'Permission denied.');
        
        $modulo = Package::find($id);
        Package::destroy($id);

        return Redirect::route('packages')->with(array(
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.borrado_registro', array('nombre' => $modulo->name_012))
        ));
    }
    
    public function destroySelect()
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'delete')) App::abort(403, 'Permission denied.');
        
        $nElements = Input::get('nElementsDataTable'); 
        $ids = array();
        for($i=0; $i<$nElements; $i++)
        {
            if(Input::get('element' . $i) != false)
            {
                array_push($ids, Input::get('element'.$i));
            }
        }
                
        Package::deleteModulos($ids);

        return Redirect::route('packages')->with(array(
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.borrado_registros')
        ));
    }
}