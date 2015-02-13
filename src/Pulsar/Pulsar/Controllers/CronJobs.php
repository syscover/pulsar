<?php

/**
 * To generic comments, please look, Pulsar/Pulsar/Controllers/Languages
 * @package	Pulsar
 * @author	SYSCOVER (http://www.syscover.com/)
 */

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
    Cron\CronExpression,
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Pulsar\Pulsar\Models\Package,
    Pulsar\Pulsar\Models\CronJob;

class CronJobs extends BaseController
{
    private $resource = 'admin-cron';
    
    public function index($offset=0)
    {


        Miscellaneous::sessionParamterSetPage($this->resource);

        $data['recurso']        = $this->resource;
        $data['inicio']         = $offset;
        $data['javascriptView'] = 'pulsar::pulsar.pulsar.cron_jobs.js.index';

        return view('pulsar::pulsar.pulsar.cron_jobs.index',$data);
    }
    
    public function jsonData()
    {


	    $aColumns = array('id_043', 'nombre_043', 'name_012', 'key_043', 'cron_expresion_043', 'activa_043', 'last_run_043', 'next_run_043');
        $params = array();
        
        //Paginado de la tabla
        $params =  Miscellaneous::paginateDataTable($params);
	    
        //Orden de la tabla
        $params =  Miscellaneous::dataTableSorting($params, $aColumns);
        
        //filtrados de la tabla
        $params =  Miscellaneous::filteringDataTable($params);
	        
        //Toma de datos para la tabla
        $objects        = CronJob::getCronJobsLimit($aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = CronJob::getCronJobsLimit($aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'], null, true);
        $iTotal         = CronJob::count();
        
        //cabecera JSON
        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => array()
        );
        
        $aObjects = $objects->toArray(); $i=0;
        $date = new \DateTime();
        foreach($aObjects as $aObject)
        {
		    $row = array();
		    foreach ($aColumns as $aColumn)
            {
                if($aColumn == "activa_043")
                {
                    if($aObject[$aColumn] == 1)
                    {
                        $row[] = '<i class="icomoon-icon-checkmark-3"></i>';
                    }
                    else
                    {
                        $row[] = '<i class="icomoon-icon-blocked"></i>';
                    }
                }
                elseif($aColumn == "last_run_043")
                {
                    $row[] = $date->setTimestamp($aObject[$aColumn])->format('d-m-Y H:i:s');
                }
                elseif($aColumn == "next_run_043")
                {
                    $row[] = $date->setTimestamp($aObject[$aColumn])->format('d-m-Y H:i:s');
                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
		    }

            $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_043'].'">';

            //Botones de acciones
            $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'access')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to('/').'/'.Config::get('pulsar::pulsar.rootUri').'/pulsar/cron/jobs/'.$aObject['id_043'].'/run/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.ejecutar').'"><i class="icon-bolt"></i></a>' : '';
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" title="" href="'.URL::to('/').'/'.Config::get('pulsar::pulsar.rootUri').'/pulsar/cron/jobs/'.$aObject['id_043'].'/edit/'.Input::get('iDisplayStart').'" data-original-title="'.Lang::get('pulsar::pulsar.editar_registro').'"><i class="icon-pencil"></i></a>' : '';
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" title="" href="javascript:deleteElement(\''.$aObject['id_043'].'\')" data-original-title="'.Lang::get('pulsar::pulsar.borrar_registro').'"><i class="icon-trash"></i></a>' : '';
		    $row[] =  $acciones;
                
            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return view('pulsar::pulsar.pulsar.common.json_display',$data);
    }
    
    public function run($id, $offset=0)
    {

        
        $cronJob = CronJob::find($id);
        $comando = Config::get('pulsar::cron.'.$cronJob->key_043);
        
        $comando(); //ejecutamos la tarea

        return Redirect::route('cronJobs', array($offset))->with(array(
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.tarea_ejecutada',array('nombre' => $cronJob->nombre_043))
        ));
    }
    
    public function create($offset=0)
    {

        
        return view('pulsar::pulsar.pulsar.cron_jobs.create',array('inicio' => $offset, 'modulos' =>  Package::all()));
    }
    
    public function store($offset=0)
    {

        
        $validation = CronJob::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createCronJob',array($offset))->withErrors($validation)->withInput();
        }
        else
        {            
            $cron = CronExpression::factory(Input::get('cronExpresion'));
            CronJob::create(array(
                'nombre_043'            => Input::get('nombre'),
                'modulo_043'            => Input::get('modulo'),
                'cron_expresion_043'    => Input::get('cronExpresion'),
                'key_043'               => Input::get('key'),
                'last_run_043'          => 0,
                'next_run_043'          => $cron->getNextRunDate()->getTimestamp(),
                'activa_043'            => Input::get('activa',0)
            )); 
            
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg',Lang::get('pulsar::pulsar.aviso_alta_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('cronJobs',array($offset));
        }
    }
    
    public function edit($id, $offset=0)
    {

        
        $data['inicio'] = $offset;
        $data['cronJob'] = CronJob::find($id);
        $data['modulos'] = Package::all();
        $date = new \DateTime();
        $data['ultimaEjecucion'] = $date->setTimestamp($data['cronJob']->last_run_043)->format('d-m-Y H:i:s');
        $data['siguienteEjecucion'] = $date->setTimestamp($data['cronJob']->next_run_043)->format('d-m-Y H:i:s');
        
        
        return view('pulsar::pulsar.pulsar.cron_jobs.edit',$data);
    }
    
    public function update($offset=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        $validation = CronJob::validate(Input::all());
        
        if ($validation->fails())
        {
            return Redirect::route('editCronJob',array(Input::get('id'), $offset))->withErrors($validation);
        }
        else
        {
            CronJob::where('id_043','=',Input::get('id'))->update(array(
                'nombre_043'            => Input::get('nombre'),
                'modulo_043'            => Input::get('modulo'),
                'cron_expresion_043'    => Input::get('cronExpresion'),
                'key_043'               => Input::get('key'),
                'activa_043'            => Input::get('activa',0)
            ));  
            
            //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
            Session::flash('msg',1);
            Session::flash('txtMsg', Lang::get('pulsar::pulsar.aviso_actualiza_registro',array('nombre' => Input::get('nombre'))));
            
            return Redirect::route('cronJobs',array($offset));
        }
    }

    public function destroy($id)
    {
        i
        
        $cronJob = CronJob::find($id);
        CronJob::destroy($id);
        
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registro',array('nombre' => $cronJob->nombre_043)));
        
        return Redirect::route('cronJobs');
    }
    
    public function destroySelect($offset=0)
    {
        i
        
        $nElements = Input::get('nElementsDataTable'); 
        $ids = array();
        for($i=0;$i<$nElements;$i++)
        {
            if(Input::get('element'.$i) != false)
            {
                array_push($ids, Input::get('element'.$i));
            }
        }
                
        CronJob::deleteCronJobs($ids);
        //Instanciamos una sessicón flash para indicar el mensaje al usuario, esta sesión solo dura durante una petición        
        Session::flash('msg',1);
        Session::flash('txtMsg', Lang::get('pulsar::pulsar.borrado_registros'));
        
        return Redirect::route('cronJobs');
    }
}