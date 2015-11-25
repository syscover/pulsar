<?php namespace Syscover\Pulsar\Models;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;

class CustomFieldResult extends Model
{
    use TraitModel;

	protected $table        = '001_028_field_result';
    protected $primaryKey   = 'object_028';
    protected $sufix        = '028';
    public $timestamps      = false;
    protected $fillable     = ['object_028', 'lang_028', 'resource_028', 'field_028', 'boolean_value_028', 'int_value_028', 'text_value_028', 'decimal_value_028', 'timestamp_value_028'];
    private static $rules   = [];

    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
    }

    public function lang()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_028');
    }
/*
    public static function addToGetRecordsLimit($parameters)
    {
        $query =  CustomFieldValue::join('001_001_lang', '001_027_field_value.lang_027', '=', '001_001_lang.id_001')
            ->join('001_026_field', '001_027_field_value.field_027', '=', '001_026_field.id_026')
            ->join('001_025_field_family', '001_026_field.family_026', '=', '001_025_field_family.id_025')
            ->newQuery();

        if(isset($parameters['lang'])) $query->where('lang_027', $parameters['lang']);
        if(isset($parameters['field'])) $query->where('field_027', $parameters['field']);

        return $query;
    }
*/
}