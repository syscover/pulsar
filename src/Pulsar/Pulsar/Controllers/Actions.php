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
use Pulsar\Pulsar\Models\Action;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Actions extends BaseController
{
    use ControllerTrait;

    protected $resource = 'admin-pass-actions';
    protected $route    = 'actions';
    protected $folder   = 'actions';
    protected $package  = 'pulsar';
    protected $aColumns = ['id_008','name_008'];
    protected $model    = '\Pulsar\Pulsar\Models\Action';

    public function jsonData()
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'access')) App::abort(403, 'Permission denied.');
        
        $params =  Miscellaneous::paginateDataTable();
        $params =  Miscellaneous::dataTableSorting($params, $this->aColumns);
        $params =  Miscellaneous::filteringDataTable($params);

        $objects        = Action::getRecordsLimit($this->aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = Action::getRecordsLimit($this->aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'], null, true);
        $iTotal         = Action::count();

        $output = array(
            "sEcho"                 => intval(Input::get('sEcho')),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => []
        );
        
        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject)
        {
		    $row = [];
		    foreach ($this->aColumns as $aColumn)
            {
                $row[] = $aObject[$aColumn];
		    }
            $row[] = '<input type="checkbox" class="uniform" name="element'.$i.'" value="'.$aObject['id_008'].'">';

            $acciones = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" href="' . url(config('pulsar.appName') . '/pulsar/actions/' . $aObject['id_008'] . '/edit/' . Input::get('iDisplayStart')) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
            $acciones .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" href="javascript:deleteElement(\'' . $aObject['id_008'] . '\')" data-original-title="' . trans('pulsar::pulsar.delete_record') . '"><i class="icon-trash"></i></a>' : null;
		    $row[] =  $acciones;
                
            $output['aaData'][] = $row;
            $i++;
	    }
                
        $data['json'] = json_encode($output);
        
        return view('pulsar::common.json_display',$data);
    }
        
    public function create($offset = 0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
        return view('pulsar::actions.create', ['offset' => $offset]);
    }
    
    public function store($offset = 0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'create')) App::abort(403, 'Permission denied.');
        
        $validation = Action::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createAction', $offset)->withErrors($validation)->withInput();
        }
        else
        {
            Action::create(array(
                'id_008'    => Input::get('id'),
                'name_008'  => Input::get('name')
            )); 

            return Redirect::route('actions', $offset)->with([
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_log_recorded', ['name' => Input::get('name')])
            ]);
        }
    }
    
    public function edit($id, $offset = 0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'access')) App::abort(403, 'Permission denied.');
        
        $data['offset'] = $offset;
        $data['action'] = Action::find($id);
        
        return view('pulsar::actions.edit',$data);
    }
    
    public function update($offset = 0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = Action::validate(Input::all(), $idRule);
        
        if ($validation->fails())
        {
            return Redirect::route('editAction',array(Input::get('id'), $offset))->withErrors($validation);
        }
        else
        {
            Action::where('id_008', Input::get('idOld'))->update(array(
                'id_008'  => Input::get('id'),
                'name_008'  => Input::get('name')
            ));

            return Redirect::route('actions', array($offset))->with(array(
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.aviso_actualiza_registro', array('nombre' => Input::get('name')))
            ));
        }
    }

    public function destroy($id)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'delete')) App::abort(403, 'Permission denied.');
        
        $accion = Action::find($id);

        Action::destroy($id);

        return Redirect::route('actions')->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_record_successful', array('name' => $accion->name_008))
        ]);
    }
}