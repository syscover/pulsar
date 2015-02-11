<?php
namespace Pulsar\Pulsar\Controllers;

use Illuminate\Support\Facades\App,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\View,
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Pulsar\Pulsar\Libraries\PulsarAcl,
    Pulsar\Pulsar\Models\Perfil,
    Pulsar\Pulsar\Models\Recurso,
    Pulsar\Pulsar\Models\Accion,
    Pulsar\Pulsar\Models\Permiso;


class Permisos extends BaseController
{
    
    private $resource = 'admin-pass-pass';
    
    public function index($perfil, $inicioPerfil=0, $inicio=0)
    {


        //Inicializa las sesiones para las búsquedas rápidas desde la vista de tablas en caso de cambio de página
        Miscellaneous::sessionParamterSetPage($this->resource);
                
        //instanciamos la variable de inicio pasra sabel el punto de inicio en caso de borrado o edición, volver al mismo punto de la lista
        $data['inicio']         = $inicio;
        $data['inicioPerfil']   = $inicioPerfil;
        $data['perfil']         = Perfil::find($perfil);
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.permisos.js.index';
                
        return View::make('pulsar::pulsar.pulsar.permisos.index',$data);
    }
    
    public function jsonData($perfil)
    {

        
        $accionesUser   = Accion::get();
        $acl            = PulsarAcl::getAclPerfil($perfil);
        
        //Columnas para instanciar filtos de la tabla
	    $aColumns = array('id_007','name_012','nombre_007');
        $params = array();
        
        //Paginado de la tabla
        $params =  Miscellaneous::paginateDataTable($params);
	    
        //Orden de la tabla
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);
        
        //filtrados de la tabla
        $params =  Miscellaneous::filteringDataTable($params);
	        
        //Toma de datos para la tabla
        $objects        = Recurso::getRecursosLimit($aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Recurso::getRecursosLimit($aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'])->count();
        $iTotal         = Recurso::count();
        
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
		    foreach ($aColumns as $aColumn){
                $row[] = $aObject[$aColumn];
            }
                
            //select de permisos
            $acciones = '<div><select id="re'.$aObject['id_007'].'" data-idrecurso="'.$aObject['id_007'].'" data-nrecurso="'.$aObject['nombre_007'].'" multiple style="width: 100%;">';
            foreach ($accionesUser as $accionUser){
                $selected = $acl->isAllowed($perfil, $aObject['id_007'], $accionUser->id_008)? ' selected=""' : '';
                $acciones .= '<option value="'.$accionUser->id_008.'"'.$selected.'>'.$accionUser->name_008.'</option>';
            }
            $acciones .= '</select></div>';

            $row[] =   $acciones;
            //$row[] =   'hola';

            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return View::make('pulsar::pulsar.pulsar.common.json_display',$data);
    }
    
    public function jsonCreate($perfil, $recurso, $accion)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
        Permiso::create(array(
            'perfil_009'    => $perfil,
            'recurso_009'   => $recurso,
            'accion_009'    => $accion
        ));
    }
    
    public function jsonDestroy($perfil, $recurso, $accion)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'delete')) App::abort(403, 'Permission denied.');
        Permiso::deletePermiso($perfil, $recurso, $accion);
    }
}