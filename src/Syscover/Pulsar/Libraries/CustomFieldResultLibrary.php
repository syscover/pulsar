<?php namespace Syscover\Pulsar\Libraries;

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
}