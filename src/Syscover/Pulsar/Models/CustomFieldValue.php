<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class CustomFieldValue
 *
 * Model with properties
 * <br><b>[id, lang, field, sorting, featured, value, data_lang, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class CustomFieldValue extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_027_field_value';
    protected $primaryKey   = 'id_027';
    protected $suffix       = '027';
    public $timestamps      = false;
    protected $fillable     = ['id_027', 'lang_027', 'field_027', 'sorting_027', 'featured_027', 'value_027', 'data_lang_027', 'data_027'];
    protected $maps         = [];
    protected $relationMaps = [
        'lang'      => \Syscover\Pulsar\Models\Lang::class,
        'field'     => \Syscover\Pulsar\Models\CustomField::class,
        'group'     => \Syscover\Pulsar\Models\CustomFieldGroup::class,
    ];
    private static $rules   = [
        'value' => 'required'
    ];

    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_001_lang', '001_027_field_value.lang_027', '=', '001_001_lang.id_001')
            ->join('001_026_field', '001_027_field_value.field_027', '=', '001_026_field.id_026')
            ->join('001_025_field_group', '001_026_field.group_026', '=', '001_025_field_group.id_025');
    }

    public function getLang()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_027');
    }

    public static function addToGetRecordsLimit($parameters)
    {
        $query =  CustomFieldValue::builder();

        if(isset($parameters['lang'])) $query->where('lang_027', $parameters['lang']);
        if(isset($parameters['field'])) $query->where('field_027', $parameters['field']);

        return $query;
    }
}