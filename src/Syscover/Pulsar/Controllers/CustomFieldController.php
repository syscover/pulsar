<?php namespace Syscover\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Syscover\Pulsar\Models\CustomField;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\CustomFieldFamily;

class CustomFieldController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'customField';
    protected $folder       = 'field';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_026', 'name_025', 'name_026', ['data' => 'required_026', 'type' => 'active'], 'sorting_026', 'max_length_026'];
    protected $nameM        = 'name_026';
    protected $model        = '\Syscover\Pulsar\Models\CustomField';
    protected $icon         = 'fa fa-i-cursor';
    protected $objectTrans  = 'field';

    public function jsonCustomDataBeforeActions($request, $aObject)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        return session('userAcl')->isAllowed($request->user()->profile_010, 'admin-field-value', 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('customFieldValue', ['field' => $aObject['id_026'], 'lang' => $parameters['lang'], 'offset' => $request->input('iDisplayStart')]) . '" data-original-title="' . trans_choice('pulsar::pulsar.value', 2) . '"><i class="fa fa-bars"></i></a>' : null;
    }

    public function indexCustom($parameters)
    {
        $parameters['urlParameters']['lang'] = session('baseLang');

        return $parameters;
    }

    public function createCustomRecord($request, $parameters)
    {
        $parameters['families']     = CustomFieldFamily::all();
        $parameters['fieldTypes']   = config('pulsar.fieldTypes');
        $parameters['dataTypes']    = config('pulsar.dataTypes');

        return $parameters;
    }

    public function checkSpecialRulesToStore($request, $parameters)
    {
        if(isset($parameters['id']))
        {
            $parameters['specialRules']['family'] = true;
        }

        return $parameters;
    }

    public function storeCustomRecord($request, $parameters)
    {
        // check if there is id
        if($request->has('id'))
        {
            // get object to update data and data_lang field
            $customField            = CustomField::find($request->input('id'));

            // get values
            $dataLang               = json_decode($customField->data_lang_026, true);
            $data                   = json_decode($customField->data_026, true);

            // set values
            $dataLang['langs'][]                        = $request->input('lang');
            $data['labels'][$request->input('lang')]    = $request->input('label');

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
                'id_026'            => $id,
                'family_026'        => $request->input('family'),
                'name_026'          => $request->input('name'),
                'field_type_026'    => $request->input('fieldType'),
                'data_type_026'     => $request->input('dataType'),
                'required_026'      => $request->has('required'),
                'sorting_026'       => empty($request->input('sorting'))? null : $request->input('sorting'),
                'max_length_026'    => empty($request->input('maxLength'))? null : $request->input('maxLength'),
                'pattern_026'       => empty($request->input('pattern'))? null : $request->input('pattern'),
                'label_size_026'    => empty($request->input('labelSize'))? null : $request->input('labelSize'),
                'field_size_026'    => empty($request->input('fieldSize'))? null : $request->input('fieldSize'),
                'data_lang_026'     => CustomField::addLangDataRecord($request->input('lang')),
                'data_026'          => json_encode(["labels" => [$request->input('lang') => $request->input('label')]])
            ]);
        }
    }

    public function editCustomRecord($request, $parameters)
    {
        $parameters['families']     = CustomFieldFamily::all();
        $parameters['fieldTypes']   = config('pulsar.fieldTypes');
        $parameters['dataTypes']    = config('pulsar.dataTypes');

        return $parameters;
    }

    public function updateCustomRecord($request, $parameters)
    {
        // get object to update data and data_lang field
        $customField            = CustomField::find($request->input('id'));

        // get values
        $data                   = json_decode($customField->data_026, true);

        // set values
        $data['labels'][$request->input('lang')]    = $request->input('label');

        CustomField::where('id_026', $parameters['id'])->update([
            'family_026'        => $request->input('family'),
            'name_026'          => $request->input('name'),
            'field_type_026'    => $request->input('fieldType'),
            'data_type_026'     => $request->input('dataType'),
            'required_026'      => $request->has('required'),
            'sorting_026'       => empty($request->input('sorting'))? null : $request->input('sorting'),
            'max_length_026'    => empty($request->input('maxLength'))? null : $request->input('maxLength'),
            'pattern_026'       => empty($request->input('pattern'))? null : $request->input('pattern'),
            'label_size_026'    => empty($request->input('labelSize'))? null : $request->input('labelSize'),
            'field_size_026'    => empty($request->input('fieldSize'))? null : $request->input('fieldSize'),
            'data_026'          => json_encode($data)
        ]);
    }
}

