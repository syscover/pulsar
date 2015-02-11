<?php namespace Pulsar\Pulsar\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
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
        Miscellaneous::setParameterSessionPage($this->resource);

        $data['resource']       = $this->resource;
        $data['offset']         = $offset;
        $data['javascriptView'] = 'pulsar::' . $this->folder . '.js.index';

        if(method_exists($this, 'indexCustom'))
        {
            $data = $this->indexCustom($data);
        }

        return view('pulsar::' . $this->folder . '.index', $data);
    }

    public function jsonData()
    {
        // table paginated
        $params =  Miscellaneous::paginateDataTable();
        // table sorting
        $params =  Miscellaneous::dataTableSorting($params, $this->aColumns);
        // quick search data table
        $params =  Miscellaneous::filteringDataTable($params);

        // get data to table
        $objects        = call_user_func($this->model . '::getRecordsLimit', $this->aColumns, $params['sLength'], $params['sStart'], $params['sOrder'], $params['sTypeOrder'], $params['sWhere']);
        $iFilteredTotal = call_user_func($this->model . '::getRecordsLimit', $this->aColumns, null, null, $params['sOrder'], $params['sTypeOrder'], $params['sWhere'], null, true);
        $iTotal         = call_user_func($this->model . '::count');

        $output = [
            "sEcho"                 => Input::get('sEcho'),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => []
        ];

        // instance model to get primary key
        $instance = new $this->model;
        $aObjects = $objects->toArray(); $i=0;
        foreach($aObjects as $aObject)
        {
            $row = [];
            foreach ($this->aColumns as $aColumn)
            {
                if(is_array($aColumn))
                {
                    switch ($aColumn['type']) {
                        case 'img':
                            $row[] = $aObject[$aColumn['name']] != ''? '<img src="' . asset($aColumn['url'] . $aObject[$aColumn['name']]) . '">' : null;
                            break;

                        case 'check':
                            $row[] = $aObject[$aColumn['name']]? '<i class="icomoon-icon-checkmark-3"></i>' : null;
                            break;

                        case 'active':
                            $row[] = $aObject[$aColumn['name']]? '<i class="icomoon-icon-checkmark-3"></i>' : '<i class="icomoon-icon-blocked"></i>';
                            break;
                    }
                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
            }
            $row[] = '<input type="checkbox" class="uniform" name="element' . $i . '" value="' . $aObject[$instance->getKeyName()] . '">';

            $actions = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" href="' . url(config('pulsar.appName') . '/pulsar/' . $this->folder . '/' . $aObject[$instance->getKeyName()] . '/edit/' . Input::get('iDisplayStart')) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip" href="javascript:deleteElement(\'' . $aObject[$instance->getKeyName()] . '\')" data-original-title="' . trans('pulsar::pulsar.delete_record') . '"><i class="icon-trash"></i></a>' : null;
            $row[] =  $actions;

            $output['aaData'][] = $row;
            $i++;
        }

        $data['json'] = json_encode($output);

        return view('pulsar::common.json_display',$data);
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
        if(View::exists('pulsar::' . $this->folder . '.js.edit'))
            $data['javascriptView'] = 'pulsar::' . $this->folder . '.js.edit';

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
            $this->destroyCustomRecord($object);
        }

        return Redirect::route($this->route)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_record_successful', ['name' => $object->{$this->nameM}])
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