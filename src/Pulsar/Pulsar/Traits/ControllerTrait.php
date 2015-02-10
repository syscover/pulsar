<?php namespace Pulsar\Pulsar\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Illuminate\Support\Facades\Redirect;

trait ControllerTrait {

    /**
     * @access	public
     * @param   integer  $offset
     * @return	View
     */
    public function index($offset = 0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'access')) App::abort(403, 'Permission denied.');

        Miscellaneous::sessionParamterSetPage($this->resource);

        $data['resource']       = $this->resource;
        $data['offset']         = $offset;
        $data['javascriptView'] = 'pulsar::' . $this->folder . '.js.index';

        if(method_exists($this, 'indexCustom'))
        {
            $data = $this->indexCustom($data);
        }

        return view('pulsar::' . $this->folder . '.index', $data);
    }

    /**
     * @access	public
     * @param   integer     $offset
     * @return	View
     */
    public function createRecord($offset = 0)
    {
        if(method_exists($this, 'createCustomRecord'))
        {
            $this->createCustomRecord();
        }

        return view('pulsar::' . $this->folder . '.create', ['offset' => $offset]);
    }

    /**
     * @access	public
     * @param   integer     $id
     * @param   integer     $offset
     * @return	View
     */
    public function editRecord($id, $offset = 0)
    {
        $data['offset'] = $offset;
        $data['object'] = call_user_func($this->model . '::find', $id);

        if(method_exists($this, 'editCustomRecord'))
        {
            $data = $this->editCustomRecord($data);
        }

        return view('pulsar::' . $this->folder . '.edit', $data);
    }


    /**
     * @access	public
     * @param   string  $resource
     * @param   string  $model
     * @return	Redirect
     */
    public function destroyRecord($id)
    {
        $object = call_user_func($this->model . '::find', $id);
        call_user_func($this->model . '::destroy', $id);

        if(method_exists($this, 'destroyCustomRecord'))
        {
            $this->destroyCustomRecord($id);
        }

        return Redirect::route($this->route)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_record_successful', array('name' => $object->{$this->nameM}))
        ]);
    }


    /**
     * @access	public
     * @param   string  $resource
     * @param   string  $model
     * @return	Redirect
     */
    public function destroyRecordsSelect()
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')) App::abort(403, 'Permission denied.');

        $nElements = Input::get('nElementsDataTable');
        $ids = [];

        for($i=0; $i < $nElements; $i++)
        {
            if(Input::get('element' . $i) != false)
            {
                array_push($ids, Input::get('element' . $i));
            }
        }

        call_user_func($this->model . '::deleteRecords', $ids);

        if(method_exists($this, 'destroyCustomRecords'))
        {
            $this->destroyCustomRecords($ids);
        }

        return Redirect::route($this->route)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_records_successful')
        ]);
    }
}