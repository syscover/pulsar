<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class CustomFieldResult
 *
 * Model with properties
 * <br><b>[object, lang, resource, field, boolean_value, int_value, text_value, decimal_value, timestamp_value]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class CustomFieldResult extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_028_field_result';
    protected $primaryKey   = 'object_028';
    protected $suffix       = '028';
    public $timestamps      = false;
    protected $fillable     = ['object_028', 'lang_028', 'resource_028', 'field_028', 'boolean_value_028', 'int_value_028', 'text_value_028', 'decimal_value_028', 'timestamp_value_028'];
    protected $maps         = [];
    protected $relationMaps = [
        'lang'          => \Syscover\Pulsar\Models\Lang::class,
        'field'         => \Syscover\Pulsar\Models\CustomField::class,
        'int_value'     => \Syscover\Pulsar\Models\CustomFieldValue::class,
    ];
    private static $rules   = [];

    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_001_lang', '001_028_field_result.lang_028', '=', '001_001_lang.id_001')
            ->join('001_026_field', '001_028_field_result.field_028', '=', '001_026_field.id_026')
            ->leftJoin('001_027_field_value', '001_028_field_result.int_value_028', '=', '001_027_field_value.id_027');
    }

    public function getLang()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_028');
    }

    public static function getRecords($args)
    {
        $query =  CustomFieldResult::builder();

        if(isset($args['lang_028'])) $query->where('lang_028', $args['lang_028']);
        if(isset($args['field_028'])) $query->where('field_028', $args['field_028']);
        if(isset($args['object_028'])) $query->where('object_028', $args['object_028']);
        if(isset($args['resource_028'])) $query->where('resource_028', $args['resource_028']);

        return $query->get();
    }
}