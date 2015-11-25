<?php namespace Syscover\Pulsar\Libraries;

/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL.
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource  Librarie that instance helper functions to attachments
 */

use Illuminate\Support\Facades\File;
use Syscover\Pulsar\Exceptions\InvalidArgumentException;
use Syscover\Pulsar\Models\Attachment;

class CustomFieldResultLibrary {

    /**
     *  Function to store attachment elements
     *
     * @access	public
     * @param   \Illuminate\Support\Facades\Request     $attachments
     * @param   string                                  $lang
     * @param   string                                  $routesConfigFile
     * @param   integer                                 $objectId
     * @param   string                                  $resource
     * @return  boolean
     */
    public static function storeCustomFieldResult($request, $routesConfigFile, $resource, $objectId, $lang)
    {
        $customFieldFamily  = $article->family->customFieldFamily;
        $customFields       = CustomField::getRecords(['lang_026' => $request->input('lang'), 'family_026' => $customFieldFamily->id_025]);
        $dataTypes          = collect(config('pulsar.dataTypes'))->keyBy('id');
        $customFieldResults = [];

        foreach($customFields as $customField)
        {
            $customFieldResult = [
                'object_028'            => $article->id_355,
                'lang_028'              => $request->input('lang'),
                'resource_028'          => 'cms-article-family',
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
}