<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
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
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_026_field';
    protected $primaryKey   = 'id_026';
    protected $suffix       = '026';
    public $timestamps      = false;
    protected $fillable     = ['id_026', 'group_026', 'name_026', 'field_type_026', 'field_type_text_026', 'data_type_026', 'data_type_text_026', 'required_026', 'sorting_026', 'max_length_026', 'pattern_026', 'label_size_026', 'field_size_026', 'data_lang_026', 'data_026'];
    protected $maps         = [];
    protected $relationMaps = [
        'group'   => \Syscover\Pulsar\Models\CustomFieldGroup::class
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
        return CustomField::join('001_025_field_group', '001_026_field.group_026', '=', '001_025_field_group.id_025');
    }

    public function getLang()
    {
        // lang_026 comes from json field data_026
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_026');
    }

    /**
     * It is likely to be filtered by language, for this use Constraining Eager Loads<br>
     * see <a href="http://laravel.com/docs/5.1/eloquent-relationships#eager-loading" target="_blank">Constraining Eager Loads documentation</a>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getValues()
    {
        return $this->hasMany('Syscover\Pulsar\Models\CustomFieldValue', 'field_027');
    }

    public static function addToGetRecordsLimit()
    {
        $query =  CustomField::join('001_025_field_group', '001_026_field.group_026', '=', '001_025_field_group.id_025');

        return $query;
    }

    public static function getRecords($parameters)
    {
        $query =  CustomField::join('001_025_field_group', '001_026_field.group_026', '=', '001_025_field_group.id_025');

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