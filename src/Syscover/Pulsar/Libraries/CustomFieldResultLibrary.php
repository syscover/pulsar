<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Http\Request;
use Syscover\Pulsar\Models\CustomField;
use Syscover\Pulsar\Models\CustomFieldGroup;
use Syscover\Pulsar\Models\CustomFieldResult;

class CustomFieldResultLibrary {

    /**
     *  Function to store custom field
     *
     * @access	public
     * @param   \Illuminate\Http\Request                    $request
     * @param   |Syscover\Pulsar\Models\CustomFieldGroup   $customFieldGroup
     * @param   string                                      $resource
     * @param   integer                                     $objectId
     * @param   string                                      $lang
     * @return  void
     */
    public static function storeCustomFieldResults($request, $customFieldGroup, $resource, $objectId, $lang)
    {
        $customFieldGroup  = CustomFieldGroup::find($customFieldGroup);
        $customFields       = CustomField::getRecords(['lang_026' => $lang, 'group_026' => $customFieldGroup->id_025]);
        $dataTypes          = collect(config('pulsar.dataTypes'))->keyBy('id');
        $customFieldResults = [];

        foreach($customFields as $customField)
        {
            $customFieldResult = [
                'object_028'            => $objectId,
                'lang_028'              => $lang,
                'resource_028'          => $resource,
                'field_028'             => $customField->id_026,
                'boolean_value_028'     => null,
                'int_value_028'         => null,
                'text_value_028'        => null,
                'decimal_value_028'     => null,
                'timestamp_value_028'   => null,
            ];

            // get value and record in your field data type, add suffix '_custom' to avoid conflict with other field names
            if( $dataTypes[$customField->data_type_026]->name == 'Boolean')
                $customFieldResult['boolean_value_028'] = $request->has($customField->name_026 . '_custom');

            if( $dataTypes[$customField->data_type_026]->name == 'Integer')
                $customFieldResult['int_value_028'] = $request->input($customField->name_026 . '_custom');

            if( $dataTypes[$customField->data_type_026]->name == 'Text')
                $customFieldResult['text_value_028'] = $request->input($customField->name_026 . '_custom');

            if( $dataTypes[$customField->data_type_026]->name == 'Decimal')
                $customFieldResult['decimal_value_028'] = $request->input($customField->name_026 . '_custom');

            if( $dataTypes[$customField->data_type_026]->name == 'Timestamp')
                $customFieldResult['timestamp_value_028'] = $request->input($customField->name_026 . '_custom');

            $customFieldResults[]  = $customFieldResult;
        }

        if(count($customFieldResults) > 0)
            CustomFieldResult::insert($customFieldResults);
    }

    public static function deleteCustomFieldResults($resource, $objectId, $lang = null)
    {
        $query = CustomFieldResult::where('resource_028', $resource)
            ->where('object_028', $objectId);

        if(isset($lang)) $query->where('lang_028', $lang);

        $query->delete();
    }

    /**
     * @param   $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public static function apiGetCustomFields($request)
    {
        // get custom fields
        $customFields   = CustomField::getRecords(['lang_026' => $request->input('lang'), 'group_026' => $request->input('customFieldGroup')]);
        if($request->has('object'))
        {
            if($request->has('action') && $request->input('action') == 'create')
                // if is a new object translated
                $langFieldResults = session('baseLang')->id_001;
            else
                $langFieldResults = $request->input('lang');

            // get results if there is a object
            $customFieldResults = CustomFieldResult::getRecords(['lang_028' => $langFieldResults, 'object_028' => $request->input('object'), 'resource_028' => $request->input('resource')])->keyBy('field_028');
            $dataTypes = collect(config('pulsar.dataTypes'))->keyBy('id');
        }

        $html = '';
        foreach($customFields as $customField)
        {
            $setValue = isset($customFieldResults[$customField->id_026]);

            // add suffix '_custom' to avoid conflict with other field names
            if(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view == 'pulsar::includes.html.form_select_group')
            {
                $customFieldValues = $customField->getValues;
                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'] . '_custom', 'value' => null, 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'objects' => $customFieldValues, 'idSelect' => 'id_027', 'nameSelect' => 'name_027', 'required' => $customField->required_026, 'value' => $setValue? $customFieldResults[$customField->id_026]->{$dataTypes[$customField->data_type_026]->column} : null])->render();
            }
            elseif(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view == 'pulsar::includes.html.form_checkbox_group')
            {
                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'] . '_custom', 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'required' => $customField->required_026, 'checked' => $setValue? $customFieldResults[$customField->id_026]->{$dataTypes[$customField->data_type_026]->column} : null])->render();
            }
            else
            {
                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'] . '_custom', 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'required' => $customField->required_026, 'value' => $setValue? $customFieldResults[$customField->id_026]->{$dataTypes[$customField->data_type_026]->column} : null])->render();
            }
        }

        return response()->json([
            'status'    => 'success',
            'html'      => $html
        ]);
    }
}