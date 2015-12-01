<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class CustomField
 *
 * Model with properties
 * <br><b>[id, group, name, field_type, field_type_text, data_type, data_type_text, required, sorting, max_length, pattern, label_size, field_size, data_lang, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class CustomField extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_026_field';
    protected $primaryKey   = 'id_026';
    protected $sufix        = '026';
    public $timestamps      = false;
    protected $fillable     = ['id_026', 'group_026', 'name_026', 'field_type_026', 'field_type_text_026', 'data_type_026', 'data_type_text_026', 'required_026', 'sorting_026', 'max_length_026', 'pattern_026', 'label_size_026', 'field_size_026', 'data_lang_026', 'data_026'];
    protected $maps = [
        'id'                    => 'id_026',
        'group'                 => 'group_026',
        'name'                  => 'name_026',
        'field_type'            => 'field_type_026',
        'field_type_text'       => 'field_type_text_026',
        'data_type'             => 'data_type_026',
        'data_type_text'        => 'data_type_text_026',
        'required'              => 'required_026',
        'sorting'               => 'sorting_026',
        'max_length'            => 'max_length_026',
        'pattern'               => 'pattern_026',
        'label_size'            => 'label_size_026',
        'field_size'            => 'field_size_026',
        'data_lang'             => 'data_lang_026',
        'data'                  => 'data_026',
    ];
    private static $rules   = [
        'name'      => 'required|between:2,100',
        'group'     => 'required',
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['group'])) static::$rules['group'] = '';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder()
    {
        return CustomField::join('001_025_field_group', '001_026_field.group_026', '=', '001_025_field_group.id_025')
            ->newQuery();
    }

    public function lang()
    {
        // lang_026 comes from json field data_026
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_026');
    }

    public function values()
    {
        return $this->hasMany('Syscover\Pulsar\Models\CustomFieldValue', 'field_027')
            ->where('001_027_field_value.lang_027', session('baseLang')->id_001);
    }

    public static function addToGetRecordsLimit()
    {
        $query =  CustomField::join('001_025_field_group', '001_026_field.group_026', '=', '001_025_field_group.id_025')
            ->newQuery();

        return $query;
    }

    public static function getRecords($parameters)
    {
        $query =  CustomField::join('001_025_field_group', '001_026_field.group_026', '=', '001_025_field_group.id_025')
            ->newQuery();

        if(isset($parameters['group_026'])) $query->where('group_026', $parameters['group_026']);

        $customFields = $query->get();

        if(isset($parameters['lang_026']))
        {
            foreach($customFields as &$customField)
            {
                $data = collect(json_decode($customField->data_026, true)['labels']);

                if(isset($data[$parameters['lang_026']]))
                {
                    $customField->label_026     = $data[$parameters['lang_026']];
                    $customField->lang_026      = $parameters['lang_026'];
                }
                elseif(isset($data[session('baseLang')->id_001]))
                {
                    $customField->label_026     = $data[session('baseLang')->id_001];
                    $customField->lang_026      = session('baseLang')->id_001;
                }
                else
                {
                    $customField->label_026     = null;
                    $customField->lang_026      = null;
                }
            }
        }

        return $customFields;
    }

    public static function getTranslationRecord($parameters)
    {
        $customField =  CustomField::join('001_025_field_group', '001_026_field.group_026', '=', '001_025_field_group.id_025')
            ->where('id_026', $parameters['id'])
            ->first();

        $data = collect(json_decode($customField->data_026, true)['labels']);

        if(isset($data[$parameters['lang']]))
        {
            $customField->label_026     = $data[$parameters['lang']];
            $customField->lang_026      = $parameters['lang'];
        }
        elseif(isset($data[session('baseLang')->id_001]))
        {
            $customField->label_026     = $data[session('baseLang')->id_001];
            $customField->lang_026      = session('baseLang')->id_001;
        }
        else
        {
            $customField->label_026     = null;
            $customField->lang_026      = null;
        }

        return $customField;
    }

    public static function deleteTranslationRecord($parameters)
    {
        $customField = CustomField::find($parameters['id']);

        // get values
        $data = collect(json_decode($customField->data_026, true)['labels']);
        unset($data[$parameters['lang']]);

        CustomField::where('id_026', $parameters['id'])->update([
            'data_026' => json_encode(['labels' => $data])
        ]);

        // set values on data_lang_026
        CustomField::deleteLangDataRecord($parameters);
    }
}