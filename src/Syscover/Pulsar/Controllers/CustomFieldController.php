<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Models\CustomField;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\CustomFieldGroup;
use Syscover\Pulsar\Libraries\CustomFieldResultLibrary;

/**
 * Class CustomFieldController
 * @package Syscover\Pulsar\Controllers
 */

class CustomFieldController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'customField';
    protected $folder       = 'field';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_026', 'name_025', 'name_026', 'field_type_text_026', 'data_type_text_026', ['data' => 'required_026', 'type' => 'active'], 'sorting_026', 'max_length_026'];
    protected $nameM        = 'name_026';
    protected $model        = CustomField::class;
    protected $icon         = 'fa fa-i-cursor';
    protected $objectTrans  = 'field';

    public function jsonCustomDataBeforeActions($aObject, $actionUrlParameters, $parameters)
    {
        return session('userAcl')->allows('admin-field-value', 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('customFieldValue', ['field' => $aObject['id_026'], 'lang' => $parameters['lang'], 'offset' => 0]) . '" data-original-title="' . trans_choice('pulsar::pulsar.value', 2) . '"><i class="fa fa-bars"></i></a>' : null;
    }

    public function customIndex($parameters)
    {
        $parameters['urlParameters']['lang'] = session('baseLang')->id_001;

        return $parameters;
    }

    public function createCustomRecord($parameters)
    {
        $parameters['groups']       = CustomFieldGroup::all();
        $parameters['fieldTypes']   = config('pulsar.fieldTypes');
        $parameters['dataTypes']    = config('pulsar.dataTypes');

        return $parameters;
    }

    public function checkSpecialRulesToStore($parameters)
    {
        if(isset($parameters['id']))
            $parameters['specialRules']['group'] = true;

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        // check if there is id
        if($this->request->has('id'))
        {
            // get object to update data and data_lang field
            $customField            = CustomField::find($this->request->input('id'));

            // get values
            $dataLang               = json_decode($customField->data_lang_026, true);
            $data                   = json_decode($customField->data_026, true);

            // set values
            $dataLang['langs'][]                        = $this->request->input('lang');
            $data['labels'][$this->request->input('lang')]    = $this->request->input('label');

            CustomField::where('id_026', $parameters['id'])->update([
                'data_lang_026'     => json_encode($dataLang),
                'data_026'          => json_encode($data)
            ]);
        }
        else
        {
            $id = CustomField::max('id_026');
            $id++;

            // create new object
            CustomField::create([
                'id_026'                => $id,
                'group_026'             => $this->request->input('group'),
                'name_026'              => $this->request->input('name'),
                'field_type_026'        => $this->request->input('fieldType'),
                'field_type_text_026'   => collect(config('pulsar.fieldTypes'))->keyBy('id')[$this->request->input('fieldType')]->name,
                'data_type_026'         => $this->request->input('dataType'),
                'data_type_text_026'    => collect(config('pulsar.dataTypes'))->keyBy('id')[$this->request->input('dataType')]->name,
                'required_026'          => $this->request->has('required'),
                'sorting_026'           => empty($this->request->input('sorting'))? null : $this->request->input('sorting'),
                'max_length_026'        => empty($this->request->input('maxLength'))? null : $this->request->input('maxLength'),
                'pattern_026'           => empty($this->request->input('pattern'))? null : $this->request->input('pattern'),
                'label_size_026'        => empty($this->request->input('labelSize'))? null : $this->request->input('labelSize'),
                'field_size_026'        => empty($this->request->input('fieldSize'))? null : $this->request->input('fieldSize'),
                'data_lang_026'         => CustomField::addLangDataRecord($this->request->input('lang')),
                'data_026'              => json_encode(['labels' => [$this->request->input('lang') => $this->request->input('label')]])
            ]);
        }
    }

    public function editCustomRecord($parameters)
    {
        $parameters['groups']       = CustomFieldGroup::all();
        $parameters['fieldTypes']   = config('pulsar.fieldTypes');
        $parameters['dataTypes']    = config('pulsar.dataTypes');

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        // get object to update data and data_lang field
        $customField            = CustomField::find($this->request->input('id'));

        // get values
        $data                   = json_decode($customField->data_026, true);

        // set values
        $data['labels'][$this->request->input('lang')]    = $this->request->input('label');

        CustomField::where('id_026', $parameters['id'])->update([
            'group_026'            => $this->request->input('group'),
            'name_026'              => $this->request->input('name'),
            'field_type_026'        => $this->request->input('fieldType'),
            'field_type_text_026'   => collect(config('pulsar.fieldTypes'))->keyBy('id')[$this->request->input('fieldType')]->name,
            'data_type_026'         => $this->request->input('dataType'),
            'data_type_text_026'    => collect(config('pulsar.dataTypes'))->keyBy('id')[$this->request->input('dataType')]->name,
            'required_026'          => $this->request->has('required'),
            'sorting_026'           => empty($this->request->input('sorting'))? null : $this->request->input('sorting'),
            'max_length_026'        => empty($this->request->input('maxLength'))? null : $this->request->input('maxLength'),
            'pattern_026'           => empty($this->request->input('pattern'))? null : $this->request->input('pattern'),
            'label_size_026'        => empty($this->request->input('labelSize'))? null : $this->request->input('labelSize'),
            'field_size_026'        => empty($this->request->input('fieldSize'))? null : $this->request->input('fieldSize'),
            'data_026'              => json_encode($data)
        ]);
    }

    public function apiGetCustomFields()
    {
        $html =  CustomFieldResultLibrary::getCustomFields([
            'lang'              => $this->request->input('lang'),
            'customFieldGroup'  => $this->request->input('customFieldGroup'),
            'resource'          => $this->request->input('resource'),
            'action'            => $this->request->input('action'),
            'object'            => $this->request->input('object')
        ]);

        return response()->json([
            'status'    => 'success',
            'html'      => $html
        ]);
    }
}