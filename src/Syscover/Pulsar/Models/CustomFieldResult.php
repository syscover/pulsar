<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class CustomFieldResult
 *
 * Model with properties
 * <br><b>[object, lang_id, resource, field, data_type, value]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class CustomFieldResult extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_028_field_result';
    protected $primaryKey   = 'object_id_028';
    protected $suffix       = '028';
    public $timestamps      = false;
    protected $fillable     = ['object_id_028', 'lang_id_028', 'resource_id_028', 'field_id_028', 'data_type_type_028', 'value_028'];
    protected $maps         = [];
    protected $relationMaps = [
        'lang'          => \Syscover\Pulsar\Models\Lang::class,
        'field'         => \Syscover\Pulsar\Models\CustomField::class,
        'value'         => \Syscover\Pulsar\Models\CustomFieldValue::class,
    ];
    private static $rules   = [];

    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_001_lang', '001_028_field_result.lang_id_028', '=', '001_001_lang.id_001')
            ->join('001_026_field', '001_028_field_result.field_id_028', '=', '001_026_field.id_026')
            ->leftJoin('001_027_field_value', function($join) {
                $join->on('001_028_field_result.value_028', '=', '001_027_field_value.id_027')
                    ->on('001_027_field_value.field_id_027', '=', '001_026_field.id_026')
                    ->on('001_027_field_value.lang_id_027', '=', '001_028_field_result.lang_id_028');
            });

    }

    public function getLang()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_id_028');
    }

    /**
     * @deprecated
     * @param $args
     * @return mixed
     */

    public static function getRecords($args)
    {
        $query =  CustomFieldResult::builder();

        if(isset($args['lang_id_028']))     $query->where('lang_id_028', $args['lang_id_028']);
        if(isset($args['field_id_028']))    $query->where('field_id_028', $args['field_id_028']);
        if(isset($args['object_id_028']))   $query->where('object_id_028', $args['object_id_028']);
        if(isset($args['resource_id_028'])) $query->where('resource_id_028', $args['resource_id_028']);

        return $query->get();
    }

    /**
     * Accessor function, when call value_028 set cast to value
     *
     * @param $value
     * @return string
     */
    public function getValue028Attribute($value)
    {
        if($this->data_type_type_028 == 'array')
            $value = explode(',', $value);
        else
            settype($value, $this->data_type_type_028);

        return $value;
    }

    /**
     * @param   $value
     * @return  void
     */
//    public function setValue028Attribute($value)
//    {
//        if($this->data_type_type_028 == 'array')
//            $this->attributes['value_028'] = implode(',', $value);
//        else
//            $this->attributes['value_028'] = $value;
//    }
}