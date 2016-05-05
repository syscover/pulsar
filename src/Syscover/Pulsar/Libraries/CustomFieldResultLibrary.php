<?php namespace Syscover\Pulsar\Libraries;

use Syscover\Pulsar\Models\CustomField;
use Syscover\Pulsar\Models\CustomFieldGroup;
use Syscover\Pulsar\Models\CustomFieldResult;

class CustomFieldResultLibrary
{
    /**
     *  Function to store custom field
     *
     * @access	public
     * @param   \Illuminate\Http\Request                    $request
     * @param   \Syscover\Pulsar\Models\CustomFieldGroup    $customFieldGroup
     * @param   string                                      $resource
     * @param   integer                                     $objectId
     * @param   string                                      $lang
     * @return  void
     */
    public static function storeCustomFieldResults($request, $customFieldGroup, $resource, $objectId, $lang)
    {
        $customFieldGroup   = CustomFieldGroup::find($customFieldGroup);
        $customFields       = CustomField::getRecords(['lang_026' => $lang, 'group_026' => $customFieldGroup->id_025]);
        $dataTypes          = collect(config('pulsar.dataTypes'))->keyBy('id');
        $customFieldResults = [];

        foreach($customFields as $customField)
        {
            // if we return an array but the data to be saved is not an array, we will keep as many records containing the array elements
            if(is_array($request->input($customField->name_026 . '_custom')) && $dataTypes[$customField->data_type_026]->type != 'array')
            {
                foreach($request->input($customField->name_026 . '_custom') as $value)
                {
                    $customFieldResult = [
                        'object_028'            => $objectId,
                        'lang_028'              => $lang,
                        'resource_028'          => $resource,
                        'field_028'             => $customField->id_026,
                        'data_type_028'         => $dataTypes[$customField->data_type_026]->type,
                        'value_028'             => $value
                    ];

                    $customFieldResults[]  = $customFieldResult;
                }
            }
            else
            {
                $customFieldResult = [
                    'object_028'            => $objectId,
                    'lang_028'              => $lang,
                    'resource_028'          => $resource,
                    'field_028'             => $customField->id_026,
                    'data_type_028'         => $dataTypes[$customField->data_type_026]->type,
                ];

                // if we return an array and the data to be saved is an array, the array will keep like string expression.
                // using insert not access to mutators on Syscover\Pulsar\Models\CustomFieldResult to change the value type of a string to array automatically
                // we have to do manually casting
                if(is_array($request->input($customField->name_026 . '_custom')) && $dataTypes[$customField->data_type_026]->type == 'array')
                {
                    $customFieldResult['value_028'] = implode(',', $request->input($customField->name_026 . '_custom'));
                }
                else
                {
                    // get value and record in your field data type, add suffix '_custom' to avoid conflict with other field names
                    if($dataTypes[$customField->data_type_026]->type == 'boolean')
                        $customFieldResult['value_028'] = $request->has($customField->name_026 . '_custom');
                    else
                        $customFieldResult['value_028'] = $request->input($customField->name_026 . '_custom');
                }

                $customFieldResults[]  = $customFieldResult;
            }
        }

        if(count($customFieldResults) > 0)
        {
            CustomFieldResult::insert($customFieldResults);
        }
    }

    public static function deleteCustomFieldResults($resource, $objectId, $lang = null)
    {
        $query = CustomFieldResult::where('resource_028', $resource)
            ->where('object_028', $objectId);

        if(isset($lang)) $query->where('lang_028', $lang);

        $query->delete();
    }

    /**
     * Function to get html from custom fields
     *
     * @param $parameters
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public static function getCustomFields($parameters)
    {
        // get custom fields
        $customFields   = CustomField::getRecords([
            'lang_026' => $parameters['lang'],
            'group_026' => $parameters['customFieldGroup']
        ]);

        if($parameters['object'])
        {
            if(!empty($parameters['action']) && $parameters['action'] == 'storeLang')
                // if is a new object translated
                $langFieldResults = session('baseLang')->id_001;
            else
                $langFieldResults = $parameters['lang'];

            // get results if there is a object
            $customFieldResults = CustomFieldResult::where('lang_028', $langFieldResults)
                ->where('object_028', $parameters['object'])
                ->where('resource_028', $parameters['resource'])
                ->get();
        }

        $html = '';
        foreach($customFields as $customField)
        {
            $setValue = isset($customFieldResults) && $customFieldResults->where('field_028', $customField->id_026)->count() > 0;

            // add suffix '_custom' to avoid conflict with other field names
            if(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->key == 'selectMultiple' || collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->key == 'selectMultiple2')
            {
                // TODO: se pueden coger todos los valores antes del foreach para evitar multiples consultas
                $customFieldValues = $customField
                    ->getValues()
                    ->where('lang_027', session('baseLang')->id_001)
                    ->get();

                $multipleSelectValue = null;

                if($setValue)
                {
                    if(collect(config('pulsar.dataTypes'))->keyBy('id')[$customField->data_type_026]->type == 'array')
                    {
                        $multipleSelectValue = $customFieldResults
                            ->where('field_028', $customField->id_026)
                            ->first()
                            ->value_028;
                    }
                    else
                    {
                        $multipleSelectValue = $customFieldResults
                            ->where('field_028', $customField->id_026)
                            ->pluck('value_028')
                            ->toArray();
                    }
                }

                // check if is select2 plugin
                $isSelect2 = collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->key == 'selectMultiple2';

                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'],'name' => $customField['name_026'] . '_custom[]', 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'objects' => $customFieldValues, 'idSelect' => 'id_027', 'nameSelect' => 'name_027', 'required' => $customField->required_026, 'class' => $isSelect2? 'select2' : null, 'id' => $customField['name_026'] . '_custom', 'multiple' => true, 'data' => $isSelect2? ['language' => config('app.locale'), 'width' => '100%', 'error-placement' => 'select2-' . $customField['name_026'] . '_custom' . '-outer-container'] : null, 'value' => $setValue? $multipleSelectValue : null])->render();
            }
            elseif(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->key == 'select' || collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->key == 'select2')
            {
                $customFieldValues      = $customField->getValues()->where('lang_027', session('baseLang')->id_001)->get();

                // check if is select2 plugin
                $isSelect2 = collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->key == 'select2';

                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'] . '_custom', 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'objects' => $customFieldValues, 'idSelect' => 'id_027', 'nameSelect' => 'name_027', 'required' => $customField->required_026, 'class' => $isSelect2? 'select2' : null, 'id' => $customField['name_026'] . '_custom', 'data' => $isSelect2? ['language' => config('app.locale'), 'width' => '100%', 'error-placement' => 'select2-' . $customField['name_026'] . '_custom' . '-outer-container'] : null, 'value' => $setValue? $customFieldResults->where('field_028', $customField->id_026)->first()->value_028 : null])->render();
            }
            elseif(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->key == 'checkbox')
            {
                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'] . '_custom', 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'required' => $customField->required_026, 'checked' => $setValue? $customFieldResults->where('field_028', $customField->id_026)->first()->value_028 : null])->render();
            }
            else
            {
                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'] . '_custom', 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'required' => $customField->required_026, 'value' => $setValue? $customFieldResults->where('field_028', $customField->id_026)->first()->value_028 : null])->render();
            }
        }

        return $html;
    }
}