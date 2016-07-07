<?php namespace Syscover\Pulsar\Core;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Syscover\Pulsar\Exceptions\InvalidArgumentException;
use Syscover\Pulsar\Libraries\Miscellaneous;
use Syscover\Pulsar\Models\AdvancedSearchTask;
use Syscover\Pulsar\Models\Lang;

/**
 * Class Controller
 * @package Syscover\Pulsar\Core
 */

abstract class Controller extends BaseController {

    /**
    * protected $resource;      Name of resource
    * protected $routeSuffix;   Suffix of routes
    * protected $folder;        Name of folder that contain views
    * protected $package;       Name of package
    * protected $indexColumns;  Name of column with your data type
    * protected $nameM;         Name of database column to put on message
    * protected $model;         Name of model
    * protected $icon;          Icon to buttom from entity
    * protected $objectTrans;   Name of key array to translate object
    */

    protected $resource;
    protected $request;
    protected $viewParameters = [
        'newButton'             => true,    // button from index view to create record
        'cancelButton'          => true,    // button from form view to cancel and return to index
        'checkBoxColumn'        => true,    // checkbox from index view to select various records
        'showButton'            => false,   // button from ajax response, to view record
        'editButton'            => true,    // button from ajax response, to edit record
        'deleteButton'          => true,    // button from ajax response, to delete record
        'deleteSelectButton'    => true,    // button delete records when select checkbox on index view
        'relatedButton'         => false,   // button to related elements, used on modal windows
    ];

    public function __construct(Request $request)
    {
        // set request to all controller methods
        $this->request = $request;

        $action = $request->route()->getAction();

        if(isset($action['resource']))
            $this->resource = $action['resource'];
    }

    public function exportData()
    {
        $parameters = [];

        // set advanced search
        $parameters                 = Miscellaneous::dataTableColumnFiltering($this->request, $parameters, 'array');

        // set order table
        $parameters['order']        = json_decode($this->request->input('order'), true);

        // oonfig variables to count n records
        $parametersCount            = $parameters;
        $parametersCount['count']   = true;
        $nFilteredTotal             = call_user_func($this->model . '::getIndexRecords', $this->request, $parametersCount);

        // set error if does not contain any data row
        if ($nFilteredTotal < 1)
            return null;

        // if there are more than 100 rows, set a cron job to generate a export
        if($nFilteredTotal > 100)
        {
            // set paginate parameters
            $parameters['start']    = 0;
            $parameters['length']   = config('pulsar.advancedSearchLimitLength');

            // get data from model
            $objects = call_user_func($this->model . '::getIndexRecords', $this->request, $parameters);
            
            $object = $objects->first();

            // set filename
            $filename = uniqid() . '_' . (empty($object->tableTranslation)? $object->table : trans($object->tableTranslation));
            
            // set name export file
            Excel::create($filename, function($excel) use ($objects, $object) {
                
                // Set the title
                $excel->setTitle('Export table :table');

                // Setters
                $excel->setCreator('Pulsar')
                    ->setCompany('SYSCOVER');

                // Set sheet
                $excel->sheet('Data', function ($sheet) use ($objects, $object) {

                    // get columns names
                    $headers = collect($object->getAttributes())->keys()->toArray();

                    /**
                     * Get operation columns, this value is sent from advanced search form, with this structure on json format
                     *
                     * [
                     *   ['column' => 'column_database_000', 'operation' => 'sum'],
                     *   ['column' => 'column_database_001', 'operation' => 'sum'],
                     *   ...
                     * ]
                    **/
                    if($this->request->has('operationColumns'))
                    {
                        // convert array to collection to drive elements
                        $operationColumns = collect(json_decode($this->request->input('operationColumns'), true));
                        // set row that contain sum values
                        $operationRow     = array_fill(0, count($headers) -1, '');
                    }

                    // set translations columns name
                    foreach ($headers as $key => &$columnName)
                    {
//                        if($this->request->has('operationColumns'))
//                        {
//                            // first, check if column has any operation on itself
//                            $operationColumn = $operationColumns->where('column', $columnName);
//                            if($operationColumn->count() > 0)
//                            {
//                                // get sumary column array
//                                $operationColumn = $operationColumn->first();
//                                switch ($operationColumn['operation'])
//                                {
//                                    case 'sum':
//                                        $operationRow[$key] = $objects->sum($columnName);
//                                        break;
//                                }
//                            }
//                        }

                        // get translation column if exist
                        if(isset($object->columnTranslation[$columnName]))
                            $columnName = trans($object->columnTranslation[$columnName]);

                    }

                    // set data and headers
                    $sheet->prependRow($headers);
                    $sheet->fromArray($objects->toArray(), null, 'A2', false, false);
                    $sheet->cells('A1:' . $sheet->row(0)->getHighestDataColumn() . '1', function ($cells) {
                        $cells->setBackground('#CCCCCC');
                        $cells->setFontWeight('bold');
                    });

                    // add sumary columns and styles
//                    if($this->request->has('operationColumns'))
//                    {
//                        $sheet->appendRow($operationRow);
//                        $sheet->cells('A' . $sheet->row(0)->getHighestRow() . ':' . $sheet->row(0)->getHighestDataColumn() . $sheet->row(0)->getHighestRow(), function ($cells) {
//                            $cells->setBackground('#F8F8F8');
//                            $cells->setFontWeight('bold');
//                        });
//                    }
                });
            })->store($this->request->input('extensionFile'));

            // set start to next call
            $parameters['start']        = config('pulsar.advancedSearchLimitLength');
            
            AdvancedSearchTask::create([
                'date_022'              => date('U'),
                'user_id_022'           => auth('pulsar')->user()->id_010,
                'model_022'             => $this->model,
                'parameters_022'        => json_encode($parameters),
                'extension_file_022'    => $this->request->input('extensionFile'),
                'filename_022'          => $filename,
            ]);
            
            // TODO, CONTROLAR REDIRECCIÓN
            return redirect()->route($this->routeSuffix)->with([
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_create_record_successful', [
                    'name' =>'FATA CONTROLAR ESTO'
                ])
            ]);
        }
        else
        {
            // get data from model
            $objects = call_user_func($this->model . '::getIndexRecords', $this->request, $parameters);

            $object = $objects->first();

            // set name export file
            Excel::create(empty($object->tableTranslation)? $object->table : trans($object->tableTranslation), function($excel) use ($objects, $object) {

                if(count($objects) > 0)
                {
                    // Set the title
                    $excel->setTitle('Export table :table');
                    // setters
                    $excel->setCreator('Pulsar')
                        ->setCompany('SYSCOVER');

                    // Set sheet
                    $excel->sheet('Data', function ($sheet) use ($objects, $object) {

                        // get columns names
                        $headers = collect($object->getAttributes())->keys()->toArray();

                        // get summary columns
                        if($this->request->has('operationColumns'))
                        {
                            $operationColumns = collect(json_decode($this->request->input('operationColumns'), true));
                            $operationRow   = array_fill(0, count($headers) -1, '');
                        }

                        // set translations columns name
                        foreach ($headers as $key => &$columnName)
                        {
//                            if($this->request->has('operationColumns'))
//                            {
//                                // first, check if column has any operation on itseflf
//                                $summaryColumn = $operationColumns->where('column', $columnName);
//                                if($summaryColumn->count() > 0)
//                                {
//                                    // get sumary column array
//                                    $summaryColumn = $summaryColumn->first();
//                                    switch ($summaryColumn['operation'])
//                                    {
//                                        case 'sum':
//                                            $operationRow[$key] = $objects->sum($columnName);
//                                            break;
//                                    }
//                                }
//                            }

                            // get translation column if exist
                            if(isset($object->columnTranslation[$columnName]))
                                $columnName = trans($object->columnTranslation[$columnName]);

                        }

                        // set data and headers
                        $sheet->prependRow($headers);
                        $sheet->fromModel($objects->toArray(), null, 'A2', false, false);
                        $sheet->cells('A1:' . $sheet->row(0)->getHighestDataColumn() . '1', function ($cells) {
                            $cells->setBackground('#CCCCCC');
                            $cells->setFontWeight('bold');
                        });

                        // set sumary columns and styles
                        if($this->request->has('operationColumns'))
                        {
                            $sheet->appendRow($operationRow);
                            $sheet->cells('A' . $sheet->row(0)->getHighestRow() . ':' . $sheet->row(0)->getHighestDataColumn() . $sheet->row(0)->getHighestRow(), function ($cells) {
                                $cells->setBackground('#F8F8F8');
                                $cells->setFontWeight('bold');
                            });
                        }
                    });
                }
            })->download($this->request->input('extensionFile'));
        }
    }

    /**
     * @access	public
     * @return	\Illuminate\Support\Facades\View
     */
    public function index()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        // check if url contain offset parameter, if not exist, create variable with 0 value
        if(! isset($parameters['offset'])) $parameters['offset'] = 0;

        // save original url parameters
        $parameters['urlParameters'] = $parameters;

        // set path variable to save in cookie, search param datatable component,
        // after creating urlParameters to don't send value, to URL create
        $parameters['path']                 = $this->request->path();
        $parameters['resource']             = $this->resource;
        $parameters['routeSuffix']          = $this->routeSuffix;
        $parameters['folder']               = $this->folder;
        $parameters['package']              = $this->package;
        $parameters['icon']                 = $this->icon;
        $parameters['objectTrans']          = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;

        // check if show create button
        if(isset($this->resource))
            if(! is_allowed($this->resource, 'create'))
                $this->viewParameters['newButton'] = false;

        $parametersResponse = $this->customIndex($parameters);

        if(is_array($parametersResponse))
            $parameters = array_merge($parameters, $parametersResponse);

        // set viewParamentes on parameters for throw to view
        $parameters['viewParameters'] = $this->viewParameters;

        return view($this->package . '::' . $this->folder . '.index', $parameters);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   array   $parameters
     * @return	array   $parameters | void
     */
    public function customIndex($parameters){}

    /**
     * @access      public
     * @return      json
     */
    public function jsonData()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        // get active langs if object has multiple langs
        if(isset($parameters['lang']))
            $langs = Lang::getActivesLangs();

        // set custom route suffix, if we need get data from other controller
        if($this->request->has('routeSuffix'))
            $this->routeSuffix = $this->request->input('routeSuffix');
        
        // table paginated
        $parameters =  Miscellaneous::dataTablePaginate($this->request, $parameters);
        // table sorting
        $parameters =  Miscellaneous::dataTableSorting($this->request, $parameters, $this->indexColumns);
        // quick search data table
        $parameters =  Miscellaneous::dataTableFiltering($this->request, $parameters);
        // set advanced search
        $parameters = Miscellaneous::dataTableColumnFiltering($this->request, $parameters);

        // set columns in parameters array
        $parameters['indexColumns'] = $this->indexColumns;
        $parametersCount            = $parameters;
        $parametersCount['count']   = true;

        // get data to table
        $objects        = call_user_func($this->model . '::getIndexRecords', $this->request, $parameters);
        $iFilteredTotal = call_user_func($this->model . '::getIndexRecords', $this->request, $parametersCount);
        $iTotal         = call_user_func($this->model . '::countRecords', $this->request, $parameters);

        // method to do custom actions
        if(method_exists($this, 'customJsonData'))
            $this->customJsonData($parameters);

        $response = [
            "sEcho"                 => $this->request->input('sEcho'),
            "iTotalRecords"         => $iTotal,
            "iTotalDisplayRecords"  => $iFilteredTotal,
            "aaData"                => []
        ];

        // instance model to get primary key
        $instance = new $this->model;

        foreach($objects as $key => $aObject)
        {
            // start columns instance
            // set columns with your types, check, active, etc.
            $row = [];
            foreach ($this->indexColumns as $indexColumn)
            {
                if(is_array($indexColumn))
                {
                    switch ($indexColumn['type'])
                    {
                        case 'email':
                            $row[] = ! empty($aObject->{$indexColumn['data']})? '<a href="mailto:' . $aObject->{$indexColumn['data']} . '">' . $aObject->{$indexColumn['data']} . '</a>' : null;
                            break;

                        case 'img':
                            $row[] = ! empty($aObject->{$indexColumn['data']})? '<img src="' . asset($indexColumn['url'] . $aObject[$indexColumn['data']]) . '">' : null;
                            break;

                        case 'check':
                            $row[] = $aObject[$indexColumn['data']]? '<i class="icomoon-icon-checkmark-3"></i>' : null;
                            break;

                        case 'active':
                            $row[] = $aObject[$indexColumn['data']]? '<i class="icomoon-icon-checkmark-3"></i>' : '<i class="icomoon-icon-blocked"></i>';
                            break;

                        case 'invertActive':
                            $row[] = !$aObject[$indexColumn['data']]? '<i class="icomoon-icon-checkmark-3"></i>' : '<i class="icomoon-icon-blocked"></i>';
                            break;

                        case 'date':
                            $date = new \DateTime();
                            $row[] = $date->setTimestamp($aObject[$indexColumn['data']])->format(isset($indexColumn['format'])? $indexColumn['format'] : config('pulsar.datePattern') . ' H:i:s');
                            break;

                        case 'url':
                            // the prefix is to compose the url
                            $prefix = isset($indexColumn['prefix'])? $indexColumn['prefix'] : null;
                            $row[] = '<a ' . (isset($indexColumn['target'])? 'target="' . $indexColumn['target'] . '"' : null) . ' href="' . $prefix . $aObject[$indexColumn['data']] . '"><i class="icon-link"></i></a>';
                            break;

                        case 'color':
                            $row[] = '<i class="color' . (isset($indexColumn['tooltip']) && $indexColumn['tooltip']? ' bs-tooltip' : null) . '"' . (isset($indexColumn['title'])? ' title="' . $aObject[$indexColumn['title']] . '"' : null) . ' style="background-color: ' . $aObject[$indexColumn['data']] . '"></i>';
                            break;

                        case 'alias':
                            $row[] = $aObject->{$indexColumn['alias']};
                            break;
                    }

                    if(method_exists($this, 'customColumnType'))
                    {
                        $row = $this->customColumnType($row, $indexColumn, $aObject);
                    }
                }
                else
                {
                    $row[] = $aObject->{$indexColumn};
                }
            }

            // check if show checkbocx column
            if($this->viewParameters['checkBoxColumn'])
            {
                $row[] = '<input type="checkbox" class="uniform" name="element' . $key . '" value="' . $aObject[$instance->getKeyName()] . '">';
            }
            
            $actionUrlParameters['id']        = $aObject[$instance->getKeyName()];
            $actionUrlParameters['offset']    = $this->request->input('start');

            // check if create route has modal
            // is necessary to have modal paramenter in all routes for proper operation
            if(Route::current()->hasParameter('modal'))
            {
                // set modal parameter for actions from datatable, you need instance all situations
                if (isset($parameters['modal']) && $parameters['modal'] == 1)
                    $actionUrlParameters['modal'] = 1;
                else
                    $actionUrlParameters['modal'] = 0;
            }

            // if we have parentOffset, we instantiate it
            if(isset($parameters['parentOffset'])) $actionUrlParameters['parentOffset'] = $parameters['parentOffset'];

            // get lang parameter if object has multiple language
            if(isset($parameters['lang'])) $actionUrlParameters['lang'] = $parameters['lang'];

            if(method_exists($this, 'customActionUrlParameters'))
                $actionUrlParameters = $this->customActionUrlParameters($actionUrlParameters, $parameters);

            // check if is necesary add div before actions
            $actions = isset($parameters['lang'])? '<div class="btn-group">' : null;


            // check whether it is necessary to insert custom actions
            if(method_exists($this, 'jsonCustomDataBeforeActions'))
                $actions = $actions . $this->jsonCustomDataBeforeActions($aObject, $actionUrlParameters, $parameters);


            // buttons actions
            if($this->viewParameters['relatedButton'])
                $actions .= is_allowed($this->resource, 'access')? '<a class="btn btn-xs bs-tooltip related-record" data-json=\'' . json_encode($aObject) . '\' data-original-title="' . trans('pulsar::pulsar.related_record') . '"><i class="fa fa-link"></i></a>' : null;

            // TODO: para que es isset($actionUrlParameters['modal']) && $actionUrlParameters['modal']??
            if($this->viewParameters['showButton'])
                $actions .= is_allowed($this->resource, 'access')? '<a class="btn btn-xs bs-tooltip' . (isset($actionUrlParameters['modal']) && $actionUrlParameters['modal']? ' magnific-popup' : null) . '" href="' . route('show' . ucfirst($this->routeSuffix), $actionUrlParameters) . '" data-original-title="' . trans('pulsar::pulsar.view_record') . '"><i class="fa fa-eye"></i></a>' : null;


            if($this->viewParameters['editButton'])
                $actions .= is_allowed($this->resource, 'edit')? '<a class="btn btn-xs bs-tooltip' . (isset($actionUrlParameters['modal']) && $actionUrlParameters['modal']? ' magnific-popup' : null) . '" href="' . route('edit' . ucfirst($this->routeSuffix), $actionUrlParameters) . '" data-original-title="' . trans('pulsar::pulsar.edit_record') . '"><i class="fa fa-pencil"></i></a>' : null;


            if($this->viewParameters['deleteButton'])
                $actions .= is_allowed($this->resource, 'delete') ? '<a class="btn btn-xs bs-tooltip delete-record" data-id="' . $aObject[$instance->getKeyName()] . '" data-original-title="' . trans('pulsar::pulsar.delete_record') . '" data-delete-url="' . route('delete' . ucfirst($this->routeSuffix), $actionUrlParameters) . '"><i class="fa fa-trash"></i></a>' : null;


            // set lang selector on datatable
            if(isset($parameters['lang']))
            {
                // set language to object
                $jsonObject = json_decode($aObject['data_lang_' . call_user_func($this->model . '::getSuffix')]);
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
                            <i class="brocco-icon-flag '.$colorFlag.'"></i> <i class="fa fa-angle-down"></i>
                        </span>
                        <ul class="dropdown-menu pull-right">';

                $nLangs = count($langs); $j=0;

                foreach ($langs as $lang)
                {
                    $isCreated = in_array($lang->id_001, $jsonObject->langs);
                    $actionUrlParameters['lang'] = $lang->id_001;

                    if(is_allowed($this->resource, 'edit') && is_allowed($this->resource, 'create'))
                    {
                        $actions .= '<li><a class="bs-tooltip" href="';
                        if($isCreated)
                        {
                            $actions .= route('edit' . ucfirst($this->routeSuffix), $actionUrlParameters);
                        }
                        else
                        {
                            $actions .= route('create' . ucfirst($this->routeSuffix), $actionUrlParameters);
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
        }

        return response()->json($response);
    }

    /**
     * @access      public
     * @return      \Illuminate\Support\Facades\View
     */
    public function createRecord()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $parameters['urlParameters']  = $parameters;

        // check if create route has modal
        // is necessary to have modal paramenter in all routes for proper operation
        if(Route::current()->hasParameter('modal'))
        {
            // check if this element is modal, to mark in your url petition
            if(isset($parameters['modal']) && $parameters['modal'] == 1)
                $parameters['urlParameters']['modal'] = 1;
            else
                $parameters['urlParameters']['modal'] = 0;
        }

        // get lang object
        if(isset($parameters['lang']))
            $parameters['lang'] = Lang::builder()->find($parameters['lang']);

        $parameters = $this->createCustomRecord($parameters);

        // check if parameters is a RedirectResponse object, to send request to other url
        if(is_object($parameters) && get_class($parameters) == \Illuminate\Http\RedirectResponse::class)
            return $parameters;

        $parameters['resource']       = $this->resource;
        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['icon']           = $this->icon;

        // traslate of object that we are operate, this variable is instance in controller
        $parameters['objectTrans']    = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;

        // check if action is store or storeLang
        if(isset($parameters['id']) && isset($parameters['lang']))
        {
            // set baseLang to get baseLang object
            $parametersAux          =  $parameters;
            $parametersAux['lang']  = session('baseLang')->id_001;
            
            $parameters['object']   = call_user_func($this->model . '::getTranslationRecord', $parametersAux);

            $parameters['action']   = 'storeLang';
        }
        else
        {
            $parameters['action'] = 'store';
        }

        // set viewParamentes on parameters for throw to view
        $parameters['viewParameters'] = $this->viewParameters;

        // check if exist create view, default all request go to common view
        if(view()->exists($this->package . '::' . $this->folder . '.create', $parameters))
            return view($this->package . '::' . $this->folder . '.create', $parameters);
        else
            return view($this->package . '::' . $this->folder . '.form', $parameters);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   array                       $parameters
     * @return	array                       $parameters
     */
    public function createCustomRecord($parameters)
    {
        return $parameters;
    }

    /**
     * @access	public
     * @return	\Illuminate\Support\Facades\Redirect
     */
    public function storeRecord()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $parameters['urlParameters']  = $parameters;

        if(method_exists($this, 'checkSpecialRulesToStore'))
            $parameters = $this->checkSpecialRulesToStore($parameters);

        if(! isset($parameters['specialRules']))
            $parameters['specialRules']  = [];

        $validation = call_user_func($this->model . '::validate', $this->request->all(), $parameters['specialRules']);

        // validate
        if($validation->fails())
            return redirect()
                ->route('create' . ucfirst($this->routeSuffix), $parameters['urlParameters'])
                ->withErrors($validation)
                ->withInput();

        // we use parametersResponse, because storeCustomRecord may be "void"
        $parametersResponse = $this->storeCustomRecord($parameters);


        // check if parametersResponse is a RedirectResponse objecto, to send request to other url
        if(is_object($parametersResponse) && get_class($parametersResponse) == \Illuminate\Http\RedirectResponse::class)
            return $parametersResponse;


        // merge with array from storeCustomRecords
        if(is_array($parametersResponse))
            $parameters = array_merge($parameters, $parametersResponse);


        // return to modal view
        if(isset($parameters['redirectParentJs']) && $parameters['redirectParentJs'])
            return view('pulsar::common.views.redirect_parent_js');


        return redirect()->route($this->routeSuffix, $parameters['urlParameters'])->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_create_record_successful', [
                'name' => $this->request->input('name')
            ])
        ]);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   array                       $parameters
     * @return	array                       $parameters | void
     */
    public function storeCustomRecord($parameters) {}

    /**
     * @access	public
     * @return	\Illuminate\Support\Facades\View
     */
    public function showRecord()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $parameters['urlParameters']  = $parameters;

        // check if create route has modal
        // is necessary to have modal paramenter in all routes for proper operation
        if(Route::current()->hasParameter('modal'))
        {
            // check if this element is modal, to mark in your url petition
            if (isset($parameters['modal']) && $parameters['modal'] == 1)
                $parameters['urlParameters']['modal'] = 1;
            else
                $parameters['urlParameters']['modal'] = 0;
        }

        // set path variable to save in cookie, search param datatable component,
        // after creating urlParameters to don't send value, to URL create
        $parameters['path']           = $this->request->path();
        $parameters['resource']       = $this->resource;
        $parameters['action']         = 'show';
        $parameters['package']        = $this->package;
        $parameters['folder']         = $this->folder;
        $parameters['routeSuffix']    = $this->routeSuffix;
        $parameters['icon']           = $this->icon;
        $parameters['objectTrans']    = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;

        // check if object has multiple language
        if(isset($parameters['lang']))
        {
            $parameters['object']   = call_user_func($this->model . '::getTranslationRecord', ['id' => $parameters['id'], 'lang' => $parameters['lang']]);
            $parameters['lang']     = $parameters['object']->lang;
        }
        else
        {
            // check if is implements getRecord function in model, for objects with joins
            if(method_exists($this->model, 'getRecord'))
                $parameters['object']   = call_user_func($this->model . '::getRecord', $parameters);
            else
                $parameters['object']   = call_user_func($this->model . '::builder')->find($parameters['id']);
        }

        $parameters = $this->showCustomRecord($parameters);

        if(is_object($parameters) && get_class($parameters) == \Illuminate\Http\RedirectResponse::class)
            return $parameters;

        // check if response is json format or not
        if(isset($parameters['api']) && $parameters['api'])
            return response()->json($parameters['object']);

        // set viewParamentes on parameters for throw to view
        $parameters['viewParameters'] = $this->viewParameters;

        // check if exist show view, default all request go to form view
        if(view()->exists($this->package . '::' . $this->folder . '.show', $parameters))
            return view($this->package . '::' . $this->folder . '.show', $parameters);
        else
            return view($this->package . '::' . $this->folder . '.form', $parameters);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   array                       $parameters
     * @return	array                       $parameters
     */
    public function showCustomRecord($parameters)
    {
        return $parameters;
    }

    /**
     * @access	public
     * @return	\Illuminate\Support\Facades\View
     */
    public function editRecord()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $parameters['urlParameters']  = $parameters;

        // set path variable to save in cookie, search param datatable component,
        // after creating urlParameters to don't send value, to URL create
        $parameters['path']         = $this->request->path();
        $parameters['resource']     = $this->resource;
        $parameters['action']       = 'update';
        $parameters['package']      = $this->package;
        $parameters['folder']       = $this->folder;
        $parameters['routeSuffix']  = $this->routeSuffix;
        $parameters['icon']         = $this->icon;
        $parameters['objectTrans']  = isset($this->objectTrans) &&  $this->objectTrans != null? Miscellaneous::getObjectTransValue($parameters, $this->objectTrans) : null;

        // check if object has multiple language
        if(isset($parameters['lang']))
        {
            if(method_exists($this->model, 'getTranslationRecord'))
            {
                $parameters['object'] = call_user_func($this->model . '::getTranslationRecord', $parameters);
            }
            else
            {
                throw new InvalidArgumentException('The methods getTranslationRecord on ' . $this->model . ' is not definite');
            }
            
            if(method_exists($parameters['object'], 'getLang'))
                $parameters['lang'] = $parameters['object']->getLang;


            // check that we have lang object
            if($parameters['lang'] === null)
                throw new InvalidArgumentException('The language object is not instantiated, method getLang on model ' . $this->model . ' is not defined');
        }
        else
        {
            // check if is implements getRecord function in model, for objects with joins
            if(method_exists($this->model, 'getRecord'))
                $parameters['object']   = call_user_func($this->model . '::getRecord', $parameters);
            else
                // call builder, by default is instance on TraitModel or in model object
                // this method is deprecated
                $parameters['object']   = call_user_func($this->model . '::builder')->find($parameters['id']);
        }

        $parameters = $this->editCustomRecord($parameters);

        // check if parameters is a RedirectResponse object, to send request to other url
        if(is_object($parameters) && get_class($parameters) == \Illuminate\Http\RedirectResponse::class)
            return $parameters;

        // set viewParamentes on parameters for throw to view
        $parameters['viewParameters'] = $this->viewParameters;

        // check if exist edit view, default all request go to common view
        if(view()->exists($this->package . '::' . $this->folder . '.edit', $parameters))
            return view($this->package . '::' . $this->folder . '.edit', $parameters);
        else
            return view($this->package . '::' . $this->folder . '.form', $parameters);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   array                       $parameters
     * @return	array                       $parameters
     */
    public function editCustomRecord($parameters)
    {
        return $parameters;
    }

    /**
     * @access	public
     * @return	\Illuminate\Support\Facades\Redirect
     */
    public function updateRecord()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $parameters['urlParameters']  = $parameters;

        if(method_exists($this, 'checkSpecialRulesToUpdate'))
            $parameters = $this->checkSpecialRulesToUpdate($parameters);

        // check special rule to objects with writable IDs like actions
        if($this->request->has('id') && $this->request->input('id') == $parameters['id'])
            $parameters['specialRules']['idRule'] = true;

        if(! isset($parameters['specialRules']))
            $parameters['specialRules']  = [];

        $validation = call_user_func($this->model . '::validate', $this->request->all(), $parameters['specialRules']);

        // validate
        if ($validation->fails())
            return redirect()
                ->route('edit' . ucfirst($this->routeSuffix), $parameters['urlParameters'])
                ->withErrors($validation);

        // we use parametersResponse, because updateCustomRecord may be "void"
        $parametersResponse = $this->updateCustomRecord($parameters);


        if(is_object($parametersResponse) && get_class($parametersResponse) == \Illuminate\Http\RedirectResponse::class)
            return $parametersResponse;


        if(is_array($parametersResponse))
            $parameters = array_merge($parameters, $parametersResponse);


        // return to modal view
        if(isset($parameters['redirectParentJs']) && ($parameters['redirectParentJs'] === '1'|| $parameters['redirectParentJs'] === true))
            return view('pulsar::common.views.redirect_parent_js');


        return redirect()->route($this->routeSuffix, $parameters['urlParameters'])->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_update_record', [
                'name' => $this->request->input('name')
            ])
        ]);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   array                       $parameters
     * @return	array                       $parameters | void
     */
    public function updateCustomRecord($parameters) {}

    /**
     * @access      public
     * @return      \Illuminate\Support\Facades\Redirect
     */
    public function deleteRecord()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $object = call_user_func($this->model . '::find', $parameters['id']);

        $this->deleteCustomRecord($object);

        // delete records after deleteCustomRecords, if we need do a select
        call_user_func($this->model . '::destroy', $parameters['id']);

        // delete id variable to don't send to route
        unset($parameters['id']);

        if(method_exists($this, 'deleteCustomRecordRedirect'))
        {
            $response = $this->deleteCustomRecordRedirect($object, $parameters);

            // check that we have any  different to false
            if($response !== false)
                return $response;
        }

        return redirect()->route($this->routeSuffix, $parameters)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_record_successful', ['name' => $object->{$this->nameM}])
        ]);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   mixed                       $object
     * @return	void
     */
    public function deleteCustomRecord($object) {}


    /**
     * @access      public
     * @return      \Illuminate\Support\Facades\Redirect
     */
    public function deleteTranslationRecord()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        if(isset($this->langModel))
            // this option is to tables that dependent of other tables to set your languages, example 007_170_hotel and 007_171_hotel_lang
            $object = call_user_func($this->langModel . '::getTranslationRecord', $parameters);
        else
            $object = call_user_func($this->model . '::getTranslationRecord', $parameters);


        $this->deleteCustomTranslationRecord($object);

        if(isset($this->langModel))
        {
            // this option is to tables that dependent of other tables to set your languages, example 007_170_hotel and 007_171_hotel_lang
            call_user_func($this->langModel . '::deleteTranslationRecord', $parameters, false);
            // this kind of tables has field data_lang in main table, not in language table
            call_user_func($this->model . '::deleteLangDataRecord', $parameters);
        }
        else
        {
            call_user_func($this->model . '::deleteTranslationRecord', $parameters);
        }

        return redirect()->route($this->routeSuffix, $parameters)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_record_successful', ['name' => $object->{$this->nameM}])
        ]);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   mixed                       $object
     * @return	void
     */
    public function deleteCustomTranslationRecord($object) {}


    /**
     * @access      public
     * @return      \Illuminate\Support\Facades\Redirect
     */
    public function deleteRecordsSelect()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        $nElements = $this->request->input('nElementsDataTable');
        $ids = [];

        for($i=0; $i < $nElements; $i++)
        {
            if($this->request->input('element' . $i) != false)
                array_push($ids, $this->request->input('element' . $i));
        }

        $this->deleteCustomRecordsSelect($ids);

        // delete records after deleteCustomRecords, if we need do a select
        call_user_func($this->model . '::deleteRecords', $ids);


        if(method_exists($this, 'deleteCustomRecordsRedirect'))
        {
            $response = $this->deleteCustomRecordsRedirect($parameters);

            // check that we have any  different to false
            if($response !== false)
                return $response;
        }

        return redirect()->route($this->routeSuffix, $parameters)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.message_delete_records_successful')
        ]);
    }

    /**
     * function to be overridden
     *
     * @access	public
     * @param   array                       $ids
     * @return	void
     */
    public function deleteCustomRecordsSelect($ids) {}
}