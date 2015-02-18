<?php namespace Pulsar\Pulsar\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Illuminate\Support\Facades\Redirect;

trait ControllerTrait {

    /**
    * protected $resource;      Name of resource
    * protected $routeSuffix;   Suffix of routes
    * protected $folder;        Name of folder that contain views
    * protected $package;       Name of package
    * protected $aColumns;      Name of column with your data type
    * protected $nameM;         Name of database column to put on message
    * protected $model;         Name of model
    * protected $icon;          Icon to buttom from entity
    * protected $objectTrans;   Name of key array to translate object
    */

    /**
     * @access	public
     * @param       \Illuminate\Http\Request            $request
     * @return	\Illuminate\Support\Facades\View
     */
    public function index(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        Miscellaneous::setParameterSessionPage($this->resource);

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['resource']       = $this->resource;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = $this->objectTrans;

        // check if url contain offset parameter
        if(!isset($parameters['offset'])) $parameters['offset'] = 0;

        // if there ara more arguments, we take us and send to indexCustom
        if(func_num_args() > 1)
        {
            $args = func_get_args();
        }
        else
        {
            $args = null;
        }

        if(method_exists($this, 'indexCustom'))
        {
            $parameters = $this->indexCustom($parameters);
        }

        return view('pulsar::' . $this->folder . '.index', $parameters);
    }

    public function jsonData()
    {
        // table paginated
        $args =  Miscellaneous::paginateDataTable();
        // table sorting
        $args =  Miscellaneous::dataTableSorting($args, $this->aColumns);
        // quick search data table
        $args =  Miscellaneous::filteringDataTable($args);

        // set columns in args array
        $args['aColumns'] = $this->aColumns;
        $argsCount = $args;
        $argsCount['count'] = true;

        // get data to table
        $objects        = call_user_func($this->model . '::getRecordsLimit', $args);
        $iFilteredTotal = call_user_func($this->model . '::getRecordsLimit', $argsCount);
        $iTotal         = call_user_func($this->model . '::count');

        // get properties of model class
        $class          = new \ReflectionClass($this->model);

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

                        case 'date':
                            $date = new \DateTime();
                            $row[] = $date->setTimestamp($aObject[$aColumn['name']])->format('d-m-Y H:i:s');
                            break;
                    }
                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
            }
            $row[] = '<input type="checkbox" class="uniform" name="element' . $i . '" value="' . $aObject[$instance->getKeyName()] . '">';

            // check whether it is necessary to insert a data before
            if(method_exists($this, 'jsonCustomDataBeforeActions'))
            {
                $actions = $this->jsonCustomDataBeforeActions($aObject);
                $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" href="' . route('edit'. $class->getShortName(), [$aObject[$instance->getKeyName()], Input::get('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
            }
            else
            {
                $actions = Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip" href="' . route('edit'. $class->getShortName(), [$aObject[$instance->getKeyName()], Input::get('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
            }
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip delete-record" data-id="' . $aObject[$instance->getKeyName()] .'" data-original-title="' . trans('pulsar::pulsar.delete_record') . '"><i class="icon-trash"></i></a>' : null;
            $row[] =  $actions;

            $output['aaData'][] = $row;
            $i++;
        }

        $data['json'] = json_encode($output);

        return view('pulsar::common.json_display', $data);
    }

    /**
     * @access      public
     * @param       \Illuminate\Http\Request            $request
     * @return      \Illuminate\Support\Facades\View
     */
    public function createRecord(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        if(method_exists($this, 'createCustomRecord'))
        {
            $parameters = $this->createCustomRecord($parameters);
        }

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = $this->objectTrans;

        return view('pulsar::' . $this->folder . '.create', $parameters);
    }

    /**
     * @access	public
     * @param   integer     $offset
     * @return	\Illuminate\Support\Facades\Redirect
     */
    public function storeRecord($offset = 0)
    {
        $validation = call_user_func($this->model . '::validate', Input::all());

        if ($validation->fails())
        {
            return Redirect::route('create' . $this->routeSuffix, $offset)->withErrors($validation)->withInput();
        }
        else
        {
            if(method_exists($this, 'storeCustomRecord'))
            {
                $this->storeCustomRecord();
            }

            return Redirect::route($this->routeSuffix, $offset)->with([
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_create_record_successful', ['name' => Input::get('name')])
            ]);
        }
    }

    /**
     * @access	public
     * @param   \Illuminate\Http\Request    $request
     * @return	\Illuminate\Support\Facades\View
     */
    public function editRecord(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = $this->objectTrans;
        $parameters['object']         = call_user_func($this->model . '::find', $parameters['id']);

        if(method_exists($this, 'editCustomRecord'))
        {
            $parameters = $this->editCustomRecord($parameters);
        }

        return view('pulsar::' . $this->folder . '.edit', $parameters);
    }

    /**
     * @access	public
     * @param   integer     $offset
     * @return	\Illuminate\Support\Facades\Redirect
     */
    public function updateRecord($offset = 0)
    {
        // check special rules
        if(Input::has('idOld'))
        {
            $specialRules['idRule'] = Input::get('id') == Input::get('idOld')? true : false;
            $id = Input::get('idOld');
        }
        elseif(Input::hasFile('image'))
        {
            $specialRules['imageRule'] = true;
        }
        else
        {
            $specialRules  = [];
            $id = Input::get('id');
        }

        $validation = call_user_func($this->model . '::validate', Input::all(), $specialRules);

        if ($validation->fails())
        {
            return Redirect::route('edit' . $this->routeSuffix, [$id, $offset])->withErrors($validation);
        }
        else
        {
            if(method_exists($this, 'updateCustomRecord'))
            {
                $this->updateCustomRecord($id);
            }

            return Redirect::route($this->routeSuffix, $offset)->with([
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_update_record', ['name' => Input::get('name')])
            ]);
        }
    }

    /**
     * @access      public
     * @param       integer $id
     * @return      \Illuminate\Support\Facades\Redirect
     */
    public function deleteRecord($id)
    {
        $object = call_user_func($this->model . '::find', $id);

        if(method_exists($this, 'destroyCustomRecord'))
        {
            $this->deleteCustomRecord($object);
        }

        // delete records after deleteCustomRecords, if we need do a select
        call_user_func($this->model . '::destroy', $id);

        return Redirect::route($this->routeSuffix)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_record_successful', ['name' => $object->{$this->nameM}])
        ]);
    }

    /**
     * @access      public
     * @param       \Illuminate\Http\Request    $request
     * @return      \Illuminate\Support\Facades\Redirect
     */
    public function deleteTranslationRecord(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $object = call_user_func($this->model . '::getTranslationRecord', $parameters['id'], $parameters['lang']);
        call_user_func($this->model . '::deleteTranslationRecord', $parameters['id'], $parameters['lang']);

        if(method_exists($this, 'deleteCustomTranslationRecord'))
        {
            $this->deleteCustomTranslationRecord($object);
        }

        return Redirect::route($this->routeSuffix)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_record_successful', ['name' => $object->{$this->nameM}])
        ]);
    }


    /**
     * @access      public
     * @return      \Illuminate\Support\Facades\Redirect
     */
    public function deleteRecordsSelect()
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

        if(method_exists($this, 'destroyCustomRecords'))
        {
            $this->deleteCustomRecords($ids);
        }

        // delete records after deleteCustomRecords, if we need do a select
        call_user_func($this->model . '::deleteRecords', $ids);

        return Redirect::route($this->routeSuffix)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_records_successful')
        ]);
    }
}