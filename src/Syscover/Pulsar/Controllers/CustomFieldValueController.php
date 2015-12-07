<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Models\CustomField;
use Syscover\Pulsar\Models\CustomFieldValue;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\CustomFieldGroup;

/**
 * Class CustomFieldValueController
 * @package Syscover\Pulsar\Controllers
 */

class CustomFieldValueController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'customFieldValue';
    protected $folder       = 'field_value';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_027', 'name_026', 'name_001', 'value_027', ['data' => 'featured_027', 'type' => 'active']];
    protected $nameM        = 'value_027';
    protected $model        = \Syscover\Pulsar\Models\CustomFieldValue::class;
    protected $icon         = 'fa fa-bars';
    protected $objectTrans  = 'value';

    public function indexCustom($parameters)
    {
        $parameters['urlParameters']['lang']    = session('baseLang');
        $parameters['field']                    = CustomField::getTranslationRecord(['id' => $parameters['field'], 'lang' => $parameters['lang']]);
        $parameters['customTransHeader']        = trans_choice($this->objectTrans, 2) . ' (' .trans_choice('pulsar::pulsar.field', 1) . ': ' . $parameters['field']->name_026 . ')';

        return $parameters;
    }

    public function customActionUrlParameters($actionUrlParameters, $parameters)
    {
        $actionUrlParameters['field'] = $parameters['field'];

        return $actionUrlParameters;
    }

    public function createCustomRecord($request, $parameters)
    {
        $parameters['field']                = CustomField::getTranslationRecord(['id' => $parameters['field'], 'lang' => $parameters['lang']]);
        $parameters['customTransHeader']    = trans_choice($this->objectTrans, 2) . ' (' .trans_choice('pulsar::pulsar.field', 1) . ': ' . $parameters['field']->name_026 . ')';

        return $parameters;
    }

    public function storeCustomRecord($request, $parameters)
    {
        // check if there is id
        if($request->has('id'))
        {
            $id = $request->input('id');
            $idLang = $id;
        }
        else
        {
            $id = CustomFieldValue::max('id_027');
            $id++;
            $idLang = null;
        }

        // create new object
        CustomFieldValue::create([
            'id_027'            => $id,
            'lang_027'          => $request->input('lang'),
            'field_027'         => $request->input('field'),
            'value_027'         => $request->input('value'),
            'sorting_027'       => empty($request->input('sorting'))? null : $request->input('sorting'),
            'featured_027'      => $request->has('featured'),
            'data_lang_027'     => CustomFieldValue::addLangDataRecord($request->input('lang'), $idLang),
            'data_026'          => null
        ]);
    }

    public function editCustomRecord($request, $parameters)
    {
        $parameters['field']                = CustomField::getTranslationRecord(['id' => $parameters['field'], 'lang' => $parameters['lang']->id_001]);
        $parameters['customTransHeader']    = trans_choice($this->objectTrans, 2) . ' (' .trans_choice('pulsar::pulsar.field', 1) . ': ' . $parameters['field']->name_026 . ')';

        return $parameters;
    }

    public function updateCustomRecord($request, $parameters)
    {
        CustomFieldValue::where('id_027', $parameters['id'])->where('lang_027', $request->input('lang'))->update([
            'field_027'         => $request->input('field'),
            'value_027'         => $request->input('value'),
            'sorting_027'       => empty($request->input('sorting'))? null : $request->input('sorting'),
            'featured_027'      => $request->has('featured'),
        ]);
    }
}