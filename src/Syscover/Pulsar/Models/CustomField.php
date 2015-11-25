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

class CustomField extends Model
{
    use TraitModel;

	protected $table        = '001_026_field';
    protected $primaryKey   = 'id_026';
    protected $sufix        = '026';
    public $timestamps      = false;
    protected $fillable     = ['id_026', 'family_026', 'name_026', 'field_type_026', 'field_type_text_026', 'data_type_026', 'data_type_text_026', 'required_026', 'sorting_026', 'max_length_026', 'pattern_026', 'label_size_026', 'field_size_026', 'data_lang_026', 'data_026'];
    private static $rules   = [
        'name'      => 'required|between:2,100',
        'family'    => 'required',
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['family'])) static::$rules['family'] = '';

        return Validator::make($data, static::$rules);
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
        $query =  CustomField::join('001_025_field_family', '001_026_field.family_026', '=', '001_025_field_family.id_025')
            ->newQuery();

        return $query;
    }

    public static function getRecords($parameters)
    {
        $query =  CustomField::join('001_025_field_family', '001_026_field.family_026', '=', '001_025_field_family.id_025')
            ->newQuery();

        if(isset($parameters['family_026'])) $query->where('family_026', $parameters['family_026']);

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
        $customField =  CustomField::join('001_025_field_family', '001_026_field.family_026', '=', '001_025_field_family.id_025')
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