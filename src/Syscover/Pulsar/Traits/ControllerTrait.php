<?php namespace Syscover\Pulsar\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Syscover\Pulsar\Libraries\Miscellaneous;
use Syscover\Pulsar\Models\Lang;

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

        // set path variable, after creating urlParameters to don't send value to URLs creates
        $parameters['path'] = $request->path();

        if(!isset($parameters['modal'])) Miscellaneous::setParameterSessionPage($this->resource);

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['resource']       = $this->resource;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;


        if(method_exists($this, 'indexCustom'))
        {
            $parametersResponse = $this->indexCustom($parameters);
            if(is_array($parametersResponse))
            {
                $parameters = array_merge($parameters, $parametersResponse);
            }
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

        $response = [
            "sEcho"                 => $request->input('sEcho'),
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

                        case 'invertActive':
                            $row[] = !$aObject[$aColumn['data']]? '<i class="icomoon-icon-checkmark-3"></i>' : '<i class="icomoon-icon-blocked"></i>';
                            break;

                        case 'date':
                            $date = new \DateTime();
                            $row[] = $date->setTimestamp($aObject[$aColumn['data']])->format(isset($aColumn['format'])? $aColumn['format'] : 'd-m-Y H:i:s');
                            break;

                        case 'url':
                            // the prefix is to compose the url
                            $prefix = isset($aColumn['prefix'])? $aColumn['prefix'] : null;
                            $row[] = '<a ' . (isset($aColumn['target'])? 'target="' . $aColumn['target'] . '"' : null) . ' href="' . $prefix . $aObject[$aColumn['data']] . '"><i class="icon-link"></i></a>';
                            break;

                        case 'color':
                            $row[] = '<i class="color' . (isset($aColumn['tooltip']) && $aColumn['tooltip']? ' bs-tooltip' : null) . '"' . (isset($aColumn['title'])? ' title="' . $aObject[$aColumn['title']] . '"' : null) . ' style="background-color: ' . $aObject[$aColumn['data']] . '"></i>';
                            break;
                    }

                    if(method_exists($this, 'customColumnType'))
                    {
                        $row = $this->customColumnType($row, $aColumn, $aObject, $request);
                    }
                }
                else
                {
                    $row[] = $aObject[$aColumn];
                }
            }

            if(!isset($parameters['modal']) || isset($parameters['modal']) && !$parameters['modal'])
            {
                $row[] = '<input type="checkbox" class="uniform" name="element' . $i . '" value="' . $aObject[$instance->getKeyName()] . '">';
            }

            $actionUrlParameters['id']        = $aObject[$instance->getKeyName()];
            $actionUrlParameters['offset']    = $request->input('iDisplayStart');

            //if we have parentOffset, we instantiate it
            if(isset($parameters['parentOffset'])) $actionUrlParameters['parentOffset'] = $parameters['parentOffset'];

            // get lang parameter if object has multiple language
            if(isset($parameters['lang'])) $actionUrlParameters['lang'] = $parameters['lang'];

            if(method_exists($this, 'customActionUrlParameters'))
            {
                $actionUrlParameters = $this->customActionUrlParameters($actionUrlParameters, $parameters);
            }

            // check if is necesary add div before actions
            $actions = isset($parameters['lang'])? '<div class="btn-group">' : null;

            // check whether it is necessary to insert a data before
            if(method_exists($this, 'jsonCustomDataBeforeActions'))
            {
                $actions = $this->jsonCustomDataBeforeActions($aObject, $actionUrlParameters, $parameters);
            }

            // check if request is modal
            if(isset($parameters['modal']) && $parameters['modal'])
            {
                $actions .= Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip related-record" data-json=\'' . json_encode($aObject) . '\' data-original-title="' . trans('pulsar::pulsar.related_record') . '"><i class="icon-link"></i></a>' : null;
            }
            else
            {
                //check special cases
                $onlyEditOwner = !isset($this->jsonParam['onlyEditOwner']) || isset($this->jsonParam['onlyEditOwner']) && $aObject[$this->jsonParam['onlyEditOwner']] == Auth::user()->id_010;
                $showIfNotEdit = !isset($this->jsonParam['showIfNotEdit']) || isset($this->jsonParam['showIfNotEdit']) && $this->jsonParam['showIfNotEdit'] && !$onlyEditOwner;

                if((isset($this->jsonParam['show']) && $this->jsonParam['show'] == true))
                {
                    $actions .= session('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'access')? '<a class="btn btn-xs bs-tooltip' . (isset($actionUrlParameters['modal']) && $actionUrlParameters['modal']? ' magnific-popup' : null) . '" href="' . route('show' . $this->routeSuffix, $actionUrlParameters) . '" data-original-title="' . trans('pulsar::pulsar.view_record') . '"><i class="icon-eye-open"></i></a>' : null;
                }

                if(($onlyEditOwner) && (isset($this->jsonParam['edit']) && $this->jsonParam['edit'] == true || !isset($this->jsonParam['edit'])))
                {
                    $actions .= session('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip' . (isset($actionUrlParameters['modal']) && $actionUrlParameters['modal']? ' magnific-popup' : null) . '" href="' . route('edit' . $this->routeSuffix, $actionUrlParameters) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="icon-pencil"></i></a>' : null;
                }

                if(isset($this->jsonParam['delete']) && $this->jsonParam['delete'] == true || !isset($this->jsonParam['delete']))
                {
                    $actions .= session('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'delete') ? '<a class="btn btn-xs bs-tooltip delete-record" data-id="' . $aObject[$instance->getKeyName()] . '" data-original-title="' . trans('pulsar::pulsar.delete_record') . '" data-delete-url="' . route('delete' . $this->routeSuffix, $actionUrlParameters) . '"><i class="icon-trash"></i></a>' : null;
                }
            }

            if(isset($parameters['lang'])){

                // gat active langs
                $langs      = Lang::getActivesLangs();

                // set language to object
                $jsonObject = json_decode($aObject['data_' . call_user_func($this->model . '::getSufix')]);
                $colorFlag = "MY_green";

                foreach ($langs as $lang)
                {
                    $isCreated = in_array($lang->id_001, $jsonObject->langs);

                    if(!$isCreated)
                    {
                        $colorFlag="MY_red";
                        break;
                    }
                }

                $actions .= '<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                            <i class="brocco-icon-flag '.$colorFlag.'"></i> <i class="icon-angle-down"></i>
                        </span>
                        <ul class="dropdown-menu pull-right">';

                $nLangs = count($langs); $j=0;

                foreach ($langs as $lang)
                {
                    $isCreated = in_array($lang->id_001, $jsonObject->langs);
                    $actionUrlParameters['lang'] = $lang->id_001;

                    if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'edit') && Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'create'))
                    {
                        $actions .= '<li><a class="bs-tooltip" href="';
                        if($isCreated)
                        {
                            $actions .= route('edit' . $this->routeSuffix, $actionUrlParameters);
                        }
                        else
                        {
                            $actions .= route('create' . $this->routeSuffix, $actionUrlParameters);
                        }

                        $actions .= '" data-original-title="' . $lang->name_001 . '"><img src="' . asset('/packages/syscover/pulsar/storage/langs/' . $lang->image_001) . '"> ';

                        if($isCreated)
                        {
                            $actions .= trans('pulsar::pulsar.edit');
                        }
                        else
                        {
                            $actions .= trans('pulsar::pulsar.create');
                        }
                        $actions .= '</a></li>';
                    }

                    $j++;
                    if($j < $nLangs) $actions .= '<li class="divider"></li>';
                }

                $actions .= '</ul>';
                $actions .= '</div>';
            }



            $row[] =  $actions;

            $response['aaData'][] = $row;
            $i++;
        }

        return response()->json($response);
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

        // check if object has multiple language
        if(isset($parameters['id']))
        {
            $parameters['object'] = call_user_func($this->model . '::getTranslationRecord', $parameters['id'], Session::get('baseLang')->id_001);
        }
        if(isset($parameters['lang']))
        {
            $parameters['lang'] = Lang::find($parameters['lang']);
        }

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

        $validation = call_user_func($this->model . '::validate', $request->all(), $parameters['specialRules']);

        if ($validation->fails())
        {
            return redirect()->route('create' . $this->routeSuffix, $parameters)->withErrors($validation)->withInput();
        }

        if(method_exists($this, 'storeCustomRecord'))
        {
            $parametersResponse = $this->storeCustomRecord($parameters);

            if(is_object($parametersResponse) && get_class($parametersResponse) == "Illuminate\\Http\\RedirectResponse")
            {
                return $parametersResponse;
            }

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
            'txtMsg'     => trans('pulsar::pulsar.message_create_record_successful', ['name' => $request->input('name')])
        ]);
    }

    /**
     * @access	public
     * @param   \Illuminate\Http\Request    $request
     * @return	\Illuminate\Support\Facades\View
     */
    public function showRecord(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $parameters['urlParameters']  = $parameters;

        // set path variable, after creating urlParameters to don't send value to URLs creates
        $parameters['path'] = $request->path();

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;

        // check if object has multiple language
        if(isset($parameters['lang']))
        {
            $parameters['object']   = call_user_func($this->model . '::getTranslationRecord', $parameters['id'], $parameters['lang']);
            $parameters['lang']     = $parameters['object']->lang;
        }
        else
        {
            // check if is implements getRecord function in model, for objects with joins
            if(method_exists($this->model, 'getRecord'))
            {
                $parameters['object']   = call_user_func($this->model . '::getRecord', $parameters);
            }
            else
            {
                $parameters['object']   = call_user_func($this->model . '::find', $parameters['id']);
            }
        }


        if(method_exists($this, 'showCustomRecord'))
        {
            $parameters = $this->showCustomRecord($parameters);

            if(is_object($parameters) && get_class($parameters) == "Illuminate\\Http\\RedirectResponse")
            {
                return $parameters;
            }
        }

        return view($this->package . '::' . $this->folder . '.show', $parameters);
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

        // set path variable, after creating urlParameters to don't send value to URLs creates
        $parameters['path'] = $request->path();

        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;

        // check if object has multiple language
        if(isset($parameters['lang']))
        {
            $parameters['object']   = call_user_func($this->model . '::getTranslationRecord', $parameters['id'], $parameters['lang']);
            $parameters['lang']     = $parameters['object']->lang;
        }
        else
        {
            // check if is implements getRecord function in model, for objects with joins
            if(method_exists($this->model, 'getRecord'))
            {
                $parameters['object']   = call_user_func($this->model . '::getRecord', $parameters);
            }
            else
            {
                $parameters['object']   = call_user_func($this->model . '::find', $parameters['id']);
            }
        }


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
        if($request->has('id') && $request->input('id') == $parameters['id'])
        {
            $parameters['specialRules']['idRule'] = true;
        }

        if(!isset($parameters['specialRules']))
        {
            $parameters['specialRules']  = [];
        }

        $validation = call_user_func($this->model . '::validate', $request->all(), $parameters['specialRules']);

        if ($validation->fails())
        {
            return redirect()->route('edit' . $this->routeSuffix, $parameters)->withErrors($validation);
        }

        if(method_exists($this, 'updateCustomRecord'))
        {
            // we use parametersResponse, because updateCustomRecord may be "void"
            $parametersResponse = $this->updateCustomRecord($parameters);

            if(is_object($parametersResponse) && get_class($parametersResponse) == "Illuminate\\Http\\RedirectResponse")
            {
                return $parametersResponse;
            }

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
            'txtMsg'     => trans('pulsar::pulsar.message_update_record', ['name' => $request->input('name')])
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

        if(method_exists($this, 'deleteCustomRecord'))
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

        $nElements = $request->input('nElementsDataTable');
        $ids = [];

        for($i=0; $i < $nElements; $i++)
        {
            if($request->input('element' . $i) != false)
            {
                array_push($ids, $request->input('element' . $i));
            }
        }

        if(method_exists($this, 'deleteCustomRecords'))
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