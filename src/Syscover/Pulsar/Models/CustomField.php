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
    protected $fillable     = ['id_026', 'name_026', 'family_026', 'data_lang_026', 'data_026'];
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

    public static function addToGetRecordsLimit()
    {
        $query =  CustomField::join('001_025_field_family', '001_026_field.family_026', '=', '001_025_field_family.id_025')
            ->newQuery();

        return $query;
    }

    public static function getTranslationRecord($id, $lang)
    {
        $customField =  CustomField::join('001_025_field_family', '001_026_field.family_026', '=', '001_025_field_family.id_025')
            ->where('id_026', $id)
            ->first();

        $data = collect(json_decode($customField->data_026, true)['labels'])->keyBy('lang');
        $customField->label_026 = $data[$lang]['name'];
        $customField->lang_026 = $data[$lang]['lang'];

        return $customField;
    }

    public static function customDeleteTranslationRecord($id, $lang, $deleteLangDataRecord = true)
    {
        $customField = CustomField::find($id);

        // get values
        $data = collect(json_decode($customField->data_026, true)['labels'])->keyBy('lang');
        unset($data[$lang]);

        CustomField::where('id_026', $id)->update([
            'data_lang_026'     => json_encode(['labels' => $data])
        ]);
    }
}