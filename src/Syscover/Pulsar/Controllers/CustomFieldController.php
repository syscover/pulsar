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
    protected $aColumns     = ['id_026', 'name_025', 'name_026'];
    protected $nameM        = 'name_026';
    protected $model        = '\Syscover\Pulsar\Models\CustomField';
    protected $icon         = 'fa fa-i-cursor';
    protected $objectTrans  = 'field';

    public function indexCustom($parameters)
    {
        $parameters['urlParameters']['lang'] = session('baseLang');

        return $parameters;
    }

    public function createCustomRecord($request, $parameters)
    {
        $parameters['families'] = CustomFieldFamily::all();

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
                'type_026'      => 1,
                'int_value_026'     => $request->has('intValue'),
                'required_026'      => $request->has('required'),
                'sorting_026'       => $request->input('sorting'),
                'max_length_026'    => $request->input('maxLength'),
                'pattern_026'       => $request->input('pattern'),
                'label_size_026'    => $request->input('labelSize'),
                'field_size_026'    => $request->input('fieldSize'),
                'data_lang_026' => CustomField::addLangDataRecord($request->input('lang')),
                'data_026'      => json_encode(["labels" => [$request->input('lang') => $request->input('label')]])
            ]);
        }
    }

    public function editCustomRecord($request, $parameters)
    {
        $parameters['families'] = CustomFieldFamily::all();

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
            'family_026'    => $request->input('family'),
            'name_026'      => $request->input('name'),
            'type_026'      => 1,
            'int_value_026' => true,
            'required_026'  => false,
            'data_026'      => json_encode($data)
        ]);
    }
}