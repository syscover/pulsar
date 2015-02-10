<?php namespace Pulsar\Pulsar\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Illuminate\Support\Facades\Redirect;

trait ControllerTrait {

    public function index($offset = 0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'access')) App::abort(403, 'Permission denied.');

        Miscellaneous::sessionParamterSetPage($this->resource);

        $data['resource']       = $this->resource;
        $data['offset']         = $offset;
        $data['javascriptView'] = 'pulsar::' . $this->folder . '.js.index';

        return view('pulsar::' . $this->route . '.index', $data);
    }

    /**
     * @access	public
     * @param   string  $resource
     * @param   string  $model
     * @return	boolean
     */
    public function destroyRecord($resource, $model, $id)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $resource, 'delete')) App::abort(403, 'Permission denied.');

        call_user_func($model . '::destroy', $id);
    }

    /**
     * @access	public
     * @param   string  $resource
     * @param   string  $model
     * @return	boolean
     */
    public function destroyRecordsSelect()
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')) App::abort(403, 'Permission denied.');

        $nElements = Input::get('nElementsDataTable');
        $ids = array();

        for($i=0; $i < $nElements; $i++)
        {
            if(Input::get('element' . $i) != false)
            {
                array_push($ids, Input::get('element' . $i));
            }
        }

        call_user_func($this->model . '::deleteRecords', $ids);

        return Redirect::route($this->route)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_records_successful')
        ]);
    }
}