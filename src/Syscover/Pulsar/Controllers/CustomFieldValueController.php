<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Models\CustomField;
use Syscover\Pulsar\Models\CustomFieldValue;

/**
 * Class CustomFieldValueController
 * @package Syscover\Pulsar\Controllers
 */

class CustomFieldValueController extends Controller
{
    protected $routeSuffix  = 'customFieldValue';
    protected $folder       = 'field_value';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_027', 'name_026', 'name_001', 'name_027', ['data' => 'featured_027', 'type' => 'active']];
    protected $nameM        = 'name_027';
    protected $model        = CustomFieldValue::class;
    protected $icon         = 'fa fa-bars';
    protected $objectTrans  = 'value';

    public function customIndex($parameters)
    {
        $parameters['urlParameters']['lang']    = session('baseLang')->id_001;
        $parameters['field']                    = CustomField::getTranslationRecord(['id' => $parameters['field'], 'lang' => $parameters['lang']]);
        $parameters['customTransHeader']        = trans_choice($this->objectTrans, 2) . ' (' .trans_choice('pulsar::pulsar.field', 1) . ': ' . $parameters['field']->name_026 . ')';

        return $parameters;
    }

    public function customActionUrlParameters($actionUrlParameters, $parameters)
    {
        $actionUrlParameters['field'] = $parameters['field'];

        return $actionUrlParameters;
    }

    public function createCustomRecord($parameters)
    {
        $parameters['field']                = CustomField::getTranslationRecord(['id' => $parameters['field'], 'lang' => $parameters['lang']->id_001]);
        $parameters['customTransHeader']    = trans_choice($this->objectTrans, 2) . ' (' .trans_choice('pulsar::pulsar.field', 1) . ': ' . $parameters['field']->name_026 . ')';

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        if($this->request->has('id'))
        {
            $id         = $this->request->input('id');
            $counter    = null;
        }
        else
        {
            $counter    = CustomFieldValue::where('field_027', $this->request->input('field'))->max('counter_027');
            $counter++;
            $id         = $counter;
        }

        if($this->request->input('action') === 'store')
            $idLang     = null;
        else
            $idLang     = $id;

        // create new object
        CustomFieldValue::create([
            'id_027'            => $id,
            'lang_027'          => $this->request->input('lang'),
            'field_027'         => $this->request->input('field'),
            'counter_027'       => $counter,
            'name_027'          => $this->request->input('name'),
            'sorting_027'       => empty($this->request->input('sorting'))? null : $this->request->input('sorting'),
            'featured_027'      => $this->request->has('featured'),
            'data_lang_027'     => CustomFieldValue::addLangDataRecord($this->request->input('lang'), $idLang),
            'data_026'          => null
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['field']                = CustomField::getTranslationRecord(['id' => $parameters['field'], 'lang' => $parameters['lang']->id_001]);
        $parameters['customTransHeader']    = trans_choice($this->objectTrans, 2) . ' (' .trans_choice('pulsar::pulsar.field', 1) . ': ' . $parameters['field']->name_026 . ')';

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        if($this->request->has('id'))
        {
            $id         = $this->request->input('id');
            $counter    = null;
        }
        else
        {
            $counter    = CustomFieldValue::where('field_027', $this->request->input('field'))->max('counter_027');
            $counter++;
            $id         = $counter;
        }

        CustomFieldValue::where('id_027', $parameters['id'])->where('lang_027', $this->request->input('lang'))->where('field_027', $this->request->input('field'))->update([
            'id_027'            => $id,
            'counter_027'       => $counter,
            'name_027'          => $this->request->input('name'),
            'sorting_027'       => empty($this->request->input('sorting'))? null : $this->request->input('sorting'),
            'featured_027'      => $this->request->has('featured'),
        ]);
    }
}