<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Http\Request;
use Syscover\Pulsar\Models\CustomField;
use Syscover\Pulsar\Models\CustomFieldFamily;
use Syscover\Pulsar\Models\CustomFieldResult;

class CustomFieldResultLibrary {

    /**
     *  Function to store custom field
     *
     * @access	public
     * @param   \Illuminate\Http\Request                    $request
     * @param   |Syscover\Pulsar\Models\CustomFieldFamily   $customFieldFamily
     * @param   string                                      $resource
     * @param   integer                                     $objectId
     * @param   string                                      $lang
     * @return  void
     */
    public static function storeCustomFieldResults($request, $customFieldFamily, $resource, $objectId, $lang)
    {
        $customFieldFamily  = CustomFieldFamily::find($customFieldFamily);
        $customFields       = CustomField::getRecords(['lang_026' => $lang, 'family_026' => $customFieldFamily->id_025]);
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

            // get value and record in your field data type
            if( $dataTypes[$customField->data_type_026]->name == 'Boolean')
                $customFieldResult['boolean_value_028'] = $request->has($customField->name_026);

            if( $dataTypes[$customField->data_type_026]->name == 'Integer')
                $customFieldResult['int_value_028'] = $request->input($customField->name_026);

            if( $dataTypes[$customField->data_type_026]->name == 'Text')
                $customFieldResult['text_value_028'] = $request->input($customField->name_026);

            if( $dataTypes[$customField->data_type_026]->name == 'Decimal')
                $customFieldResult['decimal_value_028'] = $request->input($customField->name_026);

            if( $dataTypes[$customField->data_type_026]->name == 'Timestamp')
                $customFieldResult['timestamp_value_028'] = $request->input($customField->name_026);

            $customFieldResults[]  = $customFieldResult;
        }

        if(count($customFieldResults) > 0)
            CustomFieldResult::insert($customFieldResults);
    }

    public static function deleteCustomFieldResults($resource, $objectId, $lang = null)
    {
        $query = CustomFieldResult::where('resource_028', $resource)
            ->where('object_028', $objectId)
            ->newQuery();

        if(isset($lang)) $query->where('lang_028', $lang);

        $query->delete();
    }

    public static function apiGetCustomFields($request)
    {
        // get custom fields
        $customFields   = CustomField::getRecords(['lang_026' => $request->input('lang'), 'family_026' => $request->input('customFieldFamily')]);
        $setValues      = false;
        if($request->has('object'))
        {
            if($request->has('action') && $request->input('action') == 'create')
                // if is a new object translated
                $langFieldResults = session('baseLang')->id_001;
            else
                $langFieldResults = $request->input('lang');

            // get results if there is a object
            $customFieldResults = CustomFieldResult::getRecords(['lang_028' => $langFieldResults, 'object_028' => $request->input('object'), 'resource_028' => $request->input('resource')])->keyBy('field_028');
            $setValues = true;
            $dataTypes = collect(config('pulsar.dataTypes'))->keyBy('id');
        }

        $html = '';
        foreach($customFields as $customField)
        {
            if(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view == 'pulsar::includes.html.form_select_group')
            {
                $customFieldValues = $customField->values;
                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'], 'value' => null, 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'objects' => $customFieldValues, 'idSelect' => 'id_027', 'nameSelect' => 'name_027', 'required' => $customField->required_026, 'value' => $setValues? $customFieldResults[$customField->id_026]->{$dataTypes[$customField->data_type_026]->column} : null])->render();
            }
            elseif(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view == 'pulsar::includes.html.form_checkbox_group')
            {
                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'], 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'required' => $customField->required_026, 'checked' => $setValues? $customFieldResults[$customField->id_026]->{$dataTypes[$customField->data_type_026]->column} : null])->render();
            }
            else
            {
                $html .= view(collect(config('pulsar.fieldTypes'))->keyBy('id')[$customField->field_type_026]->view, ['label' => $customField['label_026'], 'name' => $customField['name_026'], 'fieldSize' => empty($customField['field_size_026'])? 10 : $customField['field_size_026'], 'required' => $customField->required_026, 'value' => $setValues? $customFieldResults[$customField->id_026]->{$dataTypes[$customField->data_type_026]->column} : null])->render();
            }
        }

        return response()->json([
            'status'    => 'success',
            'html'      => $html
        ]);
    }
}