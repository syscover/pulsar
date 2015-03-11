<?php namespace Syscover\Pulsar\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Syscover\Pulsar\Libraries\Miscellaneous;

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

        // check if url contain offset parameter
        if(!isset($parameters['offset'])) $parameters['offset'] = 0;

        $parameters['urlParameters']  = $parameters;

        Miscellaneous::setParameterSessionPage($this->resource);

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['resource']       = $this->resource;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;


        if(method_exists($this, 'indexCustom'))
        {
            $parameters = $this->indexCustom($parameters);
        }

        return view($this->package . '::' . $this->folder . '.index', $parameters);
    }

    /**
     * @access      public
     * @param       \Illuminate\Http\Request            $request
     * @return      json
     */
    public function jsonData(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        // table paginated
        $parameters =  Miscellaneous::paginateDataTable($parameters);
        // table sorting
        $parameters =  Miscellaneous::dataTableSorting($parameters, $this->aColumns);
        // quick search data table
        $parameters =  Miscellaneous::filteringDataTable($parameters);

        // set columns in parameters array
        $parameters['aColumns']     = $this->aColumns;
        $parametersCount            = $parameters;
        $parametersCount['count']   = true;

        // get data to table
        $objects        = call_user_func($this->model . '::getRecordsLimit', $parameters);
        $iFilteredTotal = call_user_func($this->model . '::getRecordsLimit', $parametersCount);
        $iTotal         = call_user_func($this->model . '::countRecords', $parameters);

        // get properties of model class
        //$class          = new \ReflectionClass($this->model);

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
                    switch ($aColumn['type'])
                    {
                        case 'email':
                            $row[] = $aObject[$aColumn['data']] != ''? '<a href="mailto:' . $aObject[$aColumn['data']] . '">' . $aObject[$aColumn['data']] . '</a>' : null;
                            break;

                        case 'img':
                            $row[] = $aObject[$aColumn['data']] != ''? '<img src="' . asset($aColumn['url'] . $aObject[$aColumn['data']]) . '">' : null;
                            break;

                        case 'check':
                            $row[] = $aObject[$aColumn['data']]? '<i class="icomoon-icon-checkmark-3"></i>' : null;
                            break;

                        case 'active':
                            $row[] = $aObject[$aColumn['data']]? '<i class="icomoon-icon-checkmark-3"></i>' : '<i class="icomoon-icon-blocked"></i>';
                            break;

                        case 'date':
                            $date = new \DateTime();
                            $row[] = $date->setTimestamp($aObject[$aColumn['data']])->format('d-m-Y H:i:s');
                            break;
                    }
                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
            }
            $row[] = '<input type="checkbox" class="uniform" name="element' . $i . '" value="' . $aObject[$instance->getKeyName()] . '">';

            $actionUrlParameters['id']        = $aObject[$instance->getKeyName()];
            $actionUrlParameters['offset']    = Input::get('iDisplayStart');

            if(method_exists($this, 'customActionUrlParameters'))
            {
                $actionUrlParameters = $this->customActionUrlParameters($actionUrlParameters, $parameters);
            }

            // check whether it is necessary to insert a data before
            $actions = null;
            if(method_exists($this, 'jsonCustomDataBeforeActions'))
            {
                $actions = $this->jsonCustomDataBeforeActions($aObject);
            }
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip' . (isset($actionUrlParameters['modal']) && $actionUrlParameters['modal']? ' lightbox' : null) . '" href="' . route('edit' . $this->routeSuffix, $actionUrlParameters) . '" ' . (isset($actionUrlParameters['modal']) && $actionUrlParameters['modal']? ' data-options="{\'width\':\'90p\', \'height\':\'90p\', \'iframe\': true, \'modal\': true}"' : null) . 'data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
            $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete')? '<a class="btn btn-xs bs-tooltip delete-record" data-id="' . $aObject[$instance->getKeyName()] .'" data-original-title="' . trans('pulsar::pulsar.delete_record') . '" data-delete-url="' . route('delete' . $this->routeSuffix, $actionUrlParameters) . '"><i class="icon-trash"></i></a>' : null;
            $row[] =  $actions;

            $output['aaData'][] = $row;
            $i++;
        }

        $data['json'] = json_encode($output);

        return view('pulsar::common.views.json_display', $data);
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

        $parameters['urlParameters']  = $parameters;

        if(method_exists($this, 'createCustomRecord'))
        {
            $parameters = $this->createCustomRecord($parameters);
        }

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;

        return view($this->package . '::' . $this->folder . '.create', $parameters);
    }

    /**
     * @access	public
     * @param   \Illuminate\Http\Request    $request
     * @return	\Illuminate\Support\Facades\Redirect
     */
    public function storeRecord(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $parameters['urlParameters']  = $parameters;

        if(method_exists($this, 'checkSpecialRulesToStore'))
        {
            $parameters = $this->checkSpecialRulesToStore($parameters);
        }

        if(!isset($parameters['specialRules']))
        {
            $parameters['specialRules']  = [];
        }

        $validation = call_user_func($this->model . '::validate', Input::all(), $parameters['specialRules']);

        if ($validation->fails())
        {
            return redirect()->route('create' . $this->routeSuffix, $parameters)->withErrors($validation)->withInput();
        }

        if(method_exists($this, 'storeCustomRecord'))
        {
            $parametersResponse = $this->storeCustomRecord($parameters);
            if(is_array($parametersResponse))
            {
                $parameters = array_merge($parameters, $parametersResponse);
            }
        }

        if(isset($parameters['modal']) && $parameters['modal'])
        {
            return view('pulsar::common.views.redirect_modal');
        }

        return redirect()->route($this->routeSuffix, $parameters['urlParameters'])->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_create_record_successful', ['name' => Input::get('name')])
        ]);
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

        $parameters['urlParameters']  = $parameters;

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;
        $parameters['object']         = call_user_func($this->model . '::find', $parameters['id']);

        if(method_exists($this, 'editCustomRecord'))
        {
            $parameters = $this->editCustomRecord($parameters);
        }

        return view($this->package . '::' . $this->folder . '.edit', $parameters);
    }

    /**
     * @access	public
     * @param   \Illuminate\Http\Request    $request
     * @return	\Illuminate\Support\Facades\Redirect
     */
    public function updateRecord(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $parameters['urlParameters']  = $parameters;

        if(method_exists($this, 'checkSpecialRulesToUpdate'))
        {
            $parameters = $this->checkSpecialRulesToUpdate($parameters);
        }

        // check special rule to objects with writable IDs like actions
        if(Input::get('id') == $parameters['id'])
        {
            $parameters['specialRules']['idRule'] = true;
        }

        if(!isset($parameters['specialRules']))
        {
            $parameters['specialRules']  = [];
        }

        $validation = call_user_func($this->model . '::validate', Input::all(), $parameters['specialRules']);

        if ($validation->fails())
        {
            return redirect()->route('edit' . $this->routeSuffix, $parameters)->withErrors($validation);
        }

        if(method_exists($this, 'updateCustomRecord'))
        {
            $parametersResponse = $this->updateCustomRecord($parameters);
            if(is_array($parametersResponse))
            {
                $parameters = array_merge($parameters, $parametersResponse);
            }
        }

        if(isset($parameters['modal']) && $parameters['modal'])
        {
            return view('pulsar::common.views.redirect_modal');
        }

        return redirect()->route($this->routeSuffix, $parameters['urlParameters'])->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_update_record', ['name' => Input::get('name')])
        ]);
    }

    /**
     * @access      public
     * @param       \Illuminate\Http\Request    $request
     * @return      \Illuminate\Support\Facades\Redirect
     */
    public function deleteRecord(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $object = call_user_func($this->model . '::find', $parameters['id']);

        if(method_exists($this, 'destroyCustomRecord'))
        {
            $this->deleteCustomRecord($object);
        }

        // delete records after deleteCustomRecords, if we need do a select
        call_user_func($this->model . '::destroy', $parameters['id']);

        // delete id variable to don't send to route
        unset($parameters['id']);

        if(method_exists($this, 'deleteCustomRecordRedirect'))
        {
            return $this->deleteCustomRecordRedirect($object, $parameters);
        }

        return redirect()->route($this->routeSuffix, $parameters)->with([
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

        if(method_exists($this, 'deleteCustomTranslationRecord'))
        {
            $this->deleteCustomTranslationRecord($object);
        }

        call_user_func($this->model . '::deleteTranslationRecord', $parameters['id'], $parameters['lang']);

        return redirect()->route($this->routeSuffix, $parameters)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_record_successful', ['name' => $object->{$this->nameM}])
        ]);
    }


    /**
     * @access      public
     * @param       \Illuminate\Http\Request    $request
     * @return      \Illuminate\Support\Facades\Redirect
     */
    public function deleteRecordsSelect(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

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

        if(method_exists($this, 'deleteCustomRecordsRedirect'))
        {
            return $this->deleteCustomRecordsRedirect($parameters);
        }

        return redirect()->route($this->routeSuffix, $parameters)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_records_successful')
        ]);
    }
}