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

    public static function getRecords($args)
    {
        $query =  CustomFieldResult::join('001_001_lang', '001_028_field_result.lang_028', '=', '001_001_lang.id_001')
            ->join('001_026_field', '001_028_field_result.field_028', '=', '001_026_field.id_026')
            ->newQuery();

        if(isset($args['lang_028'])) $query->where('lang_028', $args['lang_028']);
        if(isset($args['field_028'])) $query->where('field_028', $args['field_028']);
        if(isset($args['object_028'])) $query->where('object_028', $args['object_028']);
        if(isset($args['resource_028'])) $query->where('resource_028', $args['resource_028']);

        return $query->get();
    }
}